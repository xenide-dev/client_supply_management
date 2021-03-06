$(document).ready(function(){
    var t = $('#dtList').DataTable();
    var o = $('#dtOtherList').DataTable();
    loadList(t);
    loadOtherList(o);

    $("input[class*='rowC_']").on('keyup change', function(){
        var thisVal = $(this).val().replace(/,/g, '');
        $(this).val(formatCurrency(thisVal, false));

        var val = parseFloat((thisVal == "" ? 0 : thisVal));
        var qtyClass = this.className.split(' ')[1].replace('C', 'Q');
        var qty = parseFloat($("." + qtyClass).text());
        var total = val * qty;
        var totalClass = this.className.split(' ')[1].replace('C', 'T');
        $("." + totalClass).val(formatCurrency(total, true));

        
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

    // onload
    addToTable("#orig_po");

    // add purchase order number
    var pCounter = 1;
    var prev = null;
    $("#btnAddOrder").on('click', function(){
        var orig_po = $("#orig_po").val();

        // check if orig po is empty
        if(orig_po != ""){
            var arr = orig_po.split("-");
            var curr = new Date();
            var code = "";
            if(arr.length >= 3){
                if(prev == null){
                    prev = parseInt(arr[2]) + 1;
                }else{
                    prev = prev + 1;
                }
                var code1 = ("00000" + prev).substr(-5,5);
                code = "PO No. " + curr.getFullYear() + "-" + ("00" + (curr.getMonth() + 1)).substr(-2,2) + "-" + code1;
            }

            var temp = `<div class="row poi_${pCounter}">
                            <div class="col-md-2">
                                <label>Purchase Order No.:</label>
                                <input type="text" class="form-control" name="po_number[]" placeholder="Please enter purchase order number" onchange="addToTable(this)" required value="${code}">
                            </div>
                            <div class="col-md-3">
                                <label>Supplier Name: </label>
                                <input type="text" class="form-control" name="supplier_name[]" placeholder="Please enter supplier name" required>
                            </div>
                            <div class="col-md-6">
                                <label>Supplier Address: </label>
                                <input type="text" class="form-control" name="supplier_address[]" placeholder="Please enter supplier address" required>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger form-control" onclick="deletePurchaseRow('.poi_${pCounter}')"><span class="fa fa-trash"></span></button>
                            </div>
                        </div>`;
            $(".purchase_order_container").append(temp);
            
            addToTable(".poi_" + pCounter + " [name*='po_number'");
            pCounter++;
        }else{
            swal("Error!", "Purchase order number is empty", "error");
        }
    });

    // sort by
    $("#sort_by").on('change', function(){
        loadList(t, $(this).val());
    });
});


function addToTable(id){
    var p = $(id).parents(".row");
    // get the value
    var val = $(id).val();
    if(val !== ""){
        // check if exist
        if($("select ." + p[0].classList.item(1)).length === 0){
            // add to combobox
            var option = `<option value="${val}" class="${p[0].classList.item(1)}">${val}</option>`;
            $(".po_number_item").append(option);
        }else{
            $("select ." + p[0].classList.item(1)).text(val);
            $("select ." + p[0].classList.item(1)).attr("value", val);
        }
    }else{
        $("select ." + p[0].classList.item(1)).remove();
    }
}

function deletePurchaseRow(id){
    $(id).remove();
}

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

function loadList(t, sortBy = ""){
    var loadingModal = $('#loading_modal');
    var loadingCircle = $('#loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    t.clear().draw();
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'request',
            operation: 'getAllRequest',
            sortby: sortBy
        },
        success: function(data){
            if(data.msg){
                console.log(data.info);
                data.info.forEach(element => {
                    var view_items = `<button class="btn btn-primary btn-xs" onclick="loadData(` + element.rid + `, 'request', '#requestItemsContainer tbody');" data-toggle="modal" data-target=".view_request">View Items</button>`;
                    // var issuance = `<button class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'issuance', 'Ready', this)"> Ready for Issuance</button>`;
                    var issuance = `<a href="list_requests.php?type=make_issuance_report&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs">Prepare PAR/ICS</a>`;
                    var issuanceRIS = `<a href="list_requests.php?type=make_issuance_report&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs">Prepare RIS</a>`;
                    var prepare_purchase_order = `<a href="list_requests.php?type=make_order&rid=` + element.rid + `" class="btn btn-success btn-xs" onclick="loadData(` + element.rid + `, 'item')" > Prepare Purchase Order</a>`;
                    var accept = `<button type="button" class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'request', 'Accepted', this)"> Accept</button>`;
                    var printPAR = `<a href="modules/pdf_generator/generate_pdf.php?type=par&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs" target="_blank">PAR (PDF)</a>`;
                    var printICS = `<a href="modules/pdf_generator/generate_pdf.php?type=ics&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs" target="_blank">ICS (PDF)</a>`;
                    var printRIS = `<a href="modules/pdf_generator/generate_ris.php?rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs" target="_blank">RIS (PDF)</a>`

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
                    }else if(element.status == "Incomplete"){
                        status = '<span class="label label-warning">Incomplete</span>';
                    }

                    var actions = view_items;
                    if(element.cur_user_type == "Administrator"){
                        if(element.request_type == "Requisition"){
                            if(element.status == "Processing"){
                                actions += issuanceRIS;
                            }else if(element.status == "Ready" || element.status == "Delivered"){
                                // if(element.ics_par.indexOf("par") >= 0){
                                //     actions += printPAR;
                                // }else if(element.ics_par.indexOf("ics") >= 0){
                                //     actions += printICS
                                // }
                                actions += printRIS;
                            }
                        }else if(element.request_type == "Purchase Request"){
                            if(element.status == "Approved"){
                                actions += prepare_purchase_order;
                            }else if(element.status == "Inspected"){
                                actions += accept;
                            }else if(element.status == "Accepted"){
                                if(!element.isDone){
                                    actions += issuance;
                                }
                            }else if(element.status == "Ready" || element.status == "Delivered"){
                                if(element.ics_par.indexOf("par") >= 0){
                                    actions += printPAR;
                                }else if(element.ics_par.indexOf("ics") >= 0){
                                    actions += printICS
                                }
                            }
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

function loadOtherList(t){
    var loadingModal = $('.loading_modal');
    var loadingCircle = $('.loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'request',
            operation: 'getAllOtherRequest'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    // TODOIMP: UPDATE ACTIONS COLUMN
                    var view_items = `<button class="btn btn-primary btn-xs" onclick="loadData(` + element.rid + `, 'request', '#requestItemsContainer tbody');" data-toggle="modal" data-target=".view_request">View Items</button>`;
                    // var issuance = `<button class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'issuance', 'Ready', this)"> Ready for Issuance</button>`;
                    var issuance = `<a href="list_requests.php?type=make_issuance_report&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs">Prepare PAR/ICS</a>`;
                    var prepare_purchase_order = `<a href="list_requests.php?type=make_order&rid=` + element.rid + `" class="btn btn-success btn-xs" onclick="loadData(` + element.rid + `, 'item')" > Prepare Purchase Order</a>`;
                    var accept = `<button type="button" class="btn btn-success btn-xs" onclick="processAction(` + element.rid + `, 'request', 'Accepted', this)"> Accept</button>`;
                    var printPAR = `<a href="modules/pdf_generator/generate_pdf.php?type=par&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs">PAR (PDF)</a>`;
                    var printICS = `<a href="modules/pdf_generator/generate_pdf.php?type=ics&rid=` + element.rid + `&h=` + element.hash_val + `" class="btn btn-success btn-xs">ICS (PDF)</a>`;


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
                    if(element.cur_user_type == "Administrator"){
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
                            }else if(element.status == "Ready" || element.status == "Delivered"){
                                actions += printPAR + printICS;
                            }
                        }
                    }
                    
                    t.row.add([
                        element.created_at,
                        element.request_type,
                        element.description,
                        element.requested_by,
                        element.issued_to,
                        element.purpose,
                        element.status,
                        // actions
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

function transferItems(ref){
    var row = $(ref).closest('tr').html();
    var par = $(ref).parents('tr');
    var val = par.find('input.item').val();
    if(val.indexOf('ics') >= 0){
        $("table.par tbody").append('<tr>'+row+'</tr>');
        $("table.par tbody tr:last-child input[name*='report_item']").val(val.replace('ics', 'par'));
        var nameText = $("table.par tbody tr:last-child input[name*='item_no']").prop("name");
        var nameText1 = $("table.par tbody tr:last-child input[name*='report_item']").prop("name");
        $("table.par tbody tr:last-child input[name*='item_no']").prop("name", nameText.replace('ics', 'par'));
        $("table.par tbody tr:last-child input[name*='report_item']").prop("name", nameText1.replace('ics', 'par'));
        $("table.par tbody tr:last-child button").html(">>");
        $("table.par tbody tr:last-child").children(":eq(7)").after($("table.par tbody tr:last-child").children(":eq(0)"));
    }else{
        $("table.ics tbody").append('<tr>'+row+'</tr>');
        $("table.ics tbody tr:last-child input[name*='report_item']").val(val.replace('par', 'ics'));
        var nameText = $("table.ics tbody tr:last-child input[name*='item_no']").prop("name");
        var nameText1 = $("table.ics tbody tr:last-child input[name*='report_item']").prop("name");
        $("table.ics tbody tr:last-child input[name*='item_no']").prop("name", nameText.replace('par', 'ics'));
        $("table.ics tbody tr:last-child input[name*='report_item']").prop("name", nameText1.replace('par', 'ics'));
        $("table.ics tbody tr:last-child button").html("<<");
        $("table.ics tbody tr:last-child").children(":eq(0)").before($("table.ics tbody tr:last-child").children(":eq(7)"));
    }

    $(ref).parents('tr').remove();
}