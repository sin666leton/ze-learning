import { Chart } from "chart.js/auto";

window.barChart = function (canvas, bad = 0, good = 0, veryGood = 0, smart = 0) {
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['D (<= 20)', 'C (20-49) ', 'B (50-80)', 'A (81 >=)'],
            datasets: [{
                label: 'Nilai siswa',
                data: [bad, good, veryGood, smart],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    })
}