<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.2.0
	@build			12th January, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		currencies.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Currencies Controller
 */
class CostbenefitprojectionControllerCurrencies extends JControllerAdmin
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_CURRENCIES';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Currency', $prefix = 'CostbenefitprojectionModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// [7568] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7570] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('currency.export', 'com_costbenefitprojection') && $user->authorise('core.export', 'com_costbenefitprojection'))
		{
			// [7574] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [7577] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [7579] Get the model
			$model = $this->getModel('Currencies');
			// [7581] get the data to export
			$data = $model->getExportData($pks);
			if (CostbenefitprojectionHelper::checkArray($data))
			{
				// [7585] now set the data to the spreadsheet
				$date = JFactory::getDate();
				CostbenefitprojectionHelper::xls($data,'Currencies_'.$date->format('jS_F_Y'),'Currencies exported ('.$date->format('jS F, Y').')','currencies');
			}
		}
		// [7590] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=currencies', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [7599] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7601] check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('currency.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
		{
			// [7605] Get the import model
			$model = $this->getModel('Currencies');
			// [7607] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (CostbenefitprojectionHelper::checkObject($headers))
			{
				// [7611] Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('currency_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'currencies');
				$session->set('dataType_VDM_IMPORTINTO', 'currency');
				// [7617] Redirect to import view.
				$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_SELECT_FILE_FOR_CURRENCIES');
				$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=import', false), $message);
				return;
			}
		}
		// [7629] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=currencies', false), $message, 'error');
		return;
	} 
}
