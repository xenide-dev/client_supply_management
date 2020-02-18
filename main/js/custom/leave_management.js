$(document).ready(function(){
    loadData();
    $("#employeeid1").select2({
        theme: 'bootstrap',
        dropdownParent: $(".bs-example-modal-sm")
    });
    $("#employeeid2").select2({
        theme: 'bootstrap',
        dropdownParent: $(".bs-use-credit")
    });

    $("#payroll_type").on('change', function(){
        var options = "<label>Select month:</label>" +
            "<select name=\"month\" class=\"form-control\" required>" +
                "<option value=\"\">-- Please select month --</option>" +
                "<option value=\"1\">January</option>" +
                "<option value=\"2\">February</option>" +
                "<option value=\"3\">March</option>" +
                "<option value=\"4\">April</option>" +
                "<option value=\"5\">May</option>" +
                "<option value=\"6\">June</option>" +
                "<option value=\"7\">July</option>" +
                "<option value=\"8\">August</option>" +
                "<option value=\"9\">September</option>" +
                "<option value=\"10\">October</option>" +
                "<option value=\"11\">November</option>" +
                "<option value=\"12\">December</option>" +
            "</select>" +
            "<br/>" +
            "<label>Select year:</label>" +
            "<select name=\"year\" class=\"form-control\" required>" +
            "<option value=\"\">-- Please select year --</option>";
        for (let index = new Date().getFullYear(); index > 2015; index--) {
            options += "<option value=" + index + ">" + index.toString() + "</option>";
        }
        options += "</select>" + "<br/>";

        if($(this).val() == "monthly"){
            $(".pay_container").append(options);
        }else{
            $(".pay_container").empty();
        }
    });
    $("#employeeid2").on('change', function(){
        getCredit($(this).val());
    });

    var count = 1;
    $("#btn_item_add").on('click', function(){
        var template = '<div class="row" id="cont_' + count + '">' +
                        '<div class="col-md-5 col-sm-12 col-xs-12">' +
                            '<select name="leave_type[]" class="form-control" required>' +
                                '<option value="">-- Please select a value --</option>' +
                                '<option value="basic">Basic</option>' +
                                '<option value="allowance">Allowance</option>' +
                                '<option value="rata">Rata</option>' +
                            '</select>' +
                        '</div>' +
                        '<div class="col-md-5 col-sm-12 col-xs-10">' +
                            '<input type="number" class="form-control" step="0.00001" name="amount[]" required>' +
                        '</div>' +
                        '<div class="col-md-2 col-sm-12 col-xs-2">' +
                            '<button type="button" class="btn btn-danger" onclick="removeItem(\'#cont_' + count + '\')"><span class="fa fa-minus"></span></button>' +
                        '</div>' +
                        '</div>';

        $(".item_container").append(template);
        count++;
    });

    // prevent credit deduction's form from submitting
    $('#frmCredits').on('submit', function(e) {
        e.preventDefault();
        const fetchPromises = swal({
            title: "Are you sure you want to continue?",
            text: "Once submitted, the data cannot be undone!",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        });
        fetchPromises.then((val) => {
            if(val){
                this.submit();
            }
        });
    });
});

function loadData(){
    var t = $('#datatable').DataTable();
    var loadingModal = $('#loading_modal');
    var loadingCircle = $('#loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    $.ajax({
        url: 'modules/leave_management_data.php',
        method: 'post',
        dataType: 'json',
        success: function(data){
            data.data.forEach(element => {
                t.row.add([
                    element.display_name,
                    element.sick_leave,
                    element.vacation_leave,
                    element.total,
                    "<a href=\"view_leave.php?lid=" + element.lid + "&employeeid=" + element.employeeid + "\" class=\"btn btn-success btn-xs\"><span class=\"fa fa-search\"></span> View</a>"
                ]).draw();
            });
            loadingCircle.css({'display' : 'none'});
            loadingModal.css({'display' : 'none'});
        }
    });
}

function getCredit(employeeid){
    $.ajax({
        url: 'modules/ret_credit.php',
        method: 'post',
        data: {
            employeeid: employeeid
        },
        dataType: 'json',
        success: function(data){
            $("#sick_leave").prop("max", data.sick);
            $("#sick_leave").prop("placeholder", "Total Available Credits: " + data.sick);
            $("#vacation_leave").prop("max", data.vacation);
            $("#vacation_leave").prop("placeholder", "Total Available Credits: " + data.vacation);
        },
        error: function(err){
            console.log(err.message);
        }
    });
}

function removeItem(id){
    $(id).remove();
    reCalc();
}