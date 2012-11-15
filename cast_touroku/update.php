<?php
$data = array(
  $_POST['hosyoukyuu'],
  $_POST['siharai'],
  isset($_POST['is_taiken']) ? 1 : 0,
  $_POST['name']
);
$dao = new CastDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
