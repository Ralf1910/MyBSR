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
		$this->MaintainVariable("AltpapierNextDate", "Altpapier nächster Abholtermin", 1, "~UnixTimestampDate", 50, true);
		$this->MaintainVariable("AltpapierAbholungAnzeige", "Altpapier Abholung Anzeige", 3, "", 60, true);
		$this->MaintainVariable("BioNextDate", "Biomüll nächster Abholtermin", 1, "~UnixTimestampDate", 70, true);
		$this->MaintainVariable("BioAbholungAnzeige", "Biomüll Abholung Anzeige", 3, "", 80, true);
		

		$this->UpdateAbholtermine();

		//Instanz ist aktiv
		$this->SetStatus(102);
	}

	// Aktualisierung der Variablen zur Darstellung der Abholtermine
	public function UpdateAbholtermine() {
		$AbholungHausmuell 	= array("11.01.2017", "25.01.2017", "08.02.2017", "22.02.2017", "08.03.2017", "22.03.2017", "05.04.2017", "20.04.2017", "04.05.2017", "17.05.2017", "31.05.2017", "14.06.2017", "28.06.2017",
						"12.07.2017", "26.07.2017", "09.08.2017", "23.08.2017", "06.09.2017", "20.09.2017", "05.10.2017", "18.10.2017", "02.11.2017", "15.11.2017", "29.11.2017", "13.12.2017", "28.12.2017",
					       	"10.01.2018", "24.01.2018", "07.02.2018", "21.02.2018", "07.03.2018", "21.03.2018", "05.04.2018", "18.04.2018", "03.05.2018", "16.05.2018", "30.05.2018", "13.06.2018", "27.06.2018",
					        "11.07.2018", "25.07.2018", "08.08.2018", "22.08.2018", "05.09.2018", "19.09.2018", "04.10.2018", "17.10.2018", "31.10.2018", "14.11.2018", "28.11.2018", "12.12.2018", "27.12.2018",
					        "09.01.2019", "23.01.2019", "06.02.2019", "20.02.2019", "06.03.2019", "20.03.2019", "03.04.2019", "17.04.2019", "02.05.2019", "15.05.2019", "29.05.2019", "13.06.2019", "26.06.2019", 
					        "10.07.2019", "24.07.2019", "07.08.2019", "21.08.2019", "04.09.2019", "18.09.2019", "02.10.2019", "16.10.2019", "30.10.2019", "13.11.2019", "27.11.2019", "11.12.2019", "24.12.2019",
					        "04.03.2020", "18.03.2020", "01.04.2020", "16.04.2020", "29.04.2020", "13.05.2020", "27.05.2020", "10.06.2020", "24.06.2020", "08.07.2020", "22.07.2020", "05.08.2020", "19.08.2020",
					       	"02.09.2020", "16.09.2020", "30.09.2020", "14.10.2020", "28.10.2020", "11.11.2020", "25.11.2020", "09.12.2020", "22.12.2020", "06.01.2021", "20.01.2021", "03.02.2021", "17.02.2021",
					        "03.03.2021", "17.03.2021", "31.03.2021", "14.04.2021", "28.04.2021", "12.05.2021", "27.05.2021", "09.06.2021", "23.06.2021", "07.07.2021", "21.07.2021", "04.08.2021", "18.08.2021", 
					        "01.09.2021", "15.09.2021", "29.09.2021", "13.10.2021", "27.10.2021", "10.11.2021", "24.11.2021", "08.12.2021", "22.12.2021", "05.01.2022", "19.01.2022", "02.02.2022", "16.02.2022",
					        "02.03.2022", "16.03.2022", "30.03.2022", "13.04.2022", "27.04.2022", "11.05.2022", "25.05.2022", "09.06.2022", "22.06.2022", "06.07.2022", "20.07.2022", "03.08.2022", "17.08.2022",
					        "31.08.2022", "14.09.2022", "28.09.2022", "12.10.2022", "26.10.2022", "09.11.2022", "23.11.2022", "07.12.2022", "21.12.2022", "04.01.2023", "18.01.2023", "01.02.2023", "15.02.2023", 
					        "01.03.2023", "15.03.2023", "29.03.2023", "13.04.2023", "26.04.2023", "10.05.2023", "24.05.2023", "07.06.2023", "21.06.2023", "05.07.2023", "19.07.2023", "02.08.2023", "16.08.2023", 
					        "30.08.2023", "13.09.2023", "27.09.2023", "11.10.2023", "25.10.2023", "08.11.2023", "22.11.2023", "06.12.2023", "20.12.2023", "04.01.2024", "17.01.2024", "31.01.2024", "14.02.2024",
					        "28.02.2024", "13.03.2024", "27.03.2024", "10.04.2024", "24.04.2023", "08.05.2024", "23.05.2024", "05.06.2024", "19.06.2024", "03.07.2024", "17.07.2024", "31.07.2024", "14.08.2024",
					        "28.08.2024", "11.09.2024", "25.09.2024", "09.10.2024", "06.11.2024", "20.11.2024", "04.12.2024", "18.12.2024", "02.01.2025", "15.01.2025", "29.01.2025", "12.02.2025", "26.02.2025",
					        "12.03.2025", "26.03.2025", "09.04.2025", "24.04.2025", "07.05.2025", "21.05.2025", "04.06.2025", "18.06.2025", "02.07.2025", "16.07.2025", "30.07.2025", "13.08.2025", "27.08.2025",
					        "10.09.2025", "24.09.2025");						
		$AbholungWertstoffe	= array("12.01.2017", "26.01.2017", "09.02.2017", "23.02.2017", "09.03.2017", "23.03.2017", "06.04.2017", "21.04.2017", "05.05.2017", "18.05.2017", "01.06.2017", "15.06.2017", "29.06.2017",
						"13.07.2017", "27.07.2017", "10.08.2017", "24.08.2017", "07.09.2017", "21.09.2017", "06.10.2017", "19.10.2017", "03.11.2017", "16.11.2017", "30.11.2017", "14.12.2017", "29.12.2017",
					        "11.01.2018", "25.01.2018", "08.02.2018", "22.02.2018", "08.03.2018", "22.03.2018", "06.04.2018", "19.04.2018", "04.05.2018", "17.05.2018", "31.05.2018", "14.06.2018", "28.06.2018",
					        "12.07.2018", "26.07.2018", "09.08.2018", "23.08.2018", "06.09.2018", "20.09.2018", "05.10.2018", "18.10.2018", "01.11.2018", "15.11.2018", "29.11.2018", "13.12.2018", "28.12.2018",
					        "10.01.2019", "24.01.2019", "07.02.2019", "21.02.2019", "07.03.2019", "21.03.2019", "04.04.2019", "18.04.2019", "03.05.2019", "16.05.2019", "31.05.2019", "14.06.2019", "27.06.2019", 
					        "11.07.2019", "25.07.2019", "08.08.2019", "22.08.2019", "05.09.2019", "19.09.2019", "04.10.2019", "17.10.2019", "31.10.2019", "14.11.2019", "28.11.2019", "12.12.2019", "27.12.2019",
					        "03.03.2020", "17.03.2020", "31.03.2020", "15.04.2020", "28.04.2020", "12.05.2020", "26.05.2020", "09.06.2020", "23.06.2020", "07.07.2020", "21.07.2020", "04.08.2020", "18.08.2020",
						"01.09.2020", "15.09.2020", "29.09.2020", "13.10.2020", "27.10.2020", "10.11.2020", "24.11.2020", "08.12.2020", "21.12.2020", "05.01.2021", "19.01.2021", "02.02.2021", "16.02.2021",
					        "02.03.2021", "16.03.2021", "30.03.2021", "13.04.2021", "27.04.2021", "11.05.2021", "26.05.2021", "08.06.2021", "22.06.2021", "06.07.2021", "20.07.2021", "03.08.2021", "17.08.2021", 
					        "31.08.2021", "14.09.2021", "28.09.2021", "12.10.2021", "26.10.2021", "09.11.2021", "23.11.2021", "07.12.2021", "21.12.2021", "04.01.2022", "18.01.2022", "01.02.2022", "15.02.2022",
					        "01.03.2022", "15.03.2022", "29.03.2022", "12.04.2022", "26.04.2022", "10.05.2022", "24.05.2022", "08.06.2022", "22.06.2022", "06.07.2022", "20.07.2022", "03.08.2022", "17.08.2022",
					        "31.08.2022", "14.09.2022", "28.09.2022", "12.10.2022", "26.10.2022", "09.11.2022", "23.11.2022", "07.12.2022", "21.12.2022", "04.01.2023", "18.01.2023", "01.02.2023", "15.02.2023", 
					        "01.03.2023", "15.03.2023", "29.03.2023", "13.04.2023", "26.04.2023", "10.05.2023", "24.05.2023", "07.06.2023", "21.06.2023", "05.07.2023", "19.07.2023", "02.08.2023", "16.08.2023", 
					        "30.08.2023", "13.09.2023", "27.09.2023", "11.10.2023", "25.10.2023", "08.11.2023", "22.11.2023", "06.12.2023", "20.12.2023", "28.12.2023", "10.01.2024", "24.01.2024", "07.02.2024",
					        "21.02.2024", "06.03.2024", "20.03.2024", "04.04.2024", "17.04.2024", "02.05.2024", "15.05.2024", "29.05.2024", "12.06.2024", "26.06.2024", "10.07.2024", "24.07.2024", "07.08.2024",
					        "21.08.2024", "04.09.2024", "18.09.2024", "02.10.2024", "16.10.2024", "30.10.2024", "13.11.2024", "27.11.2024", "11.12.2024", "24.12.2024", "08.01.2025", "22.01.2025", "05.02.2025",
					        "19.02.2025", "05.03.2025", "19.03.2025", "02.04.2025", "15.04.2025", "30.04.2025", "14.05.2025", "28.05.2025", "12.06.2025", "25.06.2025", "09.07.2025", "23.07.2025", "06.08.2025",
					       	"20.08.2025", "03.09.2025", "17.09.2025");	 
		$AbholungAltpapier	= array("27.10.2020", "24.11.2020", "21.12.2020", "19.01.2021", "16.02.2021", "16.03.2021", "13.04.2021", "11.05.2021", "08.06.2021", "06.07.2021", "03.08.2021", "31.08.2021", "28.09.2021",
				                "26.10.2021", "23.11.2021", "21.12.2021", "18.01.2022", "22.02.2022", "22.03.2022", "20.04.2022", "17.05.2022", "14.06.2022", "12.07.2022", "09.08.2022", "06.09.2022", "05.10.2022",
					        "01.11.2022", "28.12.2022", "24.01.2023", "21.02.2023", "21.03.2022", "18.04.2023", "16.05.2023", "13.06.2023", "11.07.2023", "08.08.2023", "05.09.2023", "27.12.2023", "23.01.2024",
					        "20.02.2024", "19.03.2024", "16.04.2024", "14.05.2023", "11.06.2024", "09.07.2024", "06.08.2024", "03.09.2024", "26.11.2024", "23.12.2024", "21.01.2025", "18.02.2025", "18.03.2025",
					       	"14.04.2025", "13.05.2025", "11.06.2025", "08.07.2025", "05.08.2025");
		$AbholungBio		= array("28.12.2020", "11.01.2021", "25.01.2021", "08.02.2021", "22.02.2021", "09.03.2021", "22.03.2021", "06.04.2021", "19.04.2021", "03.05.2021", "17.05.2021", "31.05.2021", "14.06.2021",   
						"28.06.2021", "12.07.2021", "26.07.2021", "09.08.2021", "23.08.2021", "06.09.2021", "20.09.2021", "04.10.2021", "18.10.2021", "01.11.2021", "15.11.2021", "29.11.2021", "13.12.2021",
					        "27.12.2021", "10.01.2022", "24.01.2022", "07.02.2022", "21.02.2022", "07.03.2022", "21.03.2022", "04.04.2022", "19.04.2022", "02.05.2022", "16.05.2022", "30.05.2022", "13.06.2022",
					        "27.06.2022", "11.07.2022", "25.07.2022", "08.08.2022", "22.08.2022", "05.09.2022", "19.09.2022", "04.10.2022", "17.10.2022", "31.10.2022", "14.11.2022", "28.11.2022", "12.12.2022",
					        "27.12.2022", "09.01.2023", "23.01.2023", "06.02.2023", "20.02.2023", "06.03.2023", "03.04.2023", "17.04.2023", "02.05.2023", "15.05.2023", "30.05.2023", "12.06.2023", "26.06.2023", 
					        "10.07.2023", "24.07.2023", "07.08.2023", "21.08.2023", "04.09.2023", "18.09.2023", "02.10.2023", "16.10.2023", "30.10.2023", "13.11.2023", "27.11.2023", "11.12.2023", "23.12.2023",
					        "08.01.2024", "22.01.2024", "05.02.2024", "19.02.2024", "04.03.2024", "18.03.2024", "02.04.2024", "15.05.2024", "27.05.2024", "10.06.2024", "24.06.2024", "08.07.2024", "22.07.2024",
					        "05.08.2024", "19.08.2024", "02.09.2024", "16.09.2024", "30.09.2024", "14.10.2024", "28.10.2024", "11.11.2024", "25.11.2024", "09.12.2024", "21.12.2024", "06.01.2025", "20.01.2025",
					        "03.02.2025", "17.02.2025", "03.03.2025", "17.03.2025", "31.03.2025", "12.04.2025", "28.04.2025", "12.05.2025", "26.05.2025", "10.06.2025", "23.06.2025", "07.07.2025", "21.07.2025",
					        "04.08.2025", "18.08.2025", "01.09.2025", "15.09.2025", "29.09.2025");
		
		
		$heute 				= date("d.m.Y", time());
		$morgen 			= date("d.m.Y", time() + 3600*24);
 		$uebermorgen 			= date("d.m.Y", time() + 3600*24*2);


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
		
		// Nächstes Abholdatum für Altpapier aktualisieren
		foreach ($AbholungAltpapier as &$AltpapierTermin) {
			$dateTimestampNow	= strtotime($heute);
			$dateTimestampAltpapierTermin	= strtotime($AltpapierTermin);

			if ($dateTimestampAltpapierTermin >= $dateTimestampNow) {
				SetValue($this->GetIDForIdent("AltpapierNextDate"), $dateTimestampAltpapierTermin);
				SetValue($this->GetIDForIdent("AltpapierAbholungAnzeige"), "Am ".$AltpapierTermin);
				if (strcmp($heute, 	 $AltpapierTermin) == 0) 	SetValue($this->GetIDForIdent("AltpapierAbholungAnzeige"), "Heute");
				if (strcmp($morgen, 	 $AltpapierTermin) == 0) 	SetValue($this->GetIDForIdent("AltpapierAbholungAnzeige"), "Morgen");
				if (strcmp($uebermorgen, $AltpapierTermin) == 0) 	SetValue($this->GetIDForIdent("AltpapierAbholungAnzeige"), "Übermorgen");
				break;
			} else {
				SetValue($this->GetIDForIdent("AltpapierNextDate"), 0);
				SetValue($this->GetIDForIdent("AltpapierAbholungAnzeige"), "unbekannt.");
			}
		}
		
		// Nächstes Abholdatum für Biomüll aktualisieren
		foreach ($AbholungBio as &$BioTermin) {
			$dateTimestampNow	= strtotime($heute);
			$dateTimestampBioTermin	= strtotime($BioTermin);

			if ($dateTimestampBioTermin >= $dateTimestampNow) {
				SetValue($this->GetIDForIdent("BioNextDate"), $dateTimestampBioTermin);
				SetValue($this->GetIDForIdent("BioAbholungAnzeige"), "Am ".$BioTermin);
				if (strcmp($heute, 	 $BioTermin) == 0) 	SetValue($this->GetIDForIdent("BioAbholungAnzeige"), "Heute");
				if (strcmp($morgen, 	 $BioTermin) == 0) 	SetValue($this->GetIDForIdent("BioAbholungAnzeige"), "Morgen");
				if (strcmp($uebermorgen, $BioTermin) == 0) 	SetValue($this->GetIDForIdent("BioAbholungAnzeige"), "Übermorgen");
				break;
			} else {
				SetValue($this->GetIDForIdent("BioNextDate"), 0);
				SetValue($this->GetIDForIdent("BioAbholungAnzeige"), "unbekannt.");
			}
		}
	}


 }

