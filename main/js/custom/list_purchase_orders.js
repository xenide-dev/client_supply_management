var totalItems;
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
            type: 'purchase_orders',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                        `<button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".item_list" data-backdrop="static" onclick="loadItems(${element.poid})">View Items</button>
                        <a href="modules/pdf_generator/generate_purchase_order.php?poid=${element.poid}&h=${element.h}" class="btn btn-primary btn-xs" target="_blank">Generate PDF</a>`;
                    t.row.add([
                        element.created_at,
                        element.po_number,
                        element.supplier_name,
                        element.supplier_address,
                        element.status,
                        "₱ " + element.total_amount,
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

function loadItems(poid){
    var t = $('#dtListItems').DataTable();
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'purchase_orders',
            operation: 'getAllItems',
            poid: poid
        },
        success: function(data){
            t.clear();
            if(data.msg){
                var counter = 1;
                data.info.forEach(element => {
                    t.row.add([
                        counter,
                        element.item_name + " (" + element.item_description + ")",
                        element.requested_qty + " (" + element.requested_unit + ")",
                        "₱ " + element.unit_cost,
                        "₱ " + element.total_cost
                    ]).draw();
                    counter++;
                });
            }else{
                alert('Error');
            }
        }
    });
}