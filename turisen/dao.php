<?php
include_once("../abstract/dao.php");

class TurisenDao extends SqliteItemDao {

  function sql_select(){
    return "select id, hiduke, man, gosen, nisen, sen, gohyaku, hyaku, gozyuu, zyuu, go, ichi from turisen";
  }

  function sql_update(){
    return "update turisen set hiduke=?, man=?, gosen=?, nisen=?, sen=?, gohyaku=?, hyaku=?, gozyuu=?, zyuu=?, go=?, ichi=? where id=?";
  }

  function sql_insert(){
    return "insert into turisen (hiduke, man, gosen, nisen, sen, gohyaku, hyaku, gozyuu, zyuu, go, ichi) values(?,?,?,?,?,?,?,?,?,?,?)";
  }

  function sql_delete(){
    return "delete from turisen where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["hiduke"],
        $rs->fields["man"],
        $rs->fields["gosen"],
        $rs->fields["nisen"],
        $rs->fields["sen"],
        $rs->fields["gohyaku"],
        $rs->fields["hyaku"],
        $rs->fields["gozyuu"],
        $rs->fields["zyuu"],
        $rs->fields["go"],
        $rs->fields["ichi"]
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
