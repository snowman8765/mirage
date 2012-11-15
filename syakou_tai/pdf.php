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
$hiduke1 = $_POST['hiduke1'];
$hiduke2 = $_POST['hiduke2'];
$hiduke_ja1 = substr($hiduke1,0,4)."年".substr($hiduke1,5,2)."月".substr($hiduke1,-2)."日";
$hiduke_ja2 = substr($hiduke2,0,4)."年".substr($hiduke2,5,2)."月".substr($hiduke2,-2)."日";
$tmpl->set("day1", $hiduke_ja1);
$tmpl->set("day2", $hiduke_ja2);

$cDao = new CastDao();
$castNyuuryokuDao = new CastNyuuryokuDao();

$TimeStamp1 = strtotime($hiduke1);
$TimeStamp2 = strtotime($hiduke2);
$SecondDiff = abs($TimeStamp2 - $TimeStamp1);
$DayDiff = $SecondDiff / (60 * 60 * 24);
$y = intval(substr($hiduke1,0,4));
$m = intval(substr($hiduke1,5,2));
$d = intval(substr($hiduke1,-2));
$goukei = array();
$cast_list = array();
for($i=0; $i<=$DayDiff; $i++) {
  $tmp_d = $d + $i;
  $today = date("Y-m-d", mktime(4, 0, 0, $m, $tmp_d, $y));
  $cArray = $castNyuuryokuDao->getByDate($today);
  
  foreach($cArray as $cast_nyuuryoku) {
    $cast = $cDao->getByName($cast_nyuuryoku['name']);
    if($cast_nyuuryoku["syukkin"]==1 && $cast["is_taiken"]==1) {
      $tmp_cast = array();
      $tmp_cast['number'] = $tmp_d;
      $tmp_cast['kinmu_tai']++;
      $tmp_cast['cast_tai'] = $cast["name"];
      $tmp_cast['kinmu_tai'] = 1;
      $tmp_cast['kihonkyu_tai'] = $cast["hosyoukyuu"];
      $tmp_cast['teate_tai'] = 0;
      $tmp_cast['gen_tai'] = 0;
      $tmp_cast['soneki_tai'] = 0;
      $tmp_cast['goukei_tai'] = round($tmp_cast['kihonkyu_tai'] + $tmp_cast['teate_tai'] - $tmp_cast['gen_tai'] - $tmp_cast['gen_tai'] - $tmp_cast['soneki_tai']);
      $tmp_cast['gensen_tai'] = 0;
      $tmp_cast['kousei_tai'] = round($tmp_cast['goukei_tai']*0.1);
      $tmp_cast['maebarai_tai'] = round($tmp_cast['goukei_tai'] - $tmp_cast['kousei_tai']);
      $tmp_cast['sikyuu_tai'] = 0;
      $cast_list[] = $tmp_cast;
      
      $goukei['cast_tai_kei']++;
      $goukei['kinmu_tai_kei']++;
      $goukei['kihonkyu_tai_kei'] += $tmp_cast['kihonkyu_tai'];
      $goukei['teate_tai_kei'] += $tmp_cast['teate_tai'];
      $goukei['gen_tai_kei'] += $tmp_cast['gen_tai'];
      $goukei['soneki_tai_kei'] += $tmp_cast['gen_tai'];
      $goukei['goukei_tai_kei'] += $tmp_cast['goukei_tai'];
      $goukei['gensen_tai_kei'] += $tmp_cast['gensen_tai'];
      $goukei['kousei_tai_kei'] += $tmp_cast['kousei_tai'];
      $goukei['maebarai_tai_kei'] += $tmp_cast['maebarai_tai'];
      $goukei['sikyuu_tai_kei'] += $tmp_cast['sikyuu_tai'];
    }
  }
}

$tmpl->set("cast", $cast_list);
$tmpl->set("goukei", $goukei);

$html = $tmpl->fetch('templates/syakou_taiken02.html');

$mpdf = new mPDF('ja', 'A4', 0, '', 5, 5, 5, 0, 0, 0);
$stylesheet = file_get_contents("templates/style1.css");
$mpdf->WriteHTML($stylesheet, 1);
$stylesheet = file_get_contents("templates/style2.css");
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html, 2);
$filename = mb_convert_encoding("社交表集明細表(体験入店)_".$hiduke_ja1."-".$hiduke_ja2.".pdf", "SJIS", "auto");
$mpdf->Output("../../pdf/".$filename, 'F');
$mpdf->Output($filename, "D");
?>
