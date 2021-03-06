$(document).ready(function(){
    var t = $('#dtList').DataTable();
    loadList(t);

    $("#btnTransfer").on('click', function(){
        $('#frmTransfer').parsley().validate();
        if(true === $('#frmTransfer').parsley().isValid()){
            // check if the destination uid is the same
            if($("#transfer_to").val() == $("#from_uid").val()){
                swal({
                    title: "Error!",
                    text: "The source and destination user are the same. Please select other user.",
                    icon: "error",
                    button: "Okay!",
                });
            }else{
                const fetchPromises = swal({
                    title: "Confirm Transfer",
                    text: "Are you sure you want to transfer this equipment?",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true,
                });
                fetchPromises.then((val) => {
                    if(val){
                        // perform transfer
                        $.ajax({
                            method: 'POST',
                            url: 'modules/asyncUrls/data_entry.php',
                            dataType: 'JSON',
                            data: {
                                type: 'equipments',
                                operation: 'performTransfer',
                                riid: $("#riid").val(),
                                stid: $("#stid").val(),
                                from_uid: $("#from_uid").val(),
                                transfer_type: $("#transfer_type").val(),
                                transfer_to: $("#transfer_to").val(),
                                transfer_purpose: $("#transfer_purpose").val(),
                            },
                            success: function(data){
                                if(data.msg){
                                    swal({
                                        title: "Success!",
                                        text: "Your transfer request has been submitted to regional director. Kindly wait for the response.",
                                        icon: "success",
                                        button: "Okay!",
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }else{
                                    alert('Error');
                                }
                            }
                        });
                    }
                });
            }
        }
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
            type: 'equipments',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                        `<a href="modules/pdf_generator/generate_stock_pdf.php?itemid=${element.itemid}&h=${element.h}" class="btn btn-success btn-xs" target="_blank"><span class="fa fa-file-pdf-o"></span> Stock Card (PDF)</a>
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".transfer_equipment" onclick="loadTransfer(` + element.stid + `, ` + element.from_uid + `, ` + element.riid + `);"><span class="fa fa-arrows-v"></span> Transfer</button>
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".view_equipment_history" onclick="loadEquipmentHistory(` + element.riid + `);"><span class="fa fa-search"></span> History</button>
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".qr_codes" onclick="loadQRCodes(` + element.stid + `);"><span class="fa fa-qrcode"></span> QR Codes</button>`;
                    t.row.add([
                        element.report_item_no,
                        element.name_description,
                        element.item_qty,
                        element.item_unit,
                        '₱ ' + formatCurrency(element.total_cost, true),
                        element.created_at,
                        element.issued_to,
                        element.status,
                        element.transfer_date,
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

function loadTransfer(stid, from_uid, riid){
    $("#stid").val(stid);
    $("#from_uid").val(from_uid);
    $("#riid").val(riid);
}

function loadQRCodes(stid){
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'equipments',
            operation: 'getQRCode',
            stid: stid
        },
        success: function(data){
            if(data.msg){
                $("#qrContainer").empty();
                data.info.forEach(element => {
                    var temp = `<label>QR Code: ${element.item_number}</label>
                    <div class="row">
                        <img src="qr_codes_images/${element.qr_path}" alt="${element.item_number}">
                    </div>`;
                    $("#qrContainer").append(temp);
                });
            }else{
                alert('Error');
            }
        }
    });
}

function loadEquipmentHistory(riid){
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
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