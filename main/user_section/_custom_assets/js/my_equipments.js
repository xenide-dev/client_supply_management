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