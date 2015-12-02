<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.9
	@build			2nd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		country.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Costbenefitprojection Country Model
 */
class CostbenefitprojectionModelCountry extends JModelAdmin
{    
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION';
    
	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_costbenefitprojection.country';

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'country', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (!empty($item->params))
			{
				// Convert the params field to an array.
				$registry = new Registry;
				$registry->loadString($item->params);
				$item->params = $registry->toArray();
			}

			if (!empty($item->metadata))
			{
				// Convert the metadata field to an array.
				$registry = new Registry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

			if (!empty($item->causesrisks))
			{
				// [4005] JSON Decode causesrisks.
				$item->causesrisks = json_decode($item->causesrisks);
			}
			
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_costbenefitprojection.country');
			}
		}
		$this->countrygtcm = $item->id;
		$this->countryhkwg = $item->id;

		return $item;
	}

	/**
	* Method to get list data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getMqkservice_providers()
	{
		// [6954] Get the user object.
		$user = JFactory::getUser();
		// [6956] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [6959] Select some fields
		$query->select('a.*');

		// [6966] From the costbenefitprojection_service_provider table
		$query->from($db->quoteName('#__costbenefitprojection_service_provider', 'a'));

		// Filter the providers (admin sees all)
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$serviceProviders = CostbenefitprojectionHelper::hisServiceProviders($user->id);
			if (CostbenefitprojectionHelper::checkArray($serviceProviders))
			{
				$serviceProviders = implode(',',$serviceProviders);
				// only load this users service providers
				$query->where('a.id IN (' . $serviceProviders . ')');
			}
			else
			{
				// don't allow user to see any service providers
				$query->where('a.id = -4');
			}
		}

		// [7559] From the users table.
		$query->select($db->quoteName('g.name','user_name'));
		$query->join('LEFT', $db->quoteName('#__users', 'g') . ' ON (' . $db->quoteName('a.user') . ' = ' . $db->quoteName('g.id') . ')');

		// [7559] From the costbenefitprojection_country table.
		$query->select($db->quoteName('h.name','country_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_country', 'h') . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('h.id') . ')');

		// [6982] Filter by countrygtcm global.
		$countrygtcm = $this->countrygtcm;
		if (is_numeric($countrygtcm ))
		{
			$query->where('a.country = ' . (int) $countrygtcm );
		}
		elseif (is_string($countrygtcm))
		{
			$query->where('a.country = ' . $db->quote($countrygtcm));
		}
		else
		{
			$query->where('a.country = -5');
		}

		// [7014] Order the results by ordering
		$query->order('a.ordering  ASC');

		// [7016] Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// [10620] set values to display correctly.
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				// [10623] get user object.
				$user = JFactory::getUser();
				foreach ($items as $nr => &$item)
				{
					$access = ($user->authorise('service_provider.access', 'com_costbenefitprojection.service_provider.' . (int) $item->id) && $user->authorise('service_provider.access', 'com_costbenefitprojection'));
					if (!$access)
					{
						unset($items[$nr]);
						continue;
					}

				}
			}
			return $items;
		}
		return false;
	}

	/**
	* Method to get list data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getLqmcompanies()
	{
		// [6954] Get the user object.
		$user = JFactory::getUser();
		// [6956] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [6959] Select some fields
		$query->select('a.*');

		// [6966] From the costbenefitprojection_company table
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

		// [7559] From the users table.
		$query->select($db->quoteName('g.name','user_name'));
		$query->join('LEFT', $db->quoteName('#__users', 'g') . ' ON (' . $db->quoteName('a.user') . ' = ' . $db->quoteName('g.id') . ')');

		// [7559] From the costbenefitprojection_country table.
		$query->select($db->quoteName('h.name','country_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_country', 'h') . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('h.id') . ')');

		// [7559] From the costbenefitprojection_service_provider table.
		$query->select($db->quoteName('i.user','serviceprovider_user'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_service_provider', 'i') . ' ON (' . $db->quoteName('a.serviceprovider') . ' = ' . $db->quoteName('i.id') . ')');

		// [6982] Filter by countryhkwg global.
		$countryhkwg = $this->countryhkwg;
		if (is_numeric($countryhkwg ))
		{
			$query->where('a.country = ' . (int) $countryhkwg );
		}
		elseif (is_string($countryhkwg))
		{
			$query->where('a.country = ' . $db->quote($countryhkwg));
		}
		else
		{
			$query->where('a.country = -5');
		}

		// [6999] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [7002] Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// [7007] Implement View Level Access
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// [7014] Order the results by ordering
		$query->order('a.ordering  ASC');

		// [7016] Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// [10620] set values to display correctly.
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				// [10623] get user object.
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

			// [10886] set selection value to a translatable value
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				foreach ($items as $nr => &$item)
				{
					// [10893] convert department
					$item->department = $this->selectionTranslationLqmcompanies($item->department, 'department');
					// [10893] convert per
					$item->per = $this->selectionTranslationLqmcompanies($item->per, 'per');
				}
			}

			return $items;
		}
		return false;
	}

	/**
	* Method to convert selection values to translatable string.
	*
	* @return translatable string
	*/
	public function selectionTranslationLqmcompanies($value,$name)
	{
		// [10919] Array of department language strings
		if ($name == 'department')
		{
			$departmentArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_BASIC',
				2 => 'COM_COSTBENEFITPROJECTION_COMPANY_ADVANCED'
			);
			// [10950] Now check if value is found in this array
			if (isset($departmentArray[$value]) && CostbenefitprojectionHelper::checkString($departmentArray[$value]))
			{
				return $departmentArray[$value];
			}
		}
		// [10919] Array of per language strings
		if ($name == 'per')
		{
			$perArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_COMPANY_OPEN',
				0 => 'COM_COSTBENEFITPROJECTION_COMPANY_LOCKED'
			);
			// [10950] Now check if value is found in this array
			if (isset($perArray[$value]) && CostbenefitprojectionHelper::checkString($perArray[$value]))
			{
				return $perArray[$value];
			}
		}
		return $value;
	} 

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{		// [9575] Get the form.
		$form = $this->loadForm('com_costbenefitprojection.country', 'country', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// [9660] The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// [9665] The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = JFactory::getUser();

		// [9671] Check for existing item.
		// [9672] Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('country.edit.state', 'com_costbenefitprojection.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.state', 'com_costbenefitprojection')))
		{
			// [9685] Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// [9688] Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// [9693] Modify the form based on Edit Creaded By access controls.
		if (!$user->authorise('core.edit.created_by', 'com_costbenefitprojection'))
		{
			// [9705] Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// [9707] Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// [9709] Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// [9712] Modify the form based on Edit Creaded Date access controls.
		if (!$user->authorise('core.edit.created', 'com_costbenefitprojection'))
		{
			// [9724] Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// [9726] Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	script files
	 */
	public function getScript()
	{
		return 'administrator/components/com_costbenefitprojection/models/forms/country.js';
	}
    
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published != -2)
			{
				return;
			}

			$user = JFactory::getUser();
			// [9876] The record has been set. Check the record permissions.
			return $user->authorise('country.delete', 'com_costbenefitprojection.country.' . (int) $record->id);
		}
		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		$recordId	= (!empty($record->id)) ? $record->id : 0;

		if ($recordId)
		{
			// [9963] The record has been set. Check the record permissions.
			$permission = $user->authorise('country.edit.state', 'com_costbenefitprojection.country.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// [9980] In the absense of better information, revert to the component permissions.
		return $user->authorise('country.edit.state', 'com_costbenefitprojection');
	}
    
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// [9788] Check specific edit permission then general edit permission.
		$user = JFactory::getUser();
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this country can be edited
			$is = CostbenefitprojectionHelper::userIs($user->id);
			$countries = CostbenefitprojectionHelper::hisCountries($user->id);
			if ((3 != $is) || !CostbenefitprojectionHelper::checkArray($countries) || !in_array($recordId,$countries))
			{
				return false;
			}
		}
		return $user->authorise('country.edit', 'com_costbenefitprojection.country.'. ((int) isset($data[$key]) ? $data[$key] : 0)) or $user->authorise('country.edit',  'com_costbenefitprojection');
	}
    
	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if (isset($table->name))
		{
			$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
		}
		
		if (isset($table->alias) && empty($table->alias))
		{
			$table->generateAlias();
		}
		
		if (empty($table->id))
		{
			$table->created = $date->toSql();
			// set the user
			if ($table->created_by == 0)
			{
				$table->created_by = $user->id;
			}
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__costbenefitprojection_country'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			$table->modified = $date->toSql();
			$table->modified_by = $user->id;
		}
        
		if (!empty($table->id))
		{
			// Increment the items version number.
			$table->version++;
		}
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.country.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	} 

	/**
	 * Method to get the unique fields of this table.
	 *
	 * @return  mixed  An array of field names, boolean false if none is set.
	 *
	 * @since   3.0
	 */
	protected function getUniqeFields()
	{
		return false;
	}
    
	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		// Set some needed variables.
		$this->user			= JFactory::getUser();
		$this->table			= $this->getTable();
		$this->tableClassName		= get_class($this->table);
		$this->contentType		= new JUcmType;
		$this->type			= $this->contentType->getTypeByTable($this->tableClassName);
		$this->canDo			= CostbenefitprojectionHelper::getActions('country');
		$this->batchSet			= true;

		if (!$this->canDo->get('core.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}
        
		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
		}

		$this->tagsObserver = $this->table->getObserverOfClass('JTableObserverTags');

		if (!empty($commands['move_copy']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands, $pks, $contexts);

				if (is_array($result))
				{
					foreach ($result as $old => $new)
					{
						$contexts[$new] = $contexts[$old];
					}
					$pks = array_values($result);
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands, $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $values    The new values.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since	12.2
	 */
	protected function batchCopy($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [4941] Set some needed variables.
			$this->user 		= JFactory::getUser();
			$this->table 		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= CostbenefitprojectionHelper::getActions('country');
		}

		if (!$this->canDo->get('country.create') && !$this->canDo->get('country.batch'))
		{
			return false;
		}

		if (!$this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this country can be copied
			$is = CostbenefitprojectionHelper::userIs($user->id);
			$countries = CostbenefitprojectionHelper::hisCountries($this->user->id);
			if ((3 == $is) && CostbenefitprojectionHelper::checkArray($countries))
			{
				foreach ($pks as $nr => $pk)
				{
					if (!in_array($pk,$countries))
					{
						unset($pks[$nr]);
					}
				}
	
				if (empty($pks))
				{
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				
				return false;
			}
		}


		// [4961] get list of uniqe fields
		$uniqeFields = $this->getUniqeFields();
		// [4963] remove move_copy from array
		unset($values['move_copy']);

		// [4966] make sure published is set
		if (!isset($values['published']))
		{
			$values['published'] = 0;
		}
		elseif (isset($values['published']) && !$this->canDo->get('country.edit.state'))
		{
				$values['published'] = 0;
		}

		$newIds = array();

		// [5003] Parent exists so let's proceed
		while (!empty($pks))
		{
			// [5006] Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// [5011] only allow copy if user may edit this item.

			if (!$this->user->authorise('country.edit', $contexts[$pk]))

			{

				// [5021] Not fatal error

				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));

				continue;

			}

			// [5026] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [5031] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [5038] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			list($this->table->name, $this->table->alias) = $this->_generateNewTitle($this->table->alias, $this->table->name);

			// [5074] insert all set values
			if (CostbenefitprojectionHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					if (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}

			// [5086] update all uniqe fields
			if (CostbenefitprojectionHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$this->table->$uniqeField = $this->generateUniqe($uniqeField,$this->table->$uniqeField);
				}
			}

			// [5095] Reset the ID because we are making a copy
			$this->table->id = 0;

			// [5098] TODO: Deal with ordering?
			// [5099] $this->table->ordering	= 1;

			// [5101] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [5114] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// [5122] Get the new item ID
			$newId = $this->table->get('id');

			// [5125] Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// [5129] Clean the cache
		$this->cleanCache();

		return $newIds;
	} 

	/**
	 * Batch move items to a new category
	 *
	 * @param   integer  $value     The new category ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since	12.2
	 */
	protected function batchMove($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [4743] Set some needed variables.
			$this->user		= JFactory::getUser();
			$this->table		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= CostbenefitprojectionHelper::getActions('country');
		}

		if (!$this->canDo->get('country.edit') && !$this->canDo->get('country.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		if (!$this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this country can be moved
			$is = CostbenefitprojectionHelper::userIs($user->id);
			$countries = CostbenefitprojectionHelper::hisCountries($this->user->id);
			if ((3 == $is) && CostbenefitprojectionHelper::checkArray($countries))
			{
				foreach ($pks as $nr => $pk)
				{
					if (!in_array($pk,$countries))
					{
						unset($pks[$nr]);;
					}
				}
	
				if (empty($pks))
				{
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', 0));
	
					return false;
				}
			}
			else
			{
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', 0));

				return false;
			}
		}


		// [4765] make sure published only updates if user has the permission.
		if (isset($values['published']) && !$this->canDo->get('country.edit.state'))
		{
			unset($values['published']);
		}
		// [4778] remove move_copy from array
		unset($values['move_copy']);

		// [4799] Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('country.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			// [4816] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [4821] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [4828] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// [4834] insert all set values.
			if (CostbenefitprojectionHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					// [4839] Do special action for access.
					if ('access' == $key && strlen($value) > 0)
					{
						$this->table->$key = $value;
					}
					elseif (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}


			// [4851] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [4864] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// [4873] Clean the cache
		$this->cleanCache();

		return true;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input	= JFactory::getApplication()->input;
		$filter	= JFilterInput::getInstance();
        
		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
            
			$metadata = new JRegistry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		} 

		// [4115] Set the causesrisks string to JSON string.
		if (isset($data['causesrisks']))
		{
			$data['causesrisks'] = (string) json_encode($data['causesrisks']);
		}
        
		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// [5155] Alter the name for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['name'] == $origTable->name)
			{
				list($name, $alias) = $this->_generateNewTitle($data['alias'], $data['name']);
				$data['name'] = $name;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}

		// [5182] Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null)
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['name']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
				}

				$table = JTable::getInstance('country', 'costbenefitprojectionTable');

				if ($table->load(array('alias' => $data['alias'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_SAVE_WARNING');
				}

				list($name, $alias) = $this->_generateNewTitle($data['alias'], $data['name']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		// [5221] Alter the uniqe field for save as copy
		if ($input->get('task') == 'save2copy')
		{
			// [5224] Automatic handling of other uniqe fields
			$uniqeFields = $this->getUniqeFields();
			if (CostbenefitprojectionHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$data[$uniqeField] = $this->generateUniqe($uniqeField,$data[$uniqeField]);
				}
			}
		}
		
		if (parent::save($data))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Method to generate a uniqe value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 *
	 * @return  string  New value.
	 *
	 * @since   3.0
	 */
	protected function generateUniqe($field,$value)
	{

		// set field value uniqe 
		$table = $this->getTable();

		while ($table->load(array($field => $value)))
		{
			$value = JString::increment($value);
		}

		return $value;
	}

	/**
	* Method to change the title & alias.
	*
	* @param   string   $alias        The alias.
	* @param   string   $title        The title.
	*
	* @return	array  Contains the modified title and alias.
	*
	*/
	protected function _generateNewTitle($alias, $title)
	{

		// [5255] Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}
}
