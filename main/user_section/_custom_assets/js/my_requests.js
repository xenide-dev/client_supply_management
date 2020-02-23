$(document).ready(function(){
    
});
function loadData(id, tableName, containerID){
    if(tableName == 'request'){
        $.ajax({
            method: 'POST',
            url: '../modules/asyncUrls/retrieve_list.php',
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

function processAction(rid, action, ref){
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
                url: '_modules/asyncUrls/my_requests.php',
                dataType: 'JSON',
                data: {
                    type: 'request',
                    operation: 'processDeliver',
                    rid: rid,
                    action: action
                },
                success: function(data){
                    if(data.msg){
                        swal("Success!", "Request has been approved!", "success").then(() => {
                            window.location.reload();
                        });
                        
                    }
                }
            });
        }
    });
}