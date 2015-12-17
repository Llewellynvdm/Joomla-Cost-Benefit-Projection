<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			17th December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		interventions.php
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
 * Interventions Controller
 */
class CostbenefitprojectionControllerInterventions extends JControllerAdmin
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_INTERVENTIONS';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Intervention', $prefix = 'CostbenefitprojectionModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// [7354] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7356] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('intervention.export', 'com_costbenefitprojection') && $user->authorise('core.export', 'com_costbenefitprojection'))
		{
			// [7360] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [7363] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [7365] Get the model
			$model = $this->getModel('Interventions');
			// [7367] get the data to export
			$data = $model->getExportData($pks);
			if (CostbenefitprojectionHelper::checkArray($data))
			{
				// [7371] now set the data to the spreadsheet
				$date = JFactory::getDate();
				CostbenefitprojectionHelper::xls($data,'Interventions_'.$date->format('jS_F_Y'),'Interventions exported ('.$date->format('jS F, Y').')','interventions');
			}
		}
		// [7376] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=interventions', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [7385] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [7387] check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('intervention.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
		{
			// [7391] Get the import model
			$model = $this->getModel('Interventions');
			// [7393] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (CostbenefitprojectionHelper::checkObject($headers))
			{
				// [7397] Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('intervention_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'interventions');
				$session->set('dataType_VDM_IMPORTINTO', 'intervention');
				// [7403] Redirect to import view.
				$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_SELECT_FILE_FOR_INTERVENTIONS');
				$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=import', false), $message);
				return;
			}
		}
		// [7415] Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=interventions', false), $message, 'error');
		return;
	} 
}
