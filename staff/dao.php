<?php
include_once("../abstract/dao.php");

class StaffDao extends SqliteItemDao {

  function sql_select(){
    return "select name, password from staff";
  }

  function sql_update(){
    return "update staff set password=? where name=?";
  }

  function sql_insert(){
    return "insert into staff (name, password) values(?,?)";
  }

  function sql_delete(){
    return "delete from staff where name=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["name"],
        "*****" //$rs->fields["password"]
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
}
?>
