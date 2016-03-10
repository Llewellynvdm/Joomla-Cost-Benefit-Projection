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
		// view access array
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

	public function getUsageData()
	{
                // load user for access menus
                $this->user = JFactory::getUser();
		// Create a new query object.
		$this->db = $this->getDbo();
		// admin sees all
		if ($this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// set countries
			$this->countries = $this->setCountries();
			// set companies
			$this->companies = $this->setCompanies();
		}
		else
		{
			// set countries
			$this->countries = $this->setCountries(true);
			// set companies
			$this->companies = $this->setCompanies(true);
		}
		// now work out the satistics
		if ($this->setSatistics())
		{
			return $this->usageData;
		}
		return false;
	}
	
	protected function setSatistics()
	{
		if (CostbenefitprojectionHelper::checkArray($this->companies))
		{
			// Get UTC for now.
			$dNow = new JDate;
			// set the 2 months date
			$d2month = clone $dNow;
			$d2month->modify('-2 month');
			// load to string
			$twoMonth = $d2month->format('Y-m-d H:i:s');
			// set the beginning of year date
			$dyear = clone $dNow;
			$dyear->modify('first day of January '.date('Y'));
			// load to string
			$year = $dyear->format('Y-m-d H:i:s');
			
			// Get the advanced encription.
			$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
			// Get the encription object.
			$advanced = new FOFEncryptAes($advancedkey, 256);

			// set some default data
			$this->usageData = new stdClass;
			
			// start looping the data
			foreach ($this->companies as $company)
			{
				if (!empty($company->males) && $advancedkey && !is_numeric($company->males) && $company->males === base64_encode(base64_decode($company->males, true)))
				{
					// Decode males
					$company->males = rtrim($advanced->decryptString($company->males), "\0");
				}
				else
				{
					$company->males = 0;
				}
				if (!empty($company->females) && $advancedkey && !is_numeric($company->females) && $company->females === base64_encode(base64_decode($company->females, true)))
				{
					// Decode males
					$company->females = rtrim($advanced->decryptString($company->females), "\0");
				}
				else
				{
					$company->females = 0;
				}
				// number of employees
				$employees = $company->males + $company->females;
				// set the country total companies
				$this->usageData->items[$company->country]['companies'][$company->id] = 1;
				$this->usageData->total['companies'][$company->id] = 1;
				$this->usageData->items[$company->country]['companies_employees'][$company->id] = $employees;
				$this->usageData->total['companies_employees'][$company->id] = $employees;
				// count the advanced department
				if ($company->department == 2)
				{
					// set the country total advanced companies
					$this->usageData->items[$company->country]['advanced_companies'][$company->id] = 1;
					$this->usageData->total['advanced_companies'][$company->id] = 1;
					$this->usageData->items[$company->country]['advanced_companies_employees'][$company->id] = $employees;
					$this->usageData->total['advanced_companies_employees'][$company->id] = $employees;
				}
				else
				{
					// set the country total advanced companies
					$this->usageData->items[$company->country]['advanced_companies'][$company->id] = 0;
					$this->usageData->total['advanced_companies'][$company->id] = 0;
					$this->usageData->items[$company->country]['advanced_companies_employees'][$company->id] = 0;
					$this->usageData->total['advanced_companies_employees'][$company->id] = 0;
				}
				// count the basic department
				if ($company->department == 1)
				{
					// set the country total basic companies
					$this->usageData->items[$company->country]['basic_companies'][$company->id] = 1;
					$this->usageData->total['basic_companies'][$company->id] = 1;
					$this->usageData->items[$company->country]['basic_companies_employees'][$company->id] = $employees;
					$this->usageData->total['basic_companies_employees'][$company->id] = $employees;
				}
				else
				{
					// set the country total basic companies
					$this->usageData->items[$company->country]['basic_companies'][$company->id] = 0;
					$this->usageData->total['basic_companies'][$company->id] = 0;
					$this->usageData->items[$company->country]['basic_companies_employees'][$company->id] = 0;
					$this->usageData->total['basic_companies_employees'][$company->id] = 0;
				}
				
				// count the timed usage for last 2 months
				if ($this->visitCheck($company->user,$twoMonth))
				{
					// set the country total advanced companies
					$this->usageData->items[$company->country]['last_two_months'][$company->id] = 1;
					$this->usageData->total['last_two_months'][$company->id] = 1;
					$this->usageData->items[$company->country]['last_two_months_employees'][$company->id] = $employees;
					$this->usageData->total['last_two_months_employees'][$company->id] = $employees;
				}
				else
				{
					// set the country total advanced companies
					$this->usageData->items[$company->country]['last_two_months'][$company->id] = 0;
					$this->usageData->total['last_two_months'][$company->id] = 0;
					$this->usageData->items[$company->country]['last_two_months_employees'][$company->id] = 0;
					$this->usageData->total['last_two_months_employees'][$company->id] = 0;
				}
				// count the timed usage since begining of this year
				if ($this->visitCheck($company->user,$year))
				{
					// set the country total basic companies
					$this->usageData->items[$company->country]['since_beginning_this_year'][$company->id] = 1;
					$this->usageData->total['since_beginning_this_year'][$company->id] = 1;
					$this->usageData->items[$company->country]['since_beginning_this_year_employees'][$company->id] = $employees;
					$this->usageData->total['since_beginning_this_year_employees'][$company->id] = $employees;
				}
				else
				{
					// set the country total basic companies
					$this->usageData->items[$company->country]['since_beginning_this_year'][$company->id] = 0;
					$this->usageData->total['since_beginning_this_year'][$company->id] = 0;
					$this->usageData->items[$company->country]['since_beginning_this_year_employees'][$company->id] = 0;
					$this->usageData->total['since_beginning_this_year_employees'][$company->id] = 0;
				}
			}
			
			// sum the item arrays
			foreach ($this->usageData->items as $country => $data)
			{
				// insure to set the name of the country
				$this->usageData->items[$country]['name'] = $this->countries[$country];
				foreach($data as $key => $array)
				{
					$this->usageData->items[$country][$key] = array_sum($array);
				}
			}
			// sum the total array
			foreach ($this->usageData->total as $tkey => $tarray)
			{
				$this->usageData->total[$tkey] = array_sum($tarray);
			}
			
			return true;
		}			
		return false;
	}
	
	protected function visitCheck($user,$time)
	{
		// set a token
		$token = md5($time.$user);
		if (!isset($this->checkedUser[$token]))
		{
			// Create a new query object.
			$query = $this->db->getQuery(true);
			// Get from #__costbenefitprojection_company as a
			$query->select($this->db->quoteName(array('a.id')));
			$query->from($this->db->quoteName('#__users', 'a'));
			$query->where($this->db->qn('a.lastvisitDate') . ' >= ' . $this->db->quote($time));
			// limit to only load these countries
			$query->where('a.id = ' . (int) $user);
			// load the query
			$this->db->setQuery($query);
			$this->db->execute();
			if ($this->db->getNumRows())
			{
				$this->checkedUser[$token] = true;
			}
			else
			{
				$this->checkedUser[$token] = false;
			}
		}
		return $this->checkedUser[$token];
	}
	
	protected function setCompanies($limited = false)
	{		
		// check if there is any countries loaded
		if (CostbenefitprojectionHelper::checkArray($this->countries))
		{
			// Create a new query object.
			$query = $this->db->getQuery(true);
			// Get from #__costbenefitprojection_company as a
			$query->select($this->db->quoteName(
				array('a.id','a.user','a.name','a.country','a.department','a.males','a.females'),
				array('id','user','name','country','department','males','females')));
			$query->from($this->db->quoteName('#__costbenefitprojection_company', 'a'));
			if ($limited)
			{
				// get his companies
				$ids = CostbenefitprojectionHelper::hisCompanies($this->user->id);
				// limit to only load his companies
				$query->where('a.id IN (' . implode(',', $ids) . ')');
			}
			// get only from set countries
			$countryIds = array_keys($this->countries);
			// limit to only load these countries
			$query->where('a.country IN (' . implode(',', $countryIds) . ')');
			$query->order('a.country ASC');
			// load the query
			$this->db->setQuery($query);
			$this->db->execute();
			if ($this->db->getNumRows())
			{
				return $this->db->loadObjectList();
			}
		}
		return false;
	}
	
	protected function setCountries($limited = false)
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);

		// Get from #__costbenefitprojection_country as a
		$query->select($this->db->quoteName(
			array('a.id','a.name'),
			array('id','name')));
		$query->from($this->db->quoteName('#__costbenefitprojection_country', 'a'));
		if ($limited)
		{
			// get his countries
			$ids = CostbenefitprojectionHelper::hisCountries($this->user->id);
			// limit to only load his countries
			$query->where('a.id IN (' . implode(',', $ids) . ')');
		}
		else
		{
			$query->where('CHAR_LENGTH(a.causesrisks) > 5');
			$query->where('CHAR_LENGTH(a.percentfemale) > 5');
			$query->where('CHAR_LENGTH(a.percentmale) > 5');
			$query->where('CHAR_LENGTH(a.datayear) > 3');
			$query->where('CHAR_LENGTH(a.productivity_losses) > 0');
			$query->where('CHAR_LENGTH(a.sick_leave) > 0');
			$query->where('CHAR_LENGTH(a.medical_turnovers) > 0');
		}
		$query->where('a.published = 1');
		$query->order('a.name ASC');
		// load the query
		$this->db->setQuery($query);
		$this->db->execute();
		if ($this->db->getNumRows())
		{
			return $this->db->loadAssocList('id', 'name');
		}
		return false;
	}
}
