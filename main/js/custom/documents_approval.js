$(document).ready(function(){
    $("#dtList").DataTable();
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