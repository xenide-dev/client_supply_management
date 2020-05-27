$(document).ready(function(){
    var t = $('#dtList').DataTable();
    loadList(t);
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
            type: 'purchase',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                console.log(data);
                data.info.forEach(element => {
                    var actions = "";
                    if(element.status == "Pending"){
                        actions += `<a href="#" class="btn btn-success btn-xs" onclick="loadData(` + element.poid + `, 'request', '#itemsContainer tbody');" data-toggle="modal" data-target=".viewItem"><span class="fa fa-search"></span> Inspect Items</a>`;
                    }
                    actions += `<a href="modules/pdf_generator/generate_inspection_pdf.php?poid=${element.poid}&h=${element.h}" class="btn btn-success btn-xs" target="_blank"><span class="fa fa-search"></span> Inspection Report(PDF)</a>`;

                    var requestedBy = element.lname + ", " + element.fname + " " + element.midinit;
                    t.row.add([
                        element.po_number,
                        element.supplier_name,
                        element.supplier_address,
                        element.created_at,
                        requestedBy,
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

function loadData(id, tableName, containerID){
    if(tableName == 'request'){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/retrieve_list.php',
            dataType: 'JSON',
            data: {
                type: 'purchase',
                id: id,
                operation: 'getAllItems'
            },
            success: function(data){
                if(data.msg){
                    $(containerID).empty();
                    var totalAmount = 0;
                    $.each( data.info, function( key, value ) {
                        var temp = 
                            `<tr>
                                <th scope="row">` + (key + 1) + `</th>
                                <td>` + value.item_code + `</td>
                                <td style="width: 250px;">` + (value.item_name + " (" + value.item_description + ")") + `</td>
                                <td>` + value.requested_qty + `</td>
                                <td style="width: 100px;">` + value.requested_unit + `</td>
                                <td>₱ ` + formatCurrency(value.unit_cost, true) + `</td>
                                <td>₱ ` + formatCurrency(value.total_cost, true) + `</td>
                                <td>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="flat" name="isDelivered[]" value="` + value.poiid + `" `+ ((value.isDelivered == 1) ? `checked` : ``) +`>
                                        </label>
                                    </div>
                                </td>
                            </tr>`;
                        totalAmount += value.total_cost;
                        $(containerID).append(temp);
                        $('input.flat').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                    });
                    $("#totalAmount").text(formatCurrency(totalAmount, true));
                }
            }
        });
    }
}


function processAction(poid, rid, action, ref){
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
                    operation: 'processInspection',
                    poid: poid,
                    rid: rid,
                    action: action
                },
                success: function(data){
                    if(data.msg){
                        var dtCategory = $("#dtList").DataTable();
                        dtCategory.row($(ref).parents('tr')).remove().draw();
                        swal("Success!", "Purchase Items has been inspected!", "success");
                    }
                }
            });
        }
    });
}

var poiid;
function processDelivery(){
    poiid = [];
    var flag = false;
    $(".viewItem tbody input.flat").each(function(index, value){
        var val = $(this).val();
        var state = 0;
        if(value.checked){
            flag = true;
            state = 1;
        }

        poiid.push({val: val, state: state});
    });
    if(!flag){
        swal("Error!", "Please mark at least one item", "error");
    }else{
        swal({
            title: "Do you want to save your changes?",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true,
        }).then((value) => {
            if(value){
                // proceed to save
                $.ajax({
                    method: 'POST',
                    url: 'modules/asyncUrls/data_entry.php',
                    dataType: 'JSON',
                    data: {
                        type: 'purchase',
                        operation: 'processInspection',
                        poiid: poiid,
                        flag: flag
                    },
                    success: function(data){
                        if(data.msg){
                            if(data.done){
                                swal("Success!", "Inspection report has been save!", "success").then(() => {
                                    swal({
                                        title: "Report submission notice!",
                                        text: "We notice that all the items has been delivered completely, Do you want to submit a report back to the administrator? Once you proceed, the process cannot be undone!",
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
                                                    operation: 'processInspectionReport',
                                                    poid: data.poid,
                                                    rid: data.rid,
                                                    action: 'Approved'
                                                },
                                                success: function(data){
                                                    if(data.msg){
                                                        swal("Success!", "Report has been submitted. Thank You!", "success").then(() => {
                                                            window.location.reload();
                                                        });
                                                    }
                                                }
                                            });
                                        }else{
                                            swal("Cancelled!", "Report submission has been cancelled!", "warning").then(() => {
                                                window.location.reload();
                                            });
                                        }
                                    })
                                });
                            }else{
                                swal("Success!", "Inspection report has been save!", "success").then(() => {
                                    window.location.reload();
                                });
                            }
                        }
                    }
                });
            }
        });
    }
}