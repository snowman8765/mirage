$(function(){
  $("#order_number").change(function(){
    var id = $("#order_number").val();
    $("#dataTables").dataTable({
      "bPaginate":false,
      "bInfo":false,
      "bLingthChange":false,
      "bFilter":false,
      "bAutoWidth":false,
      "bDestroy":true,
      //"bJQueryUI": true,
      "sAjaxSource":"get_by_id2.php?id="+id
    });
  });
  $("#order_number").change();
});
