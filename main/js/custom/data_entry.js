$(document).ready(function(){
    $("#dtCategory").DataTable();
    $("#dtItem").DataTable();

    $("#btnAddItem").on('click', function(){
        loadContainer(".cat_select", "item_category")
    });
});

function loadContainer(containerID, tableName){
    if(tableName == "item_category"){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/data_entry.php',
            dataType: 'JSON',
            data: {
                type: 'cat',
                operation: 'getAll'
            },
            success: function(data){
                if(data.msg){
                    $(containerID).find('option').remove().end().append('<option value="">-- Please select a value --</option>').val('');
                    data.info.forEach(element => {
                        $(containerID).append(new Option(element.cat_name, element.catid));
                    });
                }
            }
        });
        
    }
}

function loadData(id, tableName){
    if(tableName == 'cat'){
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/data_entry.php',
            dataType: 'JSON',
            data: {
                type: 'cat',
                id: id,
                operation: 'get'
            },
            success: function(data){
                if(data.msg){
                    $("#catid").val(data.info.catid);
                    $("#cat_name").val(data.info.cat_name);
                    $("#cat_descrip").val(data.info.cat_descrip);
                    $("#cat_code").val(data.info.cat_code);
                }
            }
        });
    }else if(tableName == "item"){
        loadContainer(".cat_select", "item_category");
        $.ajax({
            method: 'POST',
            url: 'modules/asyncUrls/data_entry.php',
            dataType: 'JSON',
            data: {
                type: 'item',
                id: id,
                operation: 'get'
            },
            success: function(data){
                if(data.msg){
                    $("#itemid").val(data.info.itemid);
                    $("#item_name").val(data.info.item_name);
                    $("#item_descrip").val(data.info.item_description);
                    $("#item_default_unit").val(data.info.item_default_unit);
                    $("#item_type").val(data.info.item_type);
                    $("#up_catid").val(data.info.catid);
                }
            }
        });
    }
}

function removeData(id, tableName, ref) {
    swal({
        title: "Are you sure you want to continue?",
        text: "Once proceed, the operation cannot be undone!",
        icon: "warning",
        buttons: ["No", "Yes"],
        dangerMode: true,
    }).then((val) => {
        if(val){
            if(tableName == "cat"){
                $.ajax({
                    method: 'POST',
                    url: 'modules/asyncUrls/data_entry.php',
                    dataType: 'JSON',
                    data: {
                        type: 'cat',
                        id: id,
                        operation: 'delete'
                    },
                    success: function(data){
                        if(data.msg){
                            var dtCategory = $("#dtCategory").DataTable();
                            dtCategory.row($(ref).parents('tr')).remove().draw();
                            swal("Success!", "Data has been deleted!", "success");
                        }
                    }
                });
            }else if(tableName == "item"){
                $.ajax({
                    method: 'POST',
                    url: 'modules/asyncUrls/data_entry.php',
                    dataType: 'JSON',
                    data: {
                        type: 'item',
                        id: id,
                        operation: 'delete'
                    },
                    success: function(data){
                        if(data.msg){
                            var dtCategory = $("#dtItem").DataTable();
                            dtCategory.row($(ref).parents('tr')).remove().draw();
                            swal("Success!", "Data has been deleted!", "success");
                        }
                    }
                });
            }
        }
    });
}