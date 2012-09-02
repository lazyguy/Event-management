<?php

define("Print", "echo");
$current_page = 1;
$resultPerPage = 5;

function getYear() {
    $today = getdate(date("U"));
    $year = $today['year'];  //This is current year
    return $year;
}

function getEventType($eType) {
    if (strcmp($eType, "Junior") == 0)
        $eType = 1;
    else if (strcmp($eType, "Senior") == 0)
        $eType = 2;
    else
        $eType = 3;
    return $eType;
}

function getEventName($eType) {
    switch ($eType) {
        case 1:
            return "Junior";
            break;
        case 2:
            return "Senior";
            break;
        case 3:
            return "Senior&Junior";
            break;
    }
}

function getSex($sType) {
    switch ($sType) {
        case '1':
            return 'Male';
            break;
        case '2':
            return 'Female';
            break;
    }
}

function array_to_json($array) {
    //since using php version > 5.2 there is inbuilt function to do this
    return json_encode($array);
// in case using php version < 5.2, below code will come in handy
    /*
      if (!is_array($array)) {
      return false;
      }
      $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
      if ($associative) {

      $construct = array();
      foreach ($array as $key => $value) {

      // We first copy each key/value pair into a staging array,
      // formatting each key and value properly as we go.
      // Format the key:
      if (is_numeric($key)) {
      $key = "key_$key";
      }
      $key = "\"" . addslashes($key) . "\"";

      // Format the value:
      if (is_array($value)) {
      $value = array_to_json($value);
      } else if (!is_numeric($value) || is_string($value)) {
      $value = "\"" . addslashes($value) . "\"";
      }

      // Add to staging array:
      $construct[] = "$key: $value";
      }

      // Then we collapse the staging array into the JSON form:
      $result = "{ " . implode(", ", $construct) . " }";
      } else { // If the array is a vector (not associative):
      $construct = array();
      foreach ($array as $value) {

      // Format the value:
      if (is_array($value)) {
      $value = array_to_json($value);
      } else if (!is_numeric($value) || is_string($value)) {
      $value = "'" . addslashes($value) . "'";
      }

      // Add to staging array:
      $construct[] = $value;
      }

      // Then we collapse the staging array into the JSON form:
      $result = "[ " . implode(", ", $construct) . " ]";
      }

      return $result;
     * */
}

?>