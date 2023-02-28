$(document).ready(function() {
    'use strict';

    if ($('#site_statistics').length === 0) {
        return;
    }

    var priceMin = [
        [1362096000000, 44.1],
        [1364774400000, 46.5],
        [1367366400000, 48.1],
        [1370044800000, 42.3],
        [1372636800000, 41.4],
        [1375315200000, 43.3],
        [1377993600000, 44.7],
        [1380585600000, 38.9],
        [1383264000000, 42.0],
        [1385856000000, 35.9]
    ];

    var priceMax = [
        [1362096000000, 48.0],
        [1364774400000, 50.3],
        [1367366400000, 51.4],
        [1370044800000, 47.5],
        [1372636800000, 47.9],
        [1375315200000, 46.7],
        [1377993600000, 49.3],
        [1380585600000, 45.1],
        [1383264000000, 46.2],
        [1385856000000, 44.8]
    ];


    $("#site_statistics").bind("plothover", function (event, pos, item) {
        var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                var date = new Date(parseInt(x));
                var month = new Array();
                month[0]="Jan";
                month[1]="Feb";
                month[2]="Mar";
                month[3]="Apr";
                month[4]="May";
                month[5]="Jun";
                month[6]="Jul";
                month[7]="Aug";
                month[8]="Sep";
                month[9]="Oct";
                month[10]="Nov";
                month[11]="Dec";
                var formattedDate = month[date.getMonth()];

                var formattedPrice = parseInt(y) * 1000 + "$";
                showTooltip(item.pageX, item.pageY,
                    item.series.label + " of " + formattedDate + " : " + formattedPrice);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });


    $.plot($("#site_statistics"), [
        {
            data: priceMax,
            label: "Max price"
        },
        {
            data: priceMin,
            label: "Min price"
        }
    ],
        {
            series: {
                lines: {
                    show: true,
                    lineWidth: 3,
                    fill: false,
                    hoverable: true
                },
                shadowSize: 0
            },
            legend: {
                show: false
            },

            grid: {
                labelMargin: 10,
                axisMargin: 10,
                hoverable: true,
                clickable: true,
                color: "rgba(0,0,0,0.05)",
                tickColor: "rgba(0,0,0,0.05)"
            },
            colors: ["#0076a2", "#f95446"],
            xaxis: {
                mode: "time",
                minTickSize: [1, "month"],
                maxTickSize: [1, "month"],
                min: (new Date(2013, 4)).getTime(),
                max: (new Date(2013, 12)).getTime()
            }
        });
})