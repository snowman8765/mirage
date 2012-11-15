<?php
$data = array(
  $_POST['nyuuten'],
  $_POST['taiten'],
  $_POST['sekiban'],
  $_POST['ninzuu'],
  $_POST['chg'],
  $_POST['vip'],
  $_POST['simei1'],
  $_POST['simei1_name'],
  $_POST['okyaku1'],
  $_POST['simei2'],
  $_POST['simei2_name'],
  $_POST['okyaku2'],
  $_POST['simei3'],
  $_POST['simei3_name'],
  $_POST['okyaku3'],
  $_POST['simei4'],
  $_POST['simei4_name'],
  $_POST['okyaku4'],
  $_POST['order_kei'],
  $_POST['waribiki'],
  $_POST['siharai'],
  $_POST['uriage'],
  $_POST['denpyou_bangou']
);
$dao = new DenpyouDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
