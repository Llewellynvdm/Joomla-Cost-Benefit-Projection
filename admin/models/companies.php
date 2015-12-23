<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			23rd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companies.php
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

/**
 * Companies Model
 */
class CostbenefitprojectionModelCompanies extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.name','name',
				'a.user','user',
				'a.department','department',
				'a.country','country',
				'a.serviceprovider','serviceprovider',
				'a.per','per'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		$this->setState('filter.name', $name);

		$user = $this->getUserStateFromRequest($this->context . '.filter.user', 'filter_user');
		$this->setState('filter.user', $user);

		$department = $this->getUserStateFromRequest($this->context . '.filter.department', 'filter_department');
		$this->setState('filter.department', $department);

		$country = $this->getUserStateFromRequest($this->context . '.filter.country', 'filter_country');
		$this->setState('filter.country', $country);

		$serviceprovider = $this->getUserStateFromRequest($this->context . '.filter.serviceprovider', 'filter_serviceprovider');
		$this->setState('filter.serviceprovider', $serviceprovider);

		$per = $this->getUserStateFromRequest($this->context . '.filter.per', 'filter_per');
		$this->setState('filter.per', $per);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{ 
		// [10792] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// [10867] set values to display correctly.
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			// [10870] get user object.
			$user = JFactory::getUser();
			foreach ($items as $nr => &$item)
			{
				$access = ($user->authorise('company.access', 'com_costbenefitprojection.company.' . (int) $item->id) && $user->authorise('company.access', 'com_costbenefitprojection'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		} 

		// [11133] set selection value to a translatable value
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [11140] convert department
				$item->department = $this->selectionTranslation($item->department, 'department');
				// [11140] convert per
				$item->per = $this->selectionTranslation($item->per, 'per');
			}
		}

        
		// return items
		return $items;
	}

	/**
	* Method to convert selection values to translatable string.
	*
	* @return translatable string
	*/
	public function selectionTranslation($value,$name)
	{
		// [11166] Array of department language strings
		if ($name == 'department')
		{
			$departmentArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_BASIC',
				2 => 'COM_COSTBENEFITPROJECTION_COMPANY_ADVANCED'
			);
			// [11197] Now check if value is found in this array
			if (isset($departmentArray[$value]) && CostbenefitprojectionHelper::checkString($departmentArray[$value]))
			{
				return $departmentArray[$value];
			}
		}
		// [11166] Array of per language strings
		if ($name == 'per')
		{
			$perArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_OPEN',
				0 => 'COM_COSTBENEFITPROJECTION_COMPANY_LOCKED'
			);
			// [11197] Now check if value is found in this array
			if (isset($perArray[$value]) && CostbenefitprojectionHelper::checkString($perArray[$value]))
			{
				return $perArray[$value];
			}
		}
		return $value;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// [7649] Get the user object.
		$user = JFactory::getUser();
		// [7651] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [7654] Select some fields
		$query->select('a.*');

		// [7661] From the costbenefitprojection_item table
		$query->from($db->quoteName('#__costbenefitprojection_company', 'a'));

		// Filter by companies (admin sees all)
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				$companies = implode(',',$companies);
				// only load this users companies
				$query->where('a.id IN (' . $companies . ')');
			}
			else
			{
				// dont allow user to see any companies
				$query->where('a.id = -4');
			}
		}

		// [7802] From the users table.
		$query->select($db->quoteName('g.name','user_name'));
		$query->join('LEFT', $db->quoteName('#__users', 'g') . ' ON (' . $db->quoteName('a.user') . ' = ' . $db->quoteName('g.id') . ')');

		// [7802] From the costbenefitprojection_country table.
		$query->select($db->quoteName('h.name','country_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_country', 'h') . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('h.id') . ')');

		// [7802] From the costbenefitprojection_service_provider table.
		$query->select($db->quoteName('i.user','serviceprovider_user'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_service_provider', 'i') . ' ON (' . $db->quoteName('a.serviceprovider') . ' = ' . $db->quoteName('i.id') . ')');

		// [7675] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// [7687] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [7690] Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// [7695] Implement View Level Access
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// [7772] Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE '.$search.' OR a.email LIKE '.$search.' OR a.user LIKE '.$search.' OR g.name LIKE '.$search.' OR a.department LIKE '.$search.' OR a.country LIKE '.$search.' OR h.name LIKE '.$search.' OR a.serviceprovider LIKE '.$search.' OR i.user LIKE '.$search.' OR a.per LIKE '.$search.')');
			}
		}

		// [8015] Filter by Department.
		if ($department = $this->getState('filter.department'))
		{
			$query->where('a.department = ' . $db->quote($db->escape($department, true)));
		}
		// [8006] Filter by country.
		if ($country = $this->getState('filter.country'))
		{
			$query->where('a.country = ' . $db->quote($db->escape($country, true)));
		}
		// [8006] Filter by serviceprovider.
		if ($serviceprovider = $this->getState('filter.serviceprovider'))
		{
			$query->where('a.serviceprovider = ' . $db->quote($db->escape($serviceprovider, true)));
		}
		// [8015] Filter by Per.
		if ($per = $this->getState('filter.per'))
		{
			$query->where('a.per = ' . $db->quote($db->escape($per, true)));
		}

		// [7731] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	* Method to get list export data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExportData($pks)
	{
		// [7439] setup the query
		if (CostbenefitprojectionHelper::checkArray($pks))
		{
			// [7442] Get the user object.
			$user = JFactory::getUser();
			// [7444] Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// [7447] Select some fields
			$query->select('a.*');

			// [7449] From the costbenefitprojection_company table
			$query->from($db->quoteName('#__costbenefitprojection_company', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');

			// Filter by companies (admin sees all)
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				$companies = implode(',',$companies);
				// only load this users companies
				$query->where('a.id IN (' . $companies . ')');
			}
			else
			{
				// dont allow user to see any companies
				$query->where('a.id = -4');
			}
		}
			// [7459] Implement View Level Access
			if (!$user->authorise('core.options', 'com_costbenefitprojection'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// [7466] Order the results by ordering
			$query->order('a.ordering  ASC');

			// [7468] Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// [11116] Get the advanced encription key.
				$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
				// [11118] Get the encription object.
				$advanced = new FOFEncryptAes($advancedkey, 256);

				// [10867] set values to display correctly.
				if (CostbenefitprojectionHelper::checkArray($items))
				{
					// [10870] get user object.
					$user = JFactory::getUser();
					foreach ($items as $nr => &$item)
					{
						$access = ($user->authorise('company.access', 'com_costbenefitprojection.company.' . (int) $item->id) && $user->authorise('company.access', 'com_costbenefitprojection'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						if ($advancedkey && !is_numeric($item->medical_turnovers_males) && $item->medical_turnovers_males === base64_encode(base64_decode($item->medical_turnovers_males, true)))
						{
							// [11010] decrypt medical_turnovers_males
							$item->medical_turnovers_males = $advanced->decryptString($item->medical_turnovers_males);
						}
						if ($advancedkey && !is_numeric($item->sick_leave_males) && $item->sick_leave_males === base64_encode(base64_decode($item->sick_leave_males, true)))
						{
							// [11010] decrypt sick_leave_males
							$item->sick_leave_males = $advanced->decryptString($item->sick_leave_males);
						}
						if ($advancedkey && !is_numeric($item->males) && $item->males === base64_encode(base64_decode($item->males, true)))
						{
							// [11010] decrypt males
							$item->males = $advanced->decryptString($item->males);
						}
						if ($advancedkey && !is_numeric($item->females) && $item->females === base64_encode(base64_decode($item->females, true)))
						{
							// [11010] decrypt females
							$item->females = $advanced->decryptString($item->females);
						}
						if ($advancedkey && !is_numeric($item->medical_turnovers_females) && $item->medical_turnovers_females === base64_encode(base64_decode($item->medical_turnovers_females, true)))
						{
							// [11010] decrypt medical_turnovers_females
							$item->medical_turnovers_females = $advanced->decryptString($item->medical_turnovers_females);
						}
						if ($advancedkey && !is_numeric($item->sick_leave_females) && $item->sick_leave_females === base64_encode(base64_decode($item->sick_leave_females, true)))
						{
							// [11010] decrypt sick_leave_females
							$item->sick_leave_females = $advanced->decryptString($item->sick_leave_females);
						}
						if ($advancedkey && !is_numeric($item->total_salary) && $item->total_salary === base64_encode(base64_decode($item->total_salary, true)))
						{
							// [11010] decrypt total_salary
							$item->total_salary = $advanced->decryptString($item->total_salary);
						}
						if ($advancedkey && !is_numeric($item->total_healthcare) && $item->total_healthcare === base64_encode(base64_decode($item->total_healthcare, true)))
						{
							// [11010] decrypt total_healthcare
							$item->total_healthcare = $advanced->decryptString($item->total_healthcare);
						}
						// [11080] unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// [11089] Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (CostbenefitprojectionHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}
				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// [7488] Get a db connection.
		$db = JFactory::getDbo();
		// [7490] get the columns
		$columns = $db->getTableColumns("#__costbenefitprojection_company");
		if (CostbenefitprojectionHelper::checkArray($columns))
		{
			// [7494] remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	} 
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// [10415] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.user');
		$id .= ':' . $this->getState('filter.department');
		$id .= ':' . $this->getState('filter.country');
		$id .= ':' . $this->getState('filter.serviceprovider');
		$id .= ':' . $this->getState('filter.per');

		return parent::getStoreId($id);
	}

	/**
	* Build an SQL query to checkin all items left checked out longer then a set time.
	*
	* @return  a bool
	*
	*/
	protected function checkInNow()
	{
		// [10808] Get set check in time
		$time = JComponentHelper::getParams('com_costbenefitprojection')->get('check_in');
		
		if ($time)
		{

			// [10813] Get a db connection.
			$db = JFactory::getDbo();
			// [10815] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__costbenefitprojection_company'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [10823] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [10825] reset query
				$query = $db->getQuery(true);

				// [10827] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [10832] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [10837] Check table
				$query->update($db->quoteName('#__costbenefitprojection_company'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
