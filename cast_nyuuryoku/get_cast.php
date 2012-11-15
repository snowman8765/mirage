<?php
include("../config.php");
include("../cast_touroku/dao.php");

$dao = new CastDao();
$array = $dao->getAll();
$dao->close();
foreach($array as $key=>$val) {
  $name = $val[0];
  echo "<option value='".$name."'>".$name."</option>\n";
}
?>
