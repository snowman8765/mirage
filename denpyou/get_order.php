<?php
include("../config.php");
include("../order/dao.php");
include("order_dao.php");

$olDao = new OrderListDao();
$olArray = $olDao->getByIdLimit100($_GET['denpyou_bangou'], $_GET['id']);
$olDao->close();

$oDao = new OrderDao();
$oArray = $oDao->getByIdLimit100($_GET['id']);
$oDao->close();
foreach($oArray as $order) {
  $id = $order[0];
  $name = $order[1];
  $kingaku = $order[2];
  $value = 0;
  foreach($olArray as $orderList){
    if($orderList['order_id']==$id) {
      $value = $orderList['num'];
      break;
    }
  }
  echo <<<HTML
<tr style="color:black;">
  <td bgcolor="#cccccc" class="id" style="font-size:small;">$id</td>
  <td bgcolor="#cccccc" class="name" style="font-size:small;">$name</td>
  <td bgcolor="#cccccc" class="kingaku" style="font-size:small;">$kingaku</td>
  <td bgcolor="#cccccc"><input size="1" class="order_num" id="$id" type="text" value="$value" /></td>
</tr>
HTML;
}
?>
