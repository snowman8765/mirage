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
  $_POST['ichi']
);
$dao = new TurisenDao();

if($dao->add($data)) {
  echo "成功";
} else {
  echo "失敗";
}

$dao->close();	
?>
