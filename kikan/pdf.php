<?php
include_once("../config.php");
include_once("../denpyou/dao.php");
include_once("../mokuhyou/dao.php");
include_once("../ryousyuusyo/dao.php");
include_once("../cast_touroku/dao.php");
include_once("../cast_nyuuryoku/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$tmpl = new bTemplate();

$tmpl->set("tenpo", SHOP_NAME);

$hiduke1 = $_POST['hiduke1'];
$hiduke2 = $_POST['hiduke2'];
$hiduke_ja1 = substr($hiduke1,0,4)."年".substr($hiduke1,5,2)."月".substr($hiduke1,-2)."日";
$hiduke_ja2 = substr($hiduke2,0,4)."年".substr($hiduke2,5,2)."月".substr($hiduke2,-2)."日";
$tmpl->set("day1", $hiduke_ja1);
$tmpl->set("day2", $hiduke_ja2);

$dDao = new DenpyouDao();
$mDao = new MokuhyouDao();
$rDao = new RyousyuusyoDao();
$cDao = new CastDao();
$cnDao = new CastNyuuryokuDao();
$kikan_uriage_list = array();
$TimeStamp1 = strtotime($hiduke1);
$TimeStamp2 = strtotime($hiduke2);
$SecondDiff = abs($TimeStamp2 - $TimeStamp1);
$DayDiff = $SecondDiff / (60 * 60 * 24);
$y = intval(substr($hiduke1,0,4));
$m = intval(substr($hiduke1,5,2));
$d = intval(substr($hiduke1,-2));
$ruikei = array();
for($i=0; $i<=$DayDiff; $i++) {
  $tmp_d = $d + $i;
  $today = date("Y-m-d H:i", mktime(4, 0, 0, $m, $tmp_d, $y));
  $tomorrow = date("Y-m-d H:i", mktime(3, 59, 0, $m, $tmp_d+1, $y));
  $dArray = $dDao->getByDateToDate($today, $tomorrow);
  $mArray = $mDao->getByDate(date("Y-m-d", mktime(0, 0, 0, $m, $tmp_d, $y)));
  $rArray = $rDao->getByDate(date("Y-m-d", mktime(0, 0, 0, $m, $tmp_d, $y)));
  $uriage = array();
  $uriage["day"] = date("Y年m月d日", mktime(0, 0, 0, $m, $tmp_d, $y));
  $uriage["uriage_mo_d"] = $mArray[0]['souuriage'];
  $uriage["uriage_d"] = 0;
  $uriage["kyaku_d"] = 0;
  $uriage["intime_a_d"] = 0;
  foreach($dArray as $denpyou) {
    $uriage["uriage_d"] += $denpyou['uriage'];
    $uriage["kyaku_d"] += $denpyou['ninzuu'];
    $uriage["intime_a_d"] += $denpyou['chg']*0.5 + $denpyou['ninzuu'];
  }
  $uriage['keihi_d'] = 0;
  foreach($rArray as $ryousyuusyo) {
    $uriage['keihi_d'] += $ryousyuusyo['kingaku'];
  }
  
  $uriage['syakou_d'] = 0;
  $castNyuuryokuList = $cnDao->getByDate(date("Y-m-d", mktime(0, 0, 0, $m, $tmp_d, $y)));
  foreach($castNyuuryokuList as $cast_nyuuryoku) {
    $cast = $cDao->getByName($cast_nyuuryoku['name']);
    if($cast_nyuuryoku["syukkin"]==1) {
      $uriage['syakou_d'] += $cast['hosyoukyuu'];
    }
  }
  
  $ruikei['taizai_zikan'] += $uriage['intime_a_d'];
  $uriage["uriage_ra"] = $uriage['uriage_mo_d']>0 ? round($uriage['uriage_d']/$uriage['uriage_mo_d']*100, 1) : 0;
  $uriage["kyakutanka_d"] = $uriage['kyaku_d']>0 ? round($uriage['uriage_d']/$uriage['kyaku_d']) : 0;
  $uriage["intime_a_d"] = $uriage['kyaku_d']>0 ? round($uriage['intime_a_d']/$uriage['kyaku_d'], 2) : 0;
  $kikan_uriage_list[] = $uriage;
  
  $ruikei['uriage_mo_d'] += $uriage['uriage_mo_d'];
  $ruikei['uriage_d'] += $uriage['uriage_d'];
  $ruikei['kyaku_d'] += $uriage['kyaku_d'];
  $ruikei['keihi_k'] += $uriage['keihi_d'];
  $ruikei['syakou_k'] += $uriage['syakou_d'];
  $ruikei['rieki_mo_k'] += $mArray[0]['nikkei_rieki'];
}
$dDao->close();
$dDao = null;
$mDao->close();
$mDao = null;
$rDao->close();
$rDao = null;
$cDao->close();
$cDao = null;
$cnDao->close();
$cnDao = null;

$ruikei['jyosikyu_ra'] = round($ruikei['uriage_d']/$ruikei['syakou_k']*100.0);
$ruikei['nikkei_rieki'] = $ruikei['uriage_d'] - $ruikei['syakou_k'] - $ruikei['keihi_k'];

$tmpl->set("uriage", $kikan_uriage_list);

$tmpl->set("uriage_mo_k", $ruikei['uriage_mo_d']);
$tmpl->set("uriage_k", $ruikei['uriage_d']);
$tmpl->set("uriage_ra_k", $ruikei['uriage_mo_d']>0 ? round($ruikei['uriage_d']/$ruikei['uriage_mo_d']*100, 1) : 0);
$tmpl->set("keihi_k", $ruikei['keihi_k']);
$tmpl->set("jyosikyu_ra", $ruikei['jyosikyu_ra']);
$tmpl->set("kyaku_k", $ruikei['kyaku_d']);
$tmpl->set("kyakutanka_a_k", $ruikei['kyaku_d']>0 ? round($ruikei['uriage_d']/$ruikei['kyaku_d']) : 0);
$tmpl->set("rieki_mo_k", $ruikei['rieki_mo_k']);
$tmpl->set("rieki_k", $ruikei['nikkei_rieki']);
$tmpl->set("rieki_ra_k", round($ruikei['nikkei_rieki']/$ruikei['rieki_mo_k']*100, 1));
$tmpl->set("uriage_a_k", round($ruikei['uriage_d']/$DayDiff, 0));
$tmpl->set("syakou_k", $ruikei['syakou_k']);
$tmpl->set("intime_a_k", $ruikei['kyaku_d']>0 ? round($ruikei['taizai_zikan']/$ruikei['kyaku_d'], 2) : 0);

$html = $tmpl->fetch('templates/kikan_uriage.html');

$mpdf = new mPDF('ja', 'A4', 0, '', 5, 5, 0, 0, 0, 0);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("期間売上表_".$hiduke_ja1."-".$hiduke_ja2.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
