var count = 1;
var idCount = 1;
$(document).ready(function(){
    init_parsley();
    $("#btnAddItem, #btnAddItemPurchase").on('click', function(){
        var requestType = (this.id == "btnAddItemPurchase") ? 'purchase' : 'requisition';
        $.ajax({
            method: 'POST',
            url: '_modules/asyncUrls/create_request.php',
            dataType: 'JSON',
            data: {
                type: 'item',
                requestType: requestType,
                operation: 'getAll'
            },
            success: function(data){
                if(data.msg){
                    var temp = `<div class="card-body" id="item_` + idCount + `">
                                <label class="text-primary">No. ` + count + `</label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Item/Equipment:</label>
                                        <select name="itemid[]" class="form-control select2" required id="select_item_` + idCount + `" data-parsley-errors-container="#select2-select_item_`+ idCount +`-container" data-parsley-class-handler="#select_item_`+ idCount +` + span.select2-container">
                                            <option value="">-- Please select an item/equipment --</option>
                                            `;
                    data.info.forEach(element => {
                        if(element.rem_qty != undefined){
                            var itemFormat = element.item_name + " (" + element.item_description + ") | Qty: " + element.rem_qty;
                        }else{
                            var itemFormat = element.item_name + " (" + element.item_description + ")";
                        }
                        temp += `<option value="` + element.itemid + `">` + itemFormat + `</option>`;
                    });
                    temp += `</select>
                                </div>
                                <div class="col-md-2">
                                    <label>Quantity:</label>
                                    <input type="number" min="1" name="requested_qty[]" placeholder="Please enter a quantity" class="form-control item_qty" required data-parsley-type="integer">
                                </div>
                                <div class="col-md-2">
                                    <label>Unit:</label>
                                    <input type="text" placeholder="Please select an item/equipment" name="requested_unit[]" class="form-control item_unit" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Type:</label>
                                    <input type="text" placeholder="Please select an item/equipment" class="form-control item_type" name="item_type[]" readonly required>
                                </div>
                                <div class="col-md-1">
                                    <label>Action</label>
                                    <button type="button" class="btn btn-danger btn-block" onclick="removeItem('#item_` + idCount + `');">Delete</button>
                                </div>
                            </div>
                        </div>`;
                    $(".requestItemContainer").append(temp);
                    
                    init_select2("#select_item_" + idCount, "#item_" + idCount);
                    count++;
                    idCount++;
                }
            }
        });
    });

    $("#frmForm").on('submit', function(e){
        e.preventDefault();
        // check if there is an item
        if($("div[id*='item']").length <= 0){
            swal({
                title: "Error!",
                text: "Please add at least one item to your request!",
                icon: "error",
                button: "Okay!",
            });
        }else{
            if(true === $('form').parsley().isValid()){
                const fetchPromises = swal({
                    title: "Do you want to submit your request?",
                    // text: "Once submitted, the data cannot be undone!",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true,
                });
                fetchPromises.then((val) => {
                    if(val){
                        this.submit();
                    }
                });
            }
        }
    })
});
function removeItem(id){
    $(id).remove();

    // redraw count display
    var list = $("div[id*='item_']");
    list.each(function(index, element) {
        $(element).find('.text-primary').text("No. " + (index + 1));
    });
    count--;
}
function init_select2(id, pID){
    $(id).select2({
        theme: 'bootstrap4'
    });
    $(id).on('change', function(e){
        $.ajax({
            method: 'POST',
            url: '_modules/asyncUrls/create_request.php',
            dataType: 'JSON',
            data: {
                type: 'item',
                id: $(this).val(),
                operation: 'get'
            },
            success: function(data){
                if(data.msg){
                    if(data.info.rem_qty != undefined){
                        if(data.info.rem_qty != 0){
                            $(pID).find('.item_qty').prop("max", data.info.rem_qty);
                        }
                    }

                    $(pID).find('.item_unit').val(data.info.item_default_unit);
                    $(pID).find('.item_type').val(data.info.item_type);
                }
            }
        });
    });
}