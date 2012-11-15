<?php
$data = array(
  $_POST['password'],
  $_POST['name']
);
$dao = new StaffDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
