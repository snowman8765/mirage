<?php
include_once("../config.php");
include_once("../denpyou/dao.php");
include_once("../mokuhyou/dao.php");
include_once("../ryousyuusyo/dao.php");
include_once("../cast_nyuuryoku/dao.php");
include_once("../cast_touroku/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$tmpl = new bTemplate();

$tmpl->set("tenpo", SHOP_NAME);

$hiduke = $_POST['hiduke'];
$hiduke_ja = substr($hiduke,0,4)."年".substr($hiduke,5,2)."月";
$tmpl->set("day", $hiduke_ja);

$hiduke1 = $hiduke."-01";
$hiduke2 = date("Y-m-d", mktime(0, 0, 0, substr($hiduke,5,2)+1, 0, substr($hiduke,0,4)));
$weekday = array("日", "月", "火", "水", "木", "金", "土");

$dDao = new DenpyouDao();
$mDao = new MokuhyouDao();
$rDao = new RyousyuusyoDao();
$cDao = new CastDao();
$cnDao = new CastNyuuryokuDao();

$TimeStamp1 = strtotime($hiduke1);
$TimeStamp2 = strtotime($hiduke2);
$SecondDiff = abs($TimeStamp2 - $TimeStamp1);
$DayDiff = $SecondDiff / (60 * 60 * 24);
$day_diff2 = $DayDiff+1;
$y = intval(substr($hiduke1,0,4));
$m = intval(substr($hiduke1,5,2));
$d = intval(substr($hiduke1,-2));
$uriage_list = array();
$goukei = array();
$heikin = array();
$youbi = array();
$syuu = array();
for($i=0; $i<=$DayDiff; $i++) {
  $tmp_d = $d + $i;
  $today = date("Y-m-d H:i", mktime(4, 0, 0, $m, $tmp_d, $y));
  $tomorrow = date("Y-m-d H:i", mktime(3, 59, 0, $m, $tmp_d+1, $y));
  $todayTime = mktime(0, 0, 0, $m, $tmp_d, $y);
  $dArray = $dDao->getByDateToDate($today, $tomorrow);
  $mArray = $mDao->getByDate(date("Y-m-d", $todayTime));
  $rArray = $rDao->getByDate(date("Y-m-d", $todayTime));
  $maeArray = $cnDao->getByDate(date("Y-m-d", $todayTime));
  $uriage = array();
  $uriage["day"] = date("d", $todayTime);
  $uriage["youbi"] = $weekday[date("w", $todayTime)];
  foreach($mArray as $mokuhyou) {
    $uriage["uriage_mo_d"] += $mokuhyou['souuriage'];
    $uriage["rieki_mo_d"] += $mokuhyou['nikkei_rieki'];
  }
  foreach($rArray as $ryousyuusyo) {
    $uriage['keihi_d'] += $ryousyuusyo['kingaku'];
  }
  foreach($dArray as $denpyou) {
    $uriage["uriage_d"] += $denpyou['uriage'];
    $uriage["kyaku_d"] += $denpyou['ninzuu'];
    $uriage["intime_a_d"] += $denpyou['chg']*0.5+$denpyou['ninzuu'];
    $uriage['chg_sum'] += $denpyou['chg'];
  }
  
  foreach($maeArray as $maebarai) {
    $cast = $cDao->getByName($maebarai['name']);
    $uriage['maebarai_c_d'] += $maebarai['maebarai'];
    if($maebarai['syukkin']==1) {
      $uriage['syakou_ninzuu_d']++;
      $uriage['syakou_d'] += $cast['hosyoukyuu'];
    }
  }
  
  $uriage["uriage_r"] = $uriage['uriage_d'] - $uriage['uriage_mo_d'] + $goukei['uriage_r'];
  $uriage["uriage_ra"] = $uriage['uriage_mo_d']>0 ? round($uriage['uriage_d']/$uriage['uriage_mo_d']*100, 1) : 0;
  $uriage['rieki_d'] = $uriage['uriage_d'] - $uriage['syakou_d'] - $uriage['keihi_d'];
  $uriage["rieki_r"] = $uriage['rieki_d'] - $uriage['rieki_mo_d'] + $goukei['rieki_r'];
  $uriage["rieki_ra"] = $uriage['rieki_mo_d']>0 ? round($uriage['rieki_d']/$uriage['rieki_mo_d']*100, 1) : 0;
  $uriage["kyakutanka_d"] = $uriage['kyaku_d']>0 ? round($uriage['uriage_d']/$uriage['kyaku_d']) : 0;
  $uriage["intime_a_d"] = $uriage['kyaku_d']>0 ? round($uriage['intime_a_d']/$uriage['kyaku_d'], 2) : 0;
  $uriage['charge_ra_d'] = count($dArray)>0 ? round($uriage['chg_sum']/count($dArray)*100.0) : 0;
  
  $uriage['syakou_ra'] = $uriage['syakou_d']>0 ? round($uriage['uriage_d']/$uriage['syakou_d']*100.0) : 0;
  $uriage["intime_a_d"] = $uriage['kyaku_d']>0 ? round($uriage['intime_a_d']/$uriage['kyaku_d'], 2) : 0;
  
  $uriage['syakou_tanka'] = $uriage['syakou_ninzuu_d']>0 ? round($uriage['syakou_d']/$uriage['syakou_ninzuu_d']) : 0;
  
  if($uriage['youbi']=='月') {
    $youbi['uriage_mo_mon'] += $uriage["uriage_mo_d"];
    $youbi['uriage_mon'] += $uriage["uriage_d"];
    $youbi['nissuu_mon']++;
    $youbi['uriage_ra_mon'] = 0;
    $youbi['uriage_a_mon'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='火') {
    $youbi['uriage_mo_tue'] += $uriage["uriage_mo_d"];
    $youbi['uriage_tue'] += $uriage["uriage_d"];
    $youbi['nissuu_tue']++;
    $youbi['uriage_ra_tue'] = 0;
    $youbi['uriage_a_tue'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='水') {
    $youbi['uriage_mo_wed'] += $uriage["uriage_mo_d"];
    $youbi['uriage_wed'] += $uriage["uriage_d"];
    $youbi['nissuu_wed']++;
    $youbi['uriage_ra_wed'] = 0;
    $youbi['uriage_a_wed'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='木') {
    $youbi['uriage_mo_thu'] += $uriage["uriage_mo_d"];
    $youbi['uriage_thu'] += $uriage["uriage_d"];
    $youbi['nissuu_thu']++;
    $youbi['uriage_ra_thu'] = 0;
    $youbi['uriage_a_thu'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='金') {
    $youbi['uriage_mo_fri'] += $uriage["uriage_mo_d"];
    $youbi['uriage_fri'] += $uriage["uriage_d"];
    $youbi['nissuu_fri']++;
    $youbi['uriage_ra_fri'] = 0;
    $youbi['uriage_a_fri'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='土') {
    $youbi['uriage_mo_sat'] += $uriage["uriage_mo_d"];
    $youbi['uriage_sat'] += $uriage["uriage_d"];
    $youbi['nissuu_sat']++;
    $youbi['uriage_ra_sat'] = 0;
    $youbi['uriage_a_sat'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
  } else if($uriage['youbi']=='日') {
    $youbi['uriage_mo_sun'] += $uriage["uriage_mo_d"];
    $youbi['uriage_sun'] += $uriage["uriage_d"];
    $youbi['nissuu_sun']++;
    $youbi['uriage_ra_sun'] = 0;
    $youbi['uriage_a_sun'] = 0;
    $youbi['uriage_mo_m'] += $uriage["uriage_mo_d"];
    $youbi['uriage_m'] += $uriage["uriage_d"];
    $youbi['nissuu_m']++;
    
    $syuu['number']++;
    if($uriage["day"]=='01') {
      $syuu['number']--;
    }
  }
  $syuu['uriage_mo_w'.$syuu['number']] += $uriage["uriage_mo_d"];
  $syuu['uriage_w'.$syuu['number']] += $uriage["uriage_d"];
  $syuu['nissuu_w'.$syuu['number']]++;
  $syuu['uriage_ra_w'.$syuu['number']] = 0;
  $syuu['uriage_a_w'.$syuu['number']] = 0;
  $syuu['uriage_mo_m'] += $uriage["uriage_mo_d"];
  $syuu['uriage_m'] += $uriage["uriage_d"];
  $syuu['nissuu_m']++;
  
  $goukei['uriage_r'] = $uriage['uriage_r'];
  $goukei['rieki_r'] = $uriage['rieki_r'];
  $goukei['taizai_zikan'] += $uriage['intime_a_d'];
  
  $goukei['uriage_mo_m'] += $uriage['uriage_mo_d'];
  $goukei['uriage_m'] += $uriage['uriage_d'];
  $goukei['uriage_ra_m'] += $goukei['uriage_m']>0 ? round($goukei['uriage_m']/$goukei['uriage_mo_m']*100.0/$day_diff2) : 0;
  $goukei['syakou_ninzuu_m'] += $uriage['syakou_ninzuu_d'];
  $goukei['syakou_m'] += $uriage['syakou_d'];
  $goukei['syakou_tanka_m'] += $uriage['syakou_tanka'];
  $goukei['maebarai_c_m'] += $uriage['maebarai_c_d'];
  $goukei['syakou_ra_m'] += $goukei['syakou_m']>0 ? round($goukei['uriage_m']/$goukei['syakou_m']*100.0/$day_diff2) : 0;
  $goukei['kyaku_m'] += $uriage['kyaku_d'];
  $goukei['kyakutanka_m'] += $uriage['kyakutanka_d'];
  $goukei['charge_ra_m'] += round($uriage['charge_ra_d']/$day_diff2);
  $goukei['keihi_m'] += $uriage['keihi_d'];
  $goukei['rieki_mo_m'] += $mArray[0]['nikkei_rieki'];
  $goukei['rieki_m'] += $uriage['rieki_d'];
  $goukei['rieki_ra_m'] += $goukei['rieki_mo_m']>0 ? round($goukei['rieki_m']/$goukei['rieki_mo_m']*100.0/$day_diff2, 1) : 0;
  
  $uriage_list[] = $uriage;
}
$dDao->close();
$dDao = null;
$mDao->close();
$mDao = null;
$rDao->close();
$rDao = null;

$youbi['uriage_ra_mon'] = round($youbi['uriage_mon']/$youbi['uriage_mo_mon']*100.0);
$youbi['uriage_a_mon'] = round($youbi['uriage_mon']/$youbi['nissuu_mon']);
$youbi['uriage_ra_tue'] = round($youbi['uriage_tue']/$youbi['uriage_mo_tue']*100.0);
$youbi['uriage_a_tue'] = round($youbi['uriage_tue']/$youbi['nissuu_tue']);
$youbi['uriage_ra_wed'] = round($youbi['uriage_wed']/$youbi['uriage_mo_wed']*100.0);
$youbi['uriage_a_wed'] = round($youbi['uriage_wed']/$youbi['nissuu_wed']);
$youbi['uriage_ra_thu'] = round($youbi['uriage_thu']/$youbi['uriage_mo_thu']*100.0);
$youbi['uriage_a_thu'] = round($youbi['uriage_thu']/$youbi['nissuu_thu']);
$youbi['uriage_ra_fri'] = round($youbi['uriage_fri']/$youbi['uriage_mo_fri']*100.0);
$youbi['uriage_a_fri'] = round($youbi['uriage_fri']/$youbi['nissuu_fri']);
$youbi['uriage_ra_sat'] = round($youbi['uriage_sat']/$youbi['uriage_mo_sat']*100.0);
$youbi['uriage_a_sat'] = round($youbi['uriage_sat']/$youbi['nissuu_sat']);
$youbi['uriage_ra_sun'] = round($youbi['uriage_sun']/$youbi['uriage_mo_sun']*100.0);
$youbi['uriage_a_sun'] = round($youbi['uriage_sun']/$youbi['nissuu_sun']);
$youbi['uriage_ra_m'] = round($youbi['uriage_m']/$youbi['uriage_mo_m']*100.0);
$youbi['uriage_a'] = round($youbi['uriage_m']/$youbi['nissuu_m']);

$syuu['uriage_ra_w0'] = round($syuu['uriage_w0']/$syuu['uriage_mo_w0']*100.0);
$syuu['uriage_a_w0'] = round($syuu['uriage_w0']/$syuu['nissuu_w0']);
$syuu['uriage_ra_w1'] = round($syuu['uriage_w1']/$syuu['uriage_mo_w1']*100.0);
$syuu['uriage_a_w1'] = round($syuu['uriage_w1']/$syuu['nissuu_w1']);
$syuu['uriage_ra_w2'] = round($syuu['uriage_w2']/$syuu['uriage_mo_w2']*100.0);
$syuu['uriage_a_w2'] = round($syuu['uriage_w2']/$syuu['nissuu_w2']);
$syuu['uriage_ra_w3'] = round($syuu['uriage_w3']/$syuu['uriage_mo_w3']*100.0);
$syuu['uriage_a_w3'] = round($syuu['uriage_w3']/$syuu['nissuu_w3']);
$syuu['uriage_ra_w4'] = round($syuu['uriage_w4']/$syuu['uriage_mo_w4']*100.0);
$syuu['uriage_a_w4'] = round($syuu['uriage_w4']/$syuu['nissuu_w4']);
$syuu['uriage_ra_w5'] = round($syuu['uriage_w5']/$syuu['uriage_mo_w5']*100.0);
$syuu['uriage_a_w5'] = round($syuu['uriage_w5']/$syuu['nissuu_w5']);
$syuu['uriage_ra_m'] = $youbi['uriage_ra_m'];
$syuu['uriage_a'] = $youbi['uriage_a'];
  
$heikin['taizai_zikan'] = round($goukei['taizai_zikan']/$day_diff2);
$heikin['uriage_mo_a'] = round($goukei['uriage_mo_m']/$day_diff2);
$heikin['uriage_m'] = round($goukei['uriage_m']/$day_diff2);
$heikin['syakou_ninzuu_a'] = round($goukei['syakou_ninzuu_m']/$day_diff2);
$heikin['syakou_a'] = round($goukei['syakou_m']/$day_diff2);
$heikin['maebarai_c_a'] = round($goukei['maebarai_c_m']/$day_diff2);
$heikin['kyaku_a'] = round($goukei['kyaku_m']/$day_diff2);
$heikin['keihi_m'] = round($goukei['keihi_m']/$day_diff2);
$heikin['rieki_mo_a'] = round($goukei['rieki_mo_m']/$day_diff2);
$heikin['rieki_a'] = round($goukei['rieki_m']/$day_diff2);

$tmpl->set("uriage", $uriage_list);
$tmpl->set("goukei", $goukei);
$tmpl->set("heikin", $heikin);
$tmpl->set("youbi", $youbi);
$tmpl->set("syuu", $syuu);

$html = $tmpl->fetch('templates/gekkan_uriage.html');

$mpdf = new mPDF('ja', 'A4-L', 0, '', 5, 5, 0, 0, 0, 0);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("月間売上表_".$hiduke_ja.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
