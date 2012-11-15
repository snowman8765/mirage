<?php
$data = array(
  $_POST['hiduke'],
  $_POST['soukyakusuu'],
  $_POST['souuriage'],
  $_POST['nikkei_rieki']
);
$dao = new MokuhyouDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
