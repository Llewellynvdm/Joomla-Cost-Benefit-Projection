<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		help.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Utilities\ArrayHelper;

/**
 * Costbenefitprojection Help Base Controller
 */
class CostbenefitprojectionControllerHelp extends BaseController
{
	public function __construct($config)
	{
		parent::__construct($config);
		// load the tasks
		$this->registerTask('getText', 'help');
	}

	public function help()
	{
		$user 		= JFactory::getUser();
		$jinput 	= JFactory::getApplication()->input;
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($user->id != 0 && $token == $call_token)
		{
			$task = $this->getTask();
			switch($task){
				case 'getText':
					try
					{
						$idValue = $jinput->get('id', 0, 'INT');
						if($idValue)
						{
							$result = $this->getHelpDocumentText($idValue);
						}
						else
						{
							$result = '';
						}
						echo $result;
						// stop execution gracefully
						jexit();
					}
					catch(Exception $e)
					{
						// stop execution gracefully
						jexit();
					}
				break;
			}
		}
 		else
		{
			// stop execution gracefully
			jexit();
		}
	}

	protected function getHelpDocumentText($id)
	{
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select(array('a.title','a.content'));
		$query->from('#__costbenefitprojection_help_document AS a');
		$query->where('a.id = '.(int) $id);
		$query->where('a.published = 1');
		$db->setQuery($query);
		$db->execute();
		if($db->getNumRows())
		{
			$text = array();
			$document = $db->loadObject();
			// fix image issue
			$images['src="images'] = 'src="'.JURI::root().'images';
			$images["src='images"] = "src='".JURI::root()."images";
			$images['src="/images'] = 'src="'.JURI::root().'images';
			$images["src='/images"] = "src='".JURI::root()."images";
			// set document template
			$text[] = "<!doctype html>";
			$text[] = '<html>';
			$text[] = "<head>";
			$text[] = '<meta charset="utf-8">';
			$text[] = "<title>".$document->title."</title>";
			$text[] = '<link type="text/css" href="'.JURI::root().'media/com_costbenefitprojection/uikit/css/uikit.gradient.min.css" rel="stylesheet"></link>';
			$text[] = '<script type="text/javascript" src="'.JURI::root().'media/com_costbenefitprojection/uikit/js/uikit.min.js"></script>';
			$text[] = "</head>";
			$text[] = '<body><br />';
			$text[] = '<div class="uk-container uk-container-center uk-grid-collapse">';
			$text[] = '<div class="uk-panel uk-width-1-1 uk-panel-box uk-panel-box-primary">';
			// build the help text
			$text[] = '<h1 class="uk-panel-title">'.$document->title."</h1>";
			$text[] = str_replace(array_keys($images),array_values($images),$document->content);
			// end template
			$text[] = '</div><br /><br />';
			$text[] = '</div>';
			$text[] = "</body>";
			$text[] = "</html>";

			return implode("\n",$text);
		}
		return false;
	}
}
