<?php
$data = array(
  $_POST['hiduke'],
  $_POST['man'],
  $_POST['gosen'],
  $_POST['nisen'],
  $_POST['sen'],
  $_POST['gohyaku'],
  $_POST['hyaku'],
  $_POST['gozyuu'],
  $_POST['zyuu'],
  $_POST['go'],
  $_POST['ichi'],
  $_POST['id']
);
$dao = new TurisenDao();

if($dao->update($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();
?>
