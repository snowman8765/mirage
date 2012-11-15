<?php
include("../config.php");
include("dao.php");

$dao = new StaffDao();
$array = $dao->getByName($_GET['name']);
$dao->close();
$data = json_encode($array);
print_r($data);
?>
