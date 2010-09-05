<?php
    /*
    * DAGET Rezervace
    *
    *  2010-15-02 Michal
    *  - přidal jsem funkci ...
    *  - opravil jsem chybu ...
    */



    class RezervNastaveni{
        var $id;
        var $nazev;
        var $hodnota;
        var $popis;


        Function RezervNastaveni(){
                    $CONF = &$GLOBALS["config"];
                    $i=0;
                    $dotaz=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervNastaveni");
                    while($radek=mysql_fetch_assoc($dotaz)){
                        $this->nastaveni[$i][id] = $radek["id"];
                        $this->nastaveni[$i][nazev] = $radek["nazev"];
                        $this->nastaveni[$i][hodnota] = $radek["hodnota"];
                        $this->nastaveni[$i][popis] = $radek["popis"];
                        $i++;
                    }
                    if($radek["id"]){
                        Return True;
                    }
                    Else{
                        Return False;
                    }
        }

        Function setHodnota($nazev, $hodnota, $popis=''){
                $CONF = &$GLOBALS["config"];
              //zjistím, jestli už tato hodnota v DB je
              $kontrola = mysql_fetch_assoc(mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervNastaveni WHERE nazev='$nazev'"));

              If( $kontrola["id"] ){
                  //pokud existuje, přepíšu aktuální hodnotu
                  Foreach($this->nastaveni as $key=>$value){
                          If( $value["nazev"]==$nazev ){
                              $this->nastaveni[$key]["nazev"] = $nazev;
                              $this->nastaveni[$key]["hodnota"] = $hodnota;
                              if($popis) $this->nastaveni[$key]["popis"] = $popis;
                          }
                  }
              }
              Else{
                  //popud neexistuje, vytvořím novou a dám si pozor na to, aby byl vyplněn popis
                  If( $popis ){
                      $this->nastaveni[] = array("nazev"=>$nazev, "hodnota"=>$hodnota, "popis"=>$popis);
                  }
                  Else Return False;
              }

              Return True;
       }

       Function getHodnota($nazev){
            Foreach($this->nastaveni as $key=>$value){
                          If( $value["nazev"]==$nazev ){
                              Return $this->nastaveni[$key]["hodnota"];
                          }
            }
       }

       Function getPopis($nazev){
            Foreach($this->nastaveni as $key=>$value){
                          If( $value["nazev"]==$nazev ){
                              Return $this->nastaveni[$key]["popis"];
                          }
            }
        }

       Function ulozDoDb(){
         $CONF = &$GLOBALS["config"];
         Foreach($this->nastaveni as $key=>$value){
               $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervNastaveni SET popis='".$this->nastaveni[$key][popis]."', hodnota='".$this->nastaveni[$key][hodnota]."' WHERE id='".$this->nastaveni[$key][id]."' AND nazev='".$this->nastaveni[$key][nazev]."'");
         }
         if($update AND !mysql_error()){
           Return True;
         }
         else {
           Echo "<br>".mysql_error();
           Return False;
         }

        }



    }

include("../config.php/default.conf.php");
    $CONF = &$GLOBALS["config"];

mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

$rezNas = new RezervNastaveni();
$hodnota = $rezNas->setHodnota('email','email@email.cz','email na email');
$hodnota = $rezNas->setHodnota('kurz','32','kurz');
echo $rezNas->getPopis('email');
echo $rezNas->getPopis('kurz');
$uloz = $rezNas->ulozDoDb();
echo $uloz;

?>