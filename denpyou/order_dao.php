<?php
include_once("../abstract/dao.php");

class OrderListDao extends SqliteItemDao {

  function sql_select(){
    return "select denpyou_bangou, order_id, num from order_list";
  }

  function sql_update(){
    return "update order_list set num=? where denpyou_bangou=? and order_id=?";
  }

  function sql_insert(){
    return "insert into order_list (denpyou_bangou, order_id, num) values(?, ?, ?)";
  }

  function sql_delete(){
    return "delete from order_list where denpyou_bangou=? and order_id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["denpyou_bangou"],
        $rs->fields["order_id"],
        $rs->fields["num"],
        "denpyou_bangou"=>$rs->fields["denpyou_bangou"],
        "order_id"=>$rs->fields["order_id"],
        "num"=>$rs->fields["num"]
    );
    return $array;
  }

  function getByDenpyouBangou($id) {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select()." where denpyou_bangou = '$id'";
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else {
      for($i=0; !$rs->EOF; $i++) {
        $array[$i] = $this->getItems($rs);
        $rs->MoveNext();
      }
    }
    return $array;
  }

  function getByIdLimit100($denpyou_bangou, $order_id) {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select()." where denpyou_bangou=".$denpyou_bangou." and order_id between ".$order_id." and ".($order_id+99);
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else {
      for($i=0; !$rs->EOF; $i++) {
        $array[$i] = $this->getItems($rs);
        $rs->MoveNext();
      }
    }
    return $array;
  }
}
?>
