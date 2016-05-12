//全て読み込んでから処理
$(function () {
    //表のヘッダーを固定
    FixedMidashi.create();

    //タブの処理
    $(".resultTitle").on("click", function () {
        var index = $('.resultTitle').index(this);
        console.log(index);
        //クリックされたタブと同じ順番のコンテンツを表示します。
        $('.resultBody').removeClass('activeResult');
        $('.resultBody').eq(index).addClass('activeResult');
        //一度タブについているクラスactiveTabを消し、
        $('.resultTitle').removeClass('activeTitle');
        //クリックされたタブのみにクラスselectをつけます。
        $(this).addClass('activeTitle');
    });

    //評価の表示
    var ratingAverage = $("#ratingResult").text();
    console.log(ratingAverage);

    var ratingAverage_round = Math.round(parseFloat(ratingAverage));
    //評価のイメージを作成
    console.log(ratingAverage_round);
    var ratingImageResults = $(".ratingImageResult");
    for (var i = 0; i < ratingAverage_round; i++) {
        $(ratingImageResults[i]).addClass("ratingImageResultOn");
    }
    var ratingParcentages = $(".ratingParcentage");
    var bars = $(".bar");
    for (var j = 0; j < ratingParcentages.length; j++) {
        var currentPercentage = $(ratingParcentages[j]).text();
        $(bars[j]).css("width", currentPercentage);
    }

    //男女比の表示
    
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {
        'packages': ['corechart'], 'language': 'ja'
    });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart());

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {
        // Create the data table.
        var sexNumberElements = $(".sexNumber");
        var sexNumber_male = $(sexNumberElements[0]).text();
        console.log("sexNumber_male:" + sexNumber_male);
        var sexNumber_female = $(sexNumberElements[1]).text();
        var sexNumber_none = $(sexNumberElements[2]).text();
        sexNumber_male = parseFloat(sexNumber_male);
        sexNumber_female = parseFloat(sexNumber_female);
        sexNumber_none = parseFloat(sexNumber_none);

        var data = new google.visualization.DataTable();
        data.addColumn('string', '性別');
        data.addColumn('number', '票');
        data.addRows([
            ['男性', sexNumber_male],
            ['女性', sexNumber_female],
            ['無回答', sexNumber_none]
        ]);

        // Set chart options
        var options = {
            'width': 400,
            'height': 200
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

});