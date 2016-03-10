<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		costbenefitprojection.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Set the component css/js
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_costbenefitprojection/assets/css/site.css');
$document->addScript('components/com_costbenefitprojection/assets/js/site.js');

// Require helper files
JLoader::register('CostbenefitprojectionHelper', dirname(__FILE__) . '/helpers/costbenefitprojection.php');
JLoader::register('CostbenefitprojectionHelperRoute', dirname(__FILE__) . '/helpers/route.php'); 

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Costbenefitprojection
$controller = JControllerLegacy::getInstance('Costbenefitprojection');

// Perform the request task
$jinput = JFactory::getApplication()->input;
$controller->execute($jinput->get('task', null, 'CMD'));

// Redirect if set by the controller
$controller->redirect();
