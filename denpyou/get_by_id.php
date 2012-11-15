<?php
include("../config.php");
include("dao.php");

$dao = new DenpyouDao();
$array = $dao->getByDenpyouBangou($_GET['denpyou_bangou']);
$dao->close();
$data = json_encode($array);
print_r($data);
?>
