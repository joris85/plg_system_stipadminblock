<?php
/**
 * @package     Joomla.Site
 * @subpackage  plg_system_stipsecurity
 *
 * @copyright   GNU GPL3
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 */

class PlgSystemStipadminblock extends JPlugin
{

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();

		$uri_match = 'beheer';
		$uri = $_SERVER["REQUEST_URI"];
		$host = $_SERVER["HOST"];
		$remote_ip = $_SERVER['REMOTE_ADDR'];


		if(strpos($uri,$uri_match)==1)
		{

			// check if the ip already exists
			$file = file_get_contents(JPATH_ADMINISTRATOR.'/.htaccess');
			if(!strpos($file,$remote_ip))
			{
				// add ip to htaccess
				$name = 'auto add '.date('d-m-Y H.i.s');
				file_put_contents(JPATH_ADMINISTRATOR.'/.htaccess', trim('allow from '.$remote_ip.' #'.$name).PHP_EOL, FILE_APPEND);
			}

			$app->redirect($host.'administrator');
		}



	}


}
