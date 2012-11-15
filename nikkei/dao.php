<?php
include_once("../abstract/dao.php");

class NikkeihyouDao extends SqliteItemDao {

  function sql_select(){
    return "select hiduke, ruikei_souuriage, ruikei_nikkei_rieki from nikkeihyou";
  }

  function sql_update(){
    return "update nikkeihyou set ruikei_souuriage=?, ruikei_nikkei_rieki=? where hiduke=?";
  }

  function sql_insert(){
    return "insert into nikkeihyou (hiduke, ruikei_souuriage, ruikei_nikkei_rieki) values(?, ?, ?)";
  }

  function sql_delete(){
    return "delete from nikkeihyou where hiduke=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["hiduke"],
        $rs->fields["ruikei_souuriage"],
        $rs->fields["ruikei_nikkei_rieki"],
        "hiduke"=>$rs->fields["hiduke"],
        "ruikei_souuriage"=>$rs->fields["ruikei_souuriage"],
        "ruikei_nikkei_rieki"=>$rs->fields["ruikei_nikkei_rieki"]
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
