<?php
  require_once 'helper_fun.php';

  log_message ("-----------------------", "info");
  log_message ("Neuer Aufruf.",           "info");

  $str_id = query_str_has_value ($_GET); // false/str.
  
  if ($str_id === false) {
	log_message ("main.query_str", "error");	
	exit;
  } 
  
  log_message ("ID: {$str_id}", "info");
  $str_id    = str_replace (".", "_", $str_id);  
  $file_name = $str_id . ".art";
  
  $data_map = create_mapping ($file_name); 
  log_mapping ($data_map);  
  
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

