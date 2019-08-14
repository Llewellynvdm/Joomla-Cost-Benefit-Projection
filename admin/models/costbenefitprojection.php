<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
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
			'companyresults.access' => 'companyresults.access',
			'combinedresults.access' => 'combinedresults.access',
			'combinedresults.dashboard_list' => 'combinedresults.dashboard_list',
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
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (CostbenefitprojectionHelper::checkArray($views))
			{
				foreach($views as $view)
				{
					$add = false;
					// external views (links)
					if (strpos($view,'||') !== false)
					{
						$dwd = explode('||', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $url) = $dwd;
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= $url;
							$image 		= $name.'.'.$type;
							$name 		= 'COM_COSTBENEFITPROJECTION_DASHBOARD_'.CostbenefitprojectionHelper::safeString($name,'U');
						}
					}
					// internal views
					elseif (strpos($view,'.') !== false)
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
									$url 	= 'index.php?option=com_costbenefitprojection&view='.$name.'&layout=edit';
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
								$icons[$group][$i]			= new StdClass;
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
								$icons[$group][$i]			= new StdClass;
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
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						else
						{
							$icons[$group][$i]			= new StdClass;
							$icons[$group][$i]->url 	= $url;
							$icons[$group][$i]->name 	= $name;
							$icons[$group][$i]->image 	= $image;
							$icons[$group][$i]->alt 	= $alt;
						}
					}
					else
					{
						$icons[$group][$i]			= new StdClass;
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
				// now decrypt the company staff count
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
			// remove dummy companies
			$remove = $this->getDummyComp();
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
				if ($remove)
				{
					$ids = array_diff($ids, $remove);
				}
				// limit to only load his companies
				$query->where('a.id IN (' . implode(',', $ids) . ')');
			}
			elseif ($remove)
			{
				// limit to only real companies
				$query->where('a.id NOT IN (' . implode(',', $remove) . ')');
			}
			// Check that we only use the real companies and none of the dummy companies
			// $query->where('a.mode = 1'); // this will insure only real companies are loaded (TODO) this switch has moved, and I am not sure where
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
	
	protected $dummyComp = false;
	
	protected function getDummyComp()
	{
		// insure we only get this once
		if (!CostbenefitprojectionHelper::checkArray($this->dummyComp))
		{
			// Create a new query object.
			$query = $this->db->getQuery(true);

			// Get from #__costbenefitprojection_service_provider as a
			$query->select($this->db->quoteName(
				array('a.testcompanies'),
				array('testcompanies')));
			$query->from($this->db->quoteName('#__costbenefitprojection_service_provider', 'a'));
			// load the query
			$this->db->setQuery($query);
			$this->db->execute();
			if ($this->db->getNumRows())
			{
				// get the test companies
				$testcompanies = $this->db->loadColumn();
				// global Ids
				$global = array();
				// okay now we loop the test companies to build a global id set
				foreach ($testcompanies as $json)
				{
					if (CostbenefitprojectionHelper::checkJson($json))
					{
						$global = array_merge($global, json_decode($json, true));
					}
				}
				// now insure the ids are unique 
				$this->dummyComp = array_unique($global);
			}			
		}
		return $this->dummyComp;
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

	public function getGithub()
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . "media/com_costbenefitprojection/js/marked.js");
		$document->addScriptDeclaration('
		var token = "'.JSession::getFormToken().'";
		var urlToGetAllOpenIssues = "https://api.github.com/repos/namibia/CBP-Joomla-3-Component/issues?state=open&page=1&per_page=5";
		var urlToGetAllClosedIssues = "https://api.github.com/repos/namibia/CBP-Joomla-3-Component/issues?state=closed&page=1&per_page=5";
		jQuery(document).ready(function () {
			jQuery.getJSON(urlToGetAllOpenIssues, function (openissues) {
				jQuery("#openissues").html("");
				jQuery.each(openissues, function (i, issue) {
					jQuery("#openissues")
            				.append("<h3><a href=\"" + issue.html_url + "\" target=\"_blank\">" + issue.title + "</a></h3>")
            				.append("<small><em>#" + issue.number + " '.JText::_('COM_COSTBENEFITPROJECTION_OPENED_BY').' " + issue.user.login + "<em></small>")
            				.append(marked(issue.body))
            				.append("<a href=\"" + issue.html_url + "\" target=\"_blank\">'.JText::_('COM_COSTBENEFITPROJECTION_RESPOND_TO_THIS_ISSUE_ON_GITHUB').'</a>...<hr />");
    				});
			});
			jQuery.getJSON(urlToGetAllClosedIssues, function (closedissues) {
				jQuery("#closedissues").html("");
				jQuery.each(closedissues, function (i, issue) {
					jQuery("#closedissues")
            				.append("<h3><a href=\"" + issue.html_url + "\" target=\"_blank\">" + issue.title + "</a></h3>")
            				.append("<small><em>#" + issue.number + " '.JText::_('COM_COSTBENEFITPROJECTION_OPENED_BY').' " + issue.user.login + "<em></small>")
            				.append(marked(issue.body))
            				.append("<a href=\"" + issue.html_url + "\" target=\"_blank\">'.JText::_('COM_COSTBENEFITPROJECTION_REVIEW_THIS_ISSUE_ON_GITHUB').'</a>...<hr />");
    				});
			});
		});
		// to check is READ/NEW
		function getIS(type,notice){
			if(type == 1){
				var getUrl = "index.php?option=com_costbenefitprojection&task=ajax.isNew&format=json";
			} else if (type == 2) {
				var getUrl = "index.php?option=com_costbenefitprojection&task=ajax.isRead&format=json";
			}	
			if(token.length > 0 && notice.length){
				var request = "token="+token+"&notice="+notice;
			}
			return jQuery.ajax({
				type: "POST",
				url: getUrl,
				dataType: "jsonp",
				data: request,
				jsonp: "callback"
			});
		}
		// nice little dot trick :)
		jQuery(document).ready( function($) {
			var x=0;
			setInterval(function() {
				var dots = "";
				x++;
				for (var y=0; y < x%8; y++) {
					dots+=".";
				}
				$(".loading-dots").text(dots);
			} , 500);
		});');
		$create = '<div class="btn-group pull-right">
					<a href="https://github.com/namibia/CBP-Joomla-3-Component/issues/new" class="btn btn-primary"  target="_blank">'.JText::_('COM_COSTBENEFITPROJECTION_NEW_ISSUE').'</a>
				</div></br >';
		$moreopen = '<b><a href="https://github.com/namibia/CBP-Joomla-3-Component/issues" target="_blank">'.JText::_('COM_COSTBENEFITPROJECTION_VIEW_MORE_ISSUES_ON_GITHUB').'</a>...</b>';
		$moreclosed = '<b><a href="https://github.com/namibia/CBP-Joomla-3-Component/issues?q=is%3Aissue+is%3Aclosed" target="_blank">'.JText::_('COM_COSTBENEFITPROJECTION_VIEW_MORE_ISSUES_ON_GITHUB').'</a>...</b>';

		return (object) array(
				'openissues' => $create.'<div id="openissues">'.JText::_('COM_COSTBENEFITPROJECTION_A_FEW_OPEN_ISSUES_FROM_GITHUB_IS_LOADING').'.<span class="loading-dots">.</span></small></div>'.$moreopen, 
				'closedissues' => $create.'<div id="closedissues">'.JText::_('COM_COSTBENEFITPROJECTION_A_FEW_CLOSED_ISSUES_FROM_GITHUB_IS_LOADING').'.<span class="loading-dots">.</span></small></div>'.$moreclosed
		);
	}

	public function getReadme()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
		var getreadme = "'. JURI::root() . 'administrator/components/com_costbenefitprojection/README.txt";
		jQuery(document).ready(function () {
			jQuery.get(getreadme)
			.success(function(readme) { 
				jQuery("#readme-md").html(marked(readme));
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#readme-md").html("'.JText::_('COM_COSTBENEFITPROJECTION_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});');

		return '<div id="readme-md">'.JText::_('COM_COSTBENEFITPROJECTION_THE_README_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	public function getWiki()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
		var gewiki = "https://raw.githubusercontent.com/wiki/namibia/CBP-Joomla-3-Component/Home.md";
		jQuery(document).ready(function () {
			jQuery.get(gewiki)
			.success(function(wiki) { 
				jQuery("#wiki-md").html(marked(wiki));
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#wiki-md").html("'.JText::_('COM_COSTBENEFITPROJECTION_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});');

		return '<div id="wiki-md">'.JText::_('COM_COSTBENEFITPROJECTION_THE_WIKI_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	public function getNoticeboard()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
		var noticeboard = "https://www.vdm.io/costbenefitprojection-noticeboard-md";
		jQuery(document).ready(function () {
			jQuery.get(noticeboard)
			.success(function(board) { 
				if (board.length > 5) {
					jQuery("#noticeboard-md").html(marked(board));
					getIS(1,board).done(function(result) {
						if (result){
							jQuery("#cpanel_tabTabs a").each(function() {
								if (this.href.indexOf("#vast_development_method") >= 0) {
									var textVDM = jQuery(this).text();
									jQuery(this).html("<span class=\"label label-important vdm-new-notice\">1</span> "+textVDM);
									jQuery(this).attr("id","vdm-new-notice");
									jQuery("#vdm-new-notice").click(function() {
										getIS(2,board).done(function(result) {
												if (result) {
												jQuery(".vdm-new-notice").fadeOut(500);
											}
										});
									});
								}
							});
						}
					});
				} else {
					jQuery("#noticeboard-md").html("'.JText::_('COM_COSTBENEFITPROJECTION_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATTER').'");
				}
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#noticeboard-md").html("'.JText::_('COM_COSTBENEFITPROJECTION_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});');

		return '<div id="noticeboard-md">'.JText::_('COM_COSTBENEFITPROJECTION_THE_NOTICE_BOARD_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}
}
