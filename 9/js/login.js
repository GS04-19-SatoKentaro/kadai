//全て読み込んでから処理
$(function () {
    //    alert("alert");
    //    saveFormContents();
    loadFormContents();
    checkRequirements();

    //テスト用ボタン
    $("#test").on("click", function () {
//        var checkedRadioButtonValues = getCheckedRadioButtons();
//        alert(checkedRadioButtonValues);
    });

    $(".answer").on("change", function () {
        saveFormContents();
    });

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
        $('input[type="submit"]').removeClass("inactive");
        $('input[type="submit"]').addClass("active");
    }

    function inactivateSubmit() {
        $('input[type="submit"]').removeClass("active");
        $('input[type="submit"]').addClass("inactive");
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
        sessionStorage.setItem("loginValues", inputValuesJson);

        console.log("Saved in the session storage.");
    }

    function loadFormContents() {
        var savedInputValuesJson = sessionStorage.getItem("loginValues");
        if (savedInputValuesJson) {
            var savedInputValues = JSON.parse(savedInputValuesJson);
            var inputElement = $(".answer");
            for (var i = 0; i < savedInputValues.length; i++) {
                var currentInputElement = inputElement[i];
                var currentInputValue = savedInputValues[i];
                $(currentInputElement).val(currentInputValue);
            }
        } else {}
    }
        
    $("#reset").on("click", function(){
        $(".answer").val("");
        checkRequirements();
        sessionStorage.clear();
    });
});