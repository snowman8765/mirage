<?php
include("../config.php");
include("dao.php");

$dao = new CastNyuuryokuDao();
$array = $dao->getAll();
$dao->close();
$data = array("aaData"=>$array);
$data = json_encode($data);
print_r($data);
?>
