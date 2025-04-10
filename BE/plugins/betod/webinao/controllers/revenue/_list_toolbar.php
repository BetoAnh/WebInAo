<div class="widget-body">
    <canvas id="revenueData"></canvas>
</div>
<style>
    .list-widget-container {
        display: none;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const APP_URL_API = "<?php echo env('APP_URL_API'); ?>";
    document.addEventListener('DOMContentLoaded', function () {
        fetch(`${APP_URL_API}/apiData/data`)
            .then(response => response.json())
            .then(revenueData => {

                var labels = Object.keys(revenueData);
                var dataValues = Object.values(revenueData);

                var ctx = document.getElementById('revenueData').getContext('2d');
                var revenueChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Doanh thu',
                            data: dataValues,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Biểu đồ doanh thu'
                            },
                            legend: {
                                position: 'top',
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                ticks: {
                                    autoSkip: true,
                                    maxRotation: 90,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Có lỗi xảy ra khi lấy dữ liệu:', error));
    });
</script>