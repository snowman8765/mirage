<?php
include("../init.php");
include("dao.php");

if(isset($_POST['delete'])) {
  $data = array(
    $_POST['id']
  );
  $dao = new CastDao();
  
  if($dao->delete($data)) {
    echo "成功";
  } else {
    echo "失敗";
  }
  
  $dao->close();
}
?>
