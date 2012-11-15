<?php
$data = array(
  $_POST['syori_bumon'],
  $_POST['aite_kamoku'],
  $_POST['ryousyuusyo_hiduke'],
  $_POST['siharaisaki'],
  $_POST['youto'],
  $_POST['kingaku'],
  $_POST['id']
);
$dao = new RyousyuusyoDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
