$(document).ready(function(){
    $("#btnReport").on('click', function(){
        if($("#status").val() != ""){
            const fetchPromises = swal({
                title: "Are you sure you want to continue?",
                // text: "Once submitted, the data cannot be undone!",
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true,
            });
            fetchPromises.then((val) => {
                if(val){
                    $.ajax({
                        method: 'POST',
                        url: '_modules/asyncUrls/data-entry.php',
                        dataType: 'JSON',
                        data: {
                            type: 'equipments',
                            operation: 'report',
                            riid: riid,
                            statusReport: $("#status").val()
                        },
                        success: function(data){
                            if(data.msg){
                                $("#equipmentHistoryContainer tbody").empty();
                                $.each( data.info, function( key, value ) {
                                    var temp = 
                                        `<tr>
                                            <th>${value.date_issued}</th>
                                            <td>${value.name}</td>
                                            <td>${value.acquisition}</td>
                                        </tr>`;
                                    $("#equipmentHistoryContainer tbody").append(temp);
                                });
                            }
                        }
                    });
                }
            });
        }else{
            swal({
                title: "Error!",
                text: "Please select an option!",
                icon: "error",
                button: "Okay!",
            });
        }
    });
});

function changeStatus(riid){
    $("#riid").val(riid);
    $("#status").val("");
}

function loadEquipmentHistory(riid){
    $.ajax({
        method: 'POST',
        url: '../modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'equipments',
            operation: 'history',
            riid: riid
        },
        success: function(data){
            if(data.msg){
                $("#equipmentHistoryContainer tbody").empty();
                $.each( data.info, function( key, value ) {
                    var temp = 
                        `<tr>
                            <th>${value.date_issued}</th>
                            <td>${value.name}</td>
                            <td>${value.acquisition}</td>
                        </tr>`;
                    $("#equipmentHistoryContainer tbody").append(temp);
                });
            }
        }
    });
}