$(document).ready(function(){
    // $("#submit").on('click', function(){
    //     subEntry(this);
    // });
    $("#isLifetime").on('change', function(){
        if($(this).prop("checked")){
            $("#effectivity_date_end").prop("disabled" ,true);
        }else{
            $("#effectivity_date_end").prop("disabled" ,false);
        }
    });
});
function subEntry(btn){
    $(btn).attr("disabled", true);
    $.ajax({
        type: 'POST',
        url: 'modules/adj_data.php',
        data: {
            employeeid: $("#employeeid").val(),
            effectivity_date_start: $("#effectivity_date_start").val(),
            effectivity_date_end: $("#effectivity_date_end").val(),
            adj_id: $("#adj_id").val(),
            adj_value: $("#adj_value").val(),
            isLifetime: $("#isLifetime").prop("checked")
        },
        dataType: 'JSON',
        success : function(data){
            displaySuccess("Data has been submitted");
            $(btn).attr("disabled", false);
        },
        error: function(data){
            displayError("Something went wrong");
        }
    });
}

function displaySuccess(msg){
    new PNotify({
        title: 'Success!!!',
        text: msg,
        type: 'success',
        styling: 'bootstrap3',
        delay: 5000
    });
}
function displayError(msg){
    new PNotify({
        title: 'Oops! Error!',
        text: msg,
        type: 'error',
        styling: 'bootstrap3',
        delay: 5000
    });
}