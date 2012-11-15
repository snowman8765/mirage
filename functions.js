$(function(){
  $("#dataTables").dataTable({
    "bPaginate":false,
    "bInfo":false,
    "bLingthChange":false,
    "bFilter":false,
    "bAutoWidth":false,
    //"bJQueryUI": true,
    "sAjaxSource":"get.php"
  });
  
  $("#datepicker").datepicker().datepicker("option", "dateFormat", "yy-mm-dd");
  
  $('#nyuuten').ptTimeSelect();
  $('#taiten').ptTimeSelect();
});
