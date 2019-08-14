<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Costbenefitprojection Import_health_data_sets View
 */
class CostbenefitprojectionViewImport_health_data_sets extends JViewLegacy
{
	protected $headerList;
	protected $hasPackage = false;
	protected $headers;
	protected $hasHeader = 0;
	protected $dataType;

	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			CostbenefitprojectionHelper::addSubmenu('import');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))){
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		$paths = new stdClass;
		$paths->first = '';
		$state = $this->get('state');

		$this->paths = &$paths;
		$this->state = &$state;
		// get global action permissions
		$this->canDo = CostbenefitprojectionHelper::getActions('import');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		// get the session object
		$session = JFactory::getSession();
		// check if it has package
		$this->hasPackage 	= $session->get('hasPackage', false);
		$this->dataType 		= $session->get('dataType', false);
		if($this->hasPackage && $this->dataType)
		{
			$this->headerList	= json_decode($session->get($this->dataType.'_VDM_IMPORTHEADERS', false),true);
			$this->headerListAdv = array(
					"location_name" => JText::_('COM_COSTBENEFITPROJECTION_LOCATION_NAME'),
					"year" => JText::_('COM_COSTBENEFITPROJECTION_YEAR'),
					"cause" => JText::_('COM_COSTBENEFITPROJECTION_CAUSE_ID'),
					"cause_name" => JText::_('COM_COSTBENEFITPROJECTION_CAUSE_NAME'),
					"risk" => JText::_('COM_COSTBENEFITPROJECTION_RISK_ID'),
					"risk_name" => JText::_('COM_COSTBENEFITPROJECTION_RISK_NAME'),
					"age" => JText::_('COM_COSTBENEFITPROJECTION_AGE_ID'),
					"age_name" => JText::_('COM_COSTBENEFITPROJECTION_AGE_NAME'),
					"sex" => JText::_('COM_COSTBENEFITPROJECTION_GENDER_ID'),
					"sex_name" => JText::_('COM_COSTBENEFITPROJECTION_GENDER_NAME'),
					"rt_mean" => JText::_('COM_COSTBENEFITPROJECTION_RT_MEAN_VALUE'),
					"metric" => JText::_('COM_COSTBENEFITPROJECTION_METRIC'),
					"metric_name" => JText::_('COM_COSTBENEFITPROJECTION_METRIC_NAME'));	
			// make sure these files are loaded		
			JLoader::import('PHPExcel', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
			$package		= $session->get('package', null);
			$package		= json_decode($package, true);
			$inputFileType	= PHPExcel_IOFactory::identify($package['dir']);
			if ('csv' == trim(strtolower($inputFileType),'.'))
			{
				$this->headers	= CostbenefitprojectionHelper::getFileHeadersCSV($package['dir']);
			}
			else
			{
				$this->headers	= CostbenefitprojectionHelper::getFileHeaders($this->dataType);
			}
			// set active tab
			if (in_array('rt_mean', $this->headers) || in_array('metric', $this->headers))
			{
				$this->activeTab = 'advanced';
			}
			else
			{
				$this->activeTab = 'basic';
			}
			// clear the data type
			$session->clear('dataType');
		}

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_TITLE'), 'upload');
		JHtmlSidebar::setAction('index.php?option=com_costbenefitprojection&view=import_health_data_sets');

		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_costbenefitprojection');
		}

		// set help url for this view if found
		$help_url = CostbenefitprojectionHelper::getHelpUrl('import_health_data_sets');
		if (CostbenefitprojectionHelper::checkString($help_url))
		{
			   JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $help_url);
		}
	}
}
