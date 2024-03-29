<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		health_data_sets.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Utilities\ArrayHelper;

/**
 * Health_data_sets Admin Controller
 */
class CostbenefitprojectionControllerHealth_data_sets extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_HEALTH_DATA_SETS';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Health_data', $prefix = 'CostbenefitprojectionModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('health_data.export', 'com_costbenefitprojection') && $user->authorise('core.export', 'com_costbenefitprojection'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			$pks = ArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Health_data_sets');
			// get the data to export
			$data = $model->getExportData($pks);
			if (CostbenefitprojectionHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				CostbenefitprojectionHelper::xls($data,'Health_data_sets_'.$date->format('jS_F_Y'),'Health data sets exported ('.$date->format('jS F, Y').')','health data sets');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=health_data_sets', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('health_data.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
		{
			// Get the import model
			$model = $this->getModel('Health_data_sets');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (CostbenefitprojectionHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('health_data_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'health_data_sets');
				$session->set('dataType_VDM_IMPORTINTO', 'health_data');
				// Redirect to import view.
				$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_SELECT_FILE_FOR_HEALTH_DATA_SETS');
				$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=import_health_data_sets', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=health_data_sets', false), $message, 'error');
		return;
	}


	/**
	 * get a bulk export of health_data_sets
	 */
	public function getBulkExport()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		$status = 'error';
		if ($user->authorise('health_data.bulk_export', 'com_costbenefitprojection'))
		{
			// Get the model
			$model = $this->getModel('Health_data_sets');
			// get the data
			if (($data = $model->getBulkExport()) !== false)
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				try
				{
					CostbenefitprojectionHelper::xls($data, 'Health_data_sets_' . $date->format('jS_F_Y'), 'Health_data_sets exported (' . $date->format('jS F, Y') . ')', 'health_data_sets');
				}
				catch (\RuntimeException $e)
				{
					jexit('Error: ' . $e->getMessage());
				}
			}
			else
			{
				// Set error message
				$message = JText::_('COM_COSTBENEFITPROJECTION_BULK_EXPORT_OF_HEALTH_DATA_SETS_FAILED_SHOULD_THIS_ISSUE_CONTINUE_PLEASE_INFORM_YOUR_SYSTEM_ADMINISTRATOR');
			}
		}
		else
		{
			// Set error message
			$message = JText::_('COM_COSTBENEFITPROJECTION_YOU_DO_NOT_HAVE_PERMISSION_TO_DO_A_BULK_EXPORT_OF_HEALTH_DATA_SETS');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=health_data_sets', false), $message, $status);
	}
}
