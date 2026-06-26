<?php
  /********************************************************************************
   * Dokument: show_person.php                                                    *
   * Author:   Wolfgang Adamec.                                                   *
   * Date:     2026-06-26.                                                        *
   * License:  abc                                                                *
   ********************************************************************************/          
  $str_id = "";
  $str_lang = ""; 

  require_once 'helper_fun.php';

  log_message ("-----------------------", "info");
  log_message ("New call.",               "info");

  $str_id = query_str_get_value ($_GET, "id"); // false/str.  
  if ($str_id === false) {
    log_message ("main.query_str", "error");	
    exit;
  } 

  $str_lang = query_str_get_value ($_GET, "lang"); // false/str.
  
  log_message ("ID: {$str_id}", "info");
  $str_id    = str_replace (".", "_", $str_id);  
  $file_name = "person_" . $str_id . ".art";
  
  $data_map = create_mapping ($file_name); 
  log_mapping ($data_map);  
  <M-:>wq
  if ($data_map === false) {
	log_message ("main.create_mapping", "error");	
	exit;
  }	  
  
  log_message ("nach create_mapping", "info");

  // Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $html_template = file_get_contents ("person-template.html");

  // Platzhalter durch echte Daten ersetzen
  $output = strtr ($html_template, $data_map);

  // Das fertige Ergebnis an den Browser senden
  echo $output;
?>

