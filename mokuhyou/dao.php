<?php
include_once("../abstract/dao.php");

class MokuhyouDao extends SqliteItemDao {

  function sql_select(){
    return "select hiduke, soukyakusuu, souuriage, nikkei_rieki from mokuhyou";
  }

  function sql_update(){
    return "update mokuhyou set soukyakusuu=?, souuriage=?, nikkei_rieki=? where hiduke=?;";
  }

  function sql_insert(){
    return "insert into mokuhyou (hiduke, soukyakusuu, souuriage, nikkei_rieki) values(?,?,?,?)";
  }

  function sql_delete(){
    return "delete from mokuhyou where hiduke=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["hiduke"],
        $rs->fields["soukyakusuu"],
        $rs->fields["souuriage"],
        $rs->fields["nikkei_rieki"],
        "hiduke"=>$rs->fields["hiduke"],
        "soukyakusuu"=>$rs->fields["soukyakusuu"],
        "souuriage"=>$rs->fields["souuriage"],
        "nikkei_rieki"=>$rs->fields["nikkei_rieki"]
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
