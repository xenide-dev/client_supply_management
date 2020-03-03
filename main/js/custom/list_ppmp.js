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
            type: 'ppmp',
            operation: 'getAll'
        },
        success: function(data){
            if(data.msg){
                data.info.forEach(element => {
                    var actions = 
                        `<button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".ppmp_items" data-backdrop="static" onclick="loadItems(${element.pid})">View Items</button>
                        <a href="modules/pdf_generator/generate_ppmp_pdf.php?pid=${element.pid}&h=${element.hash}" class="btn btn-primary btn-xs" target="_blank">Generate PDF</a>`;
                    var fullName = element.lname + ", " + element.fname + " " + element.midinit + ".";
                    t.row.add([
                        element.created_at,
                        element.ppmp_year,
                        fullName,
                        element.total_supplies,
                        element.total_equipments,
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

function loadItems(pid){
    var t = $('#dtListItems').DataTable();
    $.ajax({
        method: 'POST',
        url: 'modules/asyncUrls/retrieve_list.php',
        dataType: 'JSON',
        data: {
            type: 'ppmp',
            operation: 'getAllItems',
            pid: pid
        },
        success: function(data){
            if(data.msg){
                var counter = 1;
                data.info.forEach(element => {
                    t.row.add([
                        counter,
                        element.item_name + " (" + element.item_description + ")",
                        element.requested_qty + " (" + element.requested_unit + ")",
                        element.mon_jan,
                        element.mon_feb,
                        element.mon_mar,
                        element.mon_apr,
                        element.mon_may,
                        element.mon_jun,
                        element.mon_jul,
                        element.mon_aug,
                        element.mon_sep,
                        element.mon_oct,
                        element.mon_nov,
                        element.mon_dec
                    ]).draw();
                    counter++;
                });
                loadingCircle.css({'display' : 'none'});
                loadingModal.css({'display' : 'none'});
            }else{
                alert('Error');
            }
        }
    });
}