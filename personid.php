<?php
  require_once 'helper_fun.php';

  log_message ("-----------------------", "info");
  log_message ("Neuer Aufruf.",           "info");

  $data_map = create_mapping ("falcone.art"); 
  log_mapping ($data_map);  
  
  if ($data_map === false) {
	log_message ("main.create_mapping", "error");	
  }	  
  
  log_message ("nach create_mapping", "info");

  // Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $html_template = file_get_contents ("person-template.html");

  // Platzhalter durch echte Daten ersetzen
  $output = strtr ($html_template, $data_map);

  // Das fertige Ergebnis an den Browser senden
  echo $output;
?>

