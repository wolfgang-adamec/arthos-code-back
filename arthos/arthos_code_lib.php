<?php

/*******************************************************************************
 * Dokument: arthos_code_lib.php.                                              *
 * Author:   Wolfgang Adamec.                                                  *
 * Date:     2026-06-26.                                                       *
 * License:  Apache 2.0.                                                       *
 * Encoding: ANSI.                                                             *
 *******************************************************************************/          

/*******************************************************************************
 *                                                                             *
 *******************************************************************************/
function log_message ($message, $level) 
{
  // Log-Datei im selben Verzeichnis (oder einem dedizierten /logs/ Ordner)
  $logFile = __DIR__ . '/person_id.log';
    
  $timestamp = date ('Y-m-d H:i:s');    
    
  $logEntry = "[$timestamp] [$level] $message\n";
    
  // Sicherstellen, dass in die Datei geschrieben werden darf
  file_put_contents ($logFile, $logEntry, FILE_APPEND);
}

/*******************************************************************************
 *                                                                             *
 *******************************************************************************/
function get_full_file_name ($base_dir, $name_part)
{
  $arr_dateien = [];
  $suchmuster  = "";
  $int_count   = 0;
  $file_name   = "";
  $file_path   = "";

  $suchmuster = $base_dir . DIRECTORY_SEPARATOR . $name_part . "*";

  $arr_dateien = glob ($suchmuster);
  if ($arr_dateien === false) {
    return false;
  }

  $int_count = count ($arr_dateien);
  if ($int_count !== 1) {
    return false;
  }

  // Ich bekomme einen ganzen Pfad zurueck, moechte aber nur den Dateinamen zurueckgeben.
  $file_path = $arr_dateien [0];
  $file_name = basename ($file_path);

  return $file_name;
}

/*******************************************************************************
 * Return: true | false.                                                       *                                                                       
 *******************************************************************************/
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

/*******************************************************************************
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function get_hash_name ($str_line)
{ 
  $bool_result = false;
  $int_pos     = 0;
  $str_result  = "";
  
  $bool_result = str_check_hash ($str_line);
  if ($bool_result === false) {
	log_message ("get_hash_name.check_hash", "error");
	return false;
  }   
 
  // evidence#value-start (8).
  $int_pos    = strpos ($str_line, "#value-start"); // ret.: int/false.
  $str_result = substr ($str_line, 0, $int_pos);  

  log_message ("hash name", "info");
  log_message ($str_result, "info");

  return $str_result;
}

/*******************************************************************************
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function get_hash_value ($handle)
{ 
  $bool_start     = true;
  $int_pos        = 0;
  $str_hash_value = "";
  $str_line       = "";
  
  log_message ("get_hash_value.start", "info");
  
  while (true) {
    $str_line = fgets ($handle); // ret.: str./false.
	if ($str_line === false) {
	  log_message ("get_hash_value.fgets", "error");
      return false;			
	}	
	
	#value-end  
	$int_pos = strpos ($str_line, "#value-end"); // ret.: int/false.
	if ($int_pos === false) {
	  log_message ("hash-data", "info");	
	  log_message ($str_line, "info");	
	  
	  $str_line = trim ($str_line);
	  if ($bool_start === false) {
		$str_hash_value .= " ";
	  }
	  else {
		$bool_start = false;
	  }
	  $str_hash_value .= $str_line;
	}
    else {
      log_message ("hash_value", "info");
	  log_message ($str_hash_value, "info");
	  
	  return $str_hash_value;	
	}
  } // while

}

/*******************************************************************************
 *                                                                             *                                                                       
 *******************************************************************************/
function query_str_get_value ($arr_query_str, $str_attr)
{
  $bool_result = false;
  $str_return  = "";  
   
  $bool_result = array_key_exists ($str_attr, $arr_query_str);
  
  if ($bool_result === false) {
    return false;
  }
  
  $str_return = $arr_query_str [$str_attr];
  
  return $str_return;
}

/*******************************************************************************
 *                                                                             *                                                                                       
 *******************************************************************************/
function log_mapping ($mapping)
{
  $log_line = "";
  
  foreach ($mapping as $name => $value) {
    $log_line .= "{$name}: {$value}\n";	  
  }
  log_message ($log_line, "info");
}

/*******************************************************************************
 * Return: Mapping (Ass. Array) | false.                                       *                                                                                       
 *******************************************************************************/
function prepare_card ($code, $base_dir)
{
  $str_id = "";
  $str_lang = ""; 
  $str_sapi_name = "";
  $file_name_full = "";
  $file_name_part = "";
  $html_template = "";
  $output = "";
  $template_name = "";

  $str_sapi_name = php_sapi_name ();
  if ($str_sapi_name === "cli") {
    $_GET["id"]   = "1.1";
    $_GET["lang"] = "deu";
  }

  $str_id = query_str_get_value ($_GET, "id"); // false/str.  
  if ($str_id === false) {
    log_message ("prepare_card.query_str_get_value-id", "error");	
    exit;
  } 

  $str_lang = query_str_get_value ($_GET, "lang"); // false/str.
  if ($str_lang === false) {
    log_message ("prepare_card.query_str_get_value-lang", "error");
    exit;
  }  

  log_message ("ID:   {$str_id}", "info");
  log_message ("lang: {$str_lang}", "info");

  $str_id         = str_replace (".", "_", $str_id);  
  $file_name_part = $code . "_" . $str_lang . "_" . $str_id;
  
  $file_name_full = get_full_file_name ($base_dir, $file_name_part);
  if ($file_name_full ===  false) {
    log_message ("prepare_card.get_full_file_name", "error");
    exit;
  }

  log_message ($file_name_part, "info");
  log_message ($file_name_full, "info");

  $data_map = create_mapping ($file_name_full); 
  log_mapping ($data_map);  
  if ($data_map === false) {
    log_message ("prepare_card.create_mapping", "error");	
    exit;
  }	  
  
  log_message ("nach create_mapping", "info");

  $template_name = $code . "-template.html";

  // Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $html_template = file_get_contents ($template_name);

  // Platzhalter durch echte Daten ersetzen
  $output = strtr ($html_template, $data_map);
 
  return $output; 
}

/*******************************************************************************
 * Return: Mapping (Ass. Array) | false.                                       *                                                                                       
 *******************************************************************************/
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
  $str_name_ext  = "";
  $str_value     = "";
  
  $bool_result = file_exists ($file_path);
  
  if ($bool_result === false) {
    log_message ("create_mapping.file_does_not_exist", "error");
    return false;	
  }	  
  
  $handle = fopen ($file_path, "r"); // ret.: resource/false.
  
  if ($handle === false) { 
    log_message ("create_mapping.fopen", "error");
    return false;	
  }

  // Der Header wird ueberlesen.
  while (true) {
    $str_line = fgets ($handle); // ret.: str./false.
    if ($str_line === false) {
      log_message ("create_mapping.header_fgets", "error");
      return false;			
    }	
	
    log_message ("create_mapping.header_line", "info");
    log_message ($str_line, "info");
	
    $int_pos = strpos ($str_line, "#data-start"); // ret.: int/false.
    if ($int_pos !== false) {
      log_message ("Punkt erreicht", "info");
      break;
    }
  }

  log_message ("Daten.", "info");

  // last_name|Falcone
  // Die Name-Value-Paare werden nun verarbeitet.
  while (true) {
    $str_line = fgets ($handle);
    if ($str_line === false) {
      log_message ("create_mapping.data_fgets", "error");
      return false;			
    }
    $str_line = rtrim ($str_line);
    log_message ("Zeile.", "info");
    log_message ($str_line, "info");

    // Kommentare werden einfach ueberlesen.
    $int_pos = strpos ($str_line, "#comment:"); // ret.: int/false.
    if ($int_pos !== false) {
      continue;	  	
    }
	
    // Das ist die Abbruchsbedingung.
    $int_pos = strpos ($str_line, "#data-end"); // ret.: int/false.
    if ($int_pos !== false) {
      log_message ("Datei-Ende erreicht", "info");		
      break;	  	
    }

    log_message ("Zeile 2.", "info");

    $int_pos = strpos ($str_line, "|"); // ret.: int/false.
    if ($int_pos !== false) {
      log_message ("pipe verar.", "info");
      $arr_parts    = explode ("|", $str_line, 2);
      $str_name     = trim ($arr_parts [0]);	  
      $str_name_ext = "{{" . $str_name . "}}";         	  
      $str_value    = trim ($arr_parts [1]);	  	  
      $arr_ret_value [$str_name_ext] = $str_value;
	  
      log_message ("Pipe: {$str_name} {$str_value}", "info");		
    }
    else {
      // evidence#value-start
      $bool_result = str_check_hash ($str_line); // ret.: bool.
      if ($bool_result === true) {
        log_message ("Hash start", "info");				  
		
        $str_name     = get_hash_name ($str_line);  
        $str_name_ext = "{{" . $str_name . "}}";         	  
	$str_value    = get_hash_value ($handle); // ret: str/false.
		
	if ($str_value === false) {
	  log_message ("create_mapping.hash-value", "error");
	  return false;
	}
		
        $arr_ret_value [$str_name_ext] = $str_value;
		
	log_message ("Hash: {$str_name} {$str_value}", "info");		
      }
    }			
	
  } // while	  

  return $arr_ret_value;
}

