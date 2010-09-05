<?php
    /*
    * DAGET Rezervace
    *
    * 2010-18-02 Karel
    * - vytvoreni
    *
    */



    class Rezervace{
        var $id;
        var $pokojId;
        var $datum;
        var $potvrzeno;
        var $datumStart;
        var $datumEnd;
        var $hodnota;
        var $nazev;
        var $cena;
        var $pocetHostu;
        var $detaily;


        Function Rezervace($id=''){
                $CONF = &$GLOBALS["config"];
                If($id){
                    $radek=mysql_fetch_assoc(mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervace WHERE id='$id'"));
                    $dotaz=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervaceUdaje WHERE idRezervace='$id'");
                    while($radek2=mysql_fetch_assoc($dotaz)){
                     $nazev=$radek2["nazev"];
                     $this->udaj[$nazev]=$radek2["hodnota"];
                    }
                    $this->id = $id;
                    $this->pokojId = $radek["pokojId"];
                    $this->datumStart = $radek["datumStart"];
                    $this->datumEnd = $radek["datumEnd"];
                    $this->potvrzeno = $radek["potvrzeno"];
                    $this->datum = $radek["datum"];
                    $this->pocetHostu = $radek["pocetHostu"];
                    $this->cena = $radek["cena"];
                    $this->detaily = $radek["detaily"];

                    Return True;
                }
                Else{
                    $this->id = 0;
                    $this->pokojId = 0;
                    $this->datumStart = '';
                    $this->datumEnd = '';
                    $this->potvrzeno = '0';
                    $this->datum = '';
                    $this->udaj=array();
                    $this->pocetHostu = 0;
                    $this->cena = 0;
                    $this->detaily = '';
                    Return False;
                }
        }

        Function setPokoj($pokojId){
                $CONF = &$GLOBALS["config"];
                $radek=mysql_fetch_assoc(mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervPokoje WHERE id='$pokojId'"));
                if($radek["nazev"]){
                  $this->pokojId = $pokojId;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setDatumStart($datum){
                if($datum){
                  $this->datumStart = $datum;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setDatumEnd($datum){
                if($datum){
                  $this->datumEnd = $datum;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setPotvrzeno($hodnota){
                if($hodnota){
                  $this->potvrzeno = $hodnota;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setPocetHostu($pocetHostu){
                if($pocetHostu){
                  $this->pocetHostu = $pocetHostu;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setCena($cena){
                if($cena){
                  $this->cena = $cena;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setDetaily($detaily){
                if($detaily){
                  $this->detaily = $detaily;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function setUdaj($nazev, $hodnota){
                if($hodnota AND $nazev){
                  $this->udaj[$nazev] = $hodnota;
                  Return True;
                }
                else{
                  Return False;
                }
        }

        Function getPokoj(){
                Return $this->pokojId;
        }

        Function getId(){
                Return $this->Id;
        }

        Function getDatumStart(){
                Return $this->datumStart;
        }

        Function getDatumEnd(){
                Return $this->datumEnd;
        }

        Function getDatum(){
                Return $this->datum;
        }

        Function getPotvrzeno(){
                Return $this->potvrzeno;
        }

        Function getPocetHostu(){
                Return $this->pocetHostu;
        }

        Function getCena(){
                Return $this->cena;
        }

        Function getDetaily(){
                Return $this->detaily;
        }

        Function getUdaj($nazev){
                Return $this->udaj[$nazev];
        }

        Function vytvorVDb(){
          $CONF = &$GLOBALS["config"];
          if($this->pokojId AND $this->datumStart AND $this->datumEnd AND $this->udaj){
            $radek=mysql_fetch_assoc(mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervace 
                                                    WHERE pokojId='$this->pokojId' 
                                                      AND ((datumStart>'$this->datumStart' AND datumStart>'$this->datumEnd') OR (datumEnd<'$this->datumStart' AND datumEnd<'$this->datumEnd')) 
                                                      AND potvrzeno='1'") );
            if(!$radek["datumEnd"] or True){
               $insert = mysql_query("INSERT INTO $CONF[sqlPrefix]rezervRezervace (pokojId, datumStart, datumEnd, potvrzeno, pocetHostu, cena, detaily, datum)  VALUES ('$this->pokojId','$this->datumStart','$this->datumEnd','$this->potvrzeno','$this->pocetHostu','$this->cena','$this->detaily',NOW())");
               $rezervInsertId = mysql_insert_id();
               $radek2=mysql_fetch_assoc(mysql_query("SELECT id FROM $CONF[sqlPrefix]rezervRezervace WHERE pokojId='$this->pokojId' AND datumStart='$this->datumStart'"));
               foreach ($this->udaj as $key => $val) {
                    $insert = mysql_query("INSERT INTO $CONF[sqlPrefix]rezervRezervaceUdaje (idRezervace, nazev, hodnota)  VALUES ('$rezervInsertId','$key','$val')");
               }                                                                                                                /*bylo:$radek2[id]*/


               Return True;
            }
            else{
              Return False;
            }

          }
          else{
            Return False;
          }
        }

        Function ulozZmeny(){
          $CONF = &$GLOBALS["config"];
          if($this->id!=0){
            $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervRezervace SET pokojId='$this->pokojId', datumStart='$this->datumStart', datumEnd='$this->datumEnd', potvrzeno='$this->potvrzeno', pocetHostu='$this->pocetHostu', cena='$this->cena', detaily='$this->detaily' WHERE id='$this->id'");
            foreach ($this->udaj as $key => $val) {
              $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervRezervaceUdaje SET hodnota='$this->udaj[$key]' WHERE idRezervace='$this->id' AND nazev='$key'");
            }
            Return True;
          }
          else{
            Return False;
          }
        }

        Function smazZDb(){
          $CONF = &$GLOBALS["config"];
          if($this->id!=0){
            $smaz=mysql_query("DELETE FROM $CONF[sqlPrefix]rezervRezervace WHERE id = '$this->id' LIMIT 1");
            $smaz=mysql_query("DELETE FROM $CONF[sqlPrefix]rezervRezervaceUdaj WHERE idRezervace = '$this->id'");
            Return True;
          }
          else{
            Return False;
          }
        }


    }
/*
    include("../config.php/default.conf.php");
    $CONF = &$GLOBALS["config"];


mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

$rezervace = new Rezervace();
$setpokoj=$rezervace->setPokoj(1);
echo $rezervace->getPokoj();
$setdatumStart=$rezervace->setDatumStart('2010-02-20');
echo $rezervace->getDatumStart();
$setdatumEnd=$rezervace->setDatumEnd('2010-02-21');
echo $rezervace->getDatumEnd();
$setpotvrzeno=$rezervace->setPotvrzeno(-1);
echo $rezervace->getPotvrzeno();
$setudaj=$rezervace->setUdaj('email','email@email.cz');
$setudaj=$rezervace->setUdaj('telefon','9877987879');
echo $rezervace->getUdaj('email');
echo $rezervace->getUdaj('telefon');
$vytvor=$rezervace->vytvorVDb();
*/
?>