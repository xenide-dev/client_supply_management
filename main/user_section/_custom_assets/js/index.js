$(document).ready(function(){
    loadChart1();
});

function loadChart1(){
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
    $.ajax({
        method: 'POST',
        url: '../modules/asyncUrls/retrieve_list_dashboard.php',
        dataType: 'JSON',
        data: {
            type: 'requests',
            operation: 'request_per_month'
        },
        success: function(data){
            if(data.msg){
                var areaChartData = {
                    labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                    {
                        label               : 'Purchase Request',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : true,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : data.info[1].data
                    },
                    {
                        label               : 'Requistion',
                        backgroundColor     : 'rgba(210, 214, 222, 1)',
                        borderColor         : 'rgba(210, 214, 222, 1)',
                        pointRadius         : true,
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : data.info[0].data
                    },
                    ]
                }

                var areaChartOptions = {
                    maintainAspectRatio : false,
                    responsive : true,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            gridLines : {
                                display : false,
                            }
                        }],
                        yAxes: [{
                            gridLines : {
                                display : false,
                            }
                        }]
                    }
                }

                // This will get the first returned node in the jQuery collection.
                var areaChart       = new Chart(areaChartCanvas, { 
                    type: 'line',
                    data: areaChartData, 
                    options: areaChartOptions
                })
            }
        }
    });
}