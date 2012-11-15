<?php
include_once("../abstract/dao.php");

class RyousyuusyoDao extends SqliteItemDao {

  function sql_select(){
    return "select id, syori_bumon, aite_kamoku, ryousyuusyo_hiduke, siharaisaki, youto, kingaku from ryousyuusyo";
  }

  function sql_update(){
    return "update ryousyuusyo set syori_bumon=?, aite_kamoku=?, ryousyuusyo_hiduke=?, siharaisaki=?, youto=?, kingaku=? where id=?";
  }

  function sql_insert(){
    return "insert into ryousyuusyo (syori_bumon, aite_kamoku, ryousyuusyo_hiduke, siharaisaki, youto, kingaku) values(?,?,?,?,?,?)";
  }

  function sql_delete(){
    return "delete from ryousyuusyo where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["syori_bumon"],
        $rs->fields["aite_kamoku"],
        $rs->fields["ryousyuusyo_hiduke"],
        $rs->fields["siharaisaki"],
        $rs->fields["youto"],
        $rs->fields["kingaku"],
        "id"=>$rs->fields["id"],
        "syori_bumon"=>$rs->fields["syori_bumon"],
        "aite_kamoku"=>$rs->fields["aite_kamoku"],
        "ryousyuusyo_hiduke"=>$rs->fields["ryousyuusyo_hiduke"],
        "siharaisaki"=>$rs->fields["siharaisaki"],
        "youto"=>$rs->fields["youto"],
        "kingaku"=>$rs->fields["kingaku"]
    );
    return $array;
  }

  function getByDate($date) {
    $array = array();
    $sql = $this->sql_select()." where ryousyuusyo_hiduke like '$date%'";
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
