<?php
include_once("../abstract/dao.php");

class CastDao extends SqliteItemDao {

  function sql_select(){
    return "select name, hosyoukyuu, siharai, is_taiken from cast";
  }

  function sql_update(){
    return "update cast set hosyoukyuu=?, siharai=?, is_taiken=? where name=?";
  }

  function sql_insert(){
    return "insert into cast (name, hosyoukyuu, siharai, is_taiken) values(?,?,?,?)";
  }

  function sql_delete(){
    return "delete from cast where name=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["name"],
        $rs->fields["hosyoukyuu"],
        $rs->fields["siharai"],
        $rs->fields["is_taiken"],
        "name"=>$rs->fields["name"],
        "hosyoukyuu"=>$rs->fields["hosyoukyuu"],
        "siharai"=>$rs->fields["siharai"],
        "is_taiken"=>$rs->fields["is_taiken"]
    );
    return $array;
  }

  function getByName($name) {
    $array = array();
    $sql = $this->sql_select()." where name='$name'";
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else if($rs || !$rs->EOF) {
      $array = $this->getItems($rs);
    }
    return $array;
  }

  function getTaiken() {
    $array = array();
    $sql = $this->sql_select()." where is_taiken='1'";
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

  function getNotTaiken() {
    $array = array();
    $sql = $this->sql_select()." where is_taiken<>'1'";
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
