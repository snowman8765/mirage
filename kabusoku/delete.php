<?php
$data = array(
  $_POST['hiduke']
);
$dao = new KabusokuDao();

if($dao->delete($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
