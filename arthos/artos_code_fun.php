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
 * file_exists                                                                 *
 * file_put_contents                                                           *
 * glob                                                                        *
 * php_sapi_name                                                               * 
 * strlen                                                                      *
 * strpos                                                                      *
 * str_replace                                                                 *
 * substr                                                                      *
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
    $str_mn_full_file_name = art_find_entry ($arr_fn_file_names, $int_fn_name_part_len);
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

  art_log_message ("block name",        "info");
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
    $bool_result = art_str_starts_with ($str_fn_line, "#value-end");
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
function art_get_array_value ($res_par_handle, $str_par_lang)
{ 
  $bool_result        = false;
  $bool_start         = true;
  $int_pos            = 0;
  $str_mn_array_value = "";
  $str_fn_line        = "";
  $str_name           = "";
  $str_id             = "";
  $arr_fn_parts       = [];
  
  art_log_message ("art_get_array_value.start", "info");
  
  while (true) {
    $str_fn_line = fgets ($res_par_handle); // ret.: str./false.
    if ($str_fn_line === false) {
      art_log_message ("art_get_array_value.fgets", "error");
      return false;			
    }	
	
    #array-end  
    $bool_result = art_str_starts_with ($str_fn_line, "#array-end");
    if ($bool_result === false) {	
      art_log_message ("array-data", "info");	
      art_log_message ($str_fn_line, "info");		  

      $int_pos = strpos ($str_fn_line, "|"); // ret.: int/false.
      if ($int_pos === false) {
        art_log_message ("art_get_array_value.no-pipe", "error");
        return false;
      }
      $arr_fn_parts = explode ("|", $str_fn_line, 2); // 3. Parameter = limit.
      $str_name     = trim ($arr_fn_parts [0]);	  
      $str_id       = trim ($arr_fn_parts [1]);	  	  
      
      $str_mn_array_value .= "<tr><td><a href=\"show_lexeme.php?id=" . $str_id . "&lang=" . $str_par_lang . "&format=" . "normal" . "\" class=\"person-link\">" . $str_name . "</td><td class=\"id-col\">" . $str_id . "</td></tr>";
    }
    else {
      art_log_message ("array_value", "info");
      art_log_message ($str_mn_array_value, "info");
	  
      return $str_mn_array_value;	
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
 * Return value: Ass. Array | false.                                           *                                                                                     
 *******************************************************************************/
function art_scan_query_string ($arr_par_query_str)
{
  $arr_mn_result   = [];
  $int_fn_anzahl   = 0;
  $int_dummy       = 0;
  $bool_id_set     = false;
  $bool_lang_set   = false;
  $bool_format_set = false;  
  $bool_result     = false;
  $str_key		   = "";
  $str_value       = "";
  
  $int_fn_anzahl = count ($arr_par_query_str);
  if (($int_fn_anzahl !== 2) && ($int_fn_anzahl !== 3)) {
    art_log_message ("art_scan_query_str.error-count", "error");
    return false;	  
  }

  foreach ($arr_par_query_str as $str_key => $str_value) {
    $bool_result = art_check_name_value ($str_key, $str_value);  
    if ($bool_result === false) {
      art_log_message ("art_scan_query_string.art_check_name_value", "error");
      return false;
    }
	
    $arr_mn_result [$str_key] = $str_value;
	
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
    art_log_message ("art_scan_query_string.err-not-all", "error");
    return false;	  	  
  }	  

  if ($bool_format_set === false) {
    $arr_mn_result ["format"] = "normal";  
  }
  
  return $arr_mn_result;
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
function art_check_name_value ($str_par_key, $str_par_value)
{
  $int_value_len = 0;

  $int_value_len = strlen ($str_par_value);
  
  if ($int_value_len === 0) {
	art_log_message ("art_check_name_value.error-len-0", "error");
	return false;
  }

  if (($str_par_key !== "id"    ) &&
      ($str_par_key !== "lang"  ) &&
      ($str_par_key !== "format")) {
	art_log_message ("art_check_name_value.err-key", "error");
	return false;		  
  }

  if ($str_par_key === "id") {
    if ($int_value_len === 0) {
	  art_log_message ("art_check_name_value.err-len-id", "error");
	  return false;		
    }
  }
  
  if ($str_par_key === "lang") {
    if ($int_value_len !== 3) {
	  art_log_message ("art_check_name_value.err-len-lang", "error");
	  return false;
    }  
  }
  
  if ($str_par_key === "format") {
    if (($str_par_value !== "normal") &&
        ($str_par_value !== "short" ) &&
		($str_par_value !== "ext"   )) {
	  art_log_message ("art_check_name_value.err-format-value", "error");
	  return false;  
    }
  }

  return true;  
}

/*******************************************************************************
 * In dieser Funktion wird der Inhalt eines assoziativen Arrays ausgedruckt.   *                                                                                       
 *******************************************************************************/
function art_log_mapping ($arr_par_mapping)
{
  $str_log_line = "";
  $str_name     = "";
  $str_value    = "";
  
  foreach ($arr_par_mapping as $str_name => $str_value) {
    $str_log_line .= "{$str_name}: {$str_value}\n";	  
  }
  
  art_log_message ($str_log_line, "info");
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
function art_str_starts_with ($str_par_value, $str_par_substr)
{
  $str_fn_substr     = "";
  $int_fn_len_substr = 0;
  
  $int_fn_len_substr = strlen ($str_par_substr);
  $str_fn_substr     = substr ($str_par_value, 0, $int_fn_len_substr);

  // art_log_message ("sub",           "info");
  // art_log_message ($str_par_substr, "info");
  // art_log_message ("norm",          "info");
  // art_log_message ($str_fn_substr,  "info");
  
  if ($str_par_substr === $str_fn_substr) {
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
function art_prepare_card ($str_par_code, $str_par_base_dir)
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

  $arr_fn_input_params = art_scan_query_string ($_GET);
  if ($arr_fn_input_params === false) {
    art_log_message ("art_prepare_card.art_scan_query_string", "error");	
    return false;	  
  }	  

  // In this section, one by one, the 3 query string parameters are fetched.
  // "id" and "lang" are mandatory input parameters, "format" is optional.

  $str_web_id     = $arr_fn_input_params ["id"];
  $str_web_lang   = $arr_fn_input_params ["lang"];
  $str_web_format = $arr_fn_input_params ["format"];  

  art_log_message ("ID:     {$str_web_id}",     "info");
  art_log_message ("lang:   {$str_web_lang}",   "info");
  art_log_message ("format: {$str_web_format}", "info");

  // lexeme_deu_1_4_1_weg.art

  $str_web_id         = str_replace (".", "_", $str_web_id);  
  $str_file_name_part = $str_par_code . "_" . $str_web_lang . "_" . $str_web_id . "_";
  
  art_log_message ($str_file_name_part, "info");
  
  $str_fn_file_name_full = art_get_full_file_name ($str_par_base_dir, $str_file_name_part);
  if ($str_fn_file_name_full === false) {
    art_log_message ("art_prepare_card.art_get_full_file_name", "error");
    return false;
  }

  art_log_message ($str_fn_file_name_full, "info");

  // Aus dem Inhalt der ART-Datei wird fuer das HTML-Template ein Mapping erstellt.
  // The function "art_create_mapping" creates a mapping from the ART file, and this mapping is used later for the html template.
  $arr_fn_data_map = art_create_mapping ($str_fn_file_name_full, $str_web_lang); 
  art_log_mapping ($arr_fn_data_map);  
  if ($arr_fn_data_map === false) {
    art_log_message ("art_prepare_card.art_create_mapping", "error");	
    return false;
  }	  
  
  art_log_message ("nach create_mapping", "info");

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
      art_log_message ("art_prepare_card.switch_format", "error");	
      return false;	  
  }	  
  
  // The template is beeing transferred into the variable.
  $str_fn_html_template = file_get_contents ($str_template_name);
  
  // The placeholders are being replaced by real data.
  $str_mn_output = strtr ($str_fn_html_template, $arr_fn_data_map);
 
  return $str_mn_output; 
}

/*******************************************************************************
 * The function creates a mapping from the contents of the ART file. This      *
 * mapping is later used in a template rendering process.                      * 
 *                                                                             *
 * Input parameter:                                                            *
 * 1) str_par_file_path: This is the path to an artos file.                    *
 *                                                                             *
 * Return value: Mapping (Ass. Array) | false.                                 *                                                                                       
 *******************************************************************************/
function art_create_mapping ($str_par_file_path, $str_par_lang)
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
    art_log_message ("art_create_mapping.file_does_not_exist", "error");
	art_log_message ($str_par_file_path, "error");
    return false;	
  }	  
  
  $res_fn_handle = fopen ($str_par_file_path, "r"); // ret.: resource/false.
  
  if ($res_fn_handle === false) { 
    art_log_message ("art_create_mapping.fopen", "error");
    return false;	
  }

  // === Der Header wird ueberlesen. ===================================================================================
  while (true) {
    $str_fn_line = fgets ($res_fn_handle); // ret.: str./false.
    if ($str_fn_line === false) {
      art_log_message ("art_create_mapping.header_fgets", "error");
      return false;			
    }	
	
    art_log_message ("create_mapping.header_line", "info");
    art_log_message ($str_fn_line, "info");
	    
    $bool_result = art_str_starts_with ($str_fn_line, "#data-start");
    if ($bool_result === true) {
      art_log_message ("Punkt erreicht", "info");
      break;
    }
  }

  art_log_message ("Daten.", "info");

  // Nun werden die Daten verarbeitet.
  while (true) {
    $str_fn_line = fgets ($res_fn_handle);
    if ($str_fn_line === false) {
      art_log_message ("art_create_mapping.data_fgets", "error");
      return false;			
    }
    $str_fn_line = rtrim ($str_fn_line);
    art_log_message ("Zeile.", "info");
    art_log_message ($str_fn_line, "info");

    // === Kommentare werden einfach ueberlesen. =======================================================================
    $bool_result = art_str_starts_with ($str_fn_line, "##");
    if ($bool_result === true) {
      continue;	  	
    }
	
    // === Das ist die Abbruchsbedingung. ==============================================================================
    $bool_result = art_str_starts_with ($str_fn_line, "#data-end");    
    if ($bool_result === true) {
      art_log_message ("Datei-Ende erreicht", "info");		
      break;	  	
    }

    art_log_message ("Zeile 2.", "info");

    // === Die Name-Value-Paare werden nun verarbeitet. ================================================================
    // last_name|Falcone
    $int_pos = strpos ($str_fn_line, "|"); // ret.: int/false.
    if ($int_pos !== false) {
      art_log_message ("pipe verar.", "info");
      $arr_fn_parts = explode ("|", $str_fn_line, 2);
      $str_fn_name  = trim ($arr_fn_parts [0]);	  
      $str_name_ext = "{{" . $str_fn_name . "}}";         	  
      $str_fn_value = trim ($arr_fn_parts [1]);	  	  
      $arr_mn_ret_value [$str_name_ext] = $str_fn_value;

      art_log_message ("Pipe: {$str_fn_name} {$str_fn_value}", "info");		
    }
    else {
      // evidence#value-start
      $bool_result = art_check_block ($str_fn_line); // ret.: bool.
      if ($bool_result === true) {
        art_log_message ("Block start", "info");

        $str_fn_name  = art_get_block_name ($str_fn_line);  
        $str_name_ext = "{{" . $str_fn_name . "}}";
        
		$str_fn_value = art_get_block_value ($res_fn_handle); // ret: str/false.
        if ($str_fn_value === false) {
          art_log_message ("art_create_mapping.art_get_block_value", "error");
          return false;
        }

        $arr_mn_ret_value [$str_name_ext] = $str_fn_value;

        art_log_message ("Block: {$str_fn_name} {$str_fn_value}", "info");
      }
      else {
        // evidence#array-start
        $bool_result = art_check_array ($str_fn_line); // ret.: bool.
        if ($bool_result === true) {
          art_log_message ("Array start", "info");

          $str_fn_name  = art_get_array_name ($str_fn_line);
          $str_name_ext = "{{" . $str_fn_name . "}}";
		  
          $str_fn_value = art_get_array_value ($res_fn_handle, $str_par_lang); // ret: str/false.
          if ($str_fn_value === false) {
            art_log_message ("art_create_mapping.art_get_array_value", "error");
            return false;
          }

          $arr_mn_ret_value [$str_name_ext] = $str_fn_value;
        }
      } // check_array
    }

  } // while	  

  return $arr_mn_ret_value;
}
