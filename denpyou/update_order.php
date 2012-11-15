<?php
include("../config.php");
include("order_dao.php");

$insert_data = array(
  $_POST['denpyou_bangou'],
  $_POST['order_id'],
  $_POST['num']
);
$update_data = array(
  $_POST['num'],
  $_POST['denpyou_bangou'],
  $_POST['order_id']
);

$dao = new OrderListDao();
if($dao->add($insert_data)) {
  echo "成功";
} else {
  if($dao->update($update_data)) {
    echo "成功";
  } else {
    echo "失敗";
  }
}

$dao->close();
?>
