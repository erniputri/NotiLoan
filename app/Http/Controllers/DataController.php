<?php

namespace App\Http\Controllers;

use App\Exports\PeminjamanExport;
use App\Exports\PeminjamanTemplateExport;
use App\Http\Requests\Peminjaman\StoreFinalRequest;
use App\Http\Requests\Peminjaman\StoreStep1Request;
use App\Http\Requests\Peminjaman\StoreStep2Request;
use App\Http\Requests\Peminjaman\UpdatePeminjamanRequest;
use App\Http\Requests\Peminjaman\UpdateStep1Request;
use App\Http\Requests\Peminjaman\UpdateStep2Request;
use App\Http\Requests\Peminjaman\UpdateStep3Request;
use App\Imports\PeminjamanImport;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjamanService
    ) {
    }

    // Halaman index menggabungkan kebutuhan list data, filter pencarian, dan opsi export dinamis.
    public function index(Request $request)
    {
        $search = $request->search;
        $availableExportColumns = PeminjamanExport::availableColumns();
        $selectedExportColumns = PeminjamanExport::defaultColumns();
        $totalLoanAmount = Peminjaman::sum('pokok_pinjaman_awal');

        $query = Peminjaman::when($search, function ($query) use ($search) {
            $query->where('nama_mitra', 'like', "%{$search}%")
                ->orWhere('kontak', 'like', "%{$search}%")
                ->orWhere('kabupaten', 'like', "%{$search}%")
                ->orWhere('sektor', 'like', "%{$search}%");
        });

        $dataPeminjaman = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.data.index', compact(
            'dataPeminjaman',
            'search',
            'availableExportColumns',
            'selectedExportColumns',
            'totalLoanAmount'
        ));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('pages.data.show', compact('peminjaman'));
    }

    // Export dijaga dengan whitelist kolom agar admin fleksibel, tetapi field yang keluar tetap aman.
    public function exportExcel(Request $request)
    {
        $allowedColumns = array_keys(PeminjamanExport::availableColumns());

        $validated = $request->validate([
            'columns' => ['nullable', 'array'],
            'columns.*' => ['string', Rule::in($allowedColumns)],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $selectedColumns = $validated['columns'] ?? PeminjamanExport::defaultColumns();
        $search = $validated['search'] ?? null;

        return Excel::download(
            new PeminjamanExport($selectedColumns, $search),
            'Data-NotiLoan-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    // Import dipisah ke class khusus supaya controller tetap fokus pada alur request dan response.
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new PeminjamanImport, $request->file('file'));

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil diimport.');
    }

    // Template excel disediakan agar format import konsisten tanpa memakai data asli dari database.
    public function downloadTemplate()
    {
        return Excel::download(
            new PeminjamanTemplateExport,
            'Template-Import-NotiLoan.xlsx'
        );
    }

    // Step 1 hanya menampilkan form identitas dasar mitra.
    public function createStep1()
    {
        return view('pages.data.create-step-1');
    }

    // Data step pertama disimpan ke session agar wizard bisa berjalan bertahap sebelum final submit.
    public function storeStep1(StoreStep1Request $request)
    {
        session([
            'peminjaman.step1' => $request->safe()->only([
                'nomor_mitra',
                'virtual_account',
                'nama_mitra',
                'kontak',
                'alamat',
                'kabupaten',
                'sektor',
            ]),
        ]);

        return redirect()->route('data.create.step2');
    }

    // Guard ini mencegah user langsung loncat ke step 2 tanpa mengisi step 1 lebih dulu.
    public function createStep2()
    {
        if (! session()->has('peminjaman.step1')) {
            return redirect()->route('data.create.step1');
        }

        return view('pages.data.create-step-2');
    }

    // Step 2 mulai membentuk aturan pinjaman, termasuk tanggal jatuh tempo turunan dari tenor.
    public function storeStep2(StoreStep2Request $request)
    {
        $validated = $request->validated();
        $lama = (int) $validated['lama_angsuran_bulan'];

        session([
            'peminjaman.step2' => [
                'pokok_pinjaman_awal' => $validated['pokok_pinjaman_awal'],
                'tgl_peminjaman' => $validated['tgl_peminjaman'],
                'lama_angsuran_bulan' => $lama,
                'bunga_persen' => $validated['bunga_persen'],
                'tgl_jatuh_tempo' => Carbon::parse($validated['tgl_peminjaman'])
                    ->addMonths($lama)
                    ->format('Y-m-d'),
                'tgl_akhir_pinjaman' => Carbon::parse($validated['tgl_peminjaman'])
                    ->addMonths($lama)
                    ->format('Y-m-d'),
            ],
        ]);

        return redirect()->route('data.create.step3');
    }

    // User tidak boleh membuka step administrasi jika detail pinjaman belum tersimpan di session.
    public function createStep3()
    {
        if (! session()->has('peminjaman.step2')) {
            return redirect()->route('data.create.step1');
        }

        return view('pages.data.create-step-3');
    }

    // Final step menggabungkan seluruh data wizard lalu menyerahkannya ke service layer untuk disimpan.
    public function storeFinal(StoreFinalRequest $request)
    {
        $step1 = session('peminjaman.step1');
        $step2 = session('peminjaman.step2');

        if (! $step1 || ! $step2) {
            return redirect()
                ->route('data.create.step1')
                ->with('error', 'Session habis, silakan ulangi input.');
        }

        $this->peminjamanService->createFromWizard(
            $step1,
            $step2,
            $request->validated()
        );

        session()->forget('peminjaman');

        return redirect()
            ->route('data.index')
            ->with('tambah', 'Data berhasil ditambahkan');
    }

    // Edit wizard dipertahankan agar alur ubah data tetap seragam dengan alur tambah data.
    public function editStep1($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('pages.data.edit-step-1', compact('peminjaman'));
    }

    // Identitas dipisah ke step sendiri agar perubahan profil tidak bercampur dengan logika saldo pinjaman.
    public function updateStep1(UpdateStep1Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $this->peminjamanService->updateIdentity($peminjaman, $request->validated());

        return redirect()->route('data.edit.step2', $id);
    }

    public function editStep2($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('pages.data.edit-step-2', compact('peminjaman'));
    }

    // Step ini menyentuh nilai pinjaman dan tenor, jadi seluruh aturannya dipindah ke service.
    public function updateStep2(UpdateStep2Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $this->peminjamanService->updateLoanTerms($peminjaman, $request->validated());

        return redirect()->route('data.edit.step3', $id);
    }

    public function editStep3($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return view('pages.data.edit-step-3', compact('peminjaman'));
    }

    // Step administrasi berisi atribut pendukung yang tidak memengaruhi alur pinjaman utama secara langsung.
    public function updateStep3(UpdateStep3Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $this->peminjamanService->updateAdministrative($peminjaman, $request->validated());

        return redirect()
            ->route('data.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function edit(string $id)
    {
        $dataPeminjaman = Peminjaman::findOrFail($id);

        return view('pages.data.edit', compact('dataPeminjaman'));
    }

    // Jalur update umum ini masih dipertahankan untuk kompatibilitas bila ada form lama yang memakainya.
    public function update(UpdatePeminjamanRequest $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $this->peminjamanService->updateGeneral($peminjaman, $request->validated());

        return redirect()
            ->route('data.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    // Hapus masih dilakukan langsung di controller karena belum ada side effect tambahan selain delete record.
    public function destroy(string $id)
    {
        Peminjaman::findOrFail($id)->delete();

        return redirect()->route('data.index')->with('hapus', 'Data berhasil dihapus');
    }
}
