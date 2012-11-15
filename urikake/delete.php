<?php
$data = array(
  $_POST['id']
);
$dao = new UrikakeDao();

if($dao->delete($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
