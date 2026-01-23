document.addEventListener('DOMContentLoaded', function() {
    const url = '/cicu-ward-rounds/performance';
    let currentPeriod = 'all';

    const modal = document.getElementById('performanceModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            // redraw charts when modal is visible
            loadPerformanceData(url, currentPeriod);
        });
    }

    document.querySelectorAll('.cicu-period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPeriod = btn.dataset.period;
            loadPerformanceData(url, currentPeriod);
        });
    });

    // Optional: redraw pie chart when Pie tab is clicked (fix height issue)
    document.getElementById('pie-tab').addEventListener('shown.bs.tab', function () {
        if (amChartsInstances['cicuPieChartDiv']) {
            amChartsInstances['cicuPieChartDiv'].invalidateRawData();
        }
    });

    document.getElementById('bar-tab').addEventListener('shown.bs.tab', function () {
        if (amChartsInstances['cicuBarChartDiv']) {
            amChartsInstances['cicuBarChartDiv'].invalidateRawData();
        }
    });
});
