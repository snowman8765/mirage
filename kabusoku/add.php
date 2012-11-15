<?php
$data = array(
  $_POST['hiduke'],
  $_POST['kingaku']
);
$dao = new KabusokuDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
