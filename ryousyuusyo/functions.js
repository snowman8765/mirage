$(function(){
  var nowdate = new Date();
  var year = nowdate.getFullYear(); // ”N 
  var mon  = nowdate.getMonth() + 1; // ŒŽ 
  if(mon < 10) {mon = "0"+mon;}
  var date = nowdate.getDate(); // “ú 
  var hour = nowdate.getHours(); // Žž 
  if(hour<5) {date--;}
  if(date < 10) {date = "0"+date;}
  $("#datepicker2").datepicker().datepicker("option", "dateFormat", "yy-mm-dd");
  $("#datepicker2").val(year+"-"+mon+"-"+date);
  $("#datepicker2").change(function(){
    var date = $("#datepicker2").val();
    $("#dataTables").dataTable({
      "bPaginate":false,
      "bInfo":false,
      "bLingthChange":false,
      "bFilter":false,
      "bAutoWidth":false,
      "bDestroy":true,
      //"bJQueryUI": true,
      "sAjaxSource":"get_by_date.php?date="+date,
      "fnInitComplete":init_func
    });
  });
  $("#datepicker2").change();
  
  $("#datepicker").datepicker().datepicker("option", "dateFormat", "yy-mm-dd");
});

function init_func() {
  $("#dataTables tbody tr").click(function(){
    var id1 = $($(this).children()[0]).html();
    $.getJSON("get_by_id.php", {id:id1}, function(json){
      $("input#id").val(json[0]);
      $("input#syori_bumon").val(json[1]);
      $("input#aite_kamoku").val(json[2]);
      $("input#datepicker").val(json[3]);
      $("input#siharaisaki").val(json[4]);
      $("input#youto").val(json[5]);
      $("input#kingaku").val(json[6]);
    });
  });
}
