<?php
include_once("../abstract/dao.php");

class UrikakeDao extends SqliteItemDao {

  function sql_select(){
    return "select id, hiduke, kingaku, okyakusama, is_card from urikake";
  }

  function sql_update(){
    return "update urikake set hiduke=?, kingaku=?, okyakusama=?, is_card=? where id=?;";
  }

  function sql_insert(){
    return "insert into urikake (hiduke, kingaku, okyakusama, is_card) values(?,?,?,?)";
  }

  function sql_delete(){
    return "delete from urikake where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["hiduke"],
        $rs->fields["kingaku"],
        $rs->fields["okyakusama"],
        $rs->fields["is_card"],
        "id"=>$rs->fields["id"],
        "hiduke"=>$rs->fields["hiduke"],
        "kingaku"=>$rs->fields["kingaku"],
        "okyakusama"=>$rs->fields["okyakusama"],
        "is_card"=>$rs->fields["is_card"]
    );
    return $array;
  }

  function getByDate($date) {
    $array = array();
    $sql = $this->sql_select()." where hiduke like '$date%'";
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
