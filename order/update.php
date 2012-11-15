<?php
$data = array(
  $_POST['name'],
  $_POST['kingaku'],
  $_POST['id']
);
$dao = new OrderDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
