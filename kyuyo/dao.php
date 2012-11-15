<?php
include_once("../abstract/dao.php");

class CastDao extends SqliteItemDao {

  function sql_select(){
    return "select id, name, hosyoukyuu, is_hurikomi from cast";
  }

  function sql_update(){
    return "update cast set name=?, hosyoukyuu=?, si_hurikomi=?";
  }

  function sql_insert(){
    return "insert into cast (name, hosyoukyuu, is_hurikomi) values(?,?,?)";
  }

  function sql_delete(){
    return "delete from cast where id=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["id"],
        $rs->fields["name"],
        $rs->fields["hosyoukyuu"],
        $rs->fields["is_hurikomi"]
    );
    return $array;
  }
}
?>
