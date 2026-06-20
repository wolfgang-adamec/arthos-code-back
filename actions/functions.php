<?php

/******************************************************************************
 *                                                                            *
 ******************************************************************************/
function get_input_value ($input_array, $search_key) 
{                                                                                                                         
  $return_value = "";

  foreach ($input_array as $key => $value) {
    if ($key == $search_key) {
      $return_value = $value;
      break; 
    }
  }
  
  return $return_value;                                                                                
}






