<?php

/******************************************************************************************
 *                                                                                        *
 ******************************************************************************************/
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

/******************************************************************************************
 * Return: true | false.                                                                  *                                                                                       *
 ******************************************************************************************/
function str_check_hash ($str_line)
{
  $int_pos = 0;
  
  // evidence#value-start (8).
  $int_pos = strpos ($str_line, "#value-start"); // ret.: int/false.
  if ($int_pos === false) {
	return false;
  }
  
  return true;  
}

/******************************************************************************************
 * Return: string | false.                                                                *                                                                                       *
 ******************************************************************************************/
function get_hash_name ($str_line)
{ 
  $bool_result = false;
  $int_pos     = 0;
  $str_result  = "";
  
  $bool_result = str_check_hash ($str_line);
  if ($bool_result === false) {
	return false;
  }   
 
  // evidence#value-start (8).
  $int_pos    = strpos ($str_line, "#value-start"); // ret.: int/false.
  $str_result = substr ($str_line, 0, $int_pos);  

  return $str_result;
}

$str_name = get_hash_name ($str_line);  

/******************************************************************************************
 * Return: Mapping (Ass. Array) | false.                                                  *                                                                                       *
 ******************************************************************************************/
function create_mapping ($file_path)
{
  $arr_parts     = [];
  $arr_ret_value = [];
  $bool_result   = false;
  $handle        = null;  
  $int_dummy     = 0;
  $int_pos       = 0;
  $int_str_len   = 0;
  $str_line      = "";
  $str_name      = "";
  $str_value     = "";
  
  $bool_result = file_exists ($file_path);
  
  if ($bool_result === false) {
	log_message ("create_mapping.file_exists", "error");
    return false;	
  }	  
  
  $handle = fopen ($file_path, "r"); // ret.: resource/false.
  
  if (!$handle === false) {
	log_message ("create_mapping.fopen", "error");
    return false;	
  }

  // Der Header wird überlesen.
  while (true) {
    $str_line = fgets ($handle); // ret.: str./false.
	if ($str_line === false) {
	  log_message ("create_mapping.header_fgets", "error");
      return false;			
	}	
	
	$int_pos = strpos ($str_line, "#data-start"); // ret.: int/false.
	if ($int_pos === false) {
	  $int_dummy = 0;	
	}
    else {
	  if ($result === 0) {
	    break;
	  }	
	}
  }

  // last_name|Falcone
  // Die Name-Value-Paare werden nun verarbeitet.
  while (true) {
    $str_line = fgets ($handle);
	if ($str_line === false) {
	  log_message ("create_mapping.data_fgets", "error");
      return false;			
	}
	$str_line = rtrim ($str_line);

	$int_pos = strpos ($str_line, "#data-end"); // ret.: int/false.
	if ($int_pos === false) {
	  $int_dummy = 0;	
	}
    else {
	  if ($result === 0) {
	    break;
	  }	
	}
	
	$bool_result = str_contains ($str_line, '|') // ret.: bool.
	if ($bool_result === true) {
	  $arr_parts = explode ("|", $str_line, 2);
      $str_name  = trim ($arr_parts [0]);	  
	  $str_value = trim ($arr_parts [1]);	  
	  $arr_ret_value [$str_name] = $str_value;
	}
    else {
	  // evidence#value-start
	  $bool_result = str_check_hash ($str_line); // ret.: bool.
	  if ($bool_result === true) {
		$str_name = get_hash_name ($str_line);  
	  }
    }		
	
	
  } // while	  

  return $arr_ret_value;
}

