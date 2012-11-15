<?php
$data = array(
  $_POST['hiduke'],
  $_POST['kingaku'],
  $_POST['okyakusama'],
  isset($_POST['is_card'])?1:0,
  $_POST['id']
);
$dao = new UrikakeDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
