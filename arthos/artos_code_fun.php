<?php

/*******************************************************************************
 * Dokument: artos_code_fun.php.                                               *
 * Author:   Wolfgang Adamec.                                                  *
 * Date:     2026-06-26.                                                       *
 * License:  Apache 2.0.                                                       *
 * Encoding: ANSI.                                                             *
 *                                                                             *
 * Used functions:                                                             *
 * basename                                                                    *
 * count                                                                       *
 * ctype_digit                                                                 *
 * date                                                                        *
 * explode                                                                     *
 * fgets                                                                       *
 * file_put_contents                                                           *
 * glob                                                                        *
 * strlen                                                                      *
 * strpos                                                                      *
 * substr                                                                      *
 * date                                                                        *
 * date                                                                        *
 * date                                                                        *
 * date                                                                        *
 *******************************************************************************/          

/*******************************************************************************
 *                                                                             *
 *******************************************************************************/
function art_log_message ($str_par_message, $str_par_level) 
{
  $bool_result      = false;
  $str_log_entry    = "";
  $str_log_file     = "";
  $str_fn_timestamp = "";
    
  $str_log_file = __DIR__ . "/artos_code_fun.log";
    
  $str_fn_timestamp = date ('Y-m-d H:i:s');
    
  $str_log_entry = "[$str_fn_timestamp] [$str_par_level] $str_par_message\n";   
  
  file_put_contents ($str_log_file, $str_log_entry, FILE_APPEND);
}

/*******************************************************************************
 * If an associative array, that is the return value of a search function,     *
 * has multiple entries, this function determines the right one.               *
 *                                                                             *
 * In dieser Funktion wird aus mehreren Dateinamen derjenige herausgesucht,    *
 * der an einer bestimmten Stelle im Namen einen Buchstaben hat und keine      *
 * Ziffer.                                                                     *
 *                                                                             *
 * Input parameters:                                                           *
 * 1) arr_par_file_names: This array contains multiple file names.             *
 * 2) int_name_len:       This is the length of the partial file name.         *
 *                                                                             *
 * Return value: str_file_name | false.                                        *                                                                                        
 *               A file name is returned.                                      *
 *******************************************************************************/
function art_find_entry ($arr_par_file_names, $int_par_name_len)
{  
  $char_char        = "";
  $bool_digit       = false;
  $str_mn_ret_value = "";
  $str_fn_file_name = "";
  $str_path         = "";
  
  art_log_message ($int_par_name_len, "info");

  // lexeme_deu_1_3_1_actions_context_dialog.art, lexeme_deu_1_3_1_1_turm.
  // lexeme_deu_1_3_1_  
  
  // Php does not know of a block scope for for each structures.
  
  foreach ($arr_par_file_names as $str_path) {
    art_log_message ("find_entry_file", "info");	
    art_log_message ($str_path, "info");	
	    
	// A full path is returned but only the name portion is needed.
    $str_fn_file_name = basename ($str_path);	
    $char_char        = $str_fn_file_name [$int_par_name_len];      	  
    art_log_message ($int_par_name_len, "info");	
	    
	// Checks, if all characters of a string are cifers.
    $bool_digit = ctype_digit ($char_char);
    if ($bool_digit === false) {
      $str_mn_ret_value = $str_fn_file_name;
      return $str_mn_ret_value;	  
    }		
  }	  
  
  return false;
}

/*******************************************************************************
 * The function gets a part of a file name and tries to determine the whole    *
 * file name.                                                                  *
 *                                                                             *
 * Input parameters:                                                           *
 * 1) str_base_dir                                                             *
 * 2) str_name_part                                                            *
 *                                                                             *
 * Return value: str_file_name | false.                                        *                                                                                        
 *               The full name of a file is returned.                          *
 *******************************************************************************/
function art_get_full_file_name ($str_par_base_dir, $str_par_name_part)
{
  $arr_fn_file_names     = [];
  $str_search_pattern    = "";
  $int_fn_count          = 0;
  $int_fn_name_part_len  = 0;
  $str_file_path         = "";
  $str_mn_full_file_name = "";

  // lexeme_deu_1_3_1_actions_context_dialog.art, lexeme_deu_1_3_1_1_turm.
  // lexeme_deu_1_3_1_

  $int_fn_name_part_len = strlen ($str_par_name_part);
  $str_search_pattern   = $str_par_base_dir . DIRECTORY_SEPARATOR . $str_par_name_part . "*";

  $arr_fn_file_names = glob ($str_search_pattern);
  if ($arr_fn_file_names === false) {
    return false;
  }

  $int_fn_count = count ($arr_fn_file_names);
  if ($int_fn_count === 1) {
    $str_file_path = $arr_fn_file_names [0];
	// A full path is returned, but only the file name portion is needed.    
    $str_mn_full_file_name = basename ($str_file_path);
  }
  else {
    $str_mn_full_file_name = art_find_entry ($arr_dateien, $int_name_part_len);
  }

  return $str_mn_full_file_name;
}

/*******************************************************************************
 * This function checks, if a block structure begins.                          *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Return value: true | false.                                                 * 
 *               The return value gives the information, if a block structure  *
 *               begins or not.                                                *
 *******************************************************************************/
function art_check_block ($str_par_line)
{
  $int_pos       = 0;
  $bool_mn_value = false;
  
  // evidence#value-start (8).
  $int_pos = strpos ($str_par_line, "#value-start"); // ret.: int/false.
  if ($int_pos === false) {
	$bool_mn_value = false;  
  }
  else {
	$bool_mn_value = true;
  }
   
  return $bool_mn_value;  
}

/*******************************************************************************
 * This function checks, if a block structure begins.                          *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Return value: true | false.                                                 *                                                                       
 *               The return value gives the information, if a array structure  *
 *               begins or not.                                                *
 *******************************************************************************/
function art_check_array ($str_par_line)
{
  $int_pos       = 0;
  $bool_mn_value = false;
  
  // evidence#array-start (8).
  $int_pos = strpos ($str_par_line, "#array-start"); // ret.: int/false.
  if ($int_pos === false) {
	$bool_mn_value = false;  
  }
  else {
	$bool_mn_value = true;  	  
  }  
  
  return $bool_mn_value;  
}

/*******************************************************************************
 * This function gets the name of the block structure.                         *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Return value: string | false.                                               *                                                                       
 *******************************************************************************/
function art_get_block_name ($str_par_line)
{ 
  $bool_result       = false;
  $int_fn_pos        = 0;
  $str_mn_block_name = "";
  
  $bool_result = art_check_block ($str_par_line);
  if ($bool_result === false) {
	art_log_message ("art_get_block_name.art_check_block", "error");
	return false;
  }   
 
  // evidence#value-start (8).
  $int_fn_pos        = strpos ($str_par_line, "#value-start"); // ret.: int/false.
  $str_mn_block_name = substr ($str_par_line, 0, $int_fn_pos);  

  art_log_message ("hash name",        "info");
  art_log_message ($str_mn_block_name, "info");

  return $str_mn_block_name;
}

/*******************************************************************************
 * This function gets the name of the array structure.                         *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function art_get_array_name ($str_par_line)
{ 
  $bool_result       = false;
  $int_fn_pos        = 0;
  $str_mn_array_name = "";
  
  $bool_result = art_check_array ($str_par_line);
  if ($bool_result === false) {
	art_log_message ("art_get_array_name.art_check_array", "error");
	return false;
  }   
 
  // evidence#array-start (8).
  $int_fn_pos        = strpos ($str_par_line, "#array-start"); // ret.: int/false.  
  $str_mn_array_name = substr ($str_par_line, 0, $int_fn_pos);  

  return $str_mn_array_name;
}

/*******************************************************************************
 * This function gets the value of a block structure.                          *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function art_get_block_value ($res_par_handle)
{ 
  $bool_result     = false;
  $bool_start      = false;
  $int_pos         = 0;
  $str_block_value = "";
  $str_fn_line     = "";
     
  art_log_message ("art_get_block_value.start", "info");
  
  $bool_start = true;
  
  while (true) {
    $str_fn_line = fgets ($res_par_handle); // ret.: str./false.
	if ($str_fn_line === false) {
	  art_log_message ("art_get_block_value.fgets", "error");
      return false;			
	}	
	
	#value-end  
    $bool_result = artos_str_starts_with ($str_fn_line, "#value-end");
    if ($bool_result === false) {	
	  art_log_message ("block-data", "info");	
	  art_log_message ($str_fn_line, "info");	
	  
	  $str_fn_line = trim ($str_fn_line);
	  if ($bool_start === false) {
		$str_block_value .= " ";
	  }
	  else {
		$bool_start = false;
	  }
	  $str_block_value .= $str_fn_line;
	}
    else {
      art_log_message ("block_value", "info");
	  art_log_message ($str_block_value, "info");
	  
	  return $str_block_value;	
	}
  } // while

}

/*******************************************************************************
 * This function gets the value of a block structure.                          *
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_line: A line of an artos file                                    * 
 *                                                                             *
 * Diese Funktion bearbeitet einen Array-Datensatz im ART-Datenformat.         *
 * Return: string | false.                                                     *                                                                       
 *******************************************************************************/
function art_get_array_value ($res_par_handle)
{ 
  $bool_result     = false;
  $bool_start      = true;
  $int_pos         = 0;
  $str_array_value = "";
  $str_fn_line     = "";
  $str_name        = "";
  $str_id          = "";
  $arr_parts       = [];
  
  art_log_message ("art_get_array_value.start", "info");
  
  while (true) {
    $str_fn_line = fgets ($res_par_handle); // ret.: str./false.
    if ($str_fn_line === false) {
      art_log_message ("art_get_array_value.fgets", "error");
      return false;			
    }	
	
    #array-end  
    $bool_result = artos_str_starts_with ($str_fn_line, "#value-end");
    if ($bool_result === false) {	
      art_log_message ("array-data", "info");	
      art_log_message ($str_fn_line, "info");		  

      $int_pos = strpos ($str_fn_line, "|"); // ret.: int/false.
      if ($int_pos === false) {
        log_message ("art_get_array_value.no-pipe", "error");
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
      log_message ("scan_query_str.check_array_part", "error");
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
    return false;	  	  
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
  
  $int_len_substr = strlen ($str_par_substr);
  $str_substr     = substr ($str_par_value, 0, $int_len_substr);

  log_message ("sub",           "info");
  log_message ($str_par_substr, "info");
  log_message ("norm",          "info");
  log_message ($str_substr,     "info");
  
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
function prepare_card ($str_par_code, $str_par_base_dir)
{
  $str_web_id            = "";
  $str_web_lang          = "";
  $str_web_format        = "";
  $str_file_name_part    = "";
  $str_template_name     = "";
  $str_fn_sapi_name      = "";
  $str_fn_file_name_full = "";
  $str_fn_html_template  = "";
  $str_mn_output         = "";
  $arr_fn_input_params   = [];
  $arr_fn_data_map       = [];

  $str_fn_sapi_name = php_sapi_name ();
  if ($str_fn_sapi_name === "cli") {
	// If the script is being called from the the command line interface, these default input parameter values are assigned.
    $_GET["id"]     = "1.1";
    $_GET["lang"]   = "deu";
	$_GET["format"] = "normal";
  }

  $arr_fn_input_params = scan_query_string ($_GET);
  if ($arr_fn_input_params === false) {
    log_message ("prepare_card.scan_query_string", "error");	
    return false;	  
  }	  

  // In this section, one by one, the 3 query string parameters are fetched.
  // "id" and "lang" are mandatory input parameters, "format" is optional.

  $str_web_id     = $arr_fn_input_params ["id"];
  $str_web_lang   = $arr_fn_input_params ["lang"];
  $str_web_format = $arr_fn_input_params ["format"];  

  log_message ("ID:     {$str_web_id}",     "info");
  log_message ("lang:   {$str_web_lang}",   "info");
  log_message ("format: {$str_web_format}", "info");

  // lexeme_deu_1_4_1_weg.art

  $str_web_id         = str_replace (".", "_", $str_web_id);  
  $str_file_name_part = $str_par_code . "_" . $str_web_lang . "_" . $str_web_id . "_";
  
  log_message ($str_file_name_part, "info");
  
  $str_fn_file_name_full = get_full_file_name ($str_par_base_dir, $str_file_name_part);
  if ($str_fn_file_name_full === false) {
    log_message ("prepare_card.get_full_file_name", "error");
    return false;
  }

  log_message ($str_fn_file_name_full, "info");

  // Aus dem Inhalt der ART-Datei wird fuer das HTML-Template ein Mapping erstellt.
  $arr_fn_data_map = create_mapping ($str_fn_file_name_full); 
  log_mapping ($arr_fn_data_map);  
  if ($arr_fn_data_map === false) {
    log_message ("prepare_card.create_mapping", "error");	
    return false;
  }	  
  
  log_message ("nach create_mapping", "info");

  // lexeme-template.html, lexeme-short.html, lexeme-extended.html
  switch ($str_web_format) {
    case "normal": 
      $str_template_name = $str_par_code . "-template.html";
      break;
    case "short":
      $str_template_name = $str_par_code . "-short.html";
      break;
    case "ext":
      $str_template_name = $str_par_code . "-extended.html";
      break;
    default:
      log_message ("prepare_card.switch_format", "error");	
      return false;	  
  }	  

  // Die HTML-Vorlage reinen Text vom Linux-Server einlesen
  $str_fn_html_template = file_get_contents ($str_template_name);

  // Platzhalter durch echte Daten ersetzen.
  $str_mn_output = strtr ($str_fn_html_template, $arr_fn_data_map);
 
  return $str_mn_output; 
}

/*******************************************************************************
 * In dieser Funktion wird aus dem Inhalt einer ART-Datei fuer das HTML-       *
 * Template ein Mapping erstellt.                                              *
 *                                                                             *
 * Return value: Mapping (Ass. Array) | false.                                 *                                                                                       
 *******************************************************************************/
function create_mapping ($str_par_file_path)
{
  $arr_fn_parts     = [];
  $arr_mn_ret_value = [];
  $bool_result      = false;
  $res_fn_handle    = null;  
  $int_dummy        = 0;
  $int_pos          = 0;
  $int_str_len      = 0;
  $str_fn_line      = "";
  $str_fn_name      = "";
  $str_name_ext     = "";
  $str_fn_value     = "";
  
  $bool_result = file_exists ($str_par_file_path);
  
  if ($bool_result === false) {
    log_message ("create_mapping.file_does_not_exist", "error");
    return false;	
  }	  
  
  $res_fn_handle = fopen ($str_par_file_path, "r"); // ret.: resource/false.
  
  if ($res_fn_handle === false) { 
    log_message ("create_mapping.fopen", "error");
    return false;	
  }

  // Der Header wird ueberlesen.
  while (true) {
    $str_fn_line = fgets ($res_fn_handle); // ret.: str./false.
    if ($str_fn_line === false) {
      log_message ("create_mapping.header_fgets", "error");
      return false;			
    }	
	
    log_message ("create_mapping.header_line", "info");
    log_message ($str_fn_line, "info");
	    
    $bool_result = artos_str_starts_with ($str_fn_line, "#data-start");
    if ($bool_result === true) {
      log_message ("Punkt erreicht", "info");
      break;
    }
  }

  log_message ("Daten.", "info");

  // Nun werden die Daten verarbeitet.
  while (true) {
    $str_fn_line = fgets ($res_fn_handle);
    if ($str_fn_line === false) {
      log_message ("create_mapping.data_fgets", "error");
      return false;			
    }
    $str_fn_line = rtrim ($str_fn_line);
    log_message ("Zeile.", "info");
    log_message ($str_fn_line, "info");

    // === Kommentare werden einfach ueberlesen. =======================================================================
    $bool_result = artos_str_starts_with ($str_fn_line, "##");
    if ($bool_result === true) {
      continue;	  	
    }
	
    // === Das ist die Abbruchsbedingung. ==============================================================================
    $bool_result = artos_str_starts_with ($str_fn_line, "#data-end");    
    if ($bool_result === true) {
      log_message ("Datei-Ende erreicht", "info");		
      break;	  	
    }

    log_message ("Zeile 2.", "info");

    // === Die Name-Value-Paare werden nun verarbeitet. ================================================================
    // last_name|Falcone
    $int_pos = strpos ($str_fn_line, "|"); // ret.: int/false.
    if ($int_pos !== false) {
      log_message ("pipe verar.", "info");
      $arr_fn_parts = explode ("|", $str_fn_line, 2);
      $str_fn_name  = trim ($arr_fn_parts [0]);	  
      $str_name_ext = "{{" . $str_fn_name . "}}";         	  
      $str_fn_value = trim ($arr_fn_parts [1]);	  	  
      $arr_mn_ret_value [$str_name_ext] = $str_fn_value;

      log_message ("Pipe: {$str_fn_name} {$str_fn_value}", "info");		
    }
    else {
      // evidence#value-start
      $bool_result = str_check_hash ($str_fn_line); // ret.: bool.
      if ($bool_result === true) {
        log_message ("Hash start", "info");

        $str_name     = get_hash_name ($str_fn_line);  
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

