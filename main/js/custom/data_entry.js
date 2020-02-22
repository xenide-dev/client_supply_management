$(document).ready(function(){
    $("#dtCategory").DataTable();

    $("#btnAddItem").on('click', function(){
        loadContainer(".cat_select", "item_category")
    });

    $("#upload_csv").on('change', function(){
        var upload = document.getElementById("upload_csv");
        var file = upload.files[0];
        var formData = new FormData();
        formData.append('file', file);
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]); 
        }
        $.ajax({
            url: "modules/csv_extractor.php",
            data: formData,
            type: "POST",
            dataType: "JSON",
            processData: false, 
            contentType: false,
            success: function(data, status, xhr){
                $("#rows_container").empty();
                for (let index = 0; index < data.list.length; index++) {
                    const element = data.list[index];
                    var temp = "<div class=\"row\">" +
                                "<div class=\"col-md-1 text-center\">" +
                                    "<input type=\"checkbox\" onchange=\"changeRow(this,'#row" + index + "')\" checked>" +
                                "</div>" +
                                "<div class=\"col-md-11\">" +
                                    "<input type=\"text\" id=\"row" + index + "\" class=\"form-control\" name=\"item_rows[]\" value=\"" + element + "\">" +
                                "</div>" +
                                "</div>";

                    $("#rows_container").append(temp);
                }
            }
        });
    });

    
    loadItem();
});

function loadItem(){
    var t = $('#dtItem').DataTable();
    var loadingModal = $('#loading_modal');
    var loadingCircle = $('#loading-circle');
    loadingCircle.css({'display' : 'block'});
    loadingModal.css({'display' : 'block'});
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/data_entry.php',
        dataType: 'JSON',
        data: {
            type: 'item',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = `<a href="#" class="btn btn-success btn-xs" onclick="loadData(` + element.itemid + `, 'item')" data-toggle="modal" data-target=".bs-update-modal-sm"><span class="fa fa-edit"></span> Edit</a>
                    <a href="#" class="btn btn-danger btn-xs" onclick="removeData(` + element.itemid + `, 'item', this)"><span class="fa fa-trash"></span></a>`;
                    t.row.add([
                        element.cat_name,
                        element.item_code,
                        element.item_name,
                        element.item_description,
                        element.item_default_unit,
                        element.item_type,
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

function changeRow(own, id){
    if($(own).prop("checked")){
        $(id).prop("disabled", false);
    }else{
        $(id).prop("disabled", true);
    }
}

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
                    $("#item_code").val(data.info.item_code);
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