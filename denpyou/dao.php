<?php
include_once("../abstract/dao.php");

class DenpyouDao extends SqliteItemDao {

  function sql_select(){
    return "select denpyou_bangou, nyuuten, taiten, sekiban, ninzuu, chg, vip, simei1, simei1_name, okyaku1, simei2, simei2_name, okyaku2, simei3, simei3_name, okyaku3, simei4, simei4_name, okyaku4, order_kei, waribiki, siharai, uriage, add_date from denpyou";
  }

  function sql_update(){
    return "update denpyou set nyuuten=?, taiten=?, sekiban=?, ninzuu=?, chg=?, vip=?, simei1=?, simei1_name=?, okyaku1=?, simei2=?, simei2_name=?, okyaku2=?, simei3=?, simei3_name=?, okyaku3=?, simei4=?, simei4_name=?, okyaku4=?, order_kei=?, waribiki=?, siharai=?, uriage=? where denpyou_bangou=?";
  }

  function sql_insert(){
    return "insert into denpyou (denpyou_bangou, nyuuten, taiten, sekiban, ninzuu, chg, vip, simei1, simei1_name, okyaku1, simei2, simei2_name, okyaku2, simei3, simei3_name, okyaku3, simei4, simei4_name, okyaku4, order_kei, waribiki, siharai, uriage, add_date) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', 'localtime'))";
  }

  function sql_delete(){
    return "delete from denpyou where denpyou_bangou=?";
  }

  function getItems($rs){
    $array = array(
        $rs->fields["denpyou_bangou"],
        $rs->fields["nyuuten"],
        $rs->fields["taiten"],
        $rs->fields["sekiban"],
        $rs->fields["ninzuu"],
        $rs->fields["chg"],
        $rs->fields["vip"],
        $rs->fields["simei1"],
        $rs->fields["simei1_name"],
        $rs->fields["okyaku1"],
        $rs->fields["simei2"],
        $rs->fields["simei2_name"],
        $rs->fields["okyaku2"],
        $rs->fields["simei3"],
        $rs->fields["simei3_name"],
        $rs->fields["okyaku3"],
        $rs->fields["simei4"],
        $rs->fields["simei4_name"],
        $rs->fields["okyaku4"],   
        $rs->fields["order_kei"],  
        $rs->fields["waribiki"],
        $rs->fields["siharai"],
        $rs->fields["uriage"],
        $rs->fields["add_date"],
        "denpyou_bangou"=>$rs->fields["denpyou_bangou"],
        "nyuuten"=>$rs->fields["nyuuten"],
        "taiten"=>$rs->fields["taiten"],
        "sekiban"=>$rs->fields["sekiban"],
        "ninzuu"=>$rs->fields["ninzuu"],
        "chg"=>$rs->fields["chg"],
        "vip"=>$rs->fields["vip"],
        "simei1"=>$rs->fields["simei1"],
        "simei1_name"=>$rs->fields["simei1_name"],
        "okyaku1"=>$rs->fields["okyaku1"],
        "simei2"=>$rs->fields["simei2"],
        "simei2_name"=>$rs->fields["simei2_name"],
        "okyaku2"=>$rs->fields["okyaku2"],
        "simei3"=>$rs->fields["simei3"],
        "simei3_name"=>$rs->fields["simei3_name"],
        "okyaku3"=>$rs->fields["okyaku3"],
        "simei4"=>$rs->fields["simei4"],
        "simei4_name"=>$rs->fields["simei4_name"],
        "okyaku4"=>$rs->fields["okyaku4"],   
        "order_kei"=>$rs->fields["order_kei"],  
        "waribiki"=>$rs->fields["waribiki"],
        "siharai"=>$rs->fields["siharai"],
        "uriage"=>$rs->fields["uriage"],
        "add_date"=>$rs->fields["add_date"]
    );
    return $array;
  }

  function getForList() {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select();
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else {
      for($i=0; !$rs->EOF; $i++) {
        $array[$i] = $this->getItemsForList($rs);
        $rs->MoveNext();
      }
    }
    return $array;
  }

  function getForListByDate($date) {
    $array = array();
    $y = intval(substr($date,0,4));
    $m = intval(substr($date,5,2));
    $d = intval(substr($date,-2));
    $today = date("Y-m-d H:i", mktime(4, 0, 0, $m, $d, $y));
    $tomorrow = date("Y-m-d H:i", mktime(3, 59, 0, $m, $d+1, $y));
    //$this->conn->debug = true;
    $sql = $this->sql_select()." where add_date between '$today' and '$tomorrow'";
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else {
      for($i=0; !$rs->EOF; $i++) {
        $array[$i] = $this->getItemsForList($rs);
        $rs->MoveNext();
      }
    }
    return $array;
  }

  function getItemsForList($rs){
    $array = array(
        $rs->fields["denpyou_bangou"],
        $rs->fields["nyuuten"],
        $rs->fields["taiten"],
        $rs->fields["ninzuu"],
        $rs->fields["uriage"]
    );
    return $array;
  }

  function getByDenpyouBangou($denpyou_bangou) {
    $array = array();
    $sql = $this->sql_select()." where denpyou_bangou='$denpyou_bangou'";
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else if($rs || !$rs->EOF) {
      $array = $this->getItems($rs);
    }
    return $array;
  }

  function getByDate($date) {
    $array = array();
    $y = intval(substr($date,0,4));
    $m = intval(substr($date,5,2));
    $d = intval(substr($date,-2));
    $today = date("Y-m-d H:i", mktime(4, 0, 0, $m, $d, $y));
    $tomorrow = date("Y-m-d H:i", mktime(3, 59, 59, $m, $d+1, $y));
    $sql = $this->sql_select()." where add_date between '$today' and '$tomorrow'";
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

  function getByDateToDate($date1, $date2) {
    $array = array();
    $sql = $this->sql_select()." where add_date between '$date1' and '$date2'";
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
