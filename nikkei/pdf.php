<?php
include_once("../config.php");
include_once("./dao.php");
include_once("../cast_nyuuryoku/dao.php");
include_once("../cast_touroku/dao.php");
include_once("../denpyou/dao.php");
include_once("../kabusoku/dao.php");
include_once("../mokuhyou/dao.php");
include_once("../ryousyuusyo/dao.php");
include_once("../turisen/dao.php");
include_once("../urikake/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$tmpl = new bTemplate();

$tmpl->set("tenpo", SHOP_NAME);

$hiduke = $_POST['hiduke'];
$y = intval(substr($hiduke,0,4));
$m = intval(substr($hiduke,5,2));
$d = intval(substr($hiduke,-2));
$firstday = date("Y-m-d H:i", mktime(4, 0, 0, $m, 1, $y));
$tomorrow = date("Y-m-d H:i", mktime(3, 59, 59, $m, $d+1, $y));
$hiduke2 = substr($hiduke,0,4)."年".substr($hiduke,5,2)."月".substr($hiduke,-2)."日";
$tmpl->set("day", $hiduke2);

$dDao = new DenpyouDao();
$dArray = $dDao->getByDate($hiduke);
$dDateArray = $dDao->getByDateToDate($firstday, $tomorrow);
$dDao->close();
$dDao = null;
$souuriage = 0;
$genkin_uriage = 0;
$card_uriage = 0;
$set_count = 0;
$chg_count = 0;
$simei_count = 0;
$jyounai_simei_count = 0;
$douhan_count = 0;
$vip_count = 0;
$waribiki_kei = 0;
$sou_ninzuu = 0;
$i=1;
foreach($dArray as $d) {
  if($d['siharai'] == 'genkin') {
    $genkin_uriage += $d['uriage'];
  } else if($d['siharai'] == 'card') {
    $card_uriage += $d['uriage'];
  } else {
    $tmpl->set("urikake_".$i++, $d['uriage']);
  }
  $souuriage += $d['uriage'];
  
  $set_count++;
  $chg_count += $d['chg'];
  if($d['simei1'] == "指名") {
    $simei_count++;
  } else if($d['simei1'] == "場内指名") {
    $jyounai_simei_count++;
  } else if($d['simei1'] == "同伴" || $d['simei1'] == "前日同伴" || $d['simei1'] == "前々日同伴") {
    $douhan_count++;
  }
  if($d['simei2'] == "指名") {
    $simei_count++;
  } else if($d['simei2'] == "場内指名") {
    $jyounai_simei_count++;
  } else if($d['simei2'] == "同伴" || $d['simei2'] == "前日同伴" || $d['simei2'] == "前々日同伴") {
    $douhan_count++;
  }
  if($d['simei3'] == "指名") {
    $simei_count++;
  } else if($d['simei3'] == "場内指名") {
    $jyounai_simei_count++;
  } else if($d['simei3'] == "同伴" || $d['simei3'] == "前日同伴" || $d['simei3'] == "前々日同伴") {
    $douhan_count++;
  }
  if($d['simei4'] == "指名") {
    $simei_count++;
  } else if($d['simei4'] == "場内指名") {
    $jyounai_simei_count++;
  } else if($d['simei4'] == "同伴" || $d['simei4'] == "前日同伴" || $d['simei4'] == "前々日同伴") {
    $douhan_count++;
  }
  
  if($d['vip'] > 0) {
    $vip_count++;
  }
  
  $waribiki_kei += $d['waribiki'];
  $sou_ninzuu += $d['ninzuu'];
}
$tmpl->set("uriage_d", number_format($souuriage));
$tmpl->set("uriage_ge_d", number_format($genkin_uriage));
$tmpl->set("uriage_ca_d", number_format($card_uriage));
$tmpl->set("kyaku_d", $set_count);
$tmpl->set("charge_d", $chg_count);
$tmpl->set("simei_d", $simei_count);
$tmpl->set("jyonai_d", $jyounai_simei_count);
$tmpl->set("douhan_d", $douhan_count);
$tmpl->set("vip_d", $vip_count);
$tmpl->set("waribiki", number_format($waribiki_kei));
$tmpl->set("kyaku_d", $sou_ninzuu);

$genkin_ruikei = 0;
$card_ruikei = 0;
$urikake_ruikei = 0;
$uriage_ruikei = 0;
foreach($dDateArray as $d) {
  if($d['siharai'] == 'genkin') {
    $genkin_ruikei += intval($d['uriage']);
  } else if($d['siharai'] == 'card') {
    $card_ruikei += intval($d['uriage']);
  } else {
    $urikake_ruikei += intval($d['uriage']);
  }
  $uriage_ruikei += intval($d['uriage']);
}
$tmpl->set("genkin_ruikei", number_format($genkin_ruikei));
$tmpl->set("card_ruikei", number_format($card_ruikei));
$tmpl->set("urikake_ruikei", number_format($urikake_ruikei));
$tmpl->set("uriage_ruikei", number_format($uriage_ruikei));

$uDao = new UrikakeDao();
$uArray = $uDao->getByDate($hiduke);
$uDao->close();
$uDao = null;
$urikake_genkin_sum = 0;
$i = 1;
foreach($uArray as $u) {
  $tmpl->set("urikake_in_".$i, $u['kingaku']);
  if($u['is_card']!=0){
    $tmpl->set("urikake_in_d_".$i, $u['kingaku']);
    $urikake_genkin_sum += $u['kingaku'];
  } else {
    $tmpl->set("urikake_in_d_".$i, 0);
  }
  $i++;
}
$tmpl->set("urikake_in_d", $urikake_genkin_sum);

$mDao = new MokuhyouDao();
$mArray = $mDao->getByDate($hiduke);
$tmpl->set("kyaku_mo_d", $mArray[0]['soukyakusuu']);
$tmpl->set("uriage_mo_d", number_format($mArray[0]['souuriage']));
$tmpl->set("rieki_mo_d", number_format($mArray[0]['nikkei_rieki']));
$mArray2 = $mDao->getByDate(substr($hiduke,0,7));
$mDao->close();
$mDao = null;
$gekkan_mokuhyou = array();
$gekkan_mokuhyou['souuriage'] = 0;
$gekkan_mokuhyou['soukyakusuu'] = 0;
$gekkan_mokuhyou['nikkei_rieki'] = 0;
$count = 1;
foreach($mArray2 as $m) {
  $gekkan_mokuhyou['soukyakusuu'] += $m['soukyakusuu'];
  $gekkan_mokuhyou['souuriage'] += $m['souuriage'];
  $gekkan_mokuhyou['nikkei_rieki'] += $m['nikkei_rieki'];
  if(intval(substr($m['hiduke'],-2)) <= intval(substr($hiduke,-2))) {
    $gekkan_mokuhyou['ruikei_souuriage'] += $m['souuriage'];
    $gekkan_mokuhyou['ruikei_nikkei_rieki'] += $m['nikkei_rieki'];
  }
}
$tmpl->set("uriage_mo_m", number_format($gekkan_mokuhyou['souuriage']));
$tmpl->set("rieki_mo_m", number_format($gekkan_mokuhyou['nikkei_rieki']));
$tmpl->set("uriage_mo_r", number_format($gekkan_mokuhyou['ruikei_souuriage']));
$tmpl->set("rieki_mo_r", number_format($gekkan_mokuhyou['ruikei_nikkei_rieki']));

$kyakutanka = round($gekkan_mokuhyou['nikkei_rieki']/$gekkan_mokuhyou['soukyakusuu'], 3);
$tmpl->set("kyakutanka_mo_d", number_format($kyakutanka));

$maeDao = new CastNyuuryokuDao();
$maeArray = $maeDao->getByDate($hiduke);
$maeDao->close();
$maeDao = null;
$cDao = new CastDao();
$cast_maebarai = 0;
$taiken_maebarai = 0;
$suitei_syakou = 0;
$cast_count = 0;
$taiken_count = 0;
foreach($maeArray as $mae) {
  $cast = $cDao->getByName($mae['name']);
  if($cast['is_taiken']) {
    $taiken_maebarai += $mae['maebarai'];
    $tmpl->set("ititai".$taiken_count, $mae['name']);
    $tmpl->set("ititai_maebarai".$taiken_count, number_format($mae['maebarai']));
    $taiken_count++;
  } else {
    $cast_maebarai += $mae['maebarai'];
    $cast_count++;
  }
  if($mae['syukkin']==1) {
    $suitei_syakou += $cast["hosyoukyuu"];
  }
}
$tmpl->set("cast_maebarai", number_format($cast_maebarai));
$tmpl->set("taiken_maebarai", number_format($taiken_maebarai));
$tmpl->set("suitei_syakou", number_format($suitei_syakou));
$tmpl->set("cast_ninzuu", $cast_count);
$tmpl->set("taiken_ninzuu", $taiken_count);

$tDao = new TurisenDao();
$tArray = $tDao->getByDate($hiduke);
$tDao->close();
$tDao = null;
$turisenList = array();
$turisen_kei = 0;
foreach($tArray as $t) {
  for($i=0; $i<count($t)-2; $i++) {
    $turisenList[$i] += $t[$i+2];
  }
}
$turisen_kei = $turisenList[0]*10000 + $turisenList[1]*5000 + $turisenList[2]*2000 + $turisenList[3]*1000 + $turisenList[4]*500 + $turisenList[5]*100 + $turisenList[6]*50 + $turisenList[7]*10 + $turisenList[8]*5 + $turisenList[9];
$tmpl->set("kin_10000", $turisenList[0]);
$tmpl->set("kin_5000", $turisenList[1]);
$tmpl->set("kin_2000", $turisenList[2]);
$tmpl->set("kin_1000", $turisenList[3]);
$tmpl->set("kin_500", $turisenList[4]);
$tmpl->set("kin_100", $turisenList[5]);
$tmpl->set("kin_50", $turisenList[6]);
$tmpl->set("kin_10", $turisenList[7]);
$tmpl->set("kin_5", $turisenList[8]);
$tmpl->set("kin_1", $turisenList[9]);
$tmpl->set("tsurisen_kei", number_format($turisen_kei));

$rDao = new RyousyuusyoDao();
$rArray = $rDao->getByDate($hiduke);
$rDao->close();
$rDao = null;
$ryousyuusyo_kingaku_sum = 0;
$i = 1;
foreach($rArray as $r) {
  $tmpl->set("bumon_".$i, $r['syori_bumon']);
  $tmpl->set("kamoku_".$i, $r['aite_kamoku']);
  $tmpl->set("ryosyusyo_d_".$i, $r['ryousyuusyo_hiduke']);
  $tmpl->set("siharaisaki_".$i, $r['siharaisaki']);
  $tmpl->set("youto_".$i, $r['youto']);
  $tmpl->set("ryosyusyo_".$i, number_format($r['kingaku']));
  $ryousyuusyo_kingaku_sum += $r['kingaku'];
  $i++;
}
$tmpl->set("ryosyusyo_count", $i-1);
$tmpl->set("ryosyusyo_sum", number_format($ryousyuusyo_kingaku_sum));

$nikkei_rieki = ($souuriage - $suitei_syakou - $taiken_maebarai - $ryousyuusyo_kingaku_sum);
$tmpl->set("rieki_d", number_format($nikkei_rieki));

$kDao = new KabusokuDao();
$kArray = $kDao->getByDate($hiduke);
$kDao->close();
$kDao = null;
$kabusoku_sum = 0;
foreach($kArray as $k) {
  $kabusoku_sum += $k['kingaku'];
}
$turisen = 150000 + $kabusoku_sum;
$tmpl->set("tsurisen", number_format($turisen));

$yoteizan = $turisen + $genkin_uriage + $urikake_genkin_sum - $cast_maebarai - $taiken_maebarai - $ryousyuusyo_kingaku_sum;
$tmpl->set("yoteizan", number_format($yoteizan));
$kabusoku = $turisen_kei - $yoteizan;
$tmpl->set("kabusoku", number_format($kabusoku));

$nDao = new NikkeihyouDao();
$day = intval(substr($hiduke,-2))-1;
$day = $day<=0 ? 1 : $day;
$yesterday = date("Y-m-d", mktime(0, 0, 0, substr($hiduke,5,2), $day, substr($hiduke,0,4)));
$nArray = $nDao->getByDate($yesterday);
$gekkan_ruikei_uriage = intval($nArray[0]['ruikei_souuriage']) + intval($souuriage); 
$gekkan_ruikei_nikkei_rieki = intval($nArray[0]['ruikei_nikkei_rieki']) + intval($nikkei_rieki);
$tmpl->set("kyakutanka_d", round($souuriage/$sou_ninzuu, 3));
$tmpl->set("uriage_r", number_format($gekkan_ruikei_uriage));
$tmpl->set("rieki_r", number_format($gekkan_ruikei_nikkei_rieki));
if($nDao->add(array($hiduke, $gekkan_ruikei_uriage, $gekkan_ruikei_nikkei_rieki))) {
  //echo "成功";
} else {
  //echo "失敗";
  if($nDao->update(array($gekkan_ruikei_uriage, $gekkan_ruikei_nikkei_rieki, $hiduke))) {
    //echo "成功";
  } else {
    //echo "失敗";
  }
}
$nDao->close();
$nDao = null;
$uriagezan_mo = $gekkan_mokuhyou['souuriage'] - $gekkan_mokuhyou['ruikei_souuriage'];
$uriagezan = $gekkan_mokuhyou['souuriage'] - $gekkan_ruikei_uriage;
$uriagezan_ra = round($uriagezan/$uriagezan_mo*100,1);
$tmpl->set("uriagezan_mo", number_format($uriagezan_mo));
$tmpl->set("uriagezan", number_format($uriagezan));
$tmpl->set("uriagezan_ra", number_format($uriagezan_ra));
$riekizan_mo = $gekkan_mokuhyou['nikkei_rieki'] - $gekkan_ruikei_nikkei_rieki;
$riekizan = $gekkan_mokuhyou['nikkei_rieki'] - $gekkan_ruikei_nikkei_rieki;
$riekizan_ra = round($riekizan/$riekizan_mo*100,1);
$tmpl->set("riekizan_mo", number_format($riekizan_mo));
$tmpl->set("riekizan", number_format($riekizan));
$tmpl->set("riekizan_ra", number_format($riekizan_ra));

$tmpl->set("kyaku_ra_d", round($sou_ninzuu/$mArray[0]['soukyakusuu'] * 100, 1));
$tmpl->set("uriage_ra_d", round($souuriage/$mArray[0]['souuriage'] * 100, 1));
$tmpl->set("rieki_ra_d", round($nikkei_rieki/$mArray[0]['nikkei_rieki'] * 100, 1));
$tmpl->set("kyakutanka_ra_d", round(($souuriage/$sou_ninzuu)/$kyakutanka * 100, 1));
$tmpl->set("uriage_ra_r", round($gekkan_ruikei_uriage/$gekkan_mokuhyou['ruikei_souuriage'] * 100, 1));
$tmpl->set("rieki_ra_r", round($gekkan_ruikei_nikkei_rieki/$gekkan_mokuhyou['ruikei_nikkei_rieki'] * 100, 1));

$html = $tmpl->fetch('templates/nikkeihyou.html');

$mpdf = new mPDF('ja', 'A4', 0, '', 5, 5, 0, 0, 0, 0);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("日計表_".$hiduke2.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
