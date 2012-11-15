<?php
include_once("../abstract/dao.php");

class OrderDao extends SqliteItemDao {

  function sql_select(){
    return "select id, name, kingaku from order_item";
  }

  function sql_update(){
    return "update order_item set name=?, kingaku=? where id=?";
  }

  function sql_insert(){
    return "insert into order_item (id, name, kingaku) values(?,?,?)";
  }

  function sql_delete(){
    return "delete from order_item where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["name"],
        $rs->fields["kingaku"],
        "id"=>$rs->fields["id"],
        "name"=>$rs->fields["name"],
        "kingaku"=>$rs->fields["kingaku"],
    );
    return $array;
  }

  function getByIdLimit100($id) {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select()." where id between ".$id." and ".($id+99);
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
