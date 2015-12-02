<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			2nd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		interventions.php
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
 * Interventions Model
 */
class CostbenefitprojectionModelInterventions extends JModelList
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
				'a.company','company',
				'a.type','type',
				'a.coverage','coverage',
				'a.duration','duration',
				'a.description','description'
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

		$company = $this->getUserStateFromRequest($this->context . '.filter.company', 'filter_company');
		$this->setState('filter.company', $company);

		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$coverage = $this->getUserStateFromRequest($this->context . '.filter.coverage', 'filter_coverage');
		$this->setState('filter.coverage', $coverage);

		$duration = $this->getUserStateFromRequest($this->context . '.filter.duration', 'filter_duration');
		$this->setState('filter.duration', $duration);

		$description = $this->getUserStateFromRequest($this->context . '.filter.description', 'filter_description');
		$this->setState('filter.description', $description);
        
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
		// [10544] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// [10619] set values to display correctly.
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			// [10622] get user object.
			$user = JFactory::getUser();
			foreach ($items as $nr => &$item)
			{
				$access = ($user->authorise('intervention.access', 'com_costbenefitprojection.intervention.' . (int) $item->id) && $user->authorise('intervention.access', 'com_costbenefitprojection'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}

		// check if item is to load based on sharing setting
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				if (!CostbenefitprojectionHelper::checkIntervetionAccess($item->id,$item->share,$item->company))
				{
					unset($items[$nr]);
					continue;
				}
			}
		}  

		// [10885] set selection value to a translatable value
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [10892] convert type
				$item->type = $this->selectionTranslation($item->type, 'type');
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
		// [10918] Array of type language strings
		if ($name == 'type')
		{
			$typeArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_INTERVENTION_SINGLE',
				2 => 'COM_COSTBENEFITPROJECTION_INTERVENTION_CLUSTER'
			);
			// [10949] Now check if value is found in this array
			if (isset($typeArray[$value]) && CostbenefitprojectionHelper::checkString($typeArray[$value]))
			{
				return $typeArray[$value];
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
		// [7405] Get the user object.
		$user = JFactory::getUser();
		// [7407] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [7410] Select some fields
		$query->select('a.*');

		// [7417] From the costbenefitprojection_item table
		$query->from($db->quoteName('#__costbenefitprojection_intervention', 'a'));

		// Filter the companies (admin sees all)
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				$companies = implode(',',$companies);
				// only load this users companies
				$query->where('a.company IN (' . $companies . ')');
			}
			else
			{
				// don't allow user to see any companies
				$query->where('a.company = -4');
			}
		}

		// [7558] From the costbenefitprojection_company table.
		$query->select($db->quoteName('g.name','company_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_company', 'g') . ' ON (' . $db->quoteName('a.company') . ' = ' . $db->quoteName('g.id') . ')');

		// [7431] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		// [7528] Filter by search.
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
				$query->where('(a.name LIKE '.$search.' OR a.company LIKE '.$search.' OR g.name LIKE '.$search.' OR a.type LIKE '.$search.' OR a.coverage LIKE '.$search.' OR a.duration LIKE '.$search.' OR a.description LIKE '.$search.')');
			}
		}

		// [7762] Filter by company.
		if ($company = $this->getState('filter.company'))
		{
			$query->where('a.company = ' . $db->quote($db->escape($company, true)));
		}
		// [7771] Filter by Type.
		if ($type = $this->getState('filter.type'))
		{
			$query->where('a.type = ' . $db->quote($db->escape($type, true)));
		}
		// [7771] Filter by Coverage.
		if ($coverage = $this->getState('filter.coverage'))
		{
			$query->where('a.coverage = ' . $db->quote($db->escape($coverage, true)));
		}
		// [7771] Filter by Duration.
		if ($duration = $this->getState('filter.duration'))
		{
			$query->where('a.duration = ' . $db->quote($db->escape($duration, true)));
		}

		// [7487] Add the list ordering clause.
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
		// [7195] setup the query
		if (CostbenefitprojectionHelper::checkArray($pks))
		{
			// [7198] Get the user object.
			$user = JFactory::getUser();
			// [7200] Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// [7203] Select some fields
			$query->select('a.*');

			// [7205] From the costbenefitprojection_intervention table
			$query->from($db->quoteName('#__costbenefitprojection_intervention', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');

			// Filter the companies (admin sees all)
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				$companies = implode(',',$companies);
				// only load this users companies
				$query->where('a.company IN (' . $companies . ')');
			}
			else
			{
				// don't allow user to see any companies
				$query->where('a.company = -4');
			}
		}

			// [7222] Order the results by ordering
			$query->order('a.ordering  ASC');

			// [7224] Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// [10619] set values to display correctly.
				if (CostbenefitprojectionHelper::checkArray($items))
				{
					// [10622] get user object.
					$user = JFactory::getUser();
					foreach ($items as $nr => &$item)
					{
						$access = ($user->authorise('intervention.access', 'com_costbenefitprojection.intervention.' . (int) $item->id) && $user->authorise('intervention.access', 'com_costbenefitprojection'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						// [10832] unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// [10841] Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (CostbenefitprojectionHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}

				// check if item is to load based on sharing setting
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				if (!CostbenefitprojectionHelper::checkIntervetionAccess($item->id,$item->share,$item->company))
				{
					unset($items[$nr]);
					continue;
				}
			}
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
		// [7244] Get a db connection.
		$db = JFactory::getDbo();
		// [7246] get the columns
		$columns = $db->getTableColumns("#__costbenefitprojection_intervention");
		if (CostbenefitprojectionHelper::checkArray($columns))
		{
			// [7250] remove the headers you don't import/export.
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
		// [10167] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.company');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.coverage');
		$id .= ':' . $this->getState('filter.duration');
		$id .= ':' . $this->getState('filter.description');

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
		// [10560] Get set check in time
		$time = JComponentHelper::getParams('com_costbenefitprojection')->get('check_in');
		
		if ($time)
		{

			// [10565] Get a db connection.
			$db = JFactory::getDbo();
			// [10567] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__costbenefitprojection_intervention'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [10575] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [10577] reset query
				$query = $db->getQuery(true);

				// [10579] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [10584] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [10589] Check table
				$query->update($db->quoteName('#__costbenefitprojection_intervention'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
