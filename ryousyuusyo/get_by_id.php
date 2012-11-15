<?php
include("../config.php");
include("dao.php");

$dao = new RyousyuusyoDao();
$array = $dao->getById($_GET['id']);
$dao->close();
$data = json_encode($array);
print_r($data);
?>
