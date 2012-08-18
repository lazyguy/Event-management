<?php

define("Print", "echo");
$current_page = 1;
$resultPerPage = 5;

function getYear() {
    $today = getdate(date("U"));
    $year = $today['year'];  //This is current year
    return $year;
}

function array_to_json($array) {
    return json_encode($array);
// no need to reinvent the wheel :P
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