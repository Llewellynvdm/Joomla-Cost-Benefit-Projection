<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
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

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Utilities\ArrayHelper;

/**
 * Companies List Model
 */
class CostbenefitprojectionModelCompanies extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.access','access',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.department','department',
				'h.name','country',
				'i.user','service_provider',
				'a.per','per',
				'a.name','name',
				'g.name','user'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$department = $this->getUserStateFromRequest($this->context . '.filter.department', 'filter_department');
		$this->setState('filter.department', $department);

		$country = $this->getUserStateFromRequest($this->context . '.filter.country', 'filter_country');
		$this->setState('filter.country', $country);

		$service_provider = $this->getUserStateFromRequest($this->context . '.filter.service_provider', 'filter_service_provider');
		$this->setState('filter.service_provider', $service_provider);

		$per = $this->getUserStateFromRequest($this->context . '.filter.per', 'filter_per');
		$this->setState('filter.per', $per);

		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		$this->setState('filter.name', $name);

		$user = $this->getUserStateFromRequest($this->context . '.filter.user', 'filter_user');
		$this->setState('filter.user', $user);

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
		// Check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// Set values to display correctly.
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			// Get the user object if not set.
			if (!isset($user) || !CostbenefitprojectionHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			foreach ($items as $nr => &$item)
			{
				// Remove items the user can't access.
				$access = ($user->authorise('company.access', 'com_costbenefitprojection.company.' . (int) $item->id) && $user->authorise('company.access', 'com_costbenefitprojection'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}

		// set selection value to a translatable value
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// convert department
				$item->department = $this->selectionTranslation($item->department, 'department');
				// convert per
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
		// Array of department language strings
		if ($name === 'department')
		{
			$departmentArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_BASIC',
				2 => 'COM_COSTBENEFITPROJECTION_COMPANY_ADVANCED'
			);
			// Now check if value is found in this array
			if (isset($departmentArray[$value]) && CostbenefitprojectionHelper::checkString($departmentArray[$value]))
			{
				return $departmentArray[$value];
			}
		}
		// Array of per language strings
		if ($name === 'per')
		{
			$perArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_OPEN',
				0 => 'COM_COSTBENEFITPROJECTION_COMPANY_LOCKED'
			);
			// Now check if value is found in this array
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
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the costbenefitprojection_item table
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

		// From the users table.
		$query->select($db->quoteName('g.name','user_name'));
		$query->join('LEFT', $db->quoteName('#__users', 'g') . ' ON (' . $db->quoteName('a.user') . ' = ' . $db->quoteName('g.id') . ')');

		// From the costbenefitprojection_country table.
		$query->select($db->quoteName('h.name','country_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_country', 'h') . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('h.id') . ')');

		// From the costbenefitprojection_service_provider table.
		$query->select($db->quoteName('i.user','service_provider_user'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_service_provider', 'i') . ' ON (' . $db->quoteName('a.service_provider') . ' = ' . $db->quoteName('i.id') . ')');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
		$_access = $this->getState('filter.access');
		if ($_access && is_numeric($_access))
		{
			$query->where('a.access = ' . (int) $_access);
		}
		elseif (CostbenefitprojectionHelper::checkArray($_access))
		{
			// Secure the array for the query
			$_access = ArrayHelper::toInteger($_access);
			// Filter by the Access Array.
			$query->where('a.access IN (' . implode(',', $_access) . ')');
		}
		// Implement View Level Access
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.email LIKE '.$search.' OR a.name LIKE '.$search.' OR a.user LIKE '.$search.' OR g.name LIKE '.$search.' OR a.department LIKE '.$search.' OR a.country LIKE '.$search.' OR h.name LIKE '.$search.' OR a.service_provider LIKE '.$search.' OR i.user LIKE '.$search.' OR a.per LIKE '.$search.')');
			}
		}

		// Filter by Department.
		$_department = $this->getState('filter.department');
		if (is_numeric($_department))
		{
			if (is_float($_department))
			{
				$query->where('a.department = ' . (float) $_department);
			}
			else
			{
				$query->where('a.department = ' . (int) $_department);
			}
		}
		elseif (CostbenefitprojectionHelper::checkString($_department))
		{
			$query->where('a.department = ' . $db->quote($db->escape($_department)));
		}
		// Filter by Country.
		$_country = $this->getState('filter.country');
		if (is_numeric($_country))
		{
			if (is_float($_country))
			{
				$query->where('a.country = ' . (float) $_country);
			}
			else
			{
				$query->where('a.country = ' . (int) $_country);
			}
		}
		elseif (CostbenefitprojectionHelper::checkString($_country))
		{
			$query->where('a.country = ' . $db->quote($db->escape($_country)));
		}
		// Filter by Service_provider.
		$_service_provider = $this->getState('filter.service_provider');
		if (is_numeric($_service_provider))
		{
			if (is_float($_service_provider))
			{
				$query->where('a.service_provider = ' . (float) $_service_provider);
			}
			else
			{
				$query->where('a.service_provider = ' . (int) $_service_provider);
			}
		}
		elseif (CostbenefitprojectionHelper::checkString($_service_provider))
		{
			$query->where('a.service_provider = ' . $db->quote($db->escape($_service_provider)));
		}
		// Filter by Per.
		$_per = $this->getState('filter.per');
		if (is_numeric($_per))
		{
			if (is_float($_per))
			{
				$query->where('a.per = ' . (float) $_per);
			}
			else
			{
				$query->where('a.per = ' . (int) $_per);
			}
		}
		elseif (CostbenefitprojectionHelper::checkString($_per))
		{
			$query->where('a.per = ' . $db->quote($db->escape($_per)));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get list export data.
	 *
	 * @param   array  $pks  The ids of the items to get
	 * @param   JUser  $user  The user making the request
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getExportData($pks, $user = null)
	{
		// setup the query
		if (($pks_size = CostbenefitprojectionHelper::checkArray($pks)) !== false || 'bulk' === $pks)
		{
			// Set a value to know this is export method. (USE IN CUSTOM CODE TO ALTER OUTCOME)
			$_export = true;
			// Get the user object if not set.
			if (!isset($user) || !CostbenefitprojectionHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			// Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Select some fields
			$query->select('a.*');

			// From the costbenefitprojection_company table
			$query->from($db->quoteName('#__costbenefitprojection_company', 'a'));
			// The bulk export path
			if ('bulk' === $pks)
			{
				$query->where('a.id > 0');
			}
			// A large array of ID's will not work out well
			elseif ($pks_size > 500)
			{
				// Use lowest ID
				$query->where('a.id >= ' . (int) min($pks));
				// Use highest ID
				$query->where('a.id <= ' . (int) max($pks));
			}
			// The normal default path
			else
			{
				$query->where('a.id IN (' . implode(',',$pks) . ')');
			}

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
			// Implement View Level Access
			if (!$user->authorise('core.options', 'com_costbenefitprojection'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// Order the results by ordering
			$query->order('a.ordering  ASC');

			// Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// Get the whmcs encryption key.
				$whmcskey = CostbenefitprojectionHelper::getCryptKey('whmcs');
				// Get the encryption object.
				$whmcs = new FOFEncryptAes($whmcskey);

				// Set values to display correctly.
				if (CostbenefitprojectionHelper::checkArray($items))
				{
					foreach ($items as $nr => &$item)
					{
						// Remove items the user can't access.
						$access = ($user->authorise('company.access', 'com_costbenefitprojection.company.' . (int) $item->id) && $user->authorise('company.access', 'com_costbenefitprojection'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						if ($whmcskey && !is_numeric($item->males) && $item->males === base64_encode(base64_decode($item->males, true)))
						{
							// decrypt males
							$item->males = $whmcs->decryptString($item->males);
						}
						if ($whmcskey && !is_numeric($item->sick_leave_males) && $item->sick_leave_males === base64_encode(base64_decode($item->sick_leave_males, true)))
						{
							// decrypt sick_leave_males
							$item->sick_leave_males = $whmcs->decryptString($item->sick_leave_males);
						}
						if ($whmcskey && !is_numeric($item->sick_leave_females) && $item->sick_leave_females === base64_encode(base64_decode($item->sick_leave_females, true)))
						{
							// decrypt sick_leave_females
							$item->sick_leave_females = $whmcs->decryptString($item->sick_leave_females);
						}
						if ($whmcskey && !is_numeric($item->total_salary) && $item->total_salary === base64_encode(base64_decode($item->total_salary, true)))
						{
							// decrypt total_salary
							$item->total_salary = $whmcs->decryptString($item->total_salary);
						}
						if ($whmcskey && !is_numeric($item->total_healthcare) && $item->total_healthcare === base64_encode(base64_decode($item->total_healthcare, true)))
						{
							// decrypt total_healthcare
							$item->total_healthcare = $whmcs->decryptString($item->total_healthcare);
						}
						if ($whmcskey && !is_numeric($item->females) && $item->females === base64_encode(base64_decode($item->females, true)))
						{
							// decrypt females
							$item->females = $whmcs->decryptString($item->females);
						}
						if ($whmcskey && !is_numeric($item->medical_turnovers_males) && $item->medical_turnovers_males === base64_encode(base64_decode($item->medical_turnovers_males, true)))
						{
							// decrypt medical_turnovers_males
							$item->medical_turnovers_males = $whmcs->decryptString($item->medical_turnovers_males);
						}
						if ($whmcskey && !is_numeric($item->medical_turnovers_females) && $item->medical_turnovers_females === base64_encode(base64_decode($item->medical_turnovers_females, true)))
						{
							// decrypt medical_turnovers_females
							$item->medical_turnovers_females = $whmcs->decryptString($item->medical_turnovers_females);
						}
						// unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// Add headers to items array.
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
		// Get a db connection.
		$db = JFactory::getDbo();
		// get the columns
		$columns = $db->getTableColumns("#__costbenefitprojection_company");
		if (CostbenefitprojectionHelper::checkArray($columns))
		{
			// remove the headers you don't import/export.
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
		// Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.department');
		$id .= ':' . $this->getState('filter.country');
		$id .= ':' . $this->getState('filter.service_provider');
		$id .= ':' . $this->getState('filter.per');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.user');

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
		// Get set check in time
		$time = JComponentHelper::getParams('com_costbenefitprojection')->get('check_in');

		if ($time)
		{

			// Get a db connection.
			$db = JFactory::getDbo();
			// Reset query.
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__costbenefitprojection_company'));
			// Only select items that are checked out.
			$query->where($db->quoteName('checked_out') . '!=0');
			$db->setQuery($query, 0, 1);
			$db->execute();
			if ($db->getNumRows())
			{
				// Get Yesterdays date.
				$date = JFactory::getDate()->modify($time)->toSql();
				// Reset query.
				$query = $db->getQuery(true);

				// Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// Check table.
				$query->update($db->quoteName('#__costbenefitprojection_company'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
