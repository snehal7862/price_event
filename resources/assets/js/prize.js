'use strict';

function generateRandomColors(numColors) {
    return Array.from({ length: numColors }, () => `#${Math.floor(Math.random() * 16777215).toString(16)}`);
}

function createLabels(data, total, formatPercentages = true) {
    return Object.keys(data).map((key, index) => {
        const value = Object.values(data)[index];
        const percentage = ((value / total) * 100).toFixed(2);
        return `${key} (${formatPercentages ? percentage : value}%)`;
    });
}

function prepareChartData(data, colors, hidden = false) {
    return {
        labels: data.labels,
        datasets: [{
            data: data.values,
            backgroundColor: colors,
            hidden: hidden
        }]
    };
}

const titlesData = {
    labels: createLabels(titles, 100),
    values: Object.values(titles)
};
const probabilityColors = generateRandomColors(titlesData.labels.length);
const probabilityData = prepareChartData(titlesData, probabilityColors);

const totalAwards = Object.values(results).reduce((acc, count) => acc + count, 0);
const formatPercentages = totalAwards > 0;
const actualData = {
    labels: createLabels(results, totalAwards, formatPercentages),
    values: Object.values(results)
};
const rewardsColors = generateRandomColors(actualData.labels.length);
const rewardsData = prepareChartData(actualData, rewardsColors, !formatPercentages);

function createChart(ctx, data) {
    return new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            plugins: {
                legend: {
                    position: 'top'
                },
                datalabels: {
                    color: '#000',
                    formatter: (value, context) => context.chart.data.labels[context.dataIndex],
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    align: 'center'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

// Create charts
if(totalProbabilities === 100) {

    createChart(document.getElementById('probabilityChart'), probabilityData);
    createChart(document.getElementById('rewardsChart'), rewardsData);
}
