<?php
$data = array(
  $_POST['id'],
  $_POST['name'],
  $_POST['kingaku']
);
$dao = new OrderDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();	
?>
