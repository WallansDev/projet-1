var xValues = ["Concessions", "Acheteurs", "Morts"];
var yValues = [56, 49, 44, 24, 16];
var barColors = ["#36A2EB", "#FF1544", "#FF6384"];

new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [
      {
        backgroundColor: barColors,
        data: yValues,
      },
    ],
  },
  options: {
    legend: {
      display: false,
    },
    title: {
      display: true,
      text: "World Wine Production 2018",
    },
  },
});
