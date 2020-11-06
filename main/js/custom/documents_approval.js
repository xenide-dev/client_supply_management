$(document).ready(function(){
    $("#dtList").DataTable();
});

function formatCurrency(val, isIncludeDecimal){
    if(isIncludeDecimal){
        return new Intl.NumberFormat('en-PH', { style: 'decimal', currency: 'PHP', minimumFractionDigits: 2 }).format(val);
    }else{
        // get the whole number only
        if(val.indexOf('.') > 0){
            var wholeVal = val.substr(0, val.indexOf('.'));
            var f = wholeVal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            return f + val.substr(val.indexOf('.'));
        }else{
            return val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
    }
}

function loadData(id, tableName, containerID, additional_info = ''){
    if(tableName == 'request'){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/retrieve_list.php',
            dataType: 'JSON',
            data: {
                type: 'request',
                id: id,
                operation: (additional_info == 'PO' ? 'getAllItemsPO' : 'getAllItems')
            },
            success: function(data){
                if(data.msg){
                    $(containerID).empty();
                    var temp = "";
                    if(additional_info == ""){
                        temp = `<table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Code</th>
                                            <th>Item Name/Description</th>
                                            <th>Quantity</th>
                                            <th>Unit of Measure</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        $.each( data.info, function( key, value ) {
                            var t = `<tr>
                                        <td>${key + 1}</td>
                                        <td>${value.item_code}</td>
                                        <td>${value.item_name} / ${value.item_description}</td>
                                        <td>${value.requested_qty}</td>
                                        <td>${value.requested_unit}</td>
                                    </tr>`;
                            temp += t;
                        });       
                        temp += `</tbody>
                            </table>`;
                        $(containerID).append(temp);
                    }else{
                        $.each( data.info, function( key, value ) {
                            temp = 
                                `<p>PO #: <b>${value.po_number}</b></p>
                                <p>Supplier Name: <b>${value.supplier_name}</b></p>
                                <p>Supplier Address: <b>${value.supplier_address}</b></p>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Code</th>
                                            <th>Item Name/Description</th>
                                            <th>Quantity</th>
                                            <th>Unit of Measure</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                            $.each( value.items, function( key, value ) {
                                var t = `<tr>
                                            <td>${key + 1}</td>
                                            <td>${value.item_code}</td>
                                            <td>${value.item_name} / ${value.item_description}</td>
                                            <td>${value.requested_qty}</td>
                                            <td>${value.requested_unit}</td>
                                            <td>₱ ${formatCurrency(value.unit_cost, true)}</td>
                                            <td>₱ ${formatCurrency(value.total_cost, true)}</td>
                                        </tr>`;
                                temp += t;
                            });       
                            temp += `</tbody>
                                </table>
                                <h4>Total Amount: <b>₱ ${formatCurrency(value.total_amount, true)}</b></h4>
                                <hr/>`;
                            
                            $(containerID).append(temp);
                        });
                    }
                }
            }
        });
    }else if(tableName == "transfer"){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/retrieve_list.php',
            dataType: 'JSON',
            data: {
                type: 'transfer',
                id: id,
                operation: 'getAllItems'
            },
            success: function(data){
                if(data.msg){
                    $(containerID).empty();
                    $.each( data.info, function( key, value ) {
                        var temp = 
                            `<tr>
                                <th scope="row">` + (key + 1) + `</th>
                                <td>` + value.item_code + `</td>
                                <td>` + (value.item_name + " (" + value.item_description + ")") + `</td>
                                <td>` + value.requested_qty + `</td>
                                <td>` + value.requested_unit + `</td>
                            </tr>`;
                        $(containerID).append(temp);
                    });
                }
            }
        });
    }
}

function processAction(tid, rid, uid, action, tracer_no, request_type, ref){
    swal({
        title: "Are you sure you want to continue?",
        text: 'Once you proceed, the process cannot be undone!',
        icon: "warning",
        buttons: ["No", "Yes"],
        dangerMode: true,
    }).then((value) => {
        if(value){
            // process ajax submission
            $.ajax({
                method: 'POST',
                url: 'modules/asyncUrls/data_entry.php',
                dataType: 'JSON',
                data: {
                    type: 'request',
                    operation: 'processRequest',
                    tid: tid,
                    rid: rid,
                    uid: uid,
                    action: action,
                    tracer_no: tracer_no,
                    request_type: request_type
                },
                success: function(data){
                    if(data.msg){
                        var temp = $("#dtList").DataTable().row($(ref).parents('tr')).data();
                        if(action == "Approved"){
                            temp[5] = '<span class="label label-success">Approved</span>';
                        }else{
                            temp[5] = '<span class="label label-danger">Disapproved</span>';
                        }
                        $("#dtList").dataTable().fnUpdate(temp,$(ref).parents('tr'),undefined,false);
                        $(".row_" + tid).remove();
                        swal("Success!", "Request has been '" + action + "'", "success");
                    }
                }
            });
        }
    });
}

function processTransfer(stid, action, ref){
    swal({
        title: "Are you sure you want to continue?",
        text: 'Once you proceed, the process cannot be undone!',
        icon: "warning",
        buttons: ["No", "Yes"],
        dangerMode: true,
    }).then((value) => {
        if(value){
            // process ajax submission
            $.ajax({
                method: 'POST',
                url: 'modules/asyncUrls/data_entry.php',
                dataType: 'JSON',
                data: {
                    type: 'transfer',
                    operation: 'processRequest',
                    stid: stid,
                    action: action,
                },
                success: function(data){
                    if(data.msg){
                        var temp = $("#dtList").DataTable().row($(ref).parents('tr')).data();
                        if(action == "Approved"){
                            temp[5] = temp[5].replace("Pending", "Approved");
                        }else{
                            temp[5] = temp[5].replace("Pending", "Disapproved");
                        }
                        $("#dtList").dataTable().fnUpdate(temp,$(ref).parents('tr'),undefined,false);
                        $(".row_" + stid).remove();
                        swal("Success!", "Request has been '" + action + "'", "success");
                    }
                }
            });
        }
    });
}