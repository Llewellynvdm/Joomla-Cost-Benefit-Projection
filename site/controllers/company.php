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

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Company Controller
 */
class CostbenefitprojectionControllerCompany extends JControllerForm
{
	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	public function __construct($config = array())
	{
		$this->view_list = 'cpanel'; // safeguard for setting the return view listing to the default site view.
		parent::__construct($config);
	}

        /**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		// Access check.
		$access = JFactory::getUser()->authorise('company.access', 'com_costbenefitprojection');
		if (!$access)
		{
			return false;
		}
		// In the absense of better information, revert to the component permissions.
		return JFactory::getUser()->authorise('company.create', $this->option);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// get user object.
		$user		= JFactory::getUser();
		// get record id.
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

		// Access check.
		$access = ($user->authorise('company.access', 'com_costbenefitprojection.company.' . (int) $recordId) &&  $user->authorise('company.access', 'com_costbenefitprojection'));
		if (!$access)
		{
			return false;
		}

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('company.edit', 'com_costbenefitprojection.company.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				if ($user->authorise('company.edit.own', 'com_costbenefitprojection.company.' . $recordId))
				{
					// Now test the owner is the user.
					$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
					if (empty($ownerId))
					{
						// Need to do a lookup from the model.
						$record = $this->getModel()->getItem($recordId);

						if (empty($record))
						{
							return false;
						}
						$ownerId = $record->created_by;
					}

					// If the owner matches 'me' then allow.
					if ($ownerId == $user->id)
					{
						if ($user->authorise('company.edit.own', 'com_costbenefitprojection'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return $user->authorise('company.edit', $this->option);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   12.2
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl   = $this->input->get('tmpl');
		$layout = $this->input->get('layout', 'edit', 'string');

		$ref 	= $this->input->get('ref', 0, 'string');
		$refid 	= $this->input->get('refid', 0, 'int');

		// Setup redirect info.

		$append = '';

		if ($refid)
                {
			$append .= '&ref='.(string)$ref.'&refid='.(int)$refid;
		}
                elseif ($ref)
                {
			$append .= '&ref='.(string)$ref;
                }

		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		return $append;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Company', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=companies' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   12.2
	 */
	public function cancel($key = null)
	{
		// get the referal details
		$this->ref 		= $this->input->get('ref', 0, 'word');
		$this->refid 	= $this->input->get('refid', 0, 'int');

		$cancel = parent::cancel($key);

		if ($cancel)
		{
			if ($this->refid)
			{
				$redirect = '&view='.(string)$this->ref.'&layout=edit&id='.(int)$this->refid;

				// Redirect to the item screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . $redirect, false
					)
				);
			}
			elseif ($this->ref)
			{
				$redirect = '&view='.(string)$this->ref;

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . $redirect, false
					)
				);
			}
		}
		else
		{
			// Redirect to the items screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list, false
				)
			);
		}
		return $cancel;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		// get the referal details
		$this->ref 		= $this->input->get('ref', 0, 'word');
		$this->refid 	= $this->input->get('refid', 0, 'int');

                if ($this->ref || $this->refid)
                {
                        // to make sure the item is checkedin on redirect
                        $this->task = 'save';
                }

		$saved = parent::save($key, $urlVar);

		if ($this->refid && $saved)
		{
			$redirect = '&view='.(string)$this->ref.'&layout=edit&id='.(int)$this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref && $saved)
		{
			$redirect = '&view='.(string)$this->ref;

			// Redirect to the list screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		return $saved;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModel  &$model     The data model object.
	 * @param   array   $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		if ($validData['id']  >=  0)
		{
			// get user object
			$user = JFactory::getUser();
			// if id is 0 get id
			if (0 >=  (int) $validData['id'])
			{
				// Get the created by id
				$created_by = (isset($validData['created_by']) && $validData['created_by'] > 0) ? $validData['created_by'] : $user->id;
				// Get a db connection.
				$db = JFactory::getDbo();
				// Create a new query object.
				$query = $db->getQuery(true);
				// Select id of this company
				$query->select($db->quoteName(array('id')));
				$query->from($db->quoteName('#__costbenefitprojection_company'));
				$query->where($db->quoteName('name') . ' = '. $db->quote($validData['name']));
				$query->where($db->quoteName('email') . ' = '. $db->quote($validData['email']));
				$query->where($db->quoteName('country') . ' = '. (int) $validData['country']);
				$query->where($db->quoteName('service_provider') . ' = '. (int) $validData['service_provider']);
				$query->where($db->quoteName('created_by') . ' = '. (int) $created_by);
				if (isset($validData['created']))
				{
					$query->where($db->quoteName('created') . ' = '. $db->quote($validData['created']));
				}
				$db->setQuery($query);
				$db->execute();
				if ($db->getNumRows())
				{
					$validData['id'] = $db->loadResult();
				}
				else
				{
					return;
				}
			}
			// user setup if not set
			if (0  >=  (int) $validData['user'] && (int) $validData['id'] > 0)
			{
				$userIs = CostbenefitprojectionHelper::userIs($user->id);
				if (1 == $userIs)
				{
					// this is a company so just use its id
					$userId = $user->id;
					// add this user id to this company
					$validData['user'] = $userId;
					$model->save($validData);
				}
				else
				{
					// setup config array
					$newUser = array('name' => $validData['name'], 'email' => $validData['email']);
					$userId = CostbenefitprojectionHelper::createUser($newUser); 
					if (!is_int($userId))
					{
						$this->setMessage($userId, 'error');
					}
					else
					{
						// add this user id to this company
						$validData['user'] = $userId;
						$model->save($validData);
					}
				}
			}
			// only continue if we have a company id
			if ((int) $validData['id'] > 0)
			{
				// get params
				$params	= JComponentHelper::getParams('com_costbenefitprojection');
				// get all this users companies
				$hisCompanies = CostbenefitprojectionHelper::hisCompanies($validData['user']);
				if (CostbenefitprojectionHelper::checkArray($hisCompanies))
				{
					// set the user group based on the overall status of its companies
					$departments = CostbenefitprojectionHelper::getVars('company', $hisCompanies, 'id', 'department');
					if (in_array(2, $departments))
					{
						$memberGroups = $params->get('advancedmembergroup');
					}
					else
					{
						$memberGroups = $params->get('memberbasicgroup');
					}
				}
				else
				{
					// first company so act simply on this company department status
					if (2 == $validData['department'])
					{
						$memberGroups = $params->get('advancedmembergroup');
					}
					else
					{
						$memberGroups =  $params->get('memberbasicgroup');
					}
				}
				// update the user groups
				JUserHelper::setUserGroups((int)$validData['user'],(array)$memberGroups);

				// Get a db connection.
				$db = JFactory::getDbo();
				// Create a new query object.
				$query = $db->getQuery(true);
				// Select all records in scaling factors the belong to this company
				$query->select($db->quoteName(array('id','causerisk','published')));
				$query->from($db->quoteName('#__costbenefitprojection_scaling_factor'));
				$query->where($db->quoteName('company') . ' = '. (int) $validData['id']);
				$db->setQuery($query);
				$db->execute();
				if ($db->getNumRows())
				{
					// load the scaling factors already set
					$already = $db->loadObjectList();
					$publish = array();
					$archive = array();
					$bucket = array();
					foreach ($already as $scale)
					{
						if (CostbenefitprojectionHelper::checkArray($validData['causesrisks']))
						{
							if (in_array($scale->causerisk, $validData['causesrisks']) && $scale->published != 1)
							{
								// publish the scaling factor (update)
								$publish[$scale->id] = $scale->id;
							}
							elseif (!in_array($scale->causerisk, $validData['causesrisks']))
							{
								// archive the scaling factor (update)
								$archive[$scale->id] = $scale->id;
							}
							$bucket[] = $scale->causerisk;
						}
						else
						{
							// archive the scaling factor (update)
							$archive[$scale->id] = $scale->id;
						}
					}
					// update the needed records
					$types = array('publish' => 1,'archive' => 2);
					foreach ($types as $type => $int)
					{
						if (CostbenefitprojectionHelper::checkArray(${$type}))
						{
							foreach (${$type} as $id)
							{
								$query = $db->getQuery(true);
								// Fields to update.
								$fields = array(
									$db->quoteName('published') . ' = ' . (int) $int
								);
								// Conditions for which records should be updated.
								$conditions = array(
									$db->quoteName('id') . ' = ' . (int) $id
								);

								$query->update($db->quoteName('#__costbenefitprojection_scaling_factor'))->set($fields)->where($conditions);
								$db->setQuery($query);
								$db->execute();
							}
						}
					}
				}
				if (CostbenefitprojectionHelper::checkArray($validData['causesrisks']))
				{
					// remove those already set from the saved list of causesrisks
					if (CostbenefitprojectionHelper::checkArray($bucket))
					{
						$insert = array();
						foreach ($validData['causesrisks'] as $causerisk)
						{
							if (!in_array($causerisk,$bucket))
							{
								$insert[] = $causerisk;
							}
						}
					}
					else
					{
						$insert = $validData['causesrisks'];
					}
				}
				// insert the new records
				if (CostbenefitprojectionHelper::checkArray($insert))
				{
					$created	= $db->quote(JFactory::getDate()->toSql());
					$created_by	= JFactory::getUser()->get('id');
					$company	= $validData['id'];

					// Create a new query object.
					$query = $db->getQuery(true);
					// Insert columns.
					$columns = array(
						'causerisk', 'company', 'mortality_scaling_factor_females', 
						'mortality_scaling_factor_males', 'presenteeism_scaling_factor_females', 
						'presenteeism_scaling_factor_males', 'yld_scaling_factor_females', 
						'yld_scaling_factor_males', 'published', 
						'created_by', 'created');
					// setup the values
					$values = array();
					foreach ($insert as $new)
					{
						$array = array($new,$company,1,1,1,1,1,1,1,$created_by,$created);
						$values[] = implode(',',$array);
					}
					// Prepare the insert query.
					$query
						->insert($db->quoteName('#__costbenefitprojection_scaling_factor'))
						->columns($db->quoteName($columns))
						->values(implode('), (', $values));

					// Set the query using our newly populated query object and execute it.
					$db->setQuery($query);
					$done = $db->execute();
					if ($done)
					{
						// we must set the assets
						foreach ($insert as $causerisk)
						{
							// get all the ids. Create a new query object.
							$query = $db->getQuery(true);
							$query->select($db->quoteName(array('id')));
							$query->from($db->quoteName('#__costbenefitprojection_scaling_factor'));
							$query->where($db->quoteName('causerisk') . ' = '. (int) $causerisk);
							$query->where($db->quoteName('company') . ' = '. (int) $company);
							$db->setQuery($query);
							$db->execute();
							if ($db->getNumRows())
							{
								$aId = $db->loadResult();
								// make sure the access of asset is set
								CostbenefitprojectionHelper::setAsset($aId,'scaling_factor');
							}
						}
					}
				}
			}
		}

		return;
	}

}
