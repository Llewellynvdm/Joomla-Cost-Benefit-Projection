<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		company.php
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
 * Costbenefitprojection Company Model
 */
class CostbenefitprojectionModelCompany extends JModelAdmin
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
	public $typeAlias = 'com_costbenefitprojection.company';

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
	public function getTable($type = 'company', $prefix = 'CostbenefitprojectionTable', $config = array())
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
				// JSON Decode causesrisks.
				$item->causesrisks = json_decode($item->causesrisks);
			}

			// Get the advanced encription key.
			$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
			// Get the encription object.
			$advanced = new FOFEncryptAes($advancedkey, 256);

			if (!empty($item->medical_turnovers_females) && $advancedkey && !is_numeric($item->medical_turnovers_females) && $item->medical_turnovers_females === base64_encode(base64_decode($item->medical_turnovers_females, true)))
			{
				// advanced decript data medical_turnovers_females.
				$item->medical_turnovers_females = rtrim($advanced->decryptString($item->medical_turnovers_females), "\0");
			}

			if (!empty($item->females) && $advancedkey && !is_numeric($item->females) && $item->females === base64_encode(base64_decode($item->females, true)))
			{
				// advanced decript data females.
				$item->females = rtrim($advanced->decryptString($item->females), "\0");
			}

			if (!empty($item->sick_leave_males) && $advancedkey && !is_numeric($item->sick_leave_males) && $item->sick_leave_males === base64_encode(base64_decode($item->sick_leave_males, true)))
			{
				// advanced decript data sick_leave_males.
				$item->sick_leave_males = rtrim($advanced->decryptString($item->sick_leave_males), "\0");
			}

			if (!empty($item->medical_turnovers_males) && $advancedkey && !is_numeric($item->medical_turnovers_males) && $item->medical_turnovers_males === base64_encode(base64_decode($item->medical_turnovers_males, true)))
			{
				// advanced decript data medical_turnovers_males.
				$item->medical_turnovers_males = rtrim($advanced->decryptString($item->medical_turnovers_males), "\0");
			}

			if (!empty($item->total_salary) && $advancedkey && !is_numeric($item->total_salary) && $item->total_salary === base64_encode(base64_decode($item->total_salary, true)))
			{
				// advanced decript data total_salary.
				$item->total_salary = rtrim($advanced->decryptString($item->total_salary), "\0");
			}

			if (!empty($item->sick_leave_females) && $advancedkey && !is_numeric($item->sick_leave_females) && $item->sick_leave_females === base64_encode(base64_decode($item->sick_leave_females, true)))
			{
				// advanced decript data sick_leave_females.
				$item->sick_leave_females = rtrim($advanced->decryptString($item->sick_leave_females), "\0");
			}

			if (!empty($item->total_healthcare) && $advancedkey && !is_numeric($item->total_healthcare) && $item->total_healthcare === base64_encode(base64_decode($item->total_healthcare, true)))
			{
				// advanced decript data total_healthcare.
				$item->total_healthcare = rtrim($advanced->decryptString($item->total_healthcare), "\0");
			}

			if (!empty($item->males) && $advancedkey && !is_numeric($item->males) && $item->males === base64_encode(base64_decode($item->males, true)))
			{
				// advanced decript data males.
				$item->males = rtrim($advanced->decryptString($item->males), "\0");
			}
			
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_costbenefitprojection.company');
			}
		}
		$this->companyvvvv = $item->id;
		$this->companyvvvw = $item->id;

		return $item;
	}

	/**
	* Method to get list data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getVwcscaling_factors()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the costbenefitprojection_scaling_factor table
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

		// From the costbenefitprojection_causerisk table.
		$query->select($db->quoteName('g.name','causerisk_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_causerisk', 'g') . ' ON (' . $db->quoteName('a.causerisk') . ' = ' . $db->quoteName('g.id') . ')');

		// From the costbenefitprojection_company table.
		$query->select($db->quoteName('h.name','company_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_company', 'h') . ' ON (' . $db->quoteName('a.company') . ' = ' . $db->quoteName('h.id') . ')');

		// Filter by companyvvvv global.
		$companyvvvv = $this->companyvvvv;
		if (is_numeric($companyvvvv ))
		{
			$query->where('a.company = ' . (int) $companyvvvv );
		}
		elseif (is_string($companyvvvv))
		{
			$query->where('a.company = ' . $db->quote($companyvvvv));
		}
		else
		{
			$query->where('a.company = -5');
		}

		// Order the results by ordering
		$query->order('a.ordering  ASC');

		// Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// set values to display correctly.
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				// get user object.
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
			return $items;
		}
		return false;
	}

	/**
	* Method to get list data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getVwdinterventions()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the costbenefitprojection_intervention table
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

		// From the costbenefitprojection_company table.
		$query->select($db->quoteName('g.name','company_name'));
		$query->join('LEFT', $db->quoteName('#__costbenefitprojection_company', 'g') . ' ON (' . $db->quoteName('a.company') . ' = ' . $db->quoteName('g.id') . ')');

		// Filter by companyvvvw global.
		$companyvvvw = $this->companyvvvw;
		if (is_numeric($companyvvvw ))
		{
			$query->where('a.company = ' . (int) $companyvvvw );
		}
		elseif (is_string($companyvvvw))
		{
			$query->where('a.company = ' . $db->quote($companyvvvw));
		}
		else
		{
			$query->where('a.company = -5');
		}

		// Order the results by ordering
		$query->order('a.ordering  ASC');

		// Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// set values to display correctly.
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				// get user object.
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

			// set selection value to a translatable value
			if (CostbenefitprojectionHelper::checkArray($items))
			{
				foreach ($items as $nr => &$item)
				{
					// convert type
					$item->type = $this->selectionTranslationVwdinterventions($item->type, 'type');
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
	public function selectionTranslationVwdinterventions($value,$name)
	{
		// Array of type language strings
		if ($name == 'type')
		{
			$typeArray = array(
				1 => 'COM_COSTBENEFITPROJECTION_INTERVENTION_SINGLE',
				2 => 'COM_COSTBENEFITPROJECTION_INTERVENTION_CLUSTER'
			);
			// Now check if value is found in this array
			if (isset($typeArray[$value]) && CostbenefitprojectionHelper::checkString($typeArray[$value]))
			{
				return $typeArray[$value];
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
	{		// Get the form.
		$form = $this->loadForm('com_costbenefitprojection.company', 'company', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = JFactory::getUser();

		// Check for existing item.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('company.edit.state', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.state', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// Modify the form based on Edit Creaded By access controls.
		if ($id != 0 && (!$user->authorise('company.edit.created_by', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.created_by', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// Modify the form based on Edit Creaded Date access controls.
		if ($id != 0 && (!$user->authorise('company.edit.created', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.created', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}
		// Modify the form based on Edit Email access controls.
		if ($id != 0 && (!$user->authorise('company.edit.email', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.email', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('email', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('email', 'readonly', 'true');
			if (!$form->getValue('email'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('email', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('email', 'required', 'false');
			}
		}
		// Modify the form based on Edit User access controls.
		if ($id != 0 && (!$user->authorise('company.edit.user', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.user', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('user', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('user', 'readonly', 'true');
			if (!$form->getValue('user'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('user', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('user', 'required', 'false');
			}
		}
		// Modify the form based on Edit Department access controls.
		if ($id != 0 && (!$user->authorise('company.edit.department', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.department', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('department', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('department', 'readonly', 'true');
			// Disable radio button for display.
			$class = $form->getFieldAttribute('department', 'class', '');
			$form->setFieldAttribute('department', 'class', $class.' disabled no-click');
			if (!$form->getValue('department'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('department', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('department', 'required', 'false');
			}
		}
		// Modify the form based on Edit Country access controls.
		if ($id != 0 && (!$user->authorise('company.edit.country', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.country', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('country', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('country', 'readonly', 'true');
			if (!$form->getValue('country'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('country', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('country', 'required', 'false');
			}
		}
		// Modify the form based on Edit Service Provider access controls.
		if ($id != 0 && (!$user->authorise('company.edit.service_provider', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.service_provider', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('service_provider', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('service_provider', 'readonly', 'true');
			if (!$form->getValue('service_provider'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('service_provider', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('service_provider', 'required', 'false');
			}
		}
		// Modify the form based on Edit Per access controls.
		if ($id != 0 && (!$user->authorise('company.edit.per', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.per', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('per', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('per', 'readonly', 'true');
			// Disable radio button for display.
			$class = $form->getFieldAttribute('per', 'class', '');
			$form->setFieldAttribute('per', 'class', $class.' disabled no-click');
			if (!$form->getValue('per'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('per', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('per', 'required', 'false');
			}
		}
		// Modify the form based on Edit Percentfemale access controls.
		if ($id != 0 && (!$user->authorise('company.edit.percentfemale', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.percentfemale', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('percentfemale', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('percentfemale', 'readonly', 'true');
			// Disable radio button for display.
			$class = $form->getFieldAttribute('percentfemale', 'class', '');
			$form->setFieldAttribute('percentfemale', 'class', $class.' disabled no-click');
			if (!$form->getValue('percentfemale'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('percentfemale', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('percentfemale', 'required', 'false');
			}
		}
		// Modify the form based on Edit Causesrisks access controls.
		if ($id != 0 && (!$user->authorise('company.edit.causesrisks', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.causesrisks', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('causesrisks', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('causesrisks', 'readonly', 'true');
			if (!$form->getValue('causesrisks'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('causesrisks', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('causesrisks', 'required', 'false');
			}
		}
		// Modify the form based on Edit Datayear access controls.
		if ($id != 0 && (!$user->authorise('company.edit.datayear', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.datayear', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('datayear', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('datayear', 'readonly', 'true');
			if (!$form->getValue('datayear'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('datayear', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('datayear', 'required', 'false');
			}
		}
		// Modify the form based on Edit Percentmale access controls.
		if ($id != 0 && (!$user->authorise('company.edit.percentmale', 'com_costbenefitprojection.company.' . (int) $id))
			|| ($id == 0 && !$user->authorise('company.edit.percentmale', 'com_costbenefitprojection')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('percentmale', 'disabled', 'true');
			// Disable fields for display.
			$form->setFieldAttribute('percentmale', 'readonly', 'true');
			// Disable radio button for display.
			$class = $form->getFieldAttribute('percentmale', 'class', '');
			$form->setFieldAttribute('percentmale', 'class', $class.' disabled no-click');
			if (!$form->getValue('percentmale'))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('percentmale', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('percentmale', 'required', 'false');
			}
		}
		// Only load these values if no id is found
		if (0 == $id)
		{
			// Set redirected field name
			$redirectedField = $jinput->get('ref', null, 'STRING');
			// Set redirected field value
			$redirectedValue = $jinput->get('refid', 0, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
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
		return 'administrator/components/com_costbenefitprojection/models/forms/company.js';
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
			// The record has been set. Check the record permissions.
			return $user->authorise('company.delete', 'com_costbenefitprojection.company.' . (int) $record->id);
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
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('company.edit.state', 'com_costbenefitprojection.company.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// In the absense of better information, revert to the component permissions.
		return $user->authorise('company.edit.state', 'com_costbenefitprojection');
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
		// Check specific edit permission then general edit permission.
		$user = JFactory::getUser();
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this company can be edited
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (!CostbenefitprojectionHelper::checkArray($companies) || !in_array($recordId,$companies))
			{
				return false;
			}
		}
		// ensure lockdown
		$userIs = CostbenefitprojectionHelper::userIs($user->id);
		if (1 != $userIs && ! CostbenefitprojectionHelper::accessCompany($recordId))
		{
			// this company is locked
			return false;
		}
		return $user->authorise('company.edit', 'com_costbenefitprojection.company.'. ((int) isset($data[$key]) ? $data[$key] : 0)) or $user->authorise('company.edit',  'com_costbenefitprojection');
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
			if ($table->created_by == 0 || empty($table->created_by))
			{
				$table->created_by = $user->id;
			}
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__costbenefitprojection_company'));
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
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.company.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	* Method to validate the form data.
	*
	* @param   JForm   $form   The form to validate against.
	* @param   array   $data   The data to validate.
	* @param   string  $group  The name of the field group to validate.
	*
	* @return  mixed  Array of filtered data if valid, false otherwise.
	*
	* @see     JFormRule
	* @see     JFilterInput
	* @since   12.2
	*/
	public function validate($form, $data, $group = null)
	{
		// check if the not_required field is set
		if (CostbenefitprojectionHelper::checkString($data['not_required']))
		{
			$requiredFields = (array) explode(',',(string) $data['not_required']);
			$requiredFields = array_unique($requiredFields);
			// now change the required field attributes value
			foreach ($requiredFields as $requiredField)
			{
				// make sure there is a string value
				if (CostbenefitprojectionHelper::checkString($requiredField))
				{
					// change to false
					$form->setFieldAttribute($requiredField, 'required', 'false');
					// also clear the data set
					$data[$requiredField] = '';
				}
			}
		}
		return parent::validate($form, $data, $group);
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
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   12.2
	 */
	public function delete(&$pks)
	{
		if (!parent::delete($pks))
		{
			return false;
		}
		
		return true;
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
		$this->canDo			= CostbenefitprojectionHelper::getActions('company');
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
			// Set some needed variables.
			$this->user 		= JFactory::getUser();
			$this->table 		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= CostbenefitprojectionHelper::getActions('company');
		}

		if (!$this->canDo->get('company.create') && !$this->canDo->get('company.batch'))
		{
			return false;
		}

		if (!$this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this company can be copied
			$companies = CostbenefitprojectionHelper::hisCompanies($this->user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				foreach ($pks as $nr => $pk)
				{
					if (!in_array($pk,$companies))
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

		// get list of uniqe fields
		$uniqeFields = $this->getUniqeFields();
		// remove move_copy from array
		unset($values['move_copy']);

		// make sure published is set
		if (!isset($values['published']))
		{
			$values['published'] = 0;
		}
		elseif (isset($values['published']) && !$this->canDo->get('company.edit.state'))
		{
				$values['published'] = 0;
		}

		$newIds = array();

		// Parent exists so let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// only allow copy if user may edit this item.

			if (!$this->user->authorise('company.edit', $contexts[$pk]))

			{

				// Not fatal error

				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));

				continue;

			}

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			$this->table->name = $this->generateUniqe('name',$this->table->name);

			// insert all set values
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

			// update all uniqe fields
			if (CostbenefitprojectionHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$this->table->$uniqeField = $this->generateUniqe($uniqeField,$this->table->$uniqeField);
				}
			}

			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// TODO: Deal with ordering?
			// $this->table->ordering	= 1;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// Clean the cache
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
			// Set some needed variables.
			$this->user		= JFactory::getUser();
			$this->table		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= CostbenefitprojectionHelper::getActions('company');
		}

		if (!$this->canDo->get('company.edit') && !$this->canDo->get('company.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		if (!$this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this company can be moved
			$companies = CostbenefitprojectionHelper::hisCompanies($this->user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				foreach ($pks as $nr => $pk)
				{
					if (!in_array($pk,$companies))
					{
						unset($pks[$nr]);
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

		// make sure published only updates if user has the permission.
		if (isset($values['published']) && !$this->canDo->get('company.edit.state'))
		{
			unset($values['published']);
		}
		// remove move_copy from array
		unset($values['move_copy']);

		// Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('company.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// insert all set values.
			if (CostbenefitprojectionHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					// Do special action for access.
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


			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// Clean the cache
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

		// Set the causesrisks string to JSON string.
		if (isset($data['causesrisks']))
		{
			$data['causesrisks'] = (string) json_encode($data['causesrisks']);
		}

		// Get the advanced encription key.
		$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
		// Get the encription object
		$advanced = new FOFEncryptAes($advancedkey, 256);

		// Encript data medical_turnovers_females.
		if (isset($data['medical_turnovers_females']) && $advancedkey)
		{
			$data['medical_turnovers_females'] = $advanced->encryptString($data['medical_turnovers_females']);
		}

		// Encript data females.
		if (isset($data['females']) && $advancedkey)
		{
			$data['females'] = $advanced->encryptString($data['females']);
		}

		// Encript data sick_leave_males.
		if (isset($data['sick_leave_males']) && $advancedkey)
		{
			$data['sick_leave_males'] = $advanced->encryptString($data['sick_leave_males']);
		}

		// Encript data medical_turnovers_males.
		if (isset($data['medical_turnovers_males']) && $advancedkey)
		{
			$data['medical_turnovers_males'] = $advanced->encryptString($data['medical_turnovers_males']);
		}

		// Encript data total_salary.
		if (isset($data['total_salary']) && $advancedkey)
		{
			$data['total_salary'] = $advanced->encryptString($data['total_salary']);
		}

		// Encript data sick_leave_females.
		if (isset($data['sick_leave_females']) && $advancedkey)
		{
			$data['sick_leave_females'] = $advanced->encryptString($data['sick_leave_females']);
		}

		// Encript data total_healthcare.
		if (isset($data['total_healthcare']) && $advancedkey)
		{
			$data['total_healthcare'] = $advanced->encryptString($data['total_healthcare']);
		}

		// Encript data males.
		if (isset($data['males']) && $advancedkey)
		{
			$data['males'] = $advanced->encryptString($data['males']);
		}

		// make sure new company does not get locked
		$user = JFactory::getUser();
		if ($data['id'] == 0 && !$user->authorise('company.edit.per', 'com_costbenefitprojection'))
		{
			$data['per'] = 1;
		}
        
		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// Alter the uniqe field for save as copy
		if ($input->get('task') == 'save2copy')
		{
			// Automatic handling of other uniqe fields
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
	* @param   string   $title        The title.
	*
	* @return	array  Contains the modified title and alias.
	*
	*/
	protected function _generateNewTitle($title)
	{

		// Alter the title
		$table = $this->getTable();

		while ($table->load(array('title' => $title)))
		{
			$title = JString::increment($title);
		}

		return $title;
	}
}
