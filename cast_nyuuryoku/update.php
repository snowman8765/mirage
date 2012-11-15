<?php
$data = array(
  $_POST['hiduke'],
  $_POST['name'],
  isset($_POST['syukkin'])?1:0,
  $_POST['maebarai'],
  $_POST['penalty'],
  $_POST['cleaning'],
  $_POST['genkyuu'],
  $_POST['id']
);
$dao = new CastNyuuryokuDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
