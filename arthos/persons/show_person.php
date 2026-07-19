<?php

/*******************************************************************************
 * Dokument: show_person.php.                                                  *
 * Author:   Wolfgang Adamec.                                                  *
 * Date:     2026-06-26.                                                       *
 * License:  Apache 2.0.                                                       *
 * Encoding: ANSI.                                                             *
 *******************************************************************************/          
 
  require_once __DIR__ . "/../artos_code_fun.php";

  $str_fn_base_dir = "";
  $str_mn_card     = "";

  $str_fn_base_dir = __DIR__;

  art_log_message ("-----------------------", "info");
  art_log_message ("New call.",               "info");
 
  $str_mn_card = art_prepare_card ("person", $str_fn_base_dir); // false/str.
  if ($str_mn_card === false) {
    art_log_message ("main.prepare_card", "error");
    exit;
  }  

  // Das fertige Ergebnis an den Browser senden
  echo $str_mn_card;
?>

