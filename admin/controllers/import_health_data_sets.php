<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		import_health_data_sets.php
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
 * Costbenefitprojection Import_health_data_sets Base Controller
 */
class CostbenefitprojectionControllerImport_health_data_sets extends BaseController
{
	/**
	 * Import an spreadsheet.
	 *
	 * @return  void
	 */
	public function import()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model = $this->getModel('Import_health_data_sets');
		if ($model->import())
		{
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
			// TODO: Reset the users acl here as well to kill off any missing bits
		}

		$app = JFactory::getApplication();
		$redirect_url = $app->getUserState('com_costbenefitprojection.redirect_url');
		if (empty($redirect_url))
		{
			$redirect_url = JRoute::_('index.php?option=com_costbenefitprojection&view=import_health_data_sets', false);
		}
		else
		{
			// wipe out the user state when we're going to redirect
			$app->setUserState('com_costbenefitprojection.redirect_url', '');
			$app->setUserState('com_costbenefitprojection.message', '');
			$app->setUserState('com_costbenefitprojection.extension_message', '');
		}
		$this->setRedirect($redirect_url);
	}
}
