<?
//  Modul zur Berechnung und Anzeige der Abholtermine
//
//	Version 0.8
//
// ************************************************************

class BSR extends IPSModule {


	public function Create() {
		// Diese Zeile nicht löschen.
		parent::Create();

		// Updates einstellen
		$this->RegisterTimer("UpdateAbholtermine", 60*60*1000, 'BSR_UpdateAbholtermine($_IPS[\'TARGET\']);');
	}


	// Überschreibt die intere IPS_ApplyChanges($id) Funktion
	public function ApplyChanges() {
		// Diese Zeile nicht löschen
		parent::ApplyChanges();

		$this->SetTimerInterval("UpdateAbholtermine", 60*60*1000);


		// Variablen aktualisieren
		$this->MaintainVariable("BSRNextDate", "BSR nächster Abholtermin", 1, "~UnixTimestampDate", 10, true);
		$this->MaintainVariable("BSRAbholungAnzeige", "BSR Abholung Anzeige", 3, "", 20, true);
		$this->MaintainVariable("GruenerPunktNextDate", "Grüner Punkt nächster Abholtermin", 1, "~UnixTimestampDate", 30, true);
		$this->MaintainVariable("GruenerPunktAbholungAnzeige", "Grüner Punkt Abholung Anzeige", 3, "", 40, true);

		$this->UpdateAbholtermine();

		//Instanz ist aktiv
		$this->SetStatus(102);
	}

	// Aktualisierung der Variablen zur Darstellung der Abholtermine
	public function UpdateAbholtermine() {
		$AbholungHausmuell 	= array("11.01.2017", "25.01.2017", "08.02.2017", "22.02.2017", "08.03.2017", "22.03.2017", "05.04.2017", "20.04.2017", "04.05.2017", "17.05.2017", "31.05.2017", "14.06.2017", "28.06.2017",
						"12.07.2017", "26.07.2017", "09.08.2017", "23.08.2017", "06.09.2017", "20.09.2017", "05.10.2017", "18.10.2017", "02.11.2017", "15.11.2017", "29.11.2017", "13.12.2017", "28.12.2017",
					       	"10.01.2018", "24.01.2018", "07.02.2018", "21.02.2018", "07.03.2018", "21.03.2018", "05.04.2018", "18.04.2018", "03.05.2018", "16.05.2018", "30.05.2018", "13.06.2018", "27.06.2018",
					        "11.07.2018", "25.07.2018", "08.08.2018", "22.08.2018", "05.09.2018", "19.09.2018", "04.10.2018", "17.10.2018", "31.10.2018", "14.11.2018", "28.11.2018", "12.12.2018", "27.12.2018");
		
		$AbholungWertstoffe	= array("12.01.2017", "26.01.2017", "09.02.2017", "23.02.2017", "09.03.2017", "23.03.2017", "06.04.2017", "21.04.2017", "05.05.2017", "18.05.2017", "01.06.2017", "15.06.2017", "29.06.2017",
						"13.07.2017", "27.07.2017", "10.08.2017", "24.08.2017", "07.09.2017", "21.09.2017", "06.10.2017", "19.10.2017", "03.11.2017", "16.11.2017", "30.11.2017", "14.12.2017", "29.12.2017",
					        "11.01.2018", "25.01.2018", "08.02.2018", "22.02.2018", "08.03.2018", "22.03.2018", "06.04.2018", "19.04.2018", "04.05.2018", "17.05.2018", "31.05.2018", "14.06.2018", "28.06.2018",
					        "12.07.2018", "26.07.2018", "09.08.2018", "23.08.2018", "06.09.2018", "20.09.2018", "05.10.2018", "18.10.2018", "01.11.2018", "15.11.2018", "29.11.2018", "13.12.2018", "28.12.2018");

		$heute 				= date("d.m.Y", time());
		$morgen 			= date("d.m.Y", time() + 3600*24);
 		$uebermorgen 		= date("d.m.Y", time() + 3600*24*2);


		// Nächstes Abholdatum für die BSR aktualisieren
		foreach ($AbholungHausmuell as &$HausmuellTermin) {
			$dateTimestampNow	= strtotime($heute);
			$dateTimestampHausmuellTermin	= strtotime($HausmuellTermin);

			if ($dateTimestampHausmuellTermin >= $dateTimestampNow) {
				SetValue($this->GetIDForIdent("BSRNextDate"), $dateTimestampHausmuellTermin);
				SetValue($this->GetIDForIdent("BSRAbholungAnzeige"), "Am ".$HausmuellTermin);
				if (strcmp($heute, 		 $HausmuellTermin) == 0) 	SetValue($this->GetIDForIdent("BSRAbholungAnzeige"), "Heute");
				if (strcmp($morgen, 	 $HausmuellTermin) == 0) 	SetValue($this->GetIDForIdent("BSRAbholungAnzeige"), "Morgen");
				if (strcmp($uebermorgen, $HausmuellTermin) == 0) 	SetValue($this->GetIDForIdent("BSRAbholungAnzeige"), "Übermorgen");
				break 1;
			} else {
				SetValue($this->GetIDForIdent("BSRNextDate"), 0);
				SetValue($this->GetIDForIdent("BSRAbholungAnzeige"), "unbekannt");
			}
		}

		// Nächstes Abholdatum für den grünen Punkt aktualisieren
		foreach ($AbholungWertstoffe as &$WertstoffeTermin) {
			$dateTimestampNow	= strtotime($heute);
			$dateTimestampWertstoffeTermin	= strtotime($WertstoffeTermin);

			if ($dateTimestampWertstoffeTermin >= $dateTimestampNow) {
				SetValue($this->GetIDForIdent("GruenerPunktNextDate"), $dateTimestampWertstoffeTermin);
				SetValue($this->GetIDForIdent("GruenerPunktAbholungAnzeige"), "Am ".$WertstoffeTermin);
				if (strcmp($heute, 		 $WertstoffeTermin) == 0) 	SetValue($this->GetIDForIdent("GruenerPunktAbholungAnzeige"), "Heute");
				if (strcmp($morgen, 	 $WertstoffeTermin) == 0) 	SetValue($this->GetIDForIdent("GruenerPunktAbholungAnzeige"), "Morgen");
				if (strcmp($uebermorgen, $WertstoffeTermin) == 0) 	SetValue($this->GetIDForIdent("GruenerPunktAbholungAnzeige"), "Übermorgen");
				break;
			} else {
				SetValue($this->GetIDForIdent("GruenerPunktNextDate"), 0);
				SetValue($this->GetIDForIdent("GruenerPunktAbholungAnzeige"), "unbekannt.");
			}
		}
	}


 }

