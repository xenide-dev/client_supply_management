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
            type: 'supplies',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                    `<a href="#" class="btn btn-success btn-xs" onclick="loadData(` + element.itemid + `, 'item')" data-toggle="modal" data-target=".bs-update-modal-sm"><span class="fa fa-edit"></span> Edit</a>
                    <a href="#" class="btn btn-danger btn-xs" onclick="removeData(` + element.itemid + `, 'item', this)"><span class="fa fa-trash"></span></a>`;
                    var itemName = element.item_name + "(" + element.item_description + ")";
                    t.row.add([
                        element.item_code,
                        itemName,
                        element.item_qty,
                        element.item_unit,
                        element.reorder_point,
                        element.updated_at,
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