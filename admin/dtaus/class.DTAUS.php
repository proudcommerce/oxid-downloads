<?php

/*************************************************************
Class DTAUS

$Id: class.DTAUS.php 11865 2008-09-05 10:46:57Z arvydas $

Constructor DTAUS($typ, $kontoinhaber, $blz, $konto)
	$typ		"L" = Lastschrift, "G" = Gutschrift
	$kontoinhaber	Kontoinhaber aller Transaktionen (Quell-Konto)
	$blz		Bankleitzahl (Quell-BLZ) 
	$konto		Kontonummer (Quell-Konto)

Method addTransaction ($kontoinhaber, $blz, $konto, $betrag, $vz1, $vz2, $vz3)
	$kontoinhaber	Kontoinhaber der Transaktionen (Ziel-Konto)
	$blz		Bankleitzahl (Ziel-BLZ) 
	$konto		Kontonummer (Ziel-Konto)
	$betrag		Transaktions-Betrag in Euro mit Dezimaltrennteichen "."
			z.B. "21.20"
	$vz[1-3]	Verwendungszweck 1-3, max. 27 Zeichen lang
			Umlaute werden automatisch konvertiert
			Länge wird auf 27 Zeichen gekürzt

Method create()
	Erzeugt DTA Text 

Method createHTML()
	Erzeugt HTML Repräsentation der Klasse mit allen Transaktionen 


Beispiel PHP Code:
------------------------ 8< ----------------------------------
require_once("class.DTAUS.php");
  
$dtaus = new DTAUS("L","Firma Demo","76450000","123456");
$dtaus->addTransaktion("Firma Beispiel", "76450000", "111111", "99.00", "vz1", "vz2","vz3");
$dtaus->addTransaktion("Demo Demo", "76450000", "222222", "100.00", "vz1", "vz2","vz3");
$dtaus->addTransaktion("Hans Muster", "76450000", "333333", "1000.00", "vz1", "vz2","vz3");
     
header("Content-Disposition: attachment; filename=\"dtaus0.txt\"");
header("Content-type: text/plain");
header("Cache-control: public");
          
print $dtaus->create();
------------------------ 8< ----------------------------------

LIZENZ:
- Der Lizenznehmer darf den Quellcode noch Teile bzw. Konzepte daraus
  an Dritte weitergeben bzw. vermarkten.
- Der Lizenznehmer darf Änderungen am Code für eigene Zwecke vornehmen,
  muss aber den Author über diese Modifikationen in Kenntniss setzen.


(C) Markus Garscha <mg@gama.de>
**************************************************************/

class _DTAUS {

  function fill($text, $len) {
    $text = strtoupper($text);			// nur Grossbuchstaben

    $text = eregi_replace("ä","AE",$text);	// Umlaute umwandeln
    $text = eregi_replace("ö","OE",$text);
    $text = eregi_replace("ü","UE",$text);
    $text = eregi_replace("ß","SS",$text);
 
    $text = substr($text, 0, $len); 		// text begrenzen
    
    while (strlen($text) < $len) {
      $text .= " ";
    }

    return $text;
  }

  function zero($text, $len) {
    // $text mit "0" von links auffüllen
    while (strlen($text) < $len) {
      $text = "0" . $text;
    }
    
    // $text auf $len begrenzen
    $text = substr($text, 0, $len);
    
    return $text;
  }

  function error($msg) {
    print "ERROR: $msg\n";
  }
}


class DTAUS extends _DTAUS {
  var $_typ;
  var $_kontoinhaber;
  var $_blz;
  var $_konto;
  
  var $_trans;

  var $_cs_betrag;
  var $_cs_blz;
  var $_cs_konto;

  var $_html;

  var $_dtastream;
  var $_dtapos;

  var $errmsg;

  // Constructor
  function DTAUS($typ="L", $kontoinhaber="", $blz="", $konto="") {
    $this->_trans = array();
    $this->_cs_betrag = 0.0;
    $this->_cs_blz = 0;
    $this->_cs_konto = 0;

    $this->setTyp($typ);
    $this->setKontoinhaber($kontoinhaber);
    $this->setBLZ($blz);
    $this->setKonto($konto);
  }

  function setKontoinhaber($kontoinhaber) {
    $this->_kontoinhaber = $this->fill($kontoinhaber,27);
  }

  function setBLZ($blz) {
    $blz = preg_replace('/[^0-9]/','',$blz);
    $this->_blz= $this->zero($blz,8);
  }

  function setKonto($konto) {
    $konto = preg_replace('/[^0-9]/','',$konto);
    $this->_konto = $this->zero($konto,10);
  }

  // Typ: 
  // 	L	Lastschrift
  //	G	Gutschrift
  function setTyp($typ) {
    if ($typ == "L") {
      $this->_typ = "LK";
    } else if ($typ == "G") {
      $this->_typ = "GK";
    } else {
      $this->error("setTyp: Ungültiger Typ (L,G)");
      return false;
    }

    return true;
  }
  
  function addTransaktion($kontoinhaber, $blz, $konto, $betrag, $vz1, $vz2, $vz3) {
    $i = count($this->_trans);
    
    $betrag = str_replace(" ", "", $betrag);
    $betrag = str_replace(",", ".", $betrag);
    $betrag = sprintf("%0.2f", $betrag);
    // $betrag = round($betrag,2);
    $betrag = str_replace(".", "", $betrag); 
    
    $this->_trans[$i] = new DTAUS_ITEM($this, $kontoinhaber, $blz, $konto, $betrag, $vz1, $vz2, $vz3);
    
    $this->_cs_betrag += $betrag;
    $this->_cs_blz += $blz;
    $this->_cs_konto += $konto;

    $this->_html[$i] = "<tr><td>$konto</td><td>$blz</td><td>$kontoinhaber</td><td>$betrag</td><td>$vz1</td><td>$vz2</td><td>$vz3</td></tr>\n";
  }
  
  function _createA() {
    $data  = "0128";		// Datensatzlänge, immer 128
    $data .= "A";		// Typ: A
    $data .= $this->_typ;	// Transaktion: 
    				// 	"LB" = Lastschriften Bankseitig
                            	//	"LK" = Lastschriften Kundenseitig
			        //	"GB" = Gutschriften Bankseitig
				//	"GK" = Gutschriften Kundenseitig
    $data .= $this->_blz;	// Auftraggeber BLZ (8 Ziffern)
    $data .= "00000000";	// Nur bei Kreditinstituten
    $data .= $this->_kontoinhaber;	// Auftraggeber Name (27 Zeichen)
    $data .= date("dmy",time());	// aktuelles Datum (DDMMJJ)
    $data .= "    ";		// 4 Leerzeichen
    $data .= $this->_konto;	// Autraggeber Konto (10 Zeichen)
    $data .= "0000000000";	// Auftraggeber Referenznummer (Optional, 10 Zeichen)
    $data .= "               "; // 15 Blanks (reserviert)
    $data .= "        ";	// Ausführungsdatum (DDMMJJJJ, max. 15 Tage)
    $data .= "                        "; // 24 Blanks (reserviert)
    $data .= "1";		// Währung: 1 = Euro

    return $data;
  }

  function _createC () {
    foreach ($this->_trans as $trans) {
      $data .= $trans->_createC();
    }

    return $data;
  }

  function _createE () {
    // evtl. ein Bug? snprintf("%0.2f",...) besser?
    $cs_betrag = round($this->_cs_betrag,2);
    $cs_betrag = str_replace(".", "", $cs_betrag); 
    $cs_betrag = $this->zero($cs_betrag,13);

    $cs_konto = str_replace(".00", "", $this->_cs_konto); 
    $cs_konto = $this->zero($cs_konto ,17);

    $cs_blz = str_replace(".00", "", $this->_cs_blz); 
    $cs_blz = $this->zero($cs_blz,17);


    $data = "0128";			// Datensatzlänge
    $data .= "E";			// Typ: E
    $data .= "     ";			// reserviert (5)
    $data .= $this->zero(count($this->_trans), 7);	// Anzahl Datensätze vom Typ C (7)
    // $data .= $cs_betrag;	// Kontrollsumme Beträge (13)
    $data .= "0000000000000";	// Kontrollsumme Beträge (13)
    $data .= $cs_konto;		// Kontrollsumme Kontonr (17)
    $data .= $cs_blz;		// Kontrollsumme BLZ (17)
    $data .= $cs_betrag;	// Kontrollsumme Euro (13)
    $data .= "                                                   "; // reserviert (51)
  
    return $data;
  }

  function getCSKonto() {
    $cs_konto = str_replace(".00", "", $this->_cs_konto); 
    $cs_konto = $this->zero($cs_konto ,17);

    return $cs_konto;
  }

  function getCSBLZ() {
    $cs_blz = str_replace(".00", "", $this->_cs_blz); 
    $cs_blz = $this->zero($cs_blz,17);
    return $cs_blz;
  }

  function create() {
    return $this->_createA() . $this->_createC() . $this->_createE();
  }
  
  function createHTML() {
    $html = "<table>\n";
    foreach($this->_html as $htmlentry) {
      $html .= $htmlentry;
    }
    $html .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>". $this->_cs_betrag."</td></tr>\n";
    $html .= "</table>\n";
 
    return $html;
  }

  function parse($dta) {
    $this->_dtapos=0;
    $this->_dtastream=$dta;
    if ($this->_offget(5) == "0128A") {
      $typ = $this->_offget(2);
      $blz = $this->_offget(8);
      if (!$this->_check("00000000", "A(" . $this->_dtapos . ") 8x0")) return $this->errmsg;
      $kontoinhaber = $this->_offget(27);
      $datum = $this->_offget(6);
      if (!$this->_check("    ", "A(" . $this->_dtapos . ") 4x' '")) return $this->errmsg;
      $konto = $this->_offget(10);
      $refernenznr = $this->_offget(15);
      if (!$this->_check("          ", "A(" . $this->_dtapos . ") 10x' '")) return $this->errmsg;
      $ausfuehrungsdatum = $this->_offget(8);
      if (!$this->_check("                        ", "A(" . $this->_dtapos . ") 24x' '")) return $this->errmsg;
      $waehrung = $this->_offget(1);
      if ($waehrung == 1) {
        $waehrung = "Euro";
      } else {
        return "Ungültige Währung: '$waehrung'";
      }

      $reclen = $this->_offget(4);
      $rectyp = $this->_offget(1);
   
      print "Typ..............: $typ\n";
      print "BLZ..............: $blz\n";
      print "Kontoinhaber.....: $kontoinhaber\n";
      print "Datum............: $datum\n";
      print "Konto............: $konto\n";
      print "Referenznummer...: $referenznr\n";
      print "Ausführungsdatum.: $ausfuehrungsdatum\n";
      print "Währung .........: $waehrung\n";
      
      print "\n\n";
   
      $counter=0;
    
      while ($rectyp == 'C') {
        $vz = array();
        $counter++;
        $ablzopt = $this->_offget(8); // Auftraggeber BLZ (optional)
        $blz = $this->_offget(8); // BLZ 
        $konto = $this->_offget(10); // Konto 
        $intref = $this->_offget(13); // interne Nummer 
	$typ = $this->_offget(5);
	switch ($typ) {
	  case "05000": $typ="LK"; break;
	  case "51000": $typ="GK"; break;
	  default: return "C(" . $this->_dtapos . ") unbekannter Typ: $typ";
	}

        if (!$this->_check(" ", "C(" . $this->_dtapos . ") 1x' '")) return $this->errmsg;
        $betragdm = $this->_offget(11);
        $ablz = $this->_offget(8); // Auftraggeber BLZ
        $akonto = $this->_offget(10); // Auftraggeber Konto
        $betrageuro = $this->_offget(11);
        if (!$this->_check("   ", "C(" . $this->_dtapos . ") 3x' '")) return $this->errmsg;
	$kontoinhaber = $this->_offget(27);
        if (!$this->_check("        ", "C(" . $this->_dtapos . ") 8x' '")) return $this->errmsg;
	$auftraggeber = $this->_offget(27);
	$vz1 = $this->_offget(27);
        $waehrung = $this->_offget(1);
	if ($waehrung == 1) {
	  $waehrung = "Euro";
	} else {
	  return "Ungültige Währung: '$waehrung'";
	}
        if (!$this->_check("  ", "C(" . $this->_dtapos . ") 2x' '")) return $this->errmsg;
        $countextra = $this->_offget(2);
	for ($i=0; $i<$countextra; $i++) {
          $extratyp = $this->_offget(2);
	  if ($extratyp == "02") {
	    $vz[$i] = $this->_offget(27);
	  } else {
	    return "Ungültiger Erweiterungstyp, Offset(" . $this->_dtapos . "), Typ: $extratyp";
	  }
	}
        if (!$this->_check("           ", "C(" . $this->_dtapos . ") 11x' '")) return $this->errmsg;

        $betrageuro = sprintf("%0.2f", $betrageuro/100);
	

        print "Transaktion......: $counter\n";
        print "Auftraggeber.....: $auftraggeber\n";
        print "A BLZ (opt)......: $ablzopt\n";
        print "A BLZ............: $ablz\n";
        print "A Konto..........: $akonto\n";
        print "Kontoinhaber.....: $kontoinhaber\n";
        print "BLZ..............: $blz\n";
        print "Konto............: $konto\n";
        print "Interne Nummer...: $intref\n";
        print "Typ..............: $typ\n";
        print "Betrag...........: $betrageuro\n";
        print "Währung..........: $waehrung\n";
        print "Verwendungszweck.: $vz1\n";

        foreach($vz as $extravz) {
          print "Verwendungszweck.: $extravz\n";
	}
	
        print "---------------------------------------------------------\n"; 
	$reclen = $this->_offget(4);
	$rectyp = $this->_offget(1);
	
      }
      
    }
  }
  
  // liest $count zeichen vom $dta stream und verschiebt den 
  // lesezeiger
  function _offget ($count) {
    $start = $this->_dtapos;
    $stop = $start + $count;
    $val = substr($this->_dtastream, $start, $count);
    // print "substr: $start,$count -> $val\n";
    $this->_dtapos = $stop;
    return $val;
  }

  function _check($wert, $error) {
    $count = strlen($wert);    
    $val = $this->_offget($count);
    if ($val != $wert) {
      $this->errmsg = $error;
      return false;
    } else {
      return true;
    }
  }
}

class DTAUS_ITEM extends _DTAUS {
  var $_dtaus;

  var $_kontoinhaber;
  var $_blz;
  var $_kontonr;
  var $_betrag;
  var $_vz1;
  var $_vz2;
  var $_vz3;

  function DTAUS_ITEM($dtaus, $kontoinhaber, $blz, $konto, $betrag, $vz1, $vz2, $vz3) {
    $this->_dtaus = &$dtaus;
    $this->setKontoinhaber($kontoinhaber);
    $this->setBLZ($blz);
    $this->setKonto($konto);
    $this->setBetrag($betrag);
    $this->setVZ1($vz1);
    $this->setVZ2($vz2);
    $this->setVZ3($vz3);
  }

  function setKontoinhaber($kontoinhaber) {
    $this->_kontoinhaber = $this->fill($kontoinhaber,27);
  }

  function setBLZ($blz) {
    $this->_blz= $this->zero($blz,8);
  }

  function setKonto($konto) {
    $this->_konto = $this->zero($konto,10);
  }

  function setBetrag($betrag) {
    $this->_betrag = $this->zero($betrag,11);
  }

  function setVZ1($vz1) {
    $this->_vz1 = $this->fill($vz1,27);
  }

  function setVZ2($vz2) {
    $this->_vz2 = $this->fill($vz2,27);
  }

  function setVZ3($vz3) {
    $this->_vz3 = $this->fill($vz3,27);
  }

  function _createC () {
    $data = "0245";		// 4 Zeichen Satzlänge (187 + 2 * 29)
    $data .= "C";
    $data .= $this->_dtaus->_blz;	// Auftraggeber BLZ (optional)
    $data .= $this->_blz;	// Kunde BLZ
    $data .= $this->_konto;	// Kunde Konto
    $data .= "0000000000000";	// Interne Nummer (13)

    if ($this->_dtaus->_typ == "LK") {
      $trans = "05000";
    } 
    if ($this->_dtaus->_typ == "GK") {
      $trans = "51000";
    } 
    $data .= $trans;		// Transaktion: 
    				// 	05000 Lastschrift Einzugsermächtigung
				//	51000 Überweisung-Gutschrift
    $data .= " ";		// reserviert (1)
    $data .= "00000000000";	// Betrag (11) in DM
    $data .= $this->_dtaus->_blz;	// Auftraggeber BLZ
    $data .= $this->_dtaus->_konto;	// Auftraggeber Kontonr.
    $data .= $this->_betrag;	// Betrag in Euro
    $data .= "   ";		// reserviert (3)
    $data .= $this->_kontoinhaber; // Kunde Name (27)
    $data .= "        ";	// reserviert (8)
    $data .= $this->_dtaus->_kontoinhaber; // Auftraggeber Name (27)
    $data .= $this->_vz1; // Verwendungszweck (27)
    $data .= "1";		// Währung 1=Euro
    $data .= "  ";		// reserviert (2)
    $data .= "02";		// Anzahl Erweiterungssätze "00" bis "15"
    // 187 Zeichen
    
    $data .= "02";		// Erweiterungstyp: 02 Verwendungszweck
    $data .= $this->_vz2; // Verwendungszweck (27)
    // + 29

    $data .= "02";		// Erweiterungstyp: 02 Verwendungszweck
    $data .= $this->_vz3; // Verwendungszweck (27)
    // + 29
    
    $data .= "           ";	// reserviert (11) 

    return $data;
  }
}
?>