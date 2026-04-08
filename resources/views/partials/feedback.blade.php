@php
    $flashMessages = collect([
        ['key' => 'success', 'title' => 'Berhasil', 'class' => 'success'],
        ['key' => 'tambah', 'title' => 'Data Ditambahkan', 'class' => 'success'],
        ['key' => 'hapus', 'title' => 'Data Dihapus', 'class' => 'danger'],
        ['key' => 'info', 'title' => 'Informasi', 'class' => 'info'],
        ['key' => 'error', 'title' => 'Perlu Diperhatikan', 'class' => 'danger'],
    ])->filter(fn ($item) => session()->has($item['key']));
@endphp

@if ($flashMessages->isNotEmpty() || $errors->any())
    <div class="app-feedback-stack" id="appFeedbackStack" aria-live="polite" aria-atomic="true">
        @foreach ($flashMessages as $message)
            <div class="app-feedback-toast is-{{ $message['class'] }}" data-feedback-toast>
                <div class="app-feedback-logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
                </div>
                <div class="app-feedback-body">
                    <strong>{{ $message['title'] }}</strong>
                    <p>{{ session($message['key']) }}</p>
                </div>
                <button type="button" class="app-feedback-close" data-feedback-close aria-label="Tutup notifikasi">
                    &times;
                </button>
            </div>
        @endforeach

        @if ($errors->any())
            <div class="app-feedback-toast is-danger" data-feedback-toast>
                <div class="app-feedback-logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
                </div>
                <div class="app-feedback-body">
                    <strong>Periksa kembali input Anda</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="app-feedback-close" data-feedback-close aria-label="Tutup notifikasi">
                    &times;
                </button>
            </div>
        @endif
    </div>
@endif

<div class="app-loading-overlay" id="appLoadingOverlay" aria-hidden="true">
    <div class="app-loading-card">
        <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
        <h6>Memproses permintaan</h6>
        <p>Mohon tunggu sebentar, data sedang kami siapkan.</p>
    </div>
</div>
