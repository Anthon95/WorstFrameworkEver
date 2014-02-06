<?php

namespace core\router;

class WFEsession {

public static function init() {

session_start();

if (!isset($_SESSION['userdata']) ||!is_array($_SESSION['userdata']))
$_SESSION['userdata'] = array();
if (!isset($_SESSION['flashdata']) ||!is_array($_SESSION['flashdata']))
$_SESSION['flashdata'] = array();
}

function end_session() {

// cleans flashdata
foreach ($_SESSION['flashdata'] as $name => $data)
unset($_SESSION['flashdata'][$name]);

// usefull to retrieve the last page visited
set_flashdata(array('last_page_uri' => get_current_uri()));
}

}




/* ------ get_flashdata -----------------------------------------

  Retrieves flashdata previously defined with set_flashdata

  $data
  array containing the data names to be retrieved or a string containing one flashdata name
  sample: array('name_1', 'name_2')

  #Returns an associative array containing the data names and their values or the unique value

  ------------------------------------------------------------ */

function get_flashdata($data) {

$return = array();

if (is_string($data))
$return = $_SESSION['flashdata'][$data];
else {
foreach ($data as $name)
$return[$name] = $_SESSION['flashdata'][$name];
}

return $return;
}

/* ------ get_session_data -----------------------------------------

  Retrieves ssession data previously defined with set_session_datadata

  $data
  array containing the data names to be retrieved or a string containing one session_data name
  sample: array('name_1', 'name_2')

  $auto_destroy
  defines whether the data must be destroy after it is retrieved, by default equal to false


  #Returns an associative array containing the data names and their values or the unique value

  ------------------------------------------------------------ */

function get_session_data($data, $auto_destroy = false) {

$return = array();

if (is_string($data)) {
$return = $_SESSION[$data];
if ($auto_destroy)
unset($_SESSION[$data]);
}
else {
foreach ($data as $name) {
$return[$name] = $_SESSION[$name];
if ($auto_destroy)
unset($_SESSION[$name]);
}
}

return $return;
}

/* ------ get_userdata -----------------------------------------

  Retrieves userdata previously defined with set_userdata

  $data
  array containing the data names to be retrieved or a string containing one userdata name
  sample: array('name_1', 'name_2')

  #Returns an associative array containing the data names and their values or the unique string value

  ------------------------------------------------------------ */

function get_userdata($data) {

$return = array();

if (is_string($data))
$return = $_SESSION['userdata'][$data];
else {
foreach ($data as $name)
$return[$name] = $_SESSION['userdata'][$name];
}

return $return;
}


/* ------ isset_flashdata -----------------------------------------

  Determines whether a flashdata exists or not

  $data_name
  a string containing one flashdata name

  #Returns true or false
  ------------------------------------------------------------ */

function isset_flashdata($data_name) {

return isset($_SESSION['flashdata'][$data_name]);
}

/* ------ isset_session_data -----------------------------------------

  Determines whether a session_data exists or not

  $data_name
  a string containing one session_data name

  $auto_destroy
  defines whether the data must be destroy after it is retrieved, by default equal to false

  #Returns true or false
  ------------------------------------------------------------ */

function isset_session_data($data_name, $auto_destroy = false) {

$isset = isset($_SESSION[$data_name]);
if ($isset && $auto_destroy)
unset($_SESSION[$data_name]);
return $isset;
}

/* ------ isset_userdata -----------------------------------------

  Determines whether a userdata exists or not

  $data_name
  a string containing one userdata name

  #Returns true or false
  ------------------------------------------------------------ */

function isset_userdata($data_name) {

return isset($_SESSION['userdata'][$data_name]);
}

/* ------ kill_userdata -----------------------------------------

  Destroy session data

  $data_names
  array containing the data names to be removed
  By default equal to null, and all data are removed

  ------------------------------------------------------------ */

function kill_userdata($data_names = null) {

// destroy all data
if ($data_names == null) {
foreach ($_SESSION['userdata'] as $key => $value)
unset($_SESSION['userdata'][$key]);
}
// destroy some data
else {
foreach ($data_names as $name)
unset($_SESSION['userdata'][$name]);
}
}

/* ------ set_flashdata -----------------------------------------

  Sets flashdata

  $data
  associative array containing the data names and their values
  sample: array('name_1' => 'value_1', 'name_2' => 'value_2')

  ------------------------------------------------------------ */

function set_flashdata($data) {

foreach ($data as $name => $value)
$_SESSION['flashdata'][$name] = $value;
}

/* ------ set_session_data -----------------------------------------

  Sets session_data

  $data
  associative array containing the data names and their values
  sample: array('name_1' => 'value_1', 'name_2' => 'value_2')

  ------------------------------------------------------------ */

function set_session_data($data) {

foreach ($data as $name => $value)
$_SESSION[$name] = $value;
}

/* ------ set_userdata -----------------------------------------

  Sets userdata

  $data
  associative array containing the data names and their values
  sample: array('name_1' => 'value_1', 'name_2' => 'value_2')

  ------------------------------------------------------------ */

function set_userdata($data) {

foreach ($data as $name => $value)
$_SESSION['userdata'][$name] = $value;
}

}
?>