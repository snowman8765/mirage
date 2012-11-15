<?php
include_once("../config.php");
include_once("../denpyou/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$hiduke = $_POST['hiduke'];
$hiduke_ja = substr($hiduke,0,4)."年".substr($hiduke,5,2)."月";

$hiduke1 = $hiduke."-01";
$hiduke2 = date("Y-m-d", mktime(0, 0, 0, substr($hiduke,5,2)+1, 0, substr($hiduke,0,4)));

$hiduke_ja1 = substr($hiduke1,0,4)."年".substr($hiduke1,5,2)."月".substr($hiduke1,-2)."日";
$hiduke_ja2 = substr($hiduke2,0,4)."年".substr($hiduke2,5,2)."月".substr($hiduke2,-2)."日";

$dDao = new DenpyouDao();
$dArray = $dDao->getByDateToDate($hiduke1, $hiduke2);
$dDao->close();
$dDao = null;
$cast_list = array();
foreach($dArray as $denpyou) {
  if($denpyou['simei4']=='指名'||$denpyou['simei4']=='場内指名'||$denpyou['simei4']=='同伴'||$denpyou['simei4']=='前日同伴'||$denpyou['simei4']=='前々日同伴') {
    $simei_ninzuu = 4;
  } else if($denpyou['simei3']=='指名'||$denpyou['simei3']=='場内指名'||$denpyou['simei3']=='同伴'||$denpyou['simei3']=='前日同伴'||$denpyou['simei3']=='前々日同伴') {
    $simei_ninzuu = 3;
  } else if($denpyou['simei2']=='指名'||$denpyou['simei2']=='場内指名'||$denpyou['simei2']=='同伴'||$denpyou['simei2']=='前日同伴'||$denpyou['simei2']=='前々日同伴') {
    $simei_ninzuu = 2;
  } else {
    $simei_ninzuu = 1;
  }
  
  $tmp_name = array();
  if($denpyou['simei1_name']!='') {
    $name = $denpyou['simei1_name'];
    switch($denpyou['simei1']) {
      case '指名' : $cast_list[$name]['simei']++;$tmp_name[]=$name;break;
      case '場内指名' : $cast_list[$name]['zyounai']++;break;
      case '同伴' : $cast_list[$name]['douhan']++;break;
      case '前日同伴' : $cast_list[$name]['douhan']++;break;
      case '前々日同伴' : $cast_list[$name]['douhan']++;break;
    }
  }
  if($denpyou['simei2_name']!='') {
    $name = $denpyou['simei2_name'];
    switch($denpyou['simei2']) {
      case '指名' : $cast_list[$name]['simei']++;$tmp_name[]=$name;break;
      case '場内指名' : $cast_list[$name]['zyounai']++;break;
      case '同伴' : $cast_list[$name]['douhan']++;break;
      case '前日同伴' : $cast_list[$name]['douhan']++;break;
      case '前々日同伴' : $cast_list[$name]['douhan']++;break;
    }
  }
  if($denpyou['simei3_name']!='') {
    $name = $denpyou['simei3_name'];
    switch($denpyou['simei3']) {
      case '指名' : $cast_list[$name]['simei']++;$tmp_name[]=$name;break;
      case '場内指名' : $cast_list[$name]['zyounai']++;break;
      case '同伴' : $cast_list[$name]['douhan']++;break;
      case '前日同伴' : $cast_list[$name]['douhan']++;break;
      case '前々日同伴' : $cast_list[$name]['douhan']++;break;
    }
  }
  if($denpyou['simei4_name']!='') {
    $name = $denpyou['simei4_name'];
    switch($denpyou['simei4']) {
      case '指名' : $cast_list[$name]['simei']++;$tmp_name[]=$name;break;
      case '場内指名' : $cast_list[$name]['zyounai']++;break;
      case '同伴' : $cast_list[$name]['douhan']++;break;
      case '前日同伴' : $cast_list[$name]['douhan']++;break;
      case '前々日同伴' : $cast_list[$name]['douhan']++;break;
    }
  }
  foreach($tmp_name as $name) {
    $nomihoudai = 0;
    $time = split(":", $denpyou['nyuuten']);
    $hour = intval($time[0]);
    $minute = split(" ", $time[1]);
    $minute = intval($minute[0]);
    switch($hour) {
      case 8: $nomihoudai = NOMIHOUDAI_8;break;
      case 9: $nomihoudai = NOMIHOUDAI_9;break;
      case 10: $nomihoudai = NOMIHOUDAI_10;break;
      case 11: $nomihoudai = NOMIHOUDAI_11;break;
      case 12: $nomihoudai = NOMIHOUDAI_12;break;
      default: $nomihoudai = NOMIHOUDAI_00;
    }
    $cast_list[$name]['uriage'] += ceil(($nomihoudai*$denpyou['ninzuu'] + $denpyou['chg']*CHARGE_TANKA + $denpyou['order_kei'])*(1+ORDER_TAX)/count($tmp_name)/100)*100;
  }
}

foreach($cast_list as $key=>$row) {
  $uriage[$key] = $row['uriage'];
}
array_multisort($uriage, SORT_DESC, $cast_list);

$goukei = array();
$ranking_list = array();
$i = 1;
foreach($cast_list as $key=>$val) {
  $ranking = array();
  $ranking['num'] = $i++;
  $ranking['name'] = $key;
  $ranking['uriage'] = isset($val['uriage']) ? $val['uriage'] : 0;
  $ranking['simei'] = isset($val['simei']) ? $val['simei'] : 0;
  $ranking['jyounai'] = isset($val['zyounai']) ? $val['zyounai'] : 0;
  $ranking['douhan'] = isset($val['douhan']) ? $val['douhan'] : 0;
  $ranking_list[] = $ranking;
  
  $goukei['uriage'] += $val['uriage'];
  $goukei['simei'] += $val['simei'];
  $goukei['jyounai'] += $val['zyounai'];
  $goukei['douhan'] += $val['douhan'];
}


$tmpl = new bTemplate();

$tmpl->set("tenpo", SHOP_NAME);
$tmpl->set("day1", $hiduke_ja1);
$tmpl->set("day2", $hiduke_ja2);
$tmpl->set("ranking", $ranking_list);
$tmpl->set("goukei", $goukei);

$html = $tmpl->fetch('templates/cast_seiseki.htm');

$mpdf = new mPDF('ja', 'A4', 0, '', 5, 5, 0, 0, 0, 0);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("月間成績表_".$hiduke_ja.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
