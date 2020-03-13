$(document).ready(function(){
    $('[data-inputmask]').inputmask();

    $("#btnUpdateInfo").on('click', function(){
        $('#frmUpdate').parsley().validate();
        if(true === $('#frmUpdate').parsley().isValid()){
            // process update
            $.ajax({
                method: 'POST',
                url: '_modules/asyncUrls/data_entry.php',
                dataType: 'JSON',
                data: {
                    type: 'account',
                    operation: 'update',
                    fname: $("#fname").val(),
                    mname: $("#mname").val(),
                    lname: $("#lname").val(),
                    birthdate: $("#birthdate").val(),
                    gender: $("#gender").val(),
                    citizenship: $("#citizenship").val(),
                    religion: $("#religion").val(),
                    address: $("#address").val(),
                    contact_mobile: $("#contact_mobile").val(),
                    contact_email: $("#contact_email").val(),
                },
                success: function(data){
                    if(data.msg){
                        swal({
                            title: "Success!",
                            text: "Your information has been updated",
                            icon: "success",
                            button: "Okay!",
                        });
                    }
                }
            });
        }else{
            swal({
                title: "Error!",
                text: "Please check your data fields.",
                icon: "error",
                button: "Okay!",
            });
        }
    });
});