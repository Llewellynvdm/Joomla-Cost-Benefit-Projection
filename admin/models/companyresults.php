<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companyresults.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * Costbenefitprojection Companyresults Model
 */
class CostbenefitprojectionModelCompanyresults extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_costbenefitprojection.companyresults';

        /**
	 * Model user data.
	 *
	 * @var        strings
	 */
        protected $user;
        protected $userId;
        protected $guest;
        protected $groups;
        protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$this->app	= JFactory::getApplication();
		$this->input 	= $this->app->input;
		// Get the item main id
		$id		= $this->input->getInt('id', null);
		$this->setState('companyresults.id', $id);

		// Load the parameters.
		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$this->user	= JFactory::getUser();
                // check if this user has permission to access item
                if (!$this->user->authorise('companyresults.access', 'com_costbenefitprojection'))
                {
			JError::raiseWarning(500, JText::_('Not authorised!'));
			// redirect away if not a correct (TODO for now we go to default view)
			JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection');
			return false;
                }
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->initSet		= true;

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('companyresults.id');

		if (!$this->user->authorise('core.options', 'com_costbenefitprojection'))
		{
			// make absolutely sure that this company can be viewed
			$companies = CostbenefitprojectionHelper::hisCompanies($this->userId);
			if (!CostbenefitprojectionHelper::checkArray($companies) || !in_array($pk,$companies))
			{
				JError::raiseWarning(500, JText::_('Access denied!'));
				// redirect away if not a correct (TODO for now we go to default view)
				$app = JFactory::getApplication();
				if ($app->isAdmin())
				{
					JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection');
				}
				else
				{
					JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection&view=cpanel');
				}
				return false;
			}
		}
		
		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{

				// Get the advanced encription.
				$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
				// Get the encription object.
				$advanced = new FOFEncryptAes($advancedkey, 256);
				// Get a db connection.
				$db = JFactory::getDbo();

				// Create a new query object.
				$query = $db->getQuery(true);

				// Get from #__costbenefitprojection_company as a
				$query->select($db->quoteName(
			array('a.id','a.name','a.user','a.department','a.per','a.country','a.service_provider','a.datayear','a.working_days','a.total_salary','a.total_healthcare','a.productivity_losses','a.males','a.females','a.medical_turnovers_males','a.medical_turnovers_females','a.sick_leave_males','a.sick_leave_females','a.percentmale','a.percentfemale','a.causesrisks','a.published','a.access'),
			array('id','name','user','department','per','country','service_provider','datayear','working_days','total_salary','total_healthcare','productivity_losses','males','females','medical_turnovers_males','medical_turnovers_females','sick_leave_males','sick_leave_females','percentmale','percentfemale','causesrisks','published','access')));
				$query->from($db->quoteName('#__costbenefitprojection_company', 'a'));

				// Get from #__costbenefitprojection_country as e
				$query->select($db->quoteName(
			array('e.id','e.name','e.alias','e.user','e.currency','e.datayear','e.worldzone','e.codethree','e.codetwo','e.working_days','e.presenteeism','e.medical_turnovers','e.sick_leave','e.healthcare','e.productivity_losses','e.publicname','e.publicemail','e.publicnumber','e.publicaddress','e.percentmale','e.percentfemale','e.causesrisks','e.maledeath','e.femaledeath','e.maleyld','e.femaleyld','e.access'),
			array('country_id','country_name','country_alias','country_user','country_currency','country_datayear','country_worldzone','country_codethree','country_codetwo','country_working_days','country_presenteeism','country_medical_turnovers','country_sick_leave','country_healthcare','country_productivity_losses','country_publicname','country_publicemail','country_publicnumber','country_publicaddress','country_percentmale','country_percentfemale','country_causesrisks','country_maledeath','country_femaledeath','country_maleyld','country_femaleyld','country_access')));
				$query->join('LEFT', ($db->quoteName('#__costbenefitprojection_country', 'e')) . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('e.id') . ')');

				// Get from #__costbenefitprojection_currency as f
				$query->select($db->quoteName(
			array('f.id','f.name','f.alias','f.codethree','f.numericcode','f.symbol','f.thousands','f.decimalplace','f.decimalsymbol','f.positivestyle','f.negativestyle','f.published','f.access','f.ordering'),
			array('currency_id','currency_name','currency_alias','currency_codethree','currency_numericcode','currency_symbol','currency_thousands','currency_decimalplace','currency_decimalsymbol','currency_positivestyle','currency_negativestyle','currency_published','currency_access','currency_ordering')));
				$query->join('LEFT', ($db->quoteName('#__costbenefitprojection_currency', 'f')) . ' ON (' . $db->quoteName('e.currency') . ' = ' . $db->quoteName('f.codethree') . ')');
				$query->where('a.id = ' . (int) $pk);

				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				// Load the results as a stdClass object.
				$data = $db->loadObject();

				if (empty($data))
				{
					// If no data is found redirect to default page and show warning.
					JError::raiseWarning(500, JText::_('COM_COSTBENEFITPROJECTION_NOT_FOUND_OR_ACCESS_DENIED'));
					JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection');
					return false;
				}
				if (!empty($data->medical_turnovers_females) && $advancedkey && !is_numeric($data->medical_turnovers_females) && $data->medical_turnovers_females === base64_encode(base64_decode($data->medical_turnovers_females, true)))
				{
					// Decode medical_turnovers_females
					$data->medical_turnovers_females = rtrim($advanced->decryptString($data->medical_turnovers_females), "\0");
				}
				if (!empty($data->females) && $advancedkey && !is_numeric($data->females) && $data->females === base64_encode(base64_decode($data->females, true)))
				{
					// Decode females
					$data->females = rtrim($advanced->decryptString($data->females), "\0");
				}
				if (!empty($data->sick_leave_males) && $advancedkey && !is_numeric($data->sick_leave_males) && $data->sick_leave_males === base64_encode(base64_decode($data->sick_leave_males, true)))
				{
					// Decode sick_leave_males
					$data->sick_leave_males = rtrim($advanced->decryptString($data->sick_leave_males), "\0");
				}
				if (CostbenefitprojectionHelper::checkString($data->causesrisks))
				{
					// Decode causesrisks
					$data->causesrisks = json_decode($data->causesrisks, true);
				}
				if (!empty($data->medical_turnovers_males) && $advancedkey && !is_numeric($data->medical_turnovers_males) && $data->medical_turnovers_males === base64_encode(base64_decode($data->medical_turnovers_males, true)))
				{
					// Decode medical_turnovers_males
					$data->medical_turnovers_males = rtrim($advanced->decryptString($data->medical_turnovers_males), "\0");
				}
				if (!empty($data->total_salary) && $advancedkey && !is_numeric($data->total_salary) && $data->total_salary === base64_encode(base64_decode($data->total_salary, true)))
				{
					// Decode total_salary
					$data->total_salary = rtrim($advanced->decryptString($data->total_salary), "\0");
				}
				if (!empty($data->sick_leave_females) && $advancedkey && !is_numeric($data->sick_leave_females) && $data->sick_leave_females === base64_encode(base64_decode($data->sick_leave_females, true)))
				{
					// Decode sick_leave_females
					$data->sick_leave_females = rtrim($advanced->decryptString($data->sick_leave_females), "\0");
				}
				if (!empty($data->total_healthcare) && $advancedkey && !is_numeric($data->total_healthcare) && $data->total_healthcare === base64_encode(base64_decode($data->total_healthcare, true)))
				{
					// Decode total_healthcare
					$data->total_healthcare = rtrim($advanced->decryptString($data->total_healthcare), "\0");
				}
				if (!empty($data->males) && $advancedkey && !is_numeric($data->males) && $data->males === base64_encode(base64_decode($data->males, true)))
				{
					// Decode males
					$data->males = rtrim($advanced->decryptString($data->males), "\0");
				}
				if (CostbenefitprojectionHelper::checkString($data->country_causesrisks))
				{
					// Decode country_causesrisks
					$data->country_causesrisks = json_decode($data->country_causesrisks, true);
				}
				// Make sure the content prepare plugins fire on country_publicaddress.
				$data->country_publicaddress = JHtml::_('content.prepare',$data->country_publicaddress);
				// Checking if country_publicaddress has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($data->country_publicaddress,$this->uikitComp);
				// set the global causesrisks value.
				$this->a_causesrisks = $data->causesrisks;
				// set the global datayear value.
				$this->a_datayear = $data->datayear;
				// set the global datayear value.
				$this->e_datayear = $data->country_datayear;
				// set the global causesrisks value.
				$this->e_causesrisks = $data->country_causesrisks;
				// set countryCountryHealth_dataB to the $data object.
				$data->countryCountryHealth_dataB = $this->getCountryCountryHealth_dataEbbe_B($data->country);
				// set idCompanyScaling_factorC to the $data object.
				$data->idCompanyScaling_factorC = $this->getIdCompanyScaling_factorEbbe_C($data->id);
				// set idCompanyInterventionD to the $data object.
				$data->idCompanyInterventionD = $this->getIdCompanyInterventionEbbe_D($data->id);
				// set causesrisksIdCauseriskG to the $data object.
				$data->causesrisksIdCauseriskG = $this->getCausesrisksIdCauseriskEbbe_G($data->causesrisks);
				// set countryCountryHealth_dataBB to the $data object.
				$data->countryCountryHealth_dataBB = $this->getCountryCountryHealth_dataEbbe_BB($data->country);
				// set causesrisksIdCauseriskGG to the $data object.
				$data->causesrisksIdCauseriskGG = $this->getCausesrisksIdCauseriskEbbe_GG($data->country_causesrisks);
				// set countryCountryInterventionDD to the $data object.
				$data->countryCountryInterventionDD = $this->getCountryCountryInterventionEbbe_DD($data->country);

				// set data object to item.
				$this->_item[$pk] = $data;
                        }
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	* Method to get an array of Health_data Objects.
	*
	* @return mixed  An array of Health_data Objects on success, false on failure.
	*
	*/
	public function getCountryCountryHealth_dataEbbe_B($country)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_health_data as b
		$query->select($db->quoteName(
			array('b.id','b.causerisk','b.year','b.maledeath','b.maleyld','b.femaledeath','b.femaleyld','b.published'),
			array('id','causerisk','year','maledeath','maleyld','femaledeath','femaleyld','published')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data', 'b'));
		$query->where('b.country = ' . $db->quote($country));
				// Check if $this->a_causesrisks is an array with values.
				$array = $this->a_causesrisks;
				if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
				{
					$query->where('b.causerisk IN (' . implode(',', $array) . ')');
				}
				else
				{
					return false;
				}
		$query->where('b.published = 1');
		$query->where('b.year = ' . $db->quote($this->a_datayear));
		$query->order('b.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}

	/**
	* Method to get an array of Scaling_factor Objects.
	*
	* @return mixed  An array of Scaling_factor Objects on success, false on failure.
	*
	*/
	public function getIdCompanyScaling_factorEbbe_C($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_scaling_factor as c
		$query->select($db->quoteName(
			array('c.id','c.causerisk','c.reference','c.yld_scaling_factor_males','c.yld_scaling_factor_females','c.mortality_scaling_factor_males','c.mortality_scaling_factor_females','c.presenteeism_scaling_factor_males','c.presenteeism_scaling_factor_females','c.health_scaling_factor','c.published'),
			array('id','causerisk','reference','yld_scaling_factor_males','yld_scaling_factor_females','mortality_scaling_factor_males','mortality_scaling_factor_females','presenteeism_scaling_factor_males','presenteeism_scaling_factor_females','health_scaling_factor','published')));
		$query->from($db->quoteName('#__costbenefitprojection_scaling_factor', 'c'));
		$query->where('c.company = ' . $db->quote($id));
		$query->where('c.published = 1');
		$query->order('c.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// Make sure the content prepare plugins fire on reference.
				$item->reference = JHtml::_('content.prepare',$item->reference);
				// Checking if reference has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->reference,$this->uikitComp);
			}
			return $items;
		}
		return false;
	}

	/**
	* Method to get an array of Intervention Objects.
	*
	* @return mixed  An array of Intervention Objects on success, false on failure.
	*
	*/
	public function getIdCompanyInterventionEbbe_D($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_intervention as d
		$query->select($db->quoteName(
			array('d.id','d.name','d.type','d.coverage','d.duration','d.share','d.description','d.reference','d.intervention','d.published','d.created_by','d.modified_by','d.created','d.modified'),
			array('id','name','type','coverage','duration','share','description','reference','intervention','published','created_by','modified_by','created','modified')));
		$query->from($db->quoteName('#__costbenefitprojection_intervention', 'd'));
		$query->where('d.company = ' . $db->quote($id));
		$query->where('d.published = 1');
		$query->order('d.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// Make sure the content prepare plugins fire on reference.
				$item->reference = JHtml::_('content.prepare',$item->reference);
				// Checking if reference has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->reference,$this->uikitComp);
			}
			return $items;
		}
		return false;
	}

	/**
	* Method to get an array of Causerisk Objects.
	*
	* @return mixed  An array of Causerisk Objects on success, false on failure.
	*
	*/
	public function getCausesrisksIdCauseriskEbbe_G($causesrisks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_causerisk as g
		$query->select($db->quoteName(
			array('g.id','g.name','g.ref','g.alias','g.description'),
			array('id','name','ref','alias','description')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'g'));

		// Check if $causesrisks is an array with values.
		$array = $causesrisks;
		if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
		{
			$query->where('g.id IN (' . implode(',', $array) . ')');
		}
		else
		{
			return false;
		}

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}

	/**
	* Method to get an array of Health_data Objects.
	*
	* @return mixed  An array of Health_data Objects on success, false on failure.
	*
	*/
	public function getCountryCountryHealth_dataEbbe_BB($country)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_health_data as bb
		$query->select($db->quoteName(
			array('bb.id','bb.asset_id','bb.causerisk','bb.year','bb.country','bb.maledeath','bb.maleyld','bb.femaledeath','bb.femaleyld','bb.published','bb.created_by','bb.modified_by','bb.created','bb.modified','bb.version','bb.hits','bb.ordering'),
			array('id','asset_id','causerisk','year','country','maledeath','maleyld','femaledeath','femaleyld','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data', 'bb'));
		$query->where('bb.country = ' . $db->quote($country));
				// Check if $this->e_causesrisks is an array with values.
				$array = $this->e_causesrisks;
				if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
				{
					$query->where('bb.causerisk IN (' . implode(',', $array) . ')');
				}
				else
				{
					return false;
				}
		$query->where('bb.published = 1');
		$query->where('bb.year = ' . $db->quote($this->e_datayear));
		$query->order('bb.ordering ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}

	/**
	* Method to get an array of Causerisk Objects.
	*
	* @return mixed  An array of Causerisk Objects on success, false on failure.
	*
	*/
	public function getCausesrisksIdCauseriskEbbe_GG($causesrisks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_causerisk as gg
		$query->select($db->quoteName(
			array('gg.id','gg.name','gg.ref','gg.alias','gg.description'),
			array('id','name','ref','alias','description')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'gg'));

		// Check if $causesrisks is an array with values.
		$array = $causesrisks;
		if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
		{
			$query->where('gg.id IN (' . implode(',', $array) . ')');
		}
		else
		{
			return false;
		}

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// Make sure the content prepare plugins fire on description.
				$item->description = JHtml::_('content.prepare',$item->description);
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->description,$this->uikitComp);
			}
			return $items;
		}
		return false;
	}

	/**
	* Method to get an array of Intervention Objects.
	*
	* @return mixed  An array of Intervention Objects on success, false on failure.
	*
	*/
	public function getCountryCountryInterventionEbbe_DD($country)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_intervention as dd
		$query->select($db->quoteName(
			array('dd.id','dd.name','dd.type','dd.coverage','dd.duration','dd.share','dd.description','dd.reference','dd.intervention','dd.published','dd.created_by','dd.modified_by','dd.created','dd.modified'),
			array('id','name','type','coverage','duration','share','description','reference','intervention','published','created_by','modified_by','created','modified')));
		$query->from($db->quoteName('#__costbenefitprojection_intervention', 'dd'));
		$query->where('dd.country = ' . $db->quote($country));
		$query->where('dd.published = 1');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// Make sure the content prepare plugins fire on description.
				$item->description = JHtml::_('content.prepare',$item->description);
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->description,$this->uikitComp);
				// Make sure the content prepare plugins fire on reference.
				$item->reference = JHtml::_('content.prepare',$item->reference);
				// Checking if reference has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->reference,$this->uikitComp);
			}
			return $items;
		}
		return false;
	}


	/**
	* Get the uikit needed components
	*
	* @return mixed  An array of objects on success.
	*
	*/
	public function getUikitComp()
	{
		if (isset($this->uikitComp) && CostbenefitprojectionHelper::checkArray($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}  

// none
}
