<?php
$data = array(
  $_POST['name'],
  $_POST['hosyoukyuu'],
  $_POST['siharai'],
  isset($_POST['is_taiken']) ? 1 : 0
);
$dao = new CastDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();	
?>
