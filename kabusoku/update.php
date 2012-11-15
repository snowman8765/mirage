<?php
$data = array(
  $_POST['kingaku'],
  $_POST['hiduke']
);
$dao = new KabusokuDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
