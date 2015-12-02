<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.9
	@build			2nd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		help_documents.php
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
 * Help_documents Controller
 */
class CostbenefitprojectionControllerHelp_documents extends JControllerAdmin
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_HELP_DOCUMENTS';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Help_document', $prefix = 'CostbenefitprojectionModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// [7278] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7280] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('help_document.export', 'com_costbenefitprojection') && $user->authorise('core.export', 'com_costbenefitprojection'))
		{
			// [7284] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [7287] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [7289] Get the model
			$model = $this->getModel('Help_documents');
			// [7291] get the data to export
			$data = $model->getExportData($pks);
			if (CostbenefitprojectionHelper::checkArray($data))
			{
				// [7295] now set the data to the spreadsheet
				$date = JFactory::getDate();
				CostbenefitprojectionHelper::xls($data,'Help_documents_'.$date->format('jS_F_Y'),'Help documents exported ('.$date->format('jS F, Y').')','help documents');
			}
		}
		// [7300] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=help_documents', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [7309] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7311] check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('help_document.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
		{
			// [7315] Get the import model
			$model = $this->getModel('Help_documents');
			// [7317] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (CostbenefitprojectionHelper::checkObject($headers))
			{
				// [7321] Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('help_document_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'help_documents');
				$session->set('dataType_VDM_IMPORTINTO', 'help_document');
				// [7327] Redirect to import view.
				$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_SELECT_FILE_FOR_HELP_DOCUMENTS');
				$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=import', false), $message);
				return;
			}
		}
		// [7339] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=help_documents', false), $message, 'error');
		return;
	} 
}
