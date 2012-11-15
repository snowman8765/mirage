<?php
include("../init.php");
include("dao.php");

if(isset($_POST['add'])) {
  $data = array(
    $_POST['id'],
    $_POST['name'],
    $_POST['xx']
  );
  $dao = new CastDao();
  
  if($dao->add($data)) {
    echo "成功";
  } else {
    echo "失敗";
  }
  
  $dao->close();
}
?>
