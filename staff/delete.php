<?php
$data = array(
  $_POST['name']
);
$dao = new StaffDao();

if($dao->delete($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
