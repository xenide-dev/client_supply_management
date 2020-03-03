var count = 1;
var idCount = 1;
$(document).ready(function(){
    init_parsley();
    $("#btnAddItem, #btnAddItemPurchase").on('click', function(){
        $.ajax({
            method: 'POST',
            url: '_modules/asyncUrls/create_request.php',
            dataType: 'JSON',
            data: {
                type: 'item',
                operation: 'getAllItemPPMP'
            },
            success: function(data){
                if(data.msg){
                    var temp = `<div id="item_` + idCount + `">
                                <label class="text-primary">No. ` + count + `</label>
                                <div class="row">
                                    <div class="col-md-6">
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
                                    <input type="number" min="1" name="requested_qty[]" placeholder="Please enter a quantity" class="form-control item_qty" required data-parsley-type="integer" readonly value="0">
                                </div>
                                <div class="col-md-2">
                                    <label>Unit:</label>
                                    <input type="text" placeholder="Please select an item/equipment" name="requested_unit[]" class="form-control item_unit" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Type:</label>
                                    <input type="text" placeholder="Please select an item/equipment" class="form-control item_type" name="item_type[]" readonly required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Jan.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_jan" name="mon_jan[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Feb.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_feb" name="mon_feb[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>March</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_mar" name="mon_mar[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Apr.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_apr" name="mon_apr[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>May</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_may" name="mon_may[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>June</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_jun" name="mon_jun[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>July</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_jul" name="mon_jul[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Aug.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_aug" name="mon_aug[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Sep.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_sep" name="mon_sep[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Oct.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_oct" name="mon_oct[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Nov.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_nov" name="mon_nov[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                                <div class="col-md-1 col-sm-6 col-6">
                                    <label>Dec.</label>
                                    <input type="number" min="0" placeholder="Please select an quantity" class="form-control mon_dec" name="mon_dec[]" value="0" required onkeyup="changeQty(this);" onclick="this.select();">
                                </div>
                            </div>
                            <div class="row">
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
        if(true === $('form').parsley().isValid()){
            if($("div[id*='item']").length <= 0){
                swal({
                    title: "Error!",
                    text: "Please add at least one item to your ppmp!",
                    icon: "error",
                    button: "Okay!",
                });
            }else{
                if(true === $('form').parsley().isValid()){
                    const fetchPromises = swal({
                        title: "Do you want to submit your ppmp?",
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
        }
    });
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
                    $(pID).find('.item_unit').val(data.info.item_default_unit);
                    $(pID).find('.item_type').val(data.info.item_type);
                }
            }
        });
    });
}

function changeQty(ref){
    var p = $(ref).parents("div[id*='item']");
    var sum = 0;
    $(p).find("input[class*='mon']").each(function(index){
        if(!isNaN(parseInt(this.value))){
            sum += parseInt(this.value);
        }
    });
    $(p).find("input.item_qty").val(sum);
}