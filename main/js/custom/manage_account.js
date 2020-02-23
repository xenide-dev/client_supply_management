$(document).ready(function(){
    $('#datatable2').dataTable();
    $("#employeeid").on('keyup change', function(){
        $("#username").val($(this).val());
    });
    $("#up_employeeid").on('keyup change', function(){
        $("#up_username").val($(this).val());
    });
});

// functions
function showInfo(uid, fname, mname, lname, privileges) {
    $('#uid').val(uid);
    $('#fname').val(fname);
    $('#mname').val(mname);
    $('#lname').val(lname);

    var priv = privileges;
    if(priv.indexOf('employee') != -1){
        setSwitchState("#employee", true);
    }else{
        setSwitchState("#employee", false);
    }

    if(priv.indexOf('emp_status') != -1){
        setSwitchState("#emp_status", true);
    }else{
        setSwitchState("#emp_status", false);
    }

    if(priv.indexOf('rank') != -1){
        setSwitchState("#rank", true);
    }else{
        setSwitchState("#rank", false);
    }

    if(priv.indexOf('department') != -1){
        setSwitchState("#department", true);
    }else{
        setSwitchState("#department", false);
    }

    if(priv.indexOf('fund') != -1){
        setSwitchState("#fund", true);
    }else{
        setSwitchState("#fund", false);
    }

    if(priv.indexOf('salary_grade') != -1){
        setSwitchState("#salary_grade", true);
    }else{
        setSwitchState("#salary_grade", false);
    }

    if(priv.indexOf('set_appointment') != -1){
        setSwitchState("#set_appointment", true);
    }else{
        setSwitchState("#set_appointment", false);
    }

    if(priv.indexOf('manage_account') != -1){
        setSwitchState("#manage_account", true);
    }else{
        setSwitchState("#manage_account", false);
    }

    if(priv.indexOf('leave_management') != -1){
        setSwitchState("#leave_management", true);
    }else{
        setSwitchState("#leave_management", false);
    }

    if(priv.indexOf('reports') != -1){
        setSwitchState("#reports", true);
    }else{
        setSwitchState("#reports", false);
    }

    if(priv.indexOf('payroll') != -1){
        setSwitchState("#payroll", true);
    }else{
        setSwitchState("#payroll", false);
    }

    if(priv.indexOf('inclusion') != -1){
        setSwitchState("#inclusion", true);
    }else{
        setSwitchState("#inclusion", false);
    }

    if(priv.indexOf('deduction') != -1){
        setSwitchState("#deduction", true);
    }else{
        setSwitchState("#deduction", false);
    }

    if(priv.indexOf('withhold') != -1){
        setSwitchState("#withhold", true);
    }else{
        setSwitchState("#withhold", false);
    }
}

function setSwitchState(id, state){
    var clickCheckbox = document.querySelector(id);
    if(state == true){
        if(!clickCheckbox.checked){
            $(id).click();
        }
    }else{
        if(clickCheckbox.checked){
           $(id).click();
        }
    }
}

function editRow(uid){
    // reset checkbox
    var switchF = $("#frmUpdate .js-switch");
    for (let index = 0; index < switchF.length; index++) {
        const element = switchF[index];
        if(element.checked){
            $("#" + element.id).click();
        }
    }
    
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/manage_account.php',
        dataType: 'JSON',
        data: {
            uid: uid
        },
        success: function(data){
            if(data.msg){
                $("#uid").val(data.info.uid);
                $("#up_employeeid").val(data.info.employeeid);
                $("#up_username").val(data.info.employeeid);
                $("#fname").val(data.info.fname);
                $("#mname").val(data.info.mname);
                $("#lname").val(data.info.lname);
                $("#birthdate").val(data.info.birthdate);
                $("#citizenship").val(data.info.citizenship);
                $("#religion").val(data.info.religion);
                $("#address").val(data.info.address);
                $("#contact_mobile").val(data.info.contact_mobile);
                $("#contact_email").val(data.info.contact_email);
                $("#gender").val(data.info.gender);
                $("#user_type").val(data.info.user_type);


                var privileges = data.info.priviledges.split(",");
                privileges.forEach(element => {
                    setSwitchState("#" + element, true);
                });
            }
        }
    })
}