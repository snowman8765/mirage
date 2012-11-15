<?php
$data = array(
  $_POST['name'],
  $_POST['password']
);
$dao = new StaffDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
