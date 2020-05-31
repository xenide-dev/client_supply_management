$(document).ready(function() {

    $("#btnScan").on('click', function(){
        var decoder;
        var arg = {
            width: 300,
            height: 300,
            decoderWorker: 'qrreader/js/DecoderWorker.js',
            zoom: -1,
            resultFunction: function(result) {
                decoder.stop();

                // perform retrieve
                $.ajax({
                    method: 'POST',
                    url: 'modules/asyncUrls/retrieve_list.php',
                    dataType: 'JSON',
                    data: {
                        type: 'qr',
                        operation: 'getItem',
                        qr_code: result.code
                    },
                    success: function(data){
                        if(data.msg){
                            if(data.info != undefined){
                                var outputText = "Current Owner: " + data.info.lname + ", " + data.info.fname + " " + data.info.midinit + "\n" +
                                "Item: " + data.info.item_name  + "\n" +
                                "Item Description: " + data.info.item_description + "\n" +
                                "Item Code: " + result.code + "\n" +
                                "Unit Cost: ₱ " + formatCurrency(data.info.price, true) + "\n" +
                                "Showing 1 out of " + data.info.item_qty + " item/s";
                                swal({
                                    title: "Success!",
                                    text: outputText,
                                    icon: "success",
                                    button: "Okay!",
                                }).then(() => {
                                    swal({
                                        title: "Try again?",
                                        // text: "Are you sure you want to transfer this equipment?",
                                        icon: "info",
                                        buttons: ["No", "Yes"],
                                        dangerMode: true,
                                    }).then((val) => {
                                        if(val){
                                            decoder.play();
                                        }
                                    });
                                });
                            }else{
                                swal({
                                    title: "Error!",
                                    text: "Item not found \n QR Code: " + result.code,
                                    icon: "error",
                                    button: "Okay!",
                                }).then(() => {
                                    swal({
                                        title: "Try again?",
                                        // text: "Are you sure you want to transfer this equipment?",
                                        icon: "info",
                                        buttons: ["No", "Yes"],
                                        dangerMode: true,
                                    }).then((val) => {
                                        if(val){
                                            decoder.play();
                                        }
                                    });
                                });
                            }
                        }
                    }
                });
            }
        };
        decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
        decoder.buildSelectMenu("select", 'environment|back');
        decoder.play();
        /*  Without visible select menu
            decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
        */
        $('select').on('change', function(){
            decoder.stop().play();
        });
    });
});

function formatCurrency(val, isIncludeDecimal){
    if(isIncludeDecimal){
        return new Intl.NumberFormat('en-PH', { style: 'decimal', currency: 'PHP', minimumFractionDigits: 2 }).format(val);
    }else{
        // get the whole number only
        if(val.indexOf('.') > 0){
            var wholeVal = val.substr(0, val.indexOf('.'));
            var f = wholeVal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            return f + val.substr(val.indexOf('.'));
        }else{
            return val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
    }
}
