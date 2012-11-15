<?php
include("../config.php");
include("../cast_touroku/dao.php");

$dao = new CastDao();
$array = $dao->getAll();
$dao->close();
echo "<option value=''> </option>\n";
foreach($array as $key=>$val) {
  $name = $val[0];
  echo "<option value='".$name."'>".$name."</option>\n";
}
?>
