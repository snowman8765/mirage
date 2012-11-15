<?php
$data = array(
  $_POST['denpyou_bangou']
);
$dao = new DenpyouDao();

if($dao->delete($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
