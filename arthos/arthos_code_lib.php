<?php

/*******************************************************************************
 * Dokument: artos_code_lib.php.                                               *
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
 * In dieser Funktion wird aus mehreren Dateinamen derjenige herausgesucht,    *
 * der an einer bestimmten Stelle im Namen einen Buchstaben hat und keine      *
 * Ziffer.                                                                     *
 *******************************************************************************/
function find_entry ($arr_files, $int_name_len)
{  
  $char_char     = "";
  $bool_digit    = false;
  $str_ret_value = "";
  $str_file_name = "";
  
  // lexeme_deu_1_3_1_actions_context_dialog.art, lexeme_deu_1_3_1_1_turm.
  // lexeme_deu_1_3_1_  
  
  foreach ($arr_files as $str_path) {
	log_message ("find_entry_file", "info");	
	log_message ($str_path,		    "info");	
	
	// Ich bekomme einen ganzen Pfad zurueck, moechte aber nur den Dateinamen zurueckgeben.
    $str_file_name = basename ($str_path);	
    $char_char     = $str_file_name [$int_name_len];      	  
	log_message ($int_name_len, "info");	
	
	// Prueft, ob alle Zeichen eines Strings Ziffern sind.
	$bool_digit = ctype_digit ($char_char);
    if ($bool_digit === false) {
      $str_ret_value = $str_file_name;
      break;	  
    }		
  }	  
  
  return $str_ret_value;
}

/*******************************************************************************
 *                                                                             *
 * Input parameters:                                                           *
 * 1) str_base_dir                                                             *
 * 2) str_name_part                                                            *
 *                                                                             *
 * Return value: str_file_name | false.                                        *                                                                                        
 *******************************************************************************/
function get_full_file_name ($str_base_dir, $str_name_part)
{
  $arr_dateien       = [];
  $str_suchmuster    = "";
  $int_count         = 0;
  $str_file_name     = "";
  $str_file_path     = "";
  $int_name_part_len = 0;

  // lexeme_deu_1_3_1_actions_context_dialog.art, lexeme_deu_1_3_1_1_turm.
  // lexeme_deu_1_3_1_

  $int_name_part_len = strlen ($name_part);
  $str_suchmuster    = $str_base_dir . DIRECTORY_SEPARATOR . $str_name_part . "*";

  $arr_dateien = glob ($str_suchmuster);
  if ($arr_dateien === false) {
    return false;
  }

  $int_count = count ($arr_dateien);
  if ($int_count === 1) {
    $str_file_path = $arr_dateien [0];
    // Ich bekomme einen ganzen Pfad zurueck, moechte aber nur den Dateinamen zurueckgeben.
    $str_file_name = basename ($str_file_path);
  }
  else {
	$str_file_name = find_entry ($arr_dateien, $int_name_part_len);
  }

  return $str_file_name;
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
 * Return: true | false.                                                       *                                                                       
 *******************************************************************************/
function str_check_array ($str_line)
{
  $int_pos = 0;
  
  // evidence#array-start (8).
  $int_pos = strpos ($str_line, "#array-start"); // ret.: int/false.
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
function get_array_name ($str_line)
{ 
  $bool_result = false;
  $int_pos     = 0;
  $str_result  = "";
  
  $bool_result = str_check_array ($str_line);
  if ($bool_result === false) {
	log_message ("get_array_name.check_array", "error");
	return false;
  }   
 
  // evidence#array-start (8).
  $int_pos    = strpos ($str_line, "#array-start"); // ret.: int/false.
  $str_result = substr ($str_line, 0, $int_pos);  

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
 * Diese Funktion bearbeitet einen Array-Datensatz im ART-Datenformat.         *
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function get_array_value ($handle)
{ 
  $bool_start      = true;
  $int_pos         = 0;
  $str_array_value = "";
  $str_line        = "";
  $str_name        = "";
  $str_id          = "";
  $arr_parts       = [];
  
  log_message ("get_array_value.start", "info");
  
  while (true) {
    $str_line = fgets ($handle); // ret.: str./false.
    if ($str_line === false) {
      log_message ("get_array_value.fgets", "error");
      return false;			
    }	
	
    #array-end  
    $int_pos = strpos ($str_line, "#array-end"); // ret.: int/false.
    if ($int_pos === false) {
      log_message ("array-data", "info");	
      log_message ($str_line, "info");		  

      $int_pos = strpos ($str_line, "|"); // ret.: int/false.
      if ($int_pos === false) {
        log_message ("get_array_value.no-pipe", "error");
        return false;
      }
      $arr_parts  = explode ("|", $str_line, 2); // 3. Parameter = limit.
      $str_name   = trim ($arr_parts [0]);	  
      $str_id     = trim ($arr_parts [1]);	  	  
      
      $str_array_value .= "<tr><td>" . $str_name . "</td><td class=\"id-col\">" . $str_id . "</td></tr>";
    }
    else {
      log_message ("array_value", "info");
      log_message ($str_array_value, "info");
	  
      return $str_array_value;	
    }
  } // while

}

/*******************************************************************************
 * This function processes the whole associative array with the input          *                                                                       
 * parameters.                                                                 *
 *
 * Input parameters:                                                           *
 * 1) arr_query_string                                                         *
 *                                                                             *
 * Return value: Mapping (Ass. Array) | false.                                 *                                                                                     
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
 * This function processes the whole associative array with the input          *                                                                       
 * parameters.                                                                 *
 *
 * Input parameters:                                                           *
 * 1) arr_query_string                                                         *
 *                                                                             *
 * Return value: Ass. Array | false.                                           *                                                                                     
 *******************************************************************************/
function scan_query_string ($arr_query_str)
{
  $arr_result      = [];
  $int_anzahl      = 0;
  $int_dummy       = 0;
  $bool_id_set     = false;
  $bool_lang_set   = false;
  $bool_format_set = false;  
  
  $int_anzahl = count ($arr_query_str);
  if (($int_anzahl !== 2) && ($int_anzahl !== 3)) {
    log_message ("scan_query_str.error-count", "error");
    return false;	  
  }

  foreach ($arr_query_str as $str_key => $str_value) {
	$bool_result = check_array_part ($str_key, $str_value);  
    if ($bool_result === false) {
      log_message ("scan_query_str.error-count", "error");
      return false;
    }
	
	$arr_result [$str_key] = $str_value;
	
	if ($str_key === "id") {
	  $bool_id_set = true;
	}
	if ($str_key === "lang") {
	  $bool_lang_set = true;
	}
	if ($str_key === "format") {
	  $bool_format_set = true;
	}	
  }  
  
  if (($bool_id_set   === true) &&
	  ($bool_lang_set === true)) {
    $int_dummy = 0;		  
  }		  
  else {
    log_message ("scan_query_str.err-not-all", "error");
    break;	  	  
  }	  

  if ($bool_format_set === false) {
	$arr_result ["format"] = "normal";  
  }
  
  return $arr_result;
}

/*******************************************************************************
 * This function checks if the key and the value have a certain structure.     *
 *                                                                             *
 * Input parameters:                                                           *
 * 1) str_key                                                                  *
 * 2) str_value                                                                *
 *                                                                             *
 * Return values: true | false.                                                *                                                                                     
 *******************************************************************************/
function check_array_part ($str_key, $str_value)
{
  $int_value_len = 0;

  $int_value_len = strlen ($str_value);
  
  if ($int_value_len === 0) {
	log_message ("check_array_part.error-len-0", "error");
	return false;
  }

  if (($str_key !== "id"    ) &&
      ($str_key !== "lang"  ) &&
      ($str_key !== "format")) {
	log_message ("check_array_part.err-key", "error");
	return false;		  
  }

  if ($str_key === "id") {
    if ($int_value_len === 0) {
	  log_message ("check_array_part.err-len-id", "error");
	  return false;		
    }
  }
  
  if ($str_key === "lang") {
    if ($int_value_len !== 3) {
	  log_message ("check_array_part.err-len-lang", "error");
	  return false;
    }  
  }
  
  if ($str_key === "format") {
    if (($str_value !== "normal") &&
        ($str_value !== "short" ) &&
		($str_value !== "ext"   )) {
	  log_message ("check_array_part.err-format-value", "error");
	  return false;  
    }
  }

  return true;  
}

/*******************************************************************************
 * In dieser Funktion wird der Inhalt eines assoziativen Arrays ausgedruckt.   *                                                                                       
 *******************************************************************************/
function log_mapping ($arr_mapping)
{
  $str_log_line = "";
  
  foreach ($arr_mapping as $str_name => $str_value) {
    $str_log_line .= "{$str_name}: {$str_value}\n";	  
  }
  log_message ($str_log_line, "info");
}

/*******************************************************************************
 * This function checks if a string starts with a certain substring.           *
 *                                                                             *
 * Input parameters:                                                           *
 * 1) str_value.                                                               *
 * 2) str_substr.                                                              *
 *                                                                             *
 * Return value: true | false.                                                 *                                                                                         
 *******************************************************************************/
function artos_str_starts_with ($str_par_value, $str_par_substr)
{
  $str_substr     = "";
  $int_len_substr = 0;
  
  $int_len_substr = strlen (str_par_substr);
  $str_substr     = substr (str_par_value, 0, $int_len_substr - 1);
  
  if ($str_par_substr === $str_substr) {
	return true;
  }
  else {
	return false;  
  }	  

}

/*******************************************************************************
 * This is the function, that is called by "show-person.php" and               *
 * "show-lexeme.php".                                                          *
 * It does the following things:                                               *
 * - The parameters of the query string are being fetched.                     *
 *                                                                             *
 * Input parameters:                                                           *
 * 1) str_code: For example "lexeme" or "person". It is used to build names.   *
 *                                                                             *
 * Return value: Mapping (Ass. Array) | false.                                 *                                                                                       
 *******************************************************************************/
function prepare_card ($str_code, $str_base_dir)
{
  $str_par_id         = "";
  $str_par_lang       = "";
  $str_par_format     = "";
  $str_sapi_name      = "";
  $str_file_name_full = "";
  $str_file_name_part = "";
  $str_template_name  = "";
  $str_html_template  = "";
  $str_output         = "";
  $arr_input_params   = [];
  $arr_data_map       = [];

  $str_sapi_name = php_sapi_name ();
  if ($str_sapi_name === "cli") {
	// If the script is being called from the the command line interface, these default input parameter values are assigned.
    $_GET["id"]     = "1.1";
    $_GET["lang"]   = "deu";
	$_GET["format"] = "normal";
  }

  $arr_input_params = scan_query_string ($_GET);
  if ($arr_input_params === false) {
    log_message ("prepare_card.scan_query_string", "error");	
    return false;	  
  }	  

  // In this section, one by one, the 3 query string parameters are fetched.
  // "id" and "lang" are mandatory input parameters, "format" is optional.

  $str_par_id     = $arr_input_params ["id"];
  $str_par_lang   = $arr_input_params ["lang"];
  $str_par_format = $arr_input_params ["format"];  

  log_message ("ID:     {$str_par_id}",     "info");
  log_message ("lang:   {$str_par_lang}",   "info");
  log_message ("format: {$str_par_format}", "info");

  // lexeme_deu_1_4_1_weg.art

  $str_par_id         = str_replace (".", "_", $str_par_id);  
  $str_file_name_part = $str_code . "_" . $str_par_lang . "_" . $str_par_id . "_";
  
  log_message ($str_file_name_part, "info");
  
  $str_file_name_full = get_full_file_name ($str_base_dir, $str_file_name_part);
  if ($str_file_name_full === false) {
    log_message ("prepare_card.get_full_file_name", "error");
    return false;
  }

  log_message ($str_file_name_full, "info");

  // Aus dem Inhalt der ART-Datei wird fuer das HTML-Template ein Mapping erstellt.
  $arr_data_map = create_mapping ($str_file_name_full); 
  log_mapping ($arr_data_map);  
  if ($arr_data_map === false) {
    log_message ("prepare_card.create_mapping", "error");	
    return false;
  }	  
  
  log_message ("nach create_mapping", "info");

  // lexeme-template.html, lexeme-short.html, lexeme-extended.html
  switch ($str_format) {
    case "normal": 
      $str_template_name = $str_code . "-template.html";
      break;
    case "short":
      $str_template_name = $str_code . "-short.html";
      break;
    case "ext":
      $str_template_name = $str_code . "-extended.html";
      break;
    default:
      log_message ("prepare_card.switch_format", "error");	
      exit;	  
  }	  

  // Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $str_html_template = file_get_contents ($str_template_name);

  // Platzhalter durch echte Daten ersetzen.
  $str_output = strtr ($str_html_template, $arr_data_map);
 
  return $str_output; 
}

/*******************************************************************************
 * In dieser Funktion wird aus dem Inhalt einer ART-Datei fuer das HTML-       *
 * Template ein Mapping erstellt.                                              *
 *                                                                             *
 * Return value: Mapping (Ass. Array) | false.                                 *                                                                                       
 *******************************************************************************/
function create_mapping ($str_file_path)
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
  
  $handle = fopen ($str_file_path, "r"); // ret.: resource/false.
  
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
	    
	$bool_result = artos_str_starts_with ($str_line, "#data-start");
    if ($bool_result === true) {
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
	$bool_result = artos_str_starts_with ($str_line, "##");
	if ($bool_result === true) {
      continue;	  	
    }
	
    // Das ist die Abbruchsbedingung.
	$bool_result = artos_str_starts_with ($str_line, "#data-end");    
    if ($bool_result === true) {
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
      else {
        // evidence#array-start
        $bool_result = str_check_array ($str_line); // ret.: bool.
        if ($bool_result === true) {
          log_message ("Array start", "info");

          $str_name     = get_array_name ($str_line);
          $str_name_ext = "{{" . $str_name . "}}";
          $str_value    = get_array_value ($handle); // ret: str/false.

          if ($str_value === false) {
            log_message ("create_mapping.array-value", "error");
            return false;
          }

          $arr_ret_value [$str_name_ext] = $str_value;
        }
      } // check_array
    }

  } // while	  

  return $arr_ret_value;
}

