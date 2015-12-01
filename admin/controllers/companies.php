<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			1st December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companies.php
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
 * Companies Controller
 */
class CostbenefitprojectionControllerCompanies extends JControllerAdmin
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_COMPANIES';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Company', $prefix = 'CostbenefitprojectionModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// [7269] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7271] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('company.export', 'com_costbenefitprojection') && $user->authorise('core.export', 'com_costbenefitprojection'))
		{
			// [7275] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [7278] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [7280] Get the model
			$model = $this->getModel('Companies');
			// [7282] get the data to export
			$data = $model->getExportData($pks);
			if (CostbenefitprojectionHelper::checkArray($data))
			{
				// [7286] now set the data to the spreadsheet
				$date = JFactory::getDate();
				CostbenefitprojectionHelper::xls($data,'Companies_'.$date->format('jS_F_Y'),'Companies exported ('.$date->format('jS F, Y').')','companies');
			}
		}
		// [7291] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [7300] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7302] check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('company.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
		{
			// [7306] Get the import model
			$model = $this->getModel('Companies');
			// [7308] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (CostbenefitprojectionHelper::checkObject($headers))
			{
				// [7312] Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('company_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'companies');
				$session->set('dataType_VDM_IMPORTINTO', 'company');
				// [7318] Redirect to import view.
				$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_SELECT_FILE_FOR_COMPANIES');
				$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=import', false), $message);
				return;
			}
		}
		// [7330] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies', false), $message, 'error');
		return;
	} 

	public function redirectToCombinedresults()
	{
		// [7141] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7143] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('combinedresults.access', 'com_costbenefitprojection'))
		{
			// [7147] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [7150] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [7152] convert to string
			$ids = implode('_', $pks);
			$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=combinedresults&cid='.$ids, false));
			return;
		}
		// [7157] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_ACCESS_TO_COMBINEDRESULTS_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies', false), $message, 'error');
		return;
	}
}
