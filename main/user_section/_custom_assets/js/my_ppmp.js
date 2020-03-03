$(document).ready(function(){
    
});
function loadData(pid, uid){
    $.ajax({
        method: 'POST',
        url: '../modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'ppmp',
            operation: 'getAllItems',
            uid: uid,
            pid: pid
        },
        success: function(data){
            if(data.msg){
                $("#ppmpItemsContainer tbody").empty();
                $.each( data.info, function( key, value ) {
                    var temp = 
                        `<tr>
                            <th scope="row">` + (key + 1) + `</th>
                            <td>${value.item_name} (${value.item_description})</td>
                            <td>${value.requested_qty} (${value.requested_unit})</td>
                            <td>${value.mon_jan}</td>
                            <td>${value.mon_feb}</td>
                            <td>${value.mon_mar}</td>
                            <td>${value.mon_apr}</td>
                            <td>${value.mon_may}</td>
                            <td>${value.mon_jun}</td>
                            <td>${value.mon_jul}</td>
                            <td>${value.mon_aug}</td>
                            <td>${value.mon_sep}</td>
                            <td>${value.mon_oct}</td>
                            <td>${value.mon_nov}</td>
                            <td>${value.mon_dec}</td>
                        </tr>`;
                    $("#ppmpItemsContainer tbody").append(temp);
                });
            }
        }
    });
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