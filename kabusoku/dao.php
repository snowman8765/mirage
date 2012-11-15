<?php
include_once("../abstract/dao.php");

class KabusokuDao extends SqliteItemDao {

  function sql_select(){
    return "select hiduke, kingaku from kabusoku";
  }

  function sql_update(){
    return "update kabusoku set kingaku=? where hiduke=?;";
  }

  function sql_insert(){
    return "insert into kabusoku (hiduke, kingaku) values(?,?)";
  }

  function sql_delete(){
    return "delete from kabusoku where hiduke=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["hiduke"],
        $rs->fields["kingaku"],
        "hiduke"=>$rs->fields["hiduke"],
        "kingaku"=>$rs->fields["kingaku"]
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
