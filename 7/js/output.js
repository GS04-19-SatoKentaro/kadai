//全て読み込んでから処理
$(function () {
    //表のヘッダーを固定
    FixedMidashi.create();
    
    var ratingAverage = $("#ratingResult").text();
    console.log(ratingAverage);
    
    var ratingAverage_round = Math.round(parseFloat(ratingAverage));
    //評価のイメージを作成
    console.log(ratingAverage_round);
    var ratingImageResults = $(".ratingImageResult");
    for (var i=0; i<ratingAverage_round; i++) {
        $(ratingImageResults[i]).addClass("ratingImageResultOn");
    }
    var ratingParcentages = $(".ratingParcentage");
    var bars = $(".bar");
    for (var j=0; j<ratingParcentages.length; j++) {
        currentPercentage = $(ratingParcentages[j]).text();
        $(bars[j]).css("width",currentPercentage);
    }
});