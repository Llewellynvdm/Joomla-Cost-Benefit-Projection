<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		combinedresults.php
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
 * Costbenefitprojection Model for Combinedresults
 */
class CostbenefitprojectionModelCombinedresults extends JModelList
{
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
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
                // Get the current user for authorisation checks
		$this->user		= JFactory::getUser();
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->app		= JFactory::getApplication();
		$this->input		= $this->app->input;
		$this->initSet		= true;


		$ids = (array) array_map('intval',explode('_', $this->input->get('cid', null, 'CMD')));
		if (!$this->user->authorise('core.options', 'com_costbenefitprojection') && CostbenefitprojectionHelper::checkArray($ids))
		{
			// make absolutely sure that these companies can be viewed
			$companies = CostbenefitprojectionHelper::hisCompanies($this->userId);
			foreach($ids as $nr => $pk)
			{
				if (!CostbenefitprojectionHelper::checkArray($companies) || !in_array($pk,$companies))
				{
					// remove if not found
					unset($ids[$nr]);
				}
			}
		}
		// only continue if we have ids
		if (!CostbenefitprojectionHelper::checkArray($ids))
		{
			// redirect away if not a correct (TODO for now we go to default view)
			JError::raiseWarning(500, JText::_('No companies selected!'));
			$app = JFactory::getApplication();
			if ($app->isAdmin())
			{
				JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection');
			}
			else
			{
				JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection&view=cpanel');
			}
		} 
		// Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
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
		// Check if $ids is an array with values.
		$array = $ids;
		if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
		{
			$query->where('a.id IN (' . implode(',', $array) . ')');
		}
		else
		{
			return false;
		}

		// return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
                $user = JFactory::getUser();
                // check if this user has permission to access items
                if (!$user->authorise('combinedresults.access', 'com_costbenefitprojection'))
                {
			JError::raiseWarning(500, JText::_('Not authorised!'));
			// redirect away if not a correct (TODO for now we go to default view)
			JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection');
			return false;
                } 
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_costbenefitprojection', true);

		// Get the advanced encription.
		$advancedkey = CostbenefitprojectionHelper::getCryptKey('advanced');
		// Get the encription object.
		$advanced = new FOFEncryptAes($advancedkey, 256);

		// Convert the parameter fields into objects.
		foreach ($items as $nr => &$item)
		{
			// Always create a slug for sef URL's
			$item->slug = (isset($item->alias)) ? $item->id.':'.$item->alias : $item->id;
			if (!empty($item->medical_turnovers_females) && $advancedkey && !is_numeric($item->medical_turnovers_females) && $item->medical_turnovers_females === base64_encode(base64_decode($item->medical_turnovers_females, true)))
			{
				// Decode medical_turnovers_females
				$item->medical_turnovers_females = rtrim($advanced->decryptString($item->medical_turnovers_females), "\0");
			}
			if (!empty($item->females) && $advancedkey && !is_numeric($item->females) && $item->females === base64_encode(base64_decode($item->females, true)))
			{
				// Decode females
				$item->females = rtrim($advanced->decryptString($item->females), "\0");
			}
			if (!empty($item->sick_leave_males) && $advancedkey && !is_numeric($item->sick_leave_males) && $item->sick_leave_males === base64_encode(base64_decode($item->sick_leave_males, true)))
			{
				// Decode sick_leave_males
				$item->sick_leave_males = rtrim($advanced->decryptString($item->sick_leave_males), "\0");
			}
			if (CostbenefitprojectionHelper::checkString($item->causesrisks))
			{
				// Decode causesrisks
				$item->causesrisks = json_decode($item->causesrisks, true);
			}
			if (!empty($item->medical_turnovers_males) && $advancedkey && !is_numeric($item->medical_turnovers_males) && $item->medical_turnovers_males === base64_encode(base64_decode($item->medical_turnovers_males, true)))
			{
				// Decode medical_turnovers_males
				$item->medical_turnovers_males = rtrim($advanced->decryptString($item->medical_turnovers_males), "\0");
			}
			if (!empty($item->total_salary) && $advancedkey && !is_numeric($item->total_salary) && $item->total_salary === base64_encode(base64_decode($item->total_salary, true)))
			{
				// Decode total_salary
				$item->total_salary = rtrim($advanced->decryptString($item->total_salary), "\0");
			}
			if (!empty($item->sick_leave_females) && $advancedkey && !is_numeric($item->sick_leave_females) && $item->sick_leave_females === base64_encode(base64_decode($item->sick_leave_females, true)))
			{
				// Decode sick_leave_females
				$item->sick_leave_females = rtrim($advanced->decryptString($item->sick_leave_females), "\0");
			}
			if (!empty($item->total_healthcare) && $advancedkey && !is_numeric($item->total_healthcare) && $item->total_healthcare === base64_encode(base64_decode($item->total_healthcare, true)))
			{
				// Decode total_healthcare
				$item->total_healthcare = rtrim($advanced->decryptString($item->total_healthcare), "\0");
			}
			if (!empty($item->males) && $advancedkey && !is_numeric($item->males) && $item->males === base64_encode(base64_decode($item->males, true)))
			{
				// Decode males
				$item->males = rtrim($advanced->decryptString($item->males), "\0");
			}
			if (CostbenefitprojectionHelper::checkString($item->country_causesrisks))
			{
				// Decode country_causesrisks
				$item->country_causesrisks = json_decode($item->country_causesrisks, true);
			}
			// Make sure the content prepare plugins fire on country_publicaddress.
			$item->country_publicaddress = JHtml::_('content.prepare',$item->country_publicaddress);
			// Checking if country_publicaddress has uikit components that must be loaded.
			$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->country_publicaddress,$this->uikitComp);
			// set the global causesrisks value.
			$this->a_causesrisks = $item->causesrisks;
			// set the global datayear value.
			$this->a_datayear = $item->datayear;
			// set the global datayear value.
			$this->e_datayear = $item->country_datayear;
			// set the global causesrisks value.
			$this->e_causesrisks = $item->country_causesrisks;
			// set countryCountryHealth_dataB to the $item object.
			$item->countryCountryHealth_dataB = $this->getCountryCountryHealth_dataBcbb_B($item->country);
			// set idCompanyScaling_factorC to the $item object.
			$item->idCompanyScaling_factorC = $this->getIdCompanyScaling_factorBcbb_C($item->id);
			// set idCompanyInterventionD to the $item object.
			$item->idCompanyInterventionD = $this->getIdCompanyInterventionBcbb_D($item->id);
			// set causesrisksIdCauseriskG to the $item object.
			$item->causesrisksIdCauseriskG = $this->getCausesrisksIdCauseriskBcbb_G($item->causesrisks);
			// set countryCountryHealth_dataBB to the $item object.
			$item->countryCountryHealth_dataBB = $this->getCountryCountryHealth_dataBcbb_BB($item->country);
			// set causesrisksIdCauseriskGG to the $item object.
			$item->causesrisksIdCauseriskGG = $this->getCausesrisksIdCauseriskBcbb_GG($item->country_causesrisks);
			// set countryCountryInterventionDD to the $item object.
			$item->countryCountryInterventionDD = $this->getCountryCountryInterventionBcbb_DD($item->country);
		} 

		// return items
		return $items;
	}

	/**
	* Method to get an array of Health_data Objects.
	*
	* @return mixed  An array of Health_data Objects on success, false on failure.
	*
	*/
	public function getCountryCountryHealth_dataBcbb_B($country)
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
	public function getIdCompanyScaling_factorBcbb_C($id)
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
	public function getIdCompanyInterventionBcbb_D($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_intervention as d
		$query->select($db->quoteName(
			array('d.id','d.name','d.type','d.coverage','d.duration','d.share','d.description','d.reference','d.interventions','d.intervention','d.published','d.created_by','d.modified_by','d.created','d.modified'),
			array('id','name','type','coverage','duration','share','description','reference','interventions','intervention','published','created_by','modified_by','created','modified')));
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
	public function getCausesrisksIdCauseriskBcbb_G($causesrisks)
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
	public function getCountryCountryHealth_dataBcbb_BB($country)
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
	public function getCausesrisksIdCauseriskBcbb_GG($causesrisks)
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
	public function getCountryCountryInterventionBcbb_DD($country)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_intervention as dd
		$query->select($db->quoteName(
			array('dd.id','dd.name','dd.type','dd.coverage','dd.duration','dd.share','dd.description','dd.reference','dd.interventions','dd.intervention','dd.published','dd.created_by','dd.modified_by','dd.created','dd.modified'),
			array('id','name','type','coverage','duration','share','description','reference','interventions','intervention','published','created_by','modified_by','created','modified')));
		$query->from($db->quoteName('#__costbenefitprojection_intervention', 'dd'));
		$query->where('dd.country = ' . $db->quote($country));
		$query->where('dd.published = 1');
		$query->order('dd.ordering ASC');

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
				if (CostbenefitprojectionHelper::checkString($item->interventions))
				{
					// Decode interventions
					$item->interventions = json_decode($item->interventions, true);
				}
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
