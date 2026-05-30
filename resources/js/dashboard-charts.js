import Chart from 'chart.js/auto';

const dataScript = document.getElementById('dashboard-charts-data');

if (dataScript) {
    const dashboardData = JSON.parse(dataScript.textContent || '{}');

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8,
                },
            },
            tooltip: {
                backgroundColor: '#0f172a',
                padding: 10,
                titleColor: '#f8fafc',
                bodyColor: '#f8fafc',
            },
        },
        scales: {
            x: {
                grid: {
                    color: 'rgba(148, 163, 184, 0.2)',
                },
                ticks: {
                    color: '#64748b',
                },
            },
            y: {
                grid: {
                    color: 'rgba(148, 163, 184, 0.2)',
                },
                ticks: {
                    color: '#64748b',
                },
                beginAtZero: true,
            },
        },
    };

    const makeLineChart = (elementId, labels, data, label, color) => {
        const element = document.getElementById(elementId);
        if (!element || !labels?.length) return;

        new Chart(element, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label,
                        data,
                        borderColor: color,
                        backgroundColor: `${color}22`,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: color,
                    },
                ],
            },
            options: baseOptions,
        });
    };

    const makeDoughnutChart = (elementId, labels, data, colors) => {
        const element = document.getElementById(elementId);
        if (!element || !labels?.length) return;

        new Chart(element, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [
                    {
                        data,
                        backgroundColor: colors,
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: baseOptions.plugins,
            },
        });
    };

    const makeBarChart = (elementId, labels, data, color) => {
        const element = document.getElementById(elementId);
        if (!element || !labels?.length) return;

        new Chart(element, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total',
                        data,
                        backgroundColor: color,
                        borderRadius: 8,
                    },
                ],
            },
            options: baseOptions,
        });
    };

    if (dashboardData?.admin?.loansPerMonth) {
        makeLineChart(
            'loansTrendChart',
            dashboardData.months,
            dashboardData.admin.loansPerMonth,
            'Emprunts',
            '#2563eb'
        );
        makeLineChart(
            'membersTrendChart',
            dashboardData.months,
            dashboardData.admin.membersPerMonth,
            'Nouveaux membres',
            '#7c3aed'
        );
        makeBarChart(
            'categoryChart',
            dashboardData.admin.categoryLabels,
            dashboardData.admin.categoryTotals,
            '#f97316'
        );
        makeDoughnutChart(
            'statusChart',
            dashboardData.admin.loanStatus.labels,
            dashboardData.admin.loanStatus.values,
            ['#2563eb', '#ef4444', '#16a34a']
        );
    }

    if (dashboardData?.user?.loansPerMonth) {
        makeLineChart(
            'userLoansTrendChart',
            dashboardData.months,
            dashboardData.user.loansPerMonth,
            'Mes emprunts',
            '#0ea5e9'
        );
        makeBarChart(
            'userCategoryChart',
            dashboardData.user.categoryLabels,
            dashboardData.user.categoryTotals,
            '#f59e0b'
        );
        makeDoughnutChart(
            'userStatusChart',
            dashboardData.user.loanStatus.labels,
            dashboardData.user.loanStatus.values,
            ['#0ea5e9', '#ef4444', '#16a34a']
        );
    }
}

