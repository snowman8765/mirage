<?php
include("../config.php");
include("dao.php");

$dao = new OrderDao();
$array = $dao->getByIdLimit100($_GET['id']);
$dao->close();
$data = array("aaData"=>$array);
$data = json_encode($data);
print_r($data);
?>
