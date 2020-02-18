$(document).ready(function(){
    $("#rep_year, #rep_month, #rep_fund").on('change', function(){
        displayText();
    });
    $(".rep_bank a").on('click', function(){
        if($("#rep_year").val() == "" || $("#rep_mon").val() == "" || $("#rep_fund").val() == ""){
            alert("Please complete the filter on the left side");
            return false;
        }
        return true;
    });
});

function displayText(){
    var mon = "", yr = "";
    if($("#rep_year").val() == ""){
        yr = "<Please select a year>";
    }else{
        yr = $("#rep_year").val();
    }

    if($("#rep_month").val() == ""){
        mon = "<Please select a month>";
    }else{
        mon = $("#rep_month option:selected").text() ;
    }

    $(".label_report").text("For the Month of " + mon + ", " + yr);

    $(".rep_bank a").each(function(index){
        var prev;
        if($(this).prop("href").indexOf("&") != -1){
            prev = $(this).prop("href").substring(0, $(this).prop("href").indexOf("&"));
        }else{
            prev = $(this).prop("href");
        }

        var finalHref = prev + "&mon=" + $("#rep_month").val() + "&yr=" + yr + "&fund_type=" + $("#rep_fund").val();

        $(this).prop("href", finalHref);
    });

     
    
}