<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('kualitasKreditChart');

        if (!chartCanvas) {
            return;
        }

        const labels = JSON.parse(chartCanvas.dataset.chartLabels || '[]');
        const values = JSON.parse(chartCanvas.dataset.chartValues || '[]');

        new Chart(chartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pinjaman',
                    data: values,
                    backgroundColor: ['#1f6f50', '#f4b400', '#2980b9', '#d64550', '#8892a0'],
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 48
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(20, 30, 58, 0.08)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
