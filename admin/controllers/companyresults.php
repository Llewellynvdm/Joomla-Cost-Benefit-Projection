<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companyresults.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Costbenefitprojection Companyresults Controller
 */
class CostbenefitprojectionControllerCompanyresults extends JControllerLegacy
{
	public function __construct($config)
	{
		parent::__construct($config);
	}

        public function dashboard()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection', false));
		return;
	}

	public function editCompany()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// Get the input
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', null, 'INT');
		// set the redirecting	
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies&task=company.edit&id='.$id.'&ref=companyresults&refid='.$id, false));
	}

	public function gotoCompanies()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// set the redirecting	
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies', false));
	}
}
