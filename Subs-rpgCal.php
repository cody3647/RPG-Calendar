<?php
/**
* @package RPG Date and Calendar Mod
*
* @author Cody Williams
* @copyright 2015
* @version 1.2
* @license BSD 3-clause
*/

// First of all, we make sure we are accessing the source file via SMF so that people can not directly access the file. 
if (!defined('SMF'))
  die('Hack Attempt...');
  
function rpg_cal(&$actionArray)
{
	$actionArray['rpg_cal'] = array('rpgCal.php', 'rpgCalMain');
}

function rpgCalHeader(){
  global $context, $settings, $modSettings;
  
    if (!isset($context['jquery'])) {
      $context['html_headers'] .='<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>';
      $context['jquery']='rpgCal';
    }
  
  $context['html_headers'] .= '<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/rpgCal.js?10"></script>
    <link rel="stylesheet" type="text/css" href="' . $settings['default_theme_url'] . '/css/rpgCal.css?10" />
    <script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
    $(document).ready(function() {$(".tip").tipr();});
    // ]]></script>';
}

function rpgCalAdminMenu(&$admin_areas)
{
  global $txt, $scripturl;
  loadLanguage('rpgCal');
  $admin_areas['config']['areas']['rpg_cal']=array(
    'label' => $txt['rpg_cal_label'],
    'file' => 'rpgCalAdmin.php',
    'function' => 'rpgCalAdminMain',
    'custom_url' => $scripturl . '?action=admin;area=rpg_cal;sa=events',
    'icon' => 'calendar.gif',
    'subsections' => array(
      'events' => array($txt['rpg_cal_manage_events'], 'rpg_cal_edit_settings',),
      'bbcode' => array($txt['rpg_cal_bbcode'],'rpg_cal_edit_settings'),
    ),);
}

function rpgCalPermissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
  global $context;
  
  // Add to the non-guest permissions
  $new = array('rpg_cal_edit_settings');
  $context['non_guest_permissions'] = array_merge($context['non_guest_permissions'], $new);
  
  // And the permission list.
  $list = array(
    'rpg_cal_edit_settings' => array(false, 'maintenance', 'administrate'),
  );
  $permissionList['membergroup'] = array_merge($permissionList['membergroup'], $list);
}

function rpgCal($start=0,$end=0)
{
  global $context, $modSettings,$sourcedir;
  $context['rpgCal']=true;
  $startdate = explode("-", empty($start) ? $modSettings['rpg_start_date']:$start);
  $enddate = explode("-", empty($end) ? $modSettings['rpg_end_date']:$end);
  require_once($sourcedir . '/rpgCal.php');
  
  rpgCalCalendar($startdate,$enddate);
}

function rpgCalBBCode()
{
	global $txt, $context, $scripturl, $sourcedir;

	$context['page_title'] = $txt['rpg_cal_bbcode_title'];
	$context['sub_template'] = 'BBCode';
	$context['rpg_cal_form'] = $scripturl . ($context['current_action']=='admin') ? '?action=admin;area=rpg_cal;sa=bbcode' : '?action=rpg_cal;sa=bbcode';
	
	if (isset($_REQUEST['submit']))
	{
		$year=intval($_REQUEST['rpgCal-year']);
		$month=intval($_REQUEST['rpgCal-month']);
		require_once($sourcedir . '/rpgCal.php');
		$context['rpg_cal_bbcode']=rpgCalBBCodeContent($year,$month);
	}
}
?>