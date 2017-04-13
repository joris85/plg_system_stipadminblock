<?php
/**
 * ip whitelist plugin
 *
 * @author 		Tim Schutte
 * @link 		schutte@silverdesign.nl
 * @license		GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class plgsystemStipadminblockInstallerScript {
	
	 public function postflight($parent) {
		 $app = JFactory::getApplication();
		 
		 // Enable the plugin
		 $db = JFactory::getDbo();
		 $query = $db->getQuery(true)
		 	->update($db->quoteName("#__extensions"))
		 	->set($db->quoteName("enabled").' =  1')
		 	->where($db->quoteName("type").' = '.$db->quote('plugin'))
		 	->where($db->quoteName("element").' = '.$db->quote('stipadminblock'))
		 	->where($db->quoteName("folder").' = '.$db->quote('system'));
		 	
		 $db->setQuery($query);
		 if ($db->execute()) {
			 $app->enqueueMessage(JText::_('Stip admin block plugin enabled'));

		 }
		 else {
			 $app->enqueueMessage(JText::sprintf('Stip admin block not enabled', $db->getErrorMsg()), 'error');
		 }

		 $current_ip = $_SERVER['REMOTE_ADDR'];

		 // add own mandatory ip addresses
		 $ip_arrays[] = array('::1','localhost');
		 $ip_arrays[] = array('127.0.0.1','localhost');
		 $ip_arrays[] = array('83.87.190.78','stip');
		 $ip_arrays[] = array('83.87.201.139','joris');
		 $ip_arrays[] = array('34.250.7.114','watchfulli1');
		 $ip_arrays[] = array('34.250.132.64','watchfulli2');
		 $ip_arrays[] = array('34.250.203.214','watchfulli3');
		 $ip_arrays[] = array('173.199.154.160','watchfulli4');
		 $ip_arrays[] = array('173.199.154.161','watchfulli5');
		 $ip_arrays[] = array('173.199.154.164','watchfulli6');

		 $add_ip = true;
		 foreach($ip_arrays as $ip_array)
		 {
		 	if($ip_array[0]==$current_ip)
		    {
		    	$add_ip = false;
		    }
		 }
		 if($add_ip)
		 {
			 $ip_arrays[] = array($current_ip,'current');
		 }

		 $htaccess[] = '# file generated by Stip admin block plugin';
		 $htaccess[] = 'ErrorDocument 403 "<H1>Je bent geblokkeerd door de firewall</H1><p>Ga naar de /beheer ipv /administrator om deze blokkade op te heffen.</p>"';
		 $htaccess[] = 'deny from all';


		 foreach($ip_arrays as $ip_array)
		 {
			 $htaccess[] = 'allow from '.$ip_array[0].' #'.$ip_array[1];
		 }


		 $f = fopen(JPATH_ADMINISTRATOR."/.htaccess", "a+");
		 fwrite($f, implode("\n",$htaccess).PHP_EOL);
		 fclose($f);

		 $app->enqueueMessage(JText::_('Administrator .htaccess added'));


		 return true;
	 }
}