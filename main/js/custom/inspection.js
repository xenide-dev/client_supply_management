$(document).ready(function(){
    var t = $('#dtList').DataTable();
    loadList(t);
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
            type: 'purchase',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                    `<a href="#" class="btn btn-success btn-xs" onclick="loadData(` + element.poid + `, 'request', '#itemsContainer tbody');" data-toggle="modal" data-target=".viewItem"><span class="fa fa-search"></span> View Items</a>
                    <button type="button" class="btn btn-success btn-xs" onclick="processAction(` + element.poid + `, ` + element.rid + `, 'Approved', this);"><span class="fa fa-legal"></span> Approved</button>`;
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
                    $.each( data.info, function( key, value ) {
                        var temp = 
                            `<tr>
                                <th scope="row">` + (key + 1) + `</th>
                                <td>` + value.item_code + `</td>
                                <td>` + (value.item_name + " (" + value.item_description + ")") + `</td>
                                <td>` + value.requested_qty + `</td>
                                <td>` + value.requested_unit + `</td>
                                <td>` + value.unit_cost + `</td>
                                <td>` + value.total_cost + `</td>
                            </tr>`;
                        $(containerID).append(temp);
                    });
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