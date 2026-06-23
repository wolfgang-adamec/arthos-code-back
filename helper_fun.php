<?php

function log_message ($message, $level = 'info') 
{
  // Log-Datei im selben Verzeichnis (oder einem dedizierten /logs/ Ordner)
  $logFile = __DIR__ . '/person_id.log';
    
  $timestamp = date ('Y-m-d H:i:s');
    
  // Wenn Daten (Arrays/Objekte) übergeben werden, lesbar formatieren
  //$dataString = '';
  //if ($data !== null) {
  //  $dataString = " | Data: " . print_r($data, true);
  //   // Zeilenumbrüche aus print_r für ein sauberes Einzeilen-Log-Format bereinigen
  //  $dataString = str_replace(array("\r", "\n"), " ", $dataString);
  //}
    
  $logEntry = "[$timestamp] $message\n";
    
  // Sicherstellen, dass in die Datei geschrieben werden darf
  file_put_contents ($logFile, $logEntry, FILE_APPEND);
}

function create_mapping ($file_path)
{
  $handle = fopen ($file_path, "r");

  $return_value = [];
  
  if (!$handle) {
  }

  

}

