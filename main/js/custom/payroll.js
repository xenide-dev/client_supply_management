$(document).ready(function(){
    // $("#rec-all").on('change', function(){
    //     $("input[type='checkbox'][id^='rec_']").each(function(){
    //         if($(this).prop('checked') != $("#rec-all").prop("checked")){
    //             $(this).prop('checked', $("#rec-all").prop("checked"));
    //             var adjValue = parseFloat($(this).val().split("-")[1]);
    //             var totalReceivables = parseFloat($("#total_receivables").val());
    //             var totalDeductions = parseFloat($("#total_deductions").val());
    //             var netPay = 0;
                
    //             if($(this).prop("checked")){
    //                 totalReceivables += adjValue;
    //             }else{
    //                 totalReceivables -= adjValue;
    //             }

    //             netPay = totalReceivables - totalDeductions;

    //             $("#total_receivables").val(totalReceivables);
    //             $("#net_pay").val(netPay);
    //         }
    //     })
    // });
    // $("#de-all").on('change', function(){
    //     $("input[type='checkbox'][id^='de_']").each(function(){
    //         if($(this).prop('checked') != $("#de-all").prop("checked")){
    //             $(this).prop('checked', $("#de-all").prop("checked"));
    //             var adjValue = parseFloat($(this).val().split("-")[1]);
    //             var totalReceivables = parseFloat($("#total_receivables").val());
    //             var totalDeductions = parseFloat($("#total_deductions").val());
    //             var netPay = 0;
                
    //             if($(this).prop("checked")){
    //                 totalDeductions += adjValue;
    //             }else{
    //                 totalDeductions -= adjValue;
    //             }

    //             netPay = totalReceivables - totalDeductions;

    //             $("#total_deductions").val(totalDeductions);
    //             $("#net_pay").val(netPay);
    //         }
            
    //     })
    // });
    // $("input[type='checkbox'][id^='rec_']").on('change', function(){
    //     var adjValue = parseFloat($(this).val().split("-")[1]);
    //     var totalReceivables = parseFloat($("#total_receivables").val());
    //     var totalDeductions = parseFloat($("#total_deductions").val());
    //     var netPay = 0;
        
    //     if($(this).prop("checked")){
    //         totalReceivables += adjValue;
    //     }else{
    //         totalReceivables -= adjValue;
    //     }

    //     netPay = totalReceivables - totalDeductions;

    //     $("#total_receivables").val(totalReceivables);
    //     $("#net_pay").val(netPay);
    // });
    // $("input[type='checkbox'][id^='de_']").on('change', function(){
    //     var adjValue = parseFloat($(this).val().split("-")[1]);
    //     var totalReceivables = parseFloat($("#total_receivables").val());
    //     var totalDeductions = parseFloat($("#total_deductions").val());
    //     var netPay = 0;
        
    //     if($(this).prop("checked")){
    //         totalDeductions += adjValue;
    //     }else{
    //         totalDeductions -= adjValue;
    //     }

    //     netPay = totalReceivables - totalDeductions;

    //     $("#total_deductions").val(totalDeductions);
    //     $("#net_pay").val(netPay);
    // });
    $("#did").on('change', function(){
        if($(this).val() != ""){
            $("#employeeid").empty().append("<option value=''> -- Please select a value -- </option>");
            $.ajax({
                type: 'POST',
                data:{
                    did: $("#did").val()
                },
                url: 'modules/ret_employee.php',
                dataType: 'JSON',
                success: function(result){
                    for (let index = 0; index < result.employee.length; index++) {
                        employeeid = result.employee[index].employeeid;
                        emp_name = result.employee[index].full_name;
                        $("#employeeid").append("<option value='" + employeeid + "'>" + emp_name + "</option>");
                    }
                }
            });
        }
    });

    var counter = 0;
    $("#add_inc").on('click', function(){
        $.ajax({
            type: 'POST',
            url: 'modules/ret_adjustment.php',
            data: {
                adj_type: 'inc'
            },
            dataType: 'JSON',
            success : function(data){
                var temp = "<tr id=\"row" + counter + "\">" +
                                "<td>" +
                                "<select name=\"inc_id[]\" class=\"form-control\" required>" +
                                    "<option value=\"\">-- Please select a value --</option>";
                data.items.forEach(function(item, index){
                    temp += "<option value=\"" + item.inc_id + "\">" + item.inc_name + "</option>";
                });
                temp += "</select>" +
                        "</td>" +
                        "<td>" +
                        '<input type="text" class="form-control lop_inc" name="amount_inc[]" required onkeyup="reCalc();">' +
                        "</td>" +
                        "<td>" +
                        "<button type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem('#row" + counter + "')\"><span class=\"fa fa-trash\"></span></button>" +
                        "</td>" +
                    "</tr>";
                $("#rec_container").append(temp);
                counter++;
            },
            error: function(data){

            }
        });
    });

    var counter1 = 0;
    $("#add_deduc").on('click', function(){
        $.ajax({
            type: 'POST',
            url: 'modules/ret_adjustment.php',
            data: {
                adj_type: 'deduc'
            },
            dataType: 'JSON',
            success : function(data){
                var temp = "<tr id=\"rowd" + counter1 + "\">" +
                                "<td>" +
                                "<select name=\"de_id[]\" class=\"form-control\" required>" +
                                    "<option value=\"\">-- Please select a value --</option>";
                data.items.forEach(function(item, index){
                    temp += "<option value=\"" + item.de_id + "\">" + item.de_name + "</option>";
                });
                temp += "</select>" +
                        "</td>" +
                        "<td>" +
                        '<input type="text" class="form-control lop_de" name="amount_de[]" required onkeyup="reCalc();">' +
                        "</td>" +
                        "<td>" +
                        "<button type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem('#rowd" + counter1 + "')\"><span class=\"fa fa-trash\"></span></button>" +
                        "</td>" +
                    "</tr>";
                $("#deduc_container").append(temp);
                counter++;
            },
            error: function(data){

            }
        });
    });

    
});
function removeItem(id){
    $(id).remove();
    reCalc();
}
function reCalc(){
    var netPay = 0.0;

    // total receivables
    var total_inc = 0;
    $('.lop_inc').each(function(){
        var value = $(this).val();
        if(value != "" && $.isNumeric(value)){
            total_inc = total_inc + parseFloat(value);
        }
    });

    // total deductions
    var total_de = 0;
    $('.lop_de').each(function(){
        var value = $(this).val();
        if(value != "" && $.isNumeric(value)){
            total_de = total_de + parseFloat(value);
        }
    });
    
    var salary = parseFloat($("#salary").text());
    var totalReceivables = salary + total_inc;

    // total receivables
    $("#displayRecTotal").text("Php. " + new Intl.NumberFormat('en-PH', { style: 'decimal', currency: 'PHP', minimumFractionDigits: 2 }).format(total_inc.toFixed(2)));
    $("#displayDeducTotal").text("Php. " + new Intl.NumberFormat('en-PH', { style: 'decimal', currency: 'PHP', minimumFractionDigits: 2 }).format(total_de.toFixed(2)));

    netPay = totalReceivables - total_de;
    $("#total_receivables").val(totalReceivables.toFixed(2));
    $("#total_deductions").val(total_de.toFixed(2));
    $("#net_pay").val(netPay.toFixed(2));
}