<?php
include_once("../config.php");
include_once("./dao.php");
include_once("./order_dao.php");
include_once("../order/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$date = $_POST['add_date'];
$denpyou_bangou = $_POST['denpyou_bangou'];
$nyuuten = explode(" ", $_POST['nyuuten']);
$nomihoudai = $_POST['nomihoudai'];
if($_POST['party']>0) {
  $nomihoudai = $_POST['party'];
}
$taiten = explode(" ", $_POST['taiten']);
$sekiban = $_POST['sekiban'];
$ninzuu = $_POST['ninzuu'];
$chg = $_POST['chg'];
$vip = $_POST['vip'];
$simei1 = $_POST['simei1'];
$simei1_name = $_POST['simei1_name'];
$okyaku1 = $_POST['okyaku1'];
$simei2 = $_POST['simei2'];
$simei2_name = $_POST['simei2_name'];
$okyaku2 = $_POST['okyaku2'];
$simei3 = $_POST['simei3'];
$simei3_name = $_POST['simei3_name'];
$okyaku3 = $_POST['okyaku3'];
$simei4 = $_POST['simei4'];
$simei4_name = $_POST['simei4_name'];
$okyaku4 = $_POST['okyaku4'];
//$order_kei = $_POST['order_kei'];
//$syoukei = $_POST['syoukei'];
$waribiki = $_POST['waribiki'];
$siharai = $_POST['siharai'];
$uriage = $_POST['uriage'];

switch($simei1) {
  case '指名' : $hon_simei++;break;
  case '場内指名' : $zyounai_simei++;break;
  case '同伴' : $douhan_simei++;break;
  case '前日同伴' : $douhan_simei++;break;
  case '前々日同伴' : $douhan_simei++;break;
}
switch($simei2) {
  case '指名' : $hon_simei++;break;
  case '場内指名' : $zyounai_simei++;break;
  case '同伴' : $douhan_simei++;break;
  case '前日同伴' : $douhan_simei++;break;
  case '前々日同伴' : $douhan_simei++;break;
}
switch($simei3) {
  case '指名' : $hon_simei++;break;
  case '場内指名' : $zyounai_simei++;break;
  case '同伴' : $douhan_simei++;break;
  case '前日同伴' : $douhan_simei++;break;
  case '前々日同伴' : $douhan_simei++;break;
}
switch($simei4) {
  case '指名' : $hon_simei++;break;
  case '場内指名' : $zyounai_simei++;break;
  case '同伴' : $douhan_simei++;break;
  case '前日同伴' : $douhan_simei++;break;
  case '前々日同伴' : $douhan_simei++;break;
}
if(isset($_POST['simeiryou_komi'])) {
  $hon_simei = 0;
  $zyounai_simei = 0;
  $douhan_simei = 0;
}

$oDao = new OrderDao();
$olDao = new OrderListDao();
$olArray = $olDao->getByDenpyouBangou($denpyou_bangou);
$order = array();
$order_kei = 0;
foreach($olArray as $ol) {
  if($ol['num'] <= 0) {
    continue;
  }
  $oArray = $oDao->getById($ol['order_id']);
  $o = array();
  $o['name'] = $oArray['name'];
  $o['no'] = $ol['order_id'];
  $o['kazu'] = $ol['num'];
  $o['tanka'] = $oArray['kingaku'];
  $o['kingaku'] = $o['kazu']*$o['tanka'];
  $order[] = $o;
  $order_kei += $o['kingaku'];
}
for($i=0; $i<8-count($olArray); $i++) {
  $o = array();
  $o['name'] = '';
  $o['no'] = '';
  $o['kazu'] = '';
  $o['tanka'] = '';
  $o['kingaku'] = '';
  $order[] = $o;
}
$syoukei = $nomihoudai*$ninzuu + $chg*CHARGE_TANKA + $hon_simei*HON_SIMEI_TANKA + $zyounai_simei*ZYOUNAI_SIMEI_TANKA + $douhan_simei*DOUHAN_SIMEI_TANKA + $vip + $order_kei;

$tmpl = new bTemplate();

$tmpl->set("seki", $sekiban);
$tmpl->set("tenpo", SHOP_NAME);
$tmpl->set("day", $date);
$tmpl->set("kyaku", $ninzuu);
$tmpl->set("denpyou_no", $denpyou_bangou);
$tmpl->set("time_in", $nyuuten[0]);
$tmpl->set("time_out", $taiten[0]);
$tmpl->set("kyaku_tanka", $nomihoudai);
$tmpl->set("kingaku", $nomihoudai*$ninzuu);
$tmpl->set("charge", $chg);
$tmpl->set("charge_tanka", CHARGE_TANKA);
$tmpl->set("charge_kingaku", $chg*CHARGE_TANKA);
$tmpl->set("simei", $hon_simei);
$tmpl->set("simei_tanka", HON_SIMEI_TANKA);
$tmpl->set("simei_kingaku", $hon_simei*HON_SIMEI_TANKA);
$tmpl->set("jyounai", $zyounai_simei);
$tmpl->set("jyounai_tanka", ZYOUNAI_SIMEI_TANKA);
$tmpl->set("jyounai_kingaku", $zyounai_simei*ZYOUNAI_SIMEI_TANKA);
$tmpl->set("douhan", $douhan_simei);
$tmpl->set("douhan_tanka", DOUHAN_SIMEI_TANKA);
$tmpl->set("douhan_kingaku", $douhan_simei*DOUHAN_SIMEI_TANKA);
$tmpl->set("vip", $vip>0 ? 1 : 0);
$tmpl->set("vip_tanka", ceil($vip/$ninzuu));
$tmpl->set("vip_kingaku", $vip);
$tmpl->set("order", $order);
$tmpl->set("order_kei", $order_kei);
$tmpl->set("syoukei", $syoukei);
if($_POST['party']>0) {
  $tax = $party*$ninzuu + ceil(($chg*CHARGE_TANKA + $hon_simei*HON_SIMEI_TANKA + $zyounai_simei*ZYOUNAI_SIMEI_TANKA + $douhan_simei*DOUHAN_SIMEI_TANKA + $vip + $order_kei)*TAX/100)*100;
} else {
  $tax = ceil($syoukei*ORDER_TAX/100)*100;
}
$tmpl->set("tax", $tax);
$tmpl->set("waribiki", $waribiki);
$tmpl->set("kaikei", floor($syoukei/100)*100 + $tax - $waribiki);

$html = $tmpl->fetch('templates/denpyou03.htm');

$mpdf = new mPDF('ja', array(100, 148), 0, '', 5, 5, 0, 0, 0, 0); // array(100, 148)
$stylesheet = file_get_contents("templates/style1.css");
$mpdf->WriteHTML($stylesheet, 1);
$stylesheet = file_get_contents("templates/style2.css");
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("明細書_".$denpyou_bangou.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
