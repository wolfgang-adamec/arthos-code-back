<?php

/*******************************************************************************
 * Dokument: show_lexeme.php.                                                  *
 * Author:   Wolfgang Adamec.                                                  *
 * Date:     2026-06-27.                                                       *
 * License:  Apache 2.0.                                                       *
 * Encoding: ANSI.                                                             *
 *******************************************************************************/          
 
  require_once __DIR__ . "/../arthos_code_lib.php";

  $base_dir = __DIR__;

  log_message ("-----------------------", "info");
  log_message ("New call.",               "info");
 
  $str_card = prepare_card ("lexeme", $base_dir); // false/str.
  if ($str_card === false) {
    log_message ("main.prepare_card", "error");
    exit;
  }  

  // Das fertige Ergebnis an den Browser senden
  echo $str_card;
?>

