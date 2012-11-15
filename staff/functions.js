$(function(){
  $("#dataTables").dataTable({
    "bPaginate":false,
    "bInfo":false,
    "bLingthChange":false,
    "bFilter":false,
    "bAutoWidth":false,
    //"bJQueryUI": true,
    "sAjaxSource":"get.php",
    "fnInitComplete":function(){
      $("#dataTables tbody tr").click(function(){
        var name1 = $($(this).children()[0]).html();
        $.getJSON("get_by_name.php", {name:name1}, function(json){
          $("input#name").val(json[0]);
        });
      });
    }
  });
});
