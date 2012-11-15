$(function(){
  var nowdate = new Date();
  var year = nowdate.getFullYear(); // 年 
  var mon  = nowdate.getMonth() + 1; // 月 
  if(mon < 10) {mon = "0"+mon;}
  var date = nowdate.getDate(); // 日 
  var hour = nowdate.getHours(); // 時 
  if(hour<5) {date--;}
  if(date < 10) {date = "0"+date;}
  $("#datepicker").datepicker().datepicker("option", "dateFormat", "yy-mm-dd");
  $("#datepicker").val(year+"-"+mon+"-"+date);
  $("#datepicker").change(function(){
    var date = $("#datepicker").val();
    $("#dataTables").dataTable({
      "bPaginate":false,
      "bInfo":false,
      "bLingthChange":false,
      "bFilter":false,
      "bAutoWidth":false,
      "bDestroy": true,
      //"bJQueryUI": true,
      "sAjaxSource":"get_by_date.php?date="+date,
      "fnInitComplete":init_func
    });
  });
  $("#datepicker").change();
  
  $('#nyuuten').ptTimeSelect();
  $('#taiten').ptTimeSelect();
  
  $.get("get_cast.php", function(data){
    $("select.simei_name").html("").html(data);
  });
  
  $("input:button#order_input").click(function(){
    $("#loading").show();
    $("#disp").dialog("option", "url", "order_in.html");
    $("#disp").dialog("option", "height", 650);
    $("#disp").dialog("option", "width", 800);
    //$("#disp").dialog("option", "modal", true);
    $("#disp").dialog('open');
    return false;
  });
  $("#disp").dialog({
    autoOpen: false,
    buttons: {
      'close': function(){
        $(this).dialog('close');
        $("#disp").children().remove();
      }
    },
    open: function(){
      $("#disp").load($(this).dialog("option", "url"), null, function() {
        $("#loading").hide();
      });
    }
  });
  
  //$("input").blur(calc);
  
  $("input:button#meisai").click(function(){
    $.post('pdf.php');
  });
  
  $("input#keisan").click(function(){
    calc();
  });
  
  $("input#meisai").click(function(){
    calc();
  });
});

function init_func(){
  $("#dataTables tbody tr").click(function(){
    var id1 = $($(this).children()[0]).html();
    $.getJSON("get_by_id.php", {denpyou_bangou:id1}, function(json){
      $("input#denpyou_bangou").val(json[0]);
      $("input#nyuuten").val(json[1]);
      $("input#taiten").val(json[2]);
      $("#sekiban option").each(function(){
        if($(this).val() == json[3]) {
          $(this).attr("selected", "selected");
        }
      });
      $("input#ninzuu").val(json[4]);
      $("input#chg").val(json[5]);
      $("input#vip").val(json[6]);
      $("#simei1 option").each(function(){
        if($(this).val() == json[7]) {
          $(this).attr("selected", "selected");
        }
      });
      $("#simei1_name option").each(function(){
        if($(this).val() == json[8]) {
          $(this).attr("selected", "selected");
        }
      });
      $("input#okyaku1").val(json[9]);
      $("#simei2 option").each(function(){
        if($(this).val() == json[10]) {
          $(this).attr("selected", "selected");
        }
      });
      $("#simei2_name option").each(function(){
        if($(this).val() == json[11]) {
          $(this).attr("selected", "selected");
        }
      });
      $("input#okyaku2").val(json[12]);
      $("#simei3 option").each(function(){
        if($(this).val() == json[13]) {
          $(this).attr("selected", "selected");
        }
      });
      $("#simei3_name option").each(function(){
        if($(this).val() == json[14]) {
          $(this).attr("selected", "selected");
        }
      });
      $("input#okyaku3").val(json[15]);
      $("#simei4 option").each(function(){
        if($(this).val() == json[16]) {
          $(this).attr("selected", "selected");
        }
      });
      $("#simei4_name option").each(function(){
        if($(this).val() == json[17]) {
          $(this).attr("selected", "selected");
        }
      });
      $("input#okyaku4").val(json[18]);
      $("input#order_kei").val(json[19]);
      $("input#waribiki").val(json[20]);
      switch(json[21]) {
        case "genkin" : $("#genkin").attr("checked", "checked");break;
        case "card"   : $("#card").attr("checked", "checked");break;
        case "urikake": $("#urikake").attr("checked", "checked");break;
        default:$("#simei21").attr("checked", "checked");break;
      }
      $("input#uriage").val(json[22]);
      
      //calc();
    });
  });
}

function calc(){
  var nomihoudai = 0;
  var ninzuu = Number($("input#ninzuu").val());
  var time = $("input#nyuuten").val().split(":");
  var hour = Number(time[0]);
  var minute = time[1].split(" ");
  minute = Number(minute[0]);
  switch(hour) {
    case 8: nomihoudai = 3000;break;
    case 9: nomihoudai = 4000;break;
    default: nomihoudai = 5000;
  }
  var party = nomihoudai;
  if($("input#party").val() > 0) {
    party = Number($("input#party").val());
  }
  $("input#nomihoudai").val(party);
  
  if($("input#nyuuten").val() && $("input#taiten").val()) {
    var time1 = Number(time[0])*60+Number(time[1].split(" ")[0]);
    time1 = time[1].split(" ")[1]=="AM" ? time1+12*60 : time1;
    time = $("input#taiten").val().split(":");
    var time2 = Number(time[0])*60+Number(time[1].split(" ")[0]);
    time2 = time[1].split(" ")[1]=="AM" ? time2+12*60 : time2;
    $("input#chg").val(ninzuu*Math.ceil((time2-time1-60)/30));
  }
  
  var simei_sum = 0;
  $("select.simei option:selected").each(function(){
    var simei = $(this).val();
    if(simei != 0) {
      simei_sum += 3000;
    }
  });
  if($("input#simeiryou_komi").attr("checked")=="checked") {
    simei_sum = 0;
  }
  
  var chg = Number($("input#chg").val())*3000;
  var vip = Number($("input#vip").val());
  var order_kei = Number($("input#order_kei").val());
  var syoukei = party*ninzuu + chg + vip + simei_sum + order_kei;
  var zei = 0;
  if($("input#party").val() > 0) {
    zei = (chg + vip + simei_sum + order_kei) * 0.2;
  } else {
    zei = syoukei * 0.2;
  }
  $("input#syoukei").val(Math.ceil((syoukei+zei)/100)*100);
  $("input#uriage").val($("input#syoukei").val()-$("input#waribiki").val());
}
