$(function(){
  var denpyou_bangou = $("#denpyou_bangou").val();
  $.get("get_order.php?denpyou_bangou="+denpyou_bangou+"&id=0", function(data){
    $("#order001").html(data);
    update();
  });
  $.get("get_order.php?denpyou_bangou="+denpyou_bangou+"&id=100", function(data){
    $("#order101").html(data);
    update();
  });
  $.get("get_order.php?denpyou_bangou="+denpyou_bangou+"&id=200", function(data){
    $("#order201").html(data);
    update();
  });
  $.get("get_order.php?denpyou_bangou="+denpyou_bangou+"&id=300", function(data){
    $("#order301").html(data);
    update();
  });
  $.get("get_order.php?denpyou_bangou="+denpyou_bangou+"&id=400", function(data){
    $("#order401").html(data);
    update();
  });
});
function update() {
  $("input.order_num").blur(function(){
    var denpyou_bangou = $("#denpyou_bangou").val();
    var id = $(this).attr("id");
    var num = $(this).val();
    $.post("update_order.php",{denpyou_bangou:denpyou_bangou, order_id:id, num:num}, function(data){
      var sum = 0;
      $(".order_num").each(function(){
        var kingaku = $($(this).parent().get(0)).prev().html();
        var num = $(this).val();
        if(num>0) {
          sum += kingaku * num;
        }
      });
      $("#order_kei").val(sum);
    });
  });
}
