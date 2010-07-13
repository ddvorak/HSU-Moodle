<?php
/**
 * export the course participant list
 * 
 **/

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course
if (! $course = get_record('course', 'id', $id)) {
    print_error('courseidincorrect', 'turningtech'); //get_string('courseidincorrect', 'turningtech'));
}

// an optional URL parameter to help debugging
$print = optional_param('print', FALSE, PARAM_BOOL);

// do some authentication
require_course_login($course);

global $USER;
$context = get_context_instance(CONTEXT_COURSE, $course->id);

require_capability('mod/turningtech:manage', $context);

// now generate XML doc
$session = new TurningSession();
$session->setActiveCourse($course);
$session->loadParticipantList();
$dom = $session->exportToXml();

// download file or display it?
if($print) {
  $text = $dom->saveXML();
  $text = str_replace(array('><', '<','>',"\n"), array("&gt;\n&lt;", '&lt;', '&gt;', "<br>\n"), $text);
  echo "<pre>" . $text . "</pre>";
}
else {
  // default behavior: download file
  $filename = turningtech_generate_export_filename($course);
  header("Content-Type: text/xml");
  header('Content-Disposition: attachment; filename="'.$filename.'"');
  echo $dom->saveXML();
}



?>