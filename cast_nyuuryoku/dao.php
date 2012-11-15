<?php
include_once("../abstract/dao.php");

class CastNyuuryokuDao extends SqliteItemDao {

  function sql_select(){
    return "select id, hiduke, name, syukkin, maebarai, penalty, cleaning, genkyuu from cast_nyuuryoku";
  }

  function sql_update(){
    return "update cast_nyuuryoku set hiduke=?, name=?, syukkin=?, maebarai=?, penalty=?, cleaning=?, genkyuu=? where id=?";
  }

  function sql_insert(){
    return "insert into cast_nyuuryoku (hiduke, name, syukkin, maebarai, penalty, cleaning, genkyuu) values(?,?,?,?,?,?,?)";
  }

  function sql_delete(){
    return "delete from cast_nyuuryoku where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["hiduke"],
        $rs->fields["name"],
        $rs->fields["syukkin"],
        $rs->fields["maebarai"],
        $rs->fields["penalty"],
        $rs->fields["cleaning"],
        $rs->fields["genkyuu"],
        "id"=>$rs->fields["id"],
        "hiduke"=>$rs->fields["hiduke"],
        "name"=>$rs->fields["name"],
        "syukkin"=>$rs->fields["syukkin"],
        "maebarai"=>$rs->fields["maebarai"],
        "penalty"=>$rs->fields["penalty"],
        "cleaning"=>$rs->fields["cleaning"],
        "genkyuu"=>$rs->fields["genkyuu"]
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

  function getByDateAndName($date, $name) {
    $array = array();
    $sql = $this->sql_select()." where hiduke like '$date%' and name = '$name'";
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
