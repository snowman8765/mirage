<?php
include("../config.php");
include("dao.php");

$dao = new CastNyuuryokuDao();
$array = $dao->getByDate($_GET['date']);
$dao->close();
$data = array("aaData"=>$array);
$data = json_encode($data);
print_r($data);
?>
