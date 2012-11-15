<?php
$data = array(
  $_POST['soukyakusuu'],
  $_POST['souuriage'],
  $_POST['nikkei_rieki'],
  $_POST['hiduke']
);
$dao = new MokuhyouDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
