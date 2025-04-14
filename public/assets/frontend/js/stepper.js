var currentStep = 1;
var updateProgressBar;
var circleActive;
var circleInActive

function displayStep(stepNumber) {
    if (stepNumber >= 1 && stepNumber <= 3) {
        $(".step-" + currentStep).hide();
        $(".step-" + stepNumber).show();
        currentStep = stepNumber;
        updateProgressBar();
    }
}

$(document).ready(function () {
    $("#multi-step-form").find(".step").slice(1).hide();

    $(".next-step").click(function () {
        if (currentStep < 3) {
            var formValid = 0;
            $('.printproducthid').each(function() {
                let pID = $(this).val();
                let totalPQuantity = $(this).data('productquantity');
                console.log(pID+'-'+totalPQuantity);
                if($('.productquant-'+pID).length > 0){
                    let total = 0;
                    $('.productquant-'+pID).each(function() {
                        //var pID = $(this).val();
                        let selectedQuantityValue = parseInt($(this).val()) || 0;
                        total += selectedQuantityValue;
                        
                    });
                    if(total == parseInt(totalPQuantity)){
                        console.log('match-'+pID);
                        $('#error-product-'+pID).hide().html('');
                    }else{
                        console.log('missmatch-'+pID);
                        $('#error-product-'+pID).show().html('Selected quantities do not match the bundle quantity');
                        formValid=1;
                    }
                }
                
            });
            if(formValid > 0){
                return false;
            }
            

            $(".step-" + currentStep).addClass(
                "animate__animated animate__fadeOutLeft"
            );
            currentStep++;
            setTimeout(function () {
                $(".step")
                    .removeClass("animate__animated animate__fadeOutLeft")
                    .hide();
                $(".step-" + currentStep)
                    .show()
                    .addClass("animate__animated animate__fadeInRight");
                updateProgressBar();
                circleActive(currentStep);
            }, 500);
        }
    });

    $(".prev-step").click(function () {
        if (currentStep > 1) {
            $(".step-" + currentStep).addClass(
                "animate__animated animate__fadeOutRight"
            );
            currentStep--;
            setTimeout(function () {
                $(".step")
                    .removeClass("animate__animated animate__fadeOutRight")
                    .hide();
                $(".step-" + currentStep)
                    .show()
                    .addClass("animate__animated animate__fadeInLeft");
                updateProgressBar();
                circleInActive(currentStep);
            }, 500);
        }
    });

    updateProgressBar = function () {
        var progressPercentage = ((currentStep - 1) / 2) * 100;
        $(".progress-bar").css("width", progressPercentage + "%");
    };

    circleActive = function () {
        console.log(currentStep);
        for(var i = 1; i < currentStep; i++){
            $('#step-circle'+i).addClass('active');
        }
    };

    circleInActive = function () {
        console.log(currentStep);
        for(var i = 1; i < 3; i++){
            if(i == currentStep){
                $('#step-circle'+i).removeClass('active');
            }
            
        }
    };

    $(".productsize").on('change', function () {

        // let totalCost = 0.00;

        let SizeType = $(this).data('size');
        let extraCost = parseFloat($(this).data('cost'))
        let quantity = $(this).val();

        if(extraCost > 0 && quantity > 0){

            Swal.fire({
                icon: "info",
                text: `$${extraCost} will be charged for each garment of ${SizeType}`
              });
        }

        // $('.productsize').each(function() {
        //     let selectedOption = $(this).find(':selected');
        //     let quantity = selectedOption.val();
        //     let cost = selectedOption.data('cost');

        //     console.log('cost', selectedOption)


        //     totalCost = parseFloat(totalCost + (quantity * cost))

        //     console.log('cost', totalCost);
        // })

    });

    
});
