<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Costbenefitprojection View class
 */
class CostbenefitprojectionViewCostbenefitprojection extends JViewLegacy
{
	/**
	 * View display method
	 * @return void
	 */
	function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors')))
                {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		};
		// Assign data to the view
		$this->icons			= $this->get('Icons');
		$this->contributors		= CostbenefitprojectionHelper::getContributors();
		$this->usagedata	= $this->get('UsageData');

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		$canDo = CostbenefitprojectionHelper::getActions('costbenefitprojection');
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_DASHBOARD'), 'grid-2');

                // set help url for this view if found
                $help_url = CostbenefitprojectionHelper::getHelpUrl('costbenefitprojection');
                if (CostbenefitprojectionHelper::checkString($help_url))
                {
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $help_url);
                }

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
                {
			JToolBarHelper::preferences('com_costbenefitprojection');
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::root() . "administrator/components/com_costbenefitprojection/assets/css/dashboard.css");

		$document->setTitle(JText::_('COM_COSTBENEFITPROJECTION_DASHBOARD'));
	}
}
