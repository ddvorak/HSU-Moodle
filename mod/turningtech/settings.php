<?php
/**
 * The admin settings page for the turningtech module
 */
require_once($CFG->dirroot.'/mod/turningtech/lib.php');

if($messages = turningtech_show_messages()) {
  $settings->add(new admin_setting_heading('turningtechmeessages','',$messages));
}

// select device ID format
$settings->add(
  new admin_setting_configselect(
  	'turningtech_deviceid_format',
    get_string('deviceidformat', 'turningtech'),
    get_string('deviceidformatdescription', 'turningtech'),
    TURNINGTECH_DEVICE_ID_FORMAT_HEX,
    turningtech_get_device_id_format_options()
  )
);

// subject of reminder emails
$settings->add(
  new admin_setting_configtext(
    'turningtech_reminder_email_subject',
    get_string('reminderemailsubject', 'turningtech'),
    get_string('reminderemailsubjectdescription', 'turningtech'),
    get_string('remidneremailsubjectdefault', 'turningtech')
  )
);

$settings->add(
  new admin_setting_configtextarea(
    'turningtech_reminder_email_body',
    get_string('reminderemailbody','turningtech'),
    get_string('reminderemailbodydescription','turningtech'),
    get_string('reminderemailbodydefault','turningtech')
  )
);

$settings->add(
  new admin_setting_configtext(
    'turningtech_responseware_provider',
    get_string('responsewareprovider','turningtech'),
    get_string('responsewareproviderdescription','turningtech'),
    TURNINGTECH_DEFAULT_RESPONSEWARE_PROVIDER
  )
);

$links = array(
  'usersearch' => array(
  	'text' => get_string('usersearch', 'turningtech'),
  	'href' => "{$CFG->wwwroot}/mod/turningtech/admin.php"
  ),
  'purge' => array(
    'text' => get_string('purgedeviceids','turningtech'),
    'href' => "{$CFG->wwwroot}/mod/turningtech/admin_purge.php"
  )
);
$searchlink = "<a href='{$CFG->wwwroot}/mod/turningtech/admin.php'>" . get_string('usersearch','turningtech') . "</a>\n";
$settings->add(new admin_setting_heading('turningtech_search_users','', turningtech_ul($links)));
?>