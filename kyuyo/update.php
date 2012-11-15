<?php
include("../init.php");
include("dao.php");

if(isset($_POST['update'])) {
  $data = array(
    $_POST['id'],
    $_POST['name'],
    $_POST['hosyoukyuu'],
    $_POST['is_hurikomi']
  );
  $dao = new CastDao();
  
  if($dao->update($data)) {
    echo "成功";
  } else {
    echo "失敗";
  }
  
  $dao->close();
}
?>
