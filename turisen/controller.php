<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="1; url=turisen.html">
    <title>売上管理システム</title>
  </head>
  <body>
<?php
include("../config.php");
include("dao.php");

if(isset($_POST['add'])) {
  include("add.php");
} else if(isset($_POST['update'])) {
  include("update.php");
} else if(isset($_POST['delete'])) {
  include("delete.php");
}
?>
  </body>
</html>
