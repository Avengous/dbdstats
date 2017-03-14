// ---------- Initialize page wtih data ------------- //

$(function() {
    var today = new Date();
    //eventually get this to send based on summoner
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    
    $("#datepicker").datepicker({ 
        dateFormat: 'MM dd, yy'
    });
    $("#datepicker2").datepicker({ 
        dateFormat: 'MM dd, yy' 
    });
  	
    $("#date-info button").click(function() {
        firstdate = $("#datepicker").val();
        seconddate = $("#datepicker2").val();
        getSummaryData(firstdate, seconddate);
    });
    
    getSummaryData('December 07, 2016', date);
    $("#datepicker").datepicker('setDate', 'December 07, 2016');
    $("#datepicker2").datepicker('setDate', today);
});

function getSummaryData(firstdate, seconddate) {
    $.ajax({
        type: "POST",
        data: {
            "summoner": summoner,
            "firstdate" : firstdate,
            "seconddate" : seconddate
        },
        url: "/php/get-match-stats.php",
        success: function(response) {
          console.log(response);
            var data = $.parseJSON(response);
            
            $("#summary_kills").html(data["avg_kills"] + " Kills");
            $("#summary_deaths").html(data["avg_deaths"] + " Deaths");
            $("#summary_assists").html(data["avg_assists"] + " Assists");
        }
    });
}