$(document).ready(function(){
    var t = $('#dtList').DataTable();
    loadList(t);

    $("input[class*='rowC_']").on('keyup', function(){
        var val = parseFloat(($(this).val() == "" ? 0 : $(this).val()));
        var qtyClass = this.className.split(' ')[1].replace('C', 'Q');
        var qty = parseFloat($("." + qtyClass).text());
        var total = val * qty;
        var totalClass = this.className.split(' ')[1].replace('C', 'T');
        $("." + totalClass).val(total);
    })

    // prevent credit deduction's form from submitting
    $('#frmList').on('submit', function(e) {
        e.preventDefault();
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
    });
});

function loadData(id, tableName, containerID){
    if(tableName == 'request'){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/retrieve_list.php',
            dataType: 'JSON',
            data: {
                type: 'request',
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
            type: 'request',
            operation: 'getAllRequest'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var view_items = `<button class="btn btn-primary btn-xs" onclick="loadData(` + element.rid + `, 'request', '#requestItemsContainer tbody');" data-toggle="modal" data-target=".view_request">View Items</button>`;
                    var issuance = `<button class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'issuance', 'Ready', this)"> Ready for Issuance</button>`;
                    var prepare_purchase_order = `<a href="list_requests.php?type=make_order&rid=` + element.rid + `" class="btn btn-success btn-xs" onclick="loadData(` + element.rid + `, 'item')" > Prepare Purchase Order</a>`;
                    var accept = `<button type="button" class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'request', 'Accepted', this)"> Accept</button>`;
                    var requestedBy = element.lname + ", " + element.fname + " " + element.midinit;
                    var status = "";
                    if(element.status == "Pending"){
                        status = '<span class="label label-warning">Pending</span>';
                    }else if(element.status == "Approved"){
                        status = '<span class="label label-success">Approved</span>';
                    }else if(element.status == "Disapproved"){
                        status = '<span class="label label-success">Disapproved</span>';
                    }else if(element.status == "Delivered"){
                        status = '<span class="label label-success">Delivered</span>';
                    }else if(element.status == "Pending Items"){
                        status = '<span class="label label-success">Pending Items</span>';
                    }else if(element.status == "Processing"){
                        status = '<span class="label label-warning">Processing</span>';
                    }else if(element.status == "Inspected"){
                        status = '<span class="label label-primary">Inspected</span>';
                    }else if(element.status == "Ready"){
                        status = '<span class="label label-success">Ready for Issuance</span>';
                    }else if(element.status == "Accepted"){
                        status = '<span class="label label-success">Accepted</span>';
                    }

                    var actions = view_items;
                    if(element.request_type == "Requisition"){
                        if(element.status == "Processing"){
                            actions += issuance;
                        }
                    }else if(element.request_type == "Purchase Request"){
                        if(element.status == "Approved"){
                            actions += prepare_purchase_order;
                        }else if(element.status == "Inspected"){
                            actions += accept;
                        }else if(element.status == "Accepted"){
                            actions += issuance;
                        }
                    }
                    t.row.add([
                        element.request_no,
                        element.created_at,
                        element.request_type,
                        requestedBy,
                        element.request_purpose,
                        status,
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

function processAction(rid, type, status, ref){
    if(type == "request"){
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
                        type: 'purchase',
                        operation: 'processPurchase',
                        rid: rid,
                        action: status
                    },
                    success: function(data){
                        if(data.msg){
                            swal("Success!", "Inspection Report has been accepted!", "success").then((value) => {
                                window.location.reload();
                            });
                        }
                    }
                });
            }
        });
    }else if(type == "issuance"){
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
                        type: 'purchase',
                        operation: 'processIssuance',
                        rid: rid,
                        action: status
                    },
                    success: function(data){
                        if(data.msg){
                            swal("Thank you!", "The system will notify the user about the request.", "success").then((value) => {
                                window.location.reload();
                            });
                        }
                    }
                });
            }
        })
    }
}