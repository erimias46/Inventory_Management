




var options = {
    chart: {
        height: 280,
        type: 'donut',
    },
    legend: {
        show: false
    },
    stroke: {
        colors: ['transparent']
    },
    series: [82, 37],
    labels: ["Done Projects", "Pending Projects"],
    colors: colors,
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
}

// var chart = new ApexCharts(
//     document.querySelector("#monthly-target"),
//     options
// );

// chart.render();


//
// var colors = ["#3073F1", "#0acf97", "#fa5c7c", "#ffbc00"];
// var dataColors = document.querySelector("#project-overview-chart").dataset.colors;
// if (dataColors) {
//     colors = dataColors.split(",");
// }
// var options = {
//     chart: {
//         height: 350,
//         type: 'radialBar'
//     },
//     colors: colors,
//     series: [85, 70, 80, 65],
//     labels: ['Product Design', 'Web Development', 'Illustration Design', 'UI/UX Design'],
//     plotOptions: {
//         radialBar: {
//             track: {
//                 margin: 5,
//             }
//         }
//     }
// }

// var chart = new ApexCharts(
//     document.querySelector("#project-overview-chart"),
//     options
// );

// chart.render();