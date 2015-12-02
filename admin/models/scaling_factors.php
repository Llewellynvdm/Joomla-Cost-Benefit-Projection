<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			2nd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		scaling_factors.php
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
 * Scaling_factors Model
 */
class CostbenefitprojectionModelScaling_factors extends JModelList
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
				'a.causerisk','causerisk',
				'a.company','company',
				'a.yld_scaling_factor_males','yld_scaling_factor_males',
				'a.yld_scaling_factor_females','yld_scaling_factor_females',
				'a.mortality_scaling_factor_males','mortality_scaling_factor_males',
				'a.mortality_scaling_factor_females','mortality_scaling_factor_females',
				'a.presenteeism_scaling_factor_males','presenteeism_scaling_factor_males',
				'a.presenteeism_scaling_factor_females','presenteeism_scaling_factor_females'
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
		$causerisk = $this->getUserStateFromRequest($this->context . '.filter.causerisk', 'filter_causerisk');
		$this->setState('filter.causerisk', $causerisk);

		$company = $this->getUserStateFromRequest($this->context . '.filter.company', 'filter_company');
		$this->setState('filter.company', $company);

		$yld_scaling_factor_males = $this->getUserStateFromRequest($this->context . '.filter.yld_scaling_factor_males', 'filter_yld_scaling_factor_males');
		$this->setState('filter.yld_scaling_factor_males', $yld_scaling_factor_males);

		$yld_scaling_factor_females = $this->getUserStateFromRequest($this->context . '.filter.yld_scaling_factor_females', 'filter_yld_scaling_factor_females');
		$this->setState('filter.yld_scaling_factor_females', $yld_scaling_factor_females);

		$mortality_scaling_factor_males = $this->getUserStateFromRequest($this->context . '.filter.mortality_scaling_factor_males', 'filter_mortality_scaling_factor_males');
		$this->setState('filter.mortality_scaling_factor_males', $mortality_scaling_factor_males);

		$mortality_scaling_factor_females = $this->getUserStateFromRequest($this->context . '.filter.mortality_scaling_factor_females', 'filter_mortality_scaling_factor_females');
		$this->setState('filter.mortality_scaling_factor_females', $mortality_scaling_factor_females);

		$presenteeism_scaling_factor_males = $this->getUserStateFromRequest($this->context . '.filter.presenteeism_scaling_factor_males', 'filter_presenteeism_scaling_factor_males');
		$this->setState('filter.presenteeism_scaling_factor_males', $presenteeism_scaling_factor_males);

		$presenteeism_scaling_factor_females = $this->getUserStateFromRequest($this->context . '.filter.presenteeism_scaling_factor_females', 'filter_presenteeism_scaling_factor_females');
		$this->setState('filter.presenteeism_scaling_factor_females', $presenteeism_scaling_factor_females);
        
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
				$access = ($user->authorise('scaling_factor.access', 'com_costbenefitprojection.scaling_factor.' . (int) $item->id) && $user->authorise('scaling_factor.access', 'com_costbenefitprojection'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		} 
        
		// return items
		return $items;
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
		$query->from($db->quoteName('#__costbenefitprojection_scaling_factor', 'a'));

		// Filter by companies (admin sees all)
		if ( !$user->authorise('core.options', 'com_costbenefitprojection'))
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
				// dont allow user to see any companies
				$query->where('a.company = -4');
			}
		}

		// [7558] From the costbenefitprojection_causerisk table.
		$query->select($db->quoteName('g.name','causerisk_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_causerisk', 'g') . ' ON (' . $db->quoteName('a.causerisk') . ' = ' . $db->quoteName('g.id') . ')');

		// [7558] From the costbenefitprojection_company table.
		$query->select($db->quoteName('h.name','company_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_company', 'h') . ' ON (' . $db->quoteName('a.company') . ' = ' . $db->quoteName('h.id') . ')');

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
				$query->where('(a.causerisk LIKE '.$search.' OR g.name LIKE '.$search.' OR a.company LIKE '.$search.' OR h.name LIKE '.$search.' OR a.reference LIKE '.$search.')');
			}
		}

		// [7762] Filter by causerisk.
		if ($causerisk = $this->getState('filter.causerisk'))
		{
			$query->where('a.causerisk = ' . $db->quote($db->escape($causerisk, true)));
		}
		// [7762] Filter by company.
		if ($company = $this->getState('filter.company'))
		{
			$query->where('a.company = ' . $db->quote($db->escape($company, true)));
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

			// [7205] From the costbenefitprojection_scaling_factor table
			$query->from($db->quoteName('#__costbenefitprojection_scaling_factor', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');

			// Filter by companies (admin sees all)
		if ( !$user->authorise('core.options', 'com_costbenefitprojection'))
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
				// dont allow user to see any companies
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
						$access = ($user->authorise('scaling_factor.access', 'com_costbenefitprojection.scaling_factor.' . (int) $item->id) && $user->authorise('scaling_factor.access', 'com_costbenefitprojection'));
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
		$columns = $db->getTableColumns("#__costbenefitprojection_scaling_factor");
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
		$id .= ':' . $this->getState('filter.causerisk');
		$id .= ':' . $this->getState('filter.company');
		$id .= ':' . $this->getState('filter.yld_scaling_factor_males');
		$id .= ':' . $this->getState('filter.yld_scaling_factor_females');
		$id .= ':' . $this->getState('filter.mortality_scaling_factor_males');
		$id .= ':' . $this->getState('filter.mortality_scaling_factor_females');
		$id .= ':' . $this->getState('filter.presenteeism_scaling_factor_males');
		$id .= ':' . $this->getState('filter.presenteeism_scaling_factor_females');

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
			$query->from($db->quoteName('#__costbenefitprojection_scaling_factor'));
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
				$query->update($db->quoteName('#__costbenefitprojection_scaling_factor'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
