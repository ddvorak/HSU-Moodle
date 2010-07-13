<?php

// require the main library for this module
require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');

abstract class SoapService {
  
  // the class that we talk to when handling request
  protected $service = NULL;
  
  private $user;
  private $course;
  
  public function SoapService() {
    $this->service = new IntegrationServiceProvider();
  }
  
  /**
   * Authenticates a user
   * @param $encryptedUserId
   * @param $encryptedPassword
   * @return MoodleUser
   */
  protected function authenticateUser($encryptedUserId, $encryptedPassword) {
    if($user = $this->service->getUserByAESAuth($encryptedUserId, $encryptedPassword)) {
      $this->user = $user;
      return $user;
    }
    
    throw new SoapFault("AuthenticationException", "Could not get user from encrypted username and password");
  }
  
  /**
   * shortcut function for authenticateUser()
   * @param $request
   * @param $usernameField
   * @param $passwordField
   * @return MoodleUser
   */
  protected function authenticateRequest($request, $usernameField = 'encryptedUserId', $passwordField = 'encryptedPassword') {
    return $this->authenticateUser($request->$usernameField, $request->$passwordField);
  }
  
  /**
   * fetch the course used by the request
   * @param $request
   * @param $field
   * @return unknown_type
   */
  protected function getCourseFromRequest($request, $field = 'siteId') {
    if($course = $this->service->getCourseById($request->$field)) {
      $context = get_context_instance(CONTEXT_COURSE, $course->id);
      $role_users = get_role_users(TURNINGTECH_DEFAULT_TEACHER_ROLE, $context);
      foreach($role_users as $ru) {
        if($ru->id == $this->user->id) {
          $this->course = $course;
          return $course;
        }
      }
      $this->throwFault('AuthenticationException', get_string('userisnotinstructor', 'turningtech'));
    }
    
    throw new SoapFault('SiteConnectException', get_string('siteconnecterror', 'turningtech',$request->$field));
  }
  
  /**
   * throw a SoapFault
   * @param $type
   * @param $message
   */
  protected function throwFault($type, $message) {
    throw new SoapFault($type, $message, '', '', $type);
  }
}
?>