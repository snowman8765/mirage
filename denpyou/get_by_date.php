<?php
include("../config.php");
include("dao.php");

$dao = new DenpyouDao();
$array = $dao->getForListByDate($_GET['date']);
$dao->close();
$data = array("aaData"=>$array);
$data = json_encode($data);
print_r($data);
?>
