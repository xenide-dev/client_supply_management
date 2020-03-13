var totalItems;
$(document).ready(function(){
    var t = $('#dtList').DataTable();
    var t1 = $('#dtAPP').DataTable();
    loadList(t);
    loadAPP(t1);

    totalItems = 0;
    // prevent app's form from submitting
    $('#frmList').on('submit', function(e) {
        e.preventDefault();
        if($("#app_year").val() == ""){
            swal({
                title: "Error",
                text: "Please select a year",
                icon: "error",
                buttons: 'Okay'
            });
        }else{
            if(totalItems > 0){
                const fetchPromises = swal({
                    title: "Are you sure you want to submit this record?",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true,
                });
                fetchPromises.then((val) => {
                    if(val){
                        this.submit();
                    }
                });
            }else{
                swal({
                    title: "Error",
                    text: "Please add at least 1 item",
                    icon: "error",
                    buttons: 'Okay'
                });
            }
        }
    });

    $("#app_year").on('change', function(){
        totalItems = 0;
        $("#tblConsolidated tbody").empty();
    });
});

function loadList(t){
    var loadingModal = $('#loading_modal');
    var loadingCircle = $('#loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'ppmp',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                        `<button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".ppmp_items" data-backdrop="static" onclick="loadItems(${element.pid})">View Items</button>
                        <a href="modules/pdf_generator/generate_ppmp_pdf.php?pid=${element.pid}&h=${element.hash}" class="btn btn-primary btn-xs" target="_blank">Generate PDF</a>`;
                    var fullName = element.lname + ", " + element.fname + " " + element.midinit + ".";
                    t.row.add([
                        element.created_at,
                        element.ppmp_year,
                        fullName,
                        element.total_supplies,
                        element.total_equipments,
                        actions
                    ]).draw();
                });
                loadingCircle.css({'display' : 'none'});
                loadingModal.css({'display' : 'none'});
            }else{
                alert('Error');
            }
        }
    });
}

function loadItems(pid){
    var t = $('#dtListItems').DataTable();
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'ppmp',
            operation: 'getAllItems',
            pid: pid
        },
        success: function(data){
            t.clear();
            if(data.msg){
                var counter = 1;
                data.info.forEach(element => {
                    t.row.add([
                        counter,
                        element.item_name + " (" + element.item_description + ")",
                        element.requested_qty + " (" + element.requested_unit + ")",
                        element.mon_jan,
                        element.mon_feb,
                        element.mon_mar,
                        element.mon_apr,
                        element.mon_may,
                        element.mon_jun,
                        element.mon_jul,
                        element.mon_aug,
                        element.mon_sep,
                        element.mon_oct,
                        element.mon_nov,
                        element.mon_dec
                    ]).draw();
                    counter++;
                });
            }else{
                alert('Error');
            }
        }
    });
}

function loadConsolidated(){
    if($("#app_year").val() == ""){
        swal({
            title: "Error",
            text: "Please select a year",
            icon: "error",
            buttons: 'Okay'
        });
    }else{
        $("#tblConsolidated tbody").empty();
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/retrieve_list.php',
            dataType: 'JSON',
            data: {
                type: 'ppmp',
                operation: 'consolidate',
                app_year: $("#app_year").val()
            },
            success: function(data){
                if(data.msg){                    
                    totalItems = data.info.length;
                    data.info.forEach(function(value, index){
                        var template = `<tr class="selected">
                                            <td>
                                                <input type="checkbox" class="flat" name="chk_records[]" checked>
                                                <input type="hidden" name="itemid[]" readonly value="${value.itemid}">
                                            </td>
                                            <td>${value.item_name} (${value.item_description})</td>
                                            <td>${value.total} <input type="hidden" name="requested_qty[]" readonly value="${value.total}"></td>
                                            <td>${value.requested_unit} <input type="hidden" name="requested_unit[]" readonly value="${value.requested_unit}"></td>
                                        </tr>`;
                        $("#tblConsolidated tbody").append(template);
                    });
                    init_iCheck();
                }
            }
        });
        
    }
    
}

function init_iCheck(){
    $('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
    $('table input').on('ifChecked', function () {
        totalItems++;
        var pTr = $(this).parents('tr');
        $(pTr).addClass("selected");
        var texts = $(pTr).find("input[type='text']");
        texts.each(function() {
            $(this).prop("disabled", false);
        });
    });
    $('table input').on('ifUnchecked', function () {
        totalItems--;
        var pTr = $(this).parents('tr');
        $(pTr).removeClass("selected");
        var texts = $(pTr).find("input[type='text']");
        texts.each(function() {
            $(this).prop("disabled", true);
        });
    });
}

function loadAPP(t){
    var loadingModal = $('.loading_modal');
    var loadingCircle = $('.loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'app',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                        `<button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".app_items" data-backdrop="static" onclick="loadAPPItems(${element.aid})">View Items</button>`;
                    t.row.add([
                        element.created_at,
                        element.app_year,
                        element.total_supplies,
                        element.total_equipments,
                        actions
                    ]).draw();
                });
                loadingCircle.css({'display' : 'none'});
                loadingModal.css({'display' : 'none'});
            }else{
                alert('Error');
            }
        }
    });
}

function loadAPPItems(aid){
    var t = $('#dtAPPItems').DataTable();
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'app',
            operation: 'getAllItems',
            aid: aid
        },
        success: function(data){
            t.clear();
            if(data.msg){
                var counter = 1;
                data.info.forEach(element => {
                    t.row.add([
                        counter,
                        element.item_name + " (" + element.item_description + ")",
                        element.requested_qty + " (" + element.requested_unit + ")",
                    ]).draw();
                    counter++;
                });
            }else{
                alert('Error');
            }
        }
    });
}