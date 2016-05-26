//全て読み込んでから処理
$(function () {
    //    alert("alert");
    //    saveFormContents();
    checkRequirements();

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

        
    $("#reset").on("click", function(){
        $(".answer").val("");
        checkRequirements();
        sessionStorage.clear();
    });
});