<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			2nd December, 2015
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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

/**
 * Costbenefitprojection Model
 */
class CostbenefitprojectionModelCostbenefitprojection extends JModelList
{
	public function getIcons()
	{
                // load user for access menus
                $user = JFactory::getUser();
                // reset icon array
		$icons  = array();
                // view groups array
		$viewGroups = array(
			'main' => array('png.company.add', 'png.companies', 'png.service_provider.add', 'png.service_providers', 'png.countries', 'png.causerisk.add', 'png.causesrisks', 'png.health_data_sets', 'png.scaling_factor.add', 'png.scaling_factors', 'png.intervention.add', 'png.interventions', 'png.currencies', 'png.help_documents')
		);
		// [12190] view access array
		$viewAccess = array(
			'company.create' => 'company.create',
			'companies.access' => 'company.access',
			'company.access' => 'company.access',
			'companies.submenu' => 'company.submenu',
			'companies.dashboard_list' => 'company.dashboard_list',
			'company.dashboard_add' => 'company.dashboard_add',
			'service_provider.create' => 'service_provider.create',
			'service_providers.access' => 'service_provider.access',
			'service_provider.access' => 'service_provider.access',
			'service_providers.submenu' => 'service_provider.submenu',
			'service_providers.dashboard_list' => 'service_provider.dashboard_list',
			'service_provider.dashboard_add' => 'service_provider.dashboard_add',
			'country.create' => 'country.create',
			'countries.access' => 'country.access',
			'country.access' => 'country.access',
			'countries.submenu' => 'country.submenu',
			'countries.dashboard_list' => 'country.dashboard_list',
			'causerisk.create' => 'causerisk.create',
			'causesrisks.access' => 'causerisk.access',
			'causerisk.access' => 'causerisk.access',
			'causesrisks.submenu' => 'causerisk.submenu',
			'causesrisks.dashboard_list' => 'causerisk.dashboard_list',
			'causerisk.dashboard_add' => 'causerisk.dashboard_add',
			'health_data.create' => 'health_data.create',
			'health_data_sets.access' => 'health_data.access',
			'health_data.access' => 'health_data.access',
			'health_data_sets.submenu' => 'health_data.submenu',
			'health_data_sets.dashboard_list' => 'health_data.dashboard_list',
			'scaling_factor.create' => 'scaling_factor.create',
			'scaling_factors.access' => 'scaling_factor.access',
			'scaling_factor.access' => 'scaling_factor.access',
			'scaling_factors.submenu' => 'scaling_factor.submenu',
			'scaling_factors.dashboard_list' => 'scaling_factor.dashboard_list',
			'scaling_factor.dashboard_add' => 'scaling_factor.dashboard_add',
			'intervention.create' => 'intervention.create',
			'interventions.access' => 'intervention.access',
			'intervention.access' => 'intervention.access',
			'interventions.submenu' => 'intervention.submenu',
			'interventions.dashboard_list' => 'intervention.dashboard_list',
			'intervention.dashboard_add' => 'intervention.dashboard_add',
			'currency.create' => 'currency.create',
			'currencies.access' => 'currency.access',
			'currency.access' => 'currency.access',
			'currencies.submenu' => 'currency.submenu',
			'currencies.dashboard_list' => 'currency.dashboard_list',
			'help_document.create' => 'help_document.create',
			'help_documents.access' => 'help_document.access',
			'help_document.access' => 'help_document.access',
			'help_documents.submenu' => 'help_document.submenu',
			'help_documents.dashboard_list' => 'help_document.dashboard_list');
		foreach($viewGroups as $group => $views)
                {
			$i = 0;
			if (CostbenefitprojectionHelper::checkArray($views))
                        {
				foreach($views as $view)
				{
					$add = false;
					if (strpos($view,'.') !== false)
                                        {
                                                $dwd = explode('.', $view);
                                                if (count($dwd) == 3)
                                                {
                                                        list($type, $name, $action) = $dwd;
                                                }
                                                elseif (count($dwd) == 2)
                                                {
                                                        list($type, $name) = $dwd;
                                                        $action = false;
                                                }
                                                if ($action)
                                                {
                                                        $viewName = $name;
                                                        switch($action)
                                                        {
                                                                case 'add':
                                                                        $url 	='index.php?option=com_costbenefitprojection&view='.$name.'&layout=edit';
                                                                        $image 	= $name.'_'.$action.'.'.$type;
                                                                        $alt 	= $name.'&nbsp;'.$action;
                                                                        $name	= 'COM_COSTBENEFITPROJECTION_DASHBOARD_'.CostbenefitprojectionHelper::safeString($name,'U').'_ADD';
                                                                        $add	= true;
                                                                break;
                                                                default:
                                                                        $url 	= 'index.php?option=com_categories&view=categories&extension=com_costbenefitprojection.'.$name;
                                                                        $image 	= $name.'_'.$action.'.'.$type;
                                                                        $alt 	= $name.'&nbsp;'.$action;
                                                                        $name	= 'COM_COSTBENEFITPROJECTION_DASHBOARD_'.CostbenefitprojectionHelper::safeString($name,'U').'_'.CostbenefitprojectionHelper::safeString($action,'U');
                                                                break;
                                                        }
                                                }
                                                else
                                                {
                                                        $viewName 	= $name;
                                                        $alt 		= $name;
                                                        $url 		= 'index.php?option=com_costbenefitprojection&view='.$name;
                                                        $image 		= $name.'.'.$type;
                                                        $name 		= 'COM_COSTBENEFITPROJECTION_DASHBOARD_'.CostbenefitprojectionHelper::safeString($name,'U');
                                                        $hover		= false;
                                                }
                                        }
                                        else
                                        {
                                                $viewName 	= $view;
                                                $alt 		= $view;
                                                $url 		= 'index.php?option=com_costbenefitprojection&view='.$view;
                                                $image 		= $view.'.png';
                                                $name 		= ucwords($view).'<br /><br />';
                                                $hover		= false;
                                        }
                                        // first make sure the view access is set
                                        if (CostbenefitprojectionHelper::checkArray($viewAccess))
                                        {
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
                                                $accessTo = '';
                                                $accessAdd = '';
                                                // acces checking start
                                                $accessCreate = (isset($viewAccess[$viewName.'.create'])) ? CostbenefitprojectionHelper::checkString($viewAccess[$viewName.'.create']):false;
                                                $accessAccess = (isset($viewAccess[$viewName.'.access'])) ? CostbenefitprojectionHelper::checkString($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? CostbenefitprojectionHelper::checkString($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? CostbenefitprojectionHelper::checkString($viewAccess[$viewName.'.dashboard_list']):false;
                                                // check for adding access
                                                if ($add && $accessCreate)
                                                {
                                                        $accessAdd = $viewAccess[$viewName.'.create'];
                                                }
                                                elseif ($add)
                                                {
                                                        $accessAdd = 'core.create';
                                                }
                                                // check if acces to view is set
                                                if ($accessAccess)
                                                {
                                                        $accessTo = $viewAccess[$viewName.'.access'];
                                                }
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add	= $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_costbenefitprojection');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_costbenefitprojection');
						}
                                                if (CostbenefitprojectionHelper::checkString($accessAdd) && CostbenefitprojectionHelper::checkString($accessTo))
                                                {
                                                        // check access
                                                        if($user->authorise($accessAdd, 'com_costbenefitprojection') && $user->authorise($accessTo, 'com_costbenefitprojection') && $dashboard_add)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                elseif (CostbenefitprojectionHelper::checkString($accessTo))
                                                {
                                                        // check access
                                                        if($user->authorise($accessTo, 'com_costbenefitprojection') && $dashboard_list)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                elseif (CostbenefitprojectionHelper::checkString($accessAdd))
                                                {
                                                        // check access
                                                        if($user->authorise($accessAdd, 'com_costbenefitprojection') && $dashboard_add)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                else
                                                {
                                                        $icons[$group][$i]              = new StdClass;
                                                        $icons[$group][$i]->url 	= $url;
                                                        $icons[$group][$i]->name 	= $name;
                                                        $icons[$group][$i]->image 	= $image;
                                                        $icons[$group][$i]->alt 	= $alt;
                                                }
                                        }
                                        else
                                        {
                                                $icons[$group][$i]              = new StdClass;
                                                $icons[$group][$i]->url 	= $url;
                                                $icons[$group][$i]->name 	= $name;
                                                $icons[$group][$i]->image 	= $image;
                                                $icons[$group][$i]->alt 	= $alt;
                                        }
                                        $i++;
                                }
                        }
                        else
                        {
                                $icons[$group][$i] = false;
			}
		}
		return $icons;
	}
}
