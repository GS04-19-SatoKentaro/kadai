//全て読み込んでから処理
$(function () {
    //    alert("alert");
    //    saveFormContents();
//    loadFormContents();
    checkRating();
    checkRequirements();

    //テスト用ボタン
    $("#test").on("click", function () {
        var checkedRadioButtonValues = getCheckedRadioButtons();
        alert(checkedRadioButtonValues);
    });

    function getCheckedRadioButtons() {
        var checkedRadioButtons = $('input[type="radio"]:checked');
        var checkedRadioButtonProps = [];
        for (i = 0; i < checkedRadioButtons.length; i++) {
            var $currentButton = $(checkedRadioButtons[i]);
            var currentButtonName = $currentButton.prop('name');
            var currentButtonValue = $currentButton.val();
            checkedRadioButtonProps.push({
                name: currentButtonName,
                value: currentButtonValue
            });
        }
        return checkedRadioButtonProps;
    }

    $(".answer").on("change", function () {
        saveFormContents();
    });

    $('input[type="radio"]').on("change", function () {
        saveFormContents();
    });

    $("#rating").on("change", function () {
        checkRating();
    });

    function checkRating() {
        var ratingNumber = $("#rating").val();
        setRatingImage(ratingNumber);
    }

    $(".ratingImage").on("click", function () {
        var ratingNumber = $(".ratingImage").index(this) + 1;
        $("#rating").val(ratingNumber);
        setRatingImage(ratingNumber);
        saveFormContents();
        checkRequirements();
    });

    function setRatingImage(ratingNum) {
        var ratingImages = $(".ratingImage");
        for (var i = 0; i < ratingImages.length; i++) {
            $(ratingImages[i]).removeClass("ratingImageOn");
        }
        for (var i = 0; i < ratingNum; i++) {
            $(ratingImages[i]).addClass("ratingImageOn");
        }
    }

    //入力必須項目の確認
    $("input.required").on("change", function () {
        checkRequirements();
    });

    //入力必須項目の確認
    function checkRequirements() {
        var targetObjects = $("input.required");
        var fulfilledRequirements = true;
        for (var i = 0; i < targetObjects.length; i++) {
            var currentTarget = targetObjects[i];
            //console.log($(currentTarget).val());
            if ($(currentTarget).val() == "") {
                fulfilledRequirements = false;
                break;
            }
        }
        if (fulfilledRequirements) {
            activateSubmit();
        } else {
            inactivateSubmit();
        }
    }

    function activateSubmit() {
        $('#submit input[type="submit"]').removeClass("inactive");
        $('#submit input[type="submit"]').addClass("active");
    }

    function inactivateSubmit() {
        $('#submit input[type="submit"]').removeClass("active");
        $('#submit input[type="submit"]').addClass("inactive");
    }

    function saveFormContents() {
        var inputElement = $(".answer");
        //alert(inputElement.length);
        var inputValues = [];
        for (var i = 0; i < inputElement.length; i++) {
            //console.dir(inputElement[i]);
            inputValues.push($(inputElement[i]).val());
        }
        //alert(inputValues.join(", "));
        var inputValuesJson = JSON.stringify(inputValues);
        sessionStorage.setItem("inputValues", inputValuesJson);

        var inputRadioButtons = getCheckedRadioButtons();
        var inputRadioButtonsJson = JSON.stringify(inputRadioButtons);
        sessionStorage.setItem("inputRadioButtons", inputRadioButtonsJson);
        console.log("Saved in the session storage.");
    }

    function loadFormContents() {
        var savedInputValuesJson = sessionStorage.getItem("inputValues");
        if (savedInputValuesJson) {
            var savedInputValues = JSON.parse(savedInputValuesJson);
            var inputElement = $(".answer");
            for (var i = 0; i < savedInputValues.length; i++) {
                var currentInputElement = inputElement[i];
                var currentInputValue = savedInputValues[i];
                $(currentInputElement).val(currentInputValue);
            }
        } else {}
        var savedInputRadioButtonsJson = sessionStorage.getItem("inputRadioButtons");
        if (savedInputRadioButtonsJson) {
            var savedInputRadioButtons = JSON.parse(savedInputRadioButtonsJson);
            for (var j = 0; j < savedInputRadioButtons.length; j++) {
                var currentRadioButton = savedInputRadioButtons[j];
                var currentRadioButtonName = currentRadioButton.name;
                var currentRadioButtonValue = currentRadioButton.value;
                $('input[name="' + currentRadioButtonName + '"]').val([currentRadioButtonValue]);
            }
        } else {}
    }
        
    $("#reset").on("click", function(){
        $(".answer").val("");
        $('select[name="region"]').val("未選択");
        $('input[name=sex]').val(['無回答']);
        checkRating();
        checkRequirements();
        sessionStorage.clear();
//        $("#q1 input").val("");
    });
    
    //http://negimemo.net/2221
    //http://javascriptist.net/ref/form.submit.html
    //http://semooh.jp/jquery/api/events/submit/fn/
    //http://raining.bear-life.com/jquery/jquery%E3%81%A7submit%EF%BC%88%E3%82%B5%E3%83%96%E3%83%9F%E3%83%83%E3%83%88%EF%BC%89%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95
    //http://qiita.com/kazu56/items/cdbf4e371cdc699709f1
    $("#deleteSubmit").on("click", function(){
        if(confirm("このアンケート項目をデータベースから削除してよろしいですか？")==true){
            return true;
        }else{
            alert("キャンセルしました。");
            return false;
        }
    });
});