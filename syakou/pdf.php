<?php
include_once("../config.php");
include_once("../denpyou/dao.php");
include_once("../cast_touroku/dao.php");
include_once("../cast_nyuuryoku/dao.php");
require_once("../lib/mpdf/mpdf.php");
require_once('../lib/bTemplate.php');

$tmpl = new bTemplate();

$tmpl->set("tenpo", SHOP_NAME);

$siharaibi = $_POST['siharaibi'];
$tmpl->set("year", substr($siharaibi,0,4));
$tmpl->set("month", substr($siharaibi,5,2));
$tmpl->set("day", substr($siharaibi,-2));

$hiduke = $_POST['hiduke'];
$hiduke_ja = substr($hiduke,0,4)."年".substr($hiduke,5,2)."月";
//$tmpl->set("day", $hiduke_ja);

$hiduke1 = $hiduke."-01";
$hiduke2 = date("Y-m-d", mktime(0, 0, 0, substr($hiduke,5,2)+1, 0, substr($hiduke,0,4)));
$weekday = array("日", "月", "火", "水", "木", "金", "土");

$dDao = new DenpyouDao();
$cDao = new CastDao();
$cnDao = new CastNyuuryokuDao();

//$dArray = $dDao->getByDateToDate($hiduke1, $hiduke2);
$cArray = $cDao->getNotTaiken();

$name_list = array();
foreach($cArray as $cast) {
  $name_list[] = $cast['name'];
}

$cast_nyuuryoku_by_date = $cnDao->getByDate($hiduke);
foreach($cast_nyuuryoku_by_date as $cn) {
  $tmp = array_search($cn['name'], $name_list);
  if($tmp !== FALSE) {
    $cArray[$tmp]['kinmu_m']++;
  }
}

$TimeStamp1 = strtotime($hiduke1);
$TimeStamp2 = strtotime($hiduke2);
$SecondDiff = abs($TimeStamp2 - $TimeStamp1);
$DayDiff = $SecondDiff / (60 * 60 * 24);
$day_diff2 = $DayDiff+1;
$y = intval(substr($hiduke1,0,4));
$m = intval(substr($hiduke1,5,2));
$d = intval(substr($hiduke1,-2));
for($i=0; $i<=$DayDiff; $i++) {
  $tmp_d = $d + $i;
  $today = date("Y-m-d H:i", mktime(4, 0, 0, $m, $tmp_d, $y));
  $tomorrow = date("Y-m-d H:i", mktime(3, 59, 59, $m, $tmp_d+1, $y));
  $dArray = $dDao->getByDateToDate($today, $tomorrow);
  
  $tmp_num = array();
  $simei_list = array();
  $simei_count = 0;
  foreach($dArray as $denpyou) {
    $tmp = array_search($denpyou['simei1_name'], $name_list);
    if($tmp!==FALSE) {
      switch($denpyou['simei1']) {
        case '指名':$cArray[$tmp]['simei_count']++;$simei_count++;$simei_list[]=$tmp;break;
        case '場内指名':$cArray[$tmp]['jyonaisimei_count']++;break;
        case '同伴':$cArray[$tmp]['douhan1_count']++;break;
        case '前日同伴':$cArray[$tmp]['douhan2_count']++;break;
        case '前々日同伴':$cArray[$tmp]['douhan3_count']++;break;
      }
      $cast_nyuuryoku = $cnDao->getByDateAndName(substr($denpyou['add_date'],0,10), $denpyou['simei1_name']);
      if(isset($cast_nyuuryoku)) {
        $cArray[$tmp]['gen_pe'] += $cast_nyuuryoku['penalty'];
        $cArray[$tmp]['gen_etc'] += $cast_nyuuryoku['genkyuu'];
        $cArray[$tmp]['zatu_etc'] += $cast_nyuuryoku['cleaning'];
        $cArray[$tmp]['maebarai'] += $cast_nyuuryoku['maebarai'];
      }
    }
    $tmp = array_search($denpyou['simei2_name'], $name_list);
    if($tmp!==FALSE) {
      switch($denpyou['simei2']) {
        case '指名':$cArray[$tmp]['simei_count']++;$simei_count++;$simei_list[]=$tmp;break;
        case '場内指名':$cArray[$tmp]['jyonaisimei_count']++;break;
        case '同伴':$cArray[$tmp]['douhan1_count']++;break;
        case '前日同伴':$cArray[$tmp]['douhan2_count']++;break;
        case '前々日同伴':$cArray[$tmp]['douhan3_count']++;break;
      }
      $cast_nyuuryoku = $cnDao->getByDateAndName(substr($denpyou['add_date'],0,10), $denpyou['simei2_name']);
      if(isset($cast_nyuuryoku)) {
        $cArray[$tmp]['gen_pe'] += $cast_nyuuryoku['penalty'];
        $cArray[$tmp]['gen_etc'] += $cast_nyuuryoku['genkyuu'];
        $cArray[$tmp]['zatu_etc'] += $cast_nyuuryoku['cleaning'];
        $cArray[$tmp]['maebarai'] += $cast_nyuuryoku['maebarai'];
      }
    }
    $tmp = array_search($denpyou['simei3_name'], $name_list);
    if($tmp!==FALSE) {
      switch($denpyou['simei3']) {
        case '指名':$cArray[$tmp]['simei_count']++;$simei_count++;$simei_list[]=$tmp;break;
        case '場内指名':$cArray[$tmp]['jyonaisimei_count']++;break;
        case '同伴':$cArray[$tmp]['douhan1_count']++;break;
        case '前日同伴':$cArray[$tmp]['douhan2_count']++;break;
        case '前々日同伴':$cArray[$tmp]['douhan3_count']++;break;
      }
      $cast_nyuuryoku = $cnDao->getByDateAndName(substr($denpyou['add_date'],0,10), $denpyou['simei3_name']);
      if(isset($cast_nyuuryoku)) {
        $cArray[$tmp]['gen_pe'] += $cast_nyuuryoku['penalty'];
        $cArray[$tmp]['gen_etc'] += $cast_nyuuryoku['genkyuu'];
        $cArray[$tmp]['zatu_etc'] += $cast_nyuuryoku['cleaning'];
        $cArray[$tmp]['maebarai'] += $cast_nyuuryoku['maebarai'];
      }
    }
    $tmp = array_search($denpyou['simei4_name'], $name_list);
    if($tmp!==FALSE) {
      switch($denpyou['simei4']) {
        case '指名':$cArray[$tmp]['simei_count']++;$simei_count++;$simei_list[]=$tmp;break;
        case '場内指名':$cArray[$tmp]['jyonaisimei_count']++;break;
        case '同伴':$cArray[$tmp]['douhan1_count']++;break;
        case '前日同伴':$cArray[$tmp]['douhan2_count']++;break;
        case '前々日同伴':$cArray[$tmp]['douhan3_count']++;break;
      }
      $cast_nyuuryoku = $cnDao->getByDateAndName(substr($denpyou['add_date'],0,10), $denpyou['simei4_name']);
      if(isset($cast_nyuuryoku)) {
        $cArray[$tmp]['gen_pe'] += $cast_nyuuryoku['penalty'];
        $cArray[$tmp]['gen_etc'] += $cast_nyuuryoku['genkyuu'];
        $cArray[$tmp]['zatu_etc'] += $cast_nyuuryoku['cleaning'];
        $cArray[$tmp]['maebarai'] += $cast_nyuuryoku['maebarai'];
      }
    }
    
    $cast_uriage = ceil(($denpyou['uriage'] - $simei_count*3000)/$simei_count);
    foreach($simei_list as $tmp) {
      $cArray[$tmp]['kyuuyo_j'] += $cast_uriage;
    }
  }
}

$goukei = array();
$cast_list = array();
$i = 1;
foreach($cArray as $cast) {
  $cast['num'] = $i++;
  if(!isset($cast['kinmu_m'])) {
    $cast['kinmu_m'] = 0;
  }
  $cast['kyuuyo_h'] = $cast['hosyoukyuu'];
  if($cast['simei_count'] <= 4) {
    $cast['teate_simei'] = 0;
  } else if($cast['simei_count'] <= 20) {
    $cast['teate_simei'] = $cast['simei_count'] * 1000;
  } else if($cast['simei_count'] <= 40) {
    $cast['teate_simei'] = $cast['simei_count'] * 1500;
  } else if($cast['simei_count'] <= 60) {
    $cast['teate_simei'] = $cast['simei_count'] * 2000;
  } else {
    $cast['teate_simei'] = $cast['simei_count'] * 2500;
  }
  $cast['teate_jyonaisimei'] = $cast['jyonaisimei_count'] * 1500;
  $cast['teate_douhan'] = $cast['douhan1_count'] * 2000;
  $cast['teate_douhan'] += $cast['douhan2_count'] * 3000;
  $cast['teate_douhan'] += $cast['douhan3_count'] * 3500;
  
  $cast['kyuuyo_j']  = $cast['kyuuyo_j']-780000<=0 ? 0 : $cast['kyuuyo_j']-780000;
  if($cast['kinmu_m']==0) {
    $cast['kyuuyo_j'] = 0;
  } else if($cast['kyuuyo_j'] < 790000) {
    $cast['kyuuyo_j'] = 15000;
  } else if($cast['kyuuyo_j'] < 850000) {
    $cast['kyuuyo_j'] = 16000;
  } else if($cast['kyuuyo_j'] < 890000) {
    $cast['kyuuyo_j'] = 17000;
  } else if($cast['kyuuyo_j'] < 940000) {
    $cast['kyuuyo_j'] = 18000;
  } else if($cast['kyuuyo_j'] < 1000000) {
    $cast['kyuuyo_j'] = 19000;
  } else {
    $cast['kyuuyo_j'] -= 1000000;
    $cast['kyuuyo_j'] = floor($cast['kyuuyo_j']/50000)*10000+20000;
  }
  
  if($cast['kyuuyo_h'] > $cast['kyuuyo_j']) {
    $cast['syakou'] = $cast['kyuuyo_h'];
  } else {
    $cast['syakou'] = $cast['kyuuyo_j'];
  }
  $cast['syakou'] *= $cast['kinmu_m'];
  $cast['syakou'] += $cast['teate_simei'] + $cast['teate_jyonaisimei'] + $cast['teate_douhan'] - $cast['gen_pe'] - $cast['gen_etc'] - $cast['soneki'];
  
  $cast['gensen'] = ceil($cast['syakou'] * 0.1);
  $cast['kousei'] = $cast['kinmu_m'] * 900;
  
  //$cast['zatu_etc'] = 0;
  $cast['meisi'] = $cast['kinmu_m']>0 ? 2000 : 0;
  //$cast['maebarai'] = 0;
  
  $cast['tesuuryou'] = $cast['siharai']=='振込' ? 210 : 0;
  $cast['sikyuugaku'] = $cast['syakou'] - $cast['gensen'] - $cast['kousei'] - $cast['meisi'] - $cast['maebarai'] - $cast['tesuuryou'];
  $cast['sikyuu_ge'] = $cast['siharai']=='振込' ? 0 : $cast['sikyuugaku'];
  $cast['sikyuu_hu'] = $cast['siharai']=='振込' ? $cast['sikyuugaku'] : 0;
  
  $cast_list[] = $cast;
  
  $goukei['kinmu'] += $cast['kinmu_m'];
  $goukei['kyuuyo_h'] += $cast['kyuuyo_h'];
  $goukei['kyuuyo_j'] += $cast['kyuuyo_j'];
  $goukei['teate_simei'] += $cast['teate_simei'];
  $goukei['teate_jyonaisimei'] += $cast['teate_jyonaisimei'];
  $goukei['teate_douhan'] += $cast['teate_douhan'];
  $goukei['gen_pe'] += $cast['gen_pe'];
  $goukei['gen_etc'] += $cast['gen_etc'];
  $goukei['soneki'] += $cast['soneki'];
  $goukei['syakou'] += $cast['syakou'];
  
  $goukei['gensen'] += $cast['gensen'];
  $goukei['kousei'] += $cast['kousei'];
  $goukei['zatu_etc'] += $cast['zatu_cl'];
  $goukei['meisi'] += $cast['meisi'];
  $goukei['maebarai'] += $cast['maebarai'];
  
  $goukei['tesuuryou'] += $cast['tesuuryou'];
  $goukei['sikyuugaku'] += $cast['sikyuugaku'];
  $goukei['sikyuu_ge'] += $cast['sikyuu_ge'];
  $goukei['sikyuu_hu'] += $cast['sikyuu_hu'];
}
$goukei['cast'] = count($cast_list);

$tmpl->set("cast", $cast_list);
$tmpl->set("goukei", $goukei);

$html = $tmpl->fetch('templates/syakou.html');

$mpdf = new mPDF('ja', 'A4-L', 0, '', 5, 5, 0, 0, 0, 0);
$mpdf->WriteHTML($html);
$filename = mb_convert_encoding("社交報酬_".$hiduke_ja.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
