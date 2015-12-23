<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			23rd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		publicresults.php
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
 * Costbenefitprojection Publicresults Model
 */
class CostbenefitprojectionModelPublicresults extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_costbenefitprojection.publicresults';

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
		// Get the itme main id
		$id = JRequest::getInt('id');
		$this->setState('publicresults.id', $id);

		// Load the parameters.
		$params = $this->app->getParams();
		$this->setState('params', $params);
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
                if (!$this->user->authorise('site.publicresults.access', 'com_costbenefitprojection'))
                {
			JError::raiseWarning(500, JText::_('Not authorised!'));
			// redirect away if not a correct (TODO for now we go to default view)
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_costbenefitprojection&view=cpanel'));
			return false;
                }
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->initSet		= true;

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('publicresults.id');

		if (!$pk)
		{
			JError::raiseWarning(500, JText::_('No Direct Access Allowed!'));
			// redirect away if not a correct (TODO for now we go to default view)
			JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection&view=cpanel');
			return false;
		}
		
		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// [2451] Get a db connection.
				$db = JFactory::getDbo();

				// [2453] Create a new query object.
				$query = $db->getQuery(true);

				// [1901] Get from #__costbenefitprojection_country as a
				$query->select($db->quoteName(
			array('a.id','a.currency','a.datayear','a.percentmale','a.percentfemale','a.causesrisks','a.published'),
			array('country','currency','datayear','percentmale','percentfemale','causesrisks','published')));
				$query->from($db->quoteName('#__costbenefitprojection_country', 'a'));

				// [1901] Get from #__costbenefitprojection_country as e
				$query->select($db->quoteName(
			array('e.id','e.name','e.alias','e.user','e.currency','e.datayear','e.worldzone','e.codethree','e.codetwo','e.working_days','e.presenteeism','e.medical_turnovers','e.sick_leave','e.healthcare','e.productivity_losses','e.publicname','e.publicemail','e.publicnumber','e.publicaddress','e.percentmale','e.percentfemale','e.causesrisks','e.maledeath','e.femaledeath','e.maleyld','e.femaleyld','e.access'),
			array('country_id','country_name','country_alias','country_user','country_currency','country_datayear','country_worldzone','country_codethree','country_codetwo','country_working_days','country_presenteeism','country_medical_turnovers','country_sick_leave','country_healthcare','country_productivity_losses','country_publicname','country_publicemail','country_publicnumber','country_publicaddress','country_percentmale','country_percentfemale','country_causesrisks','country_maledeath','country_femaledeath','country_maleyld','country_femaleyld','country_access')));
				$query->join('LEFT', ($db->quoteName('#__costbenefitprojection_country', 'e')) . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('e.id') . ')');

				// [1901] Get from #__costbenefitprojection_currency as f
				$query->select($db->quoteName(
			array('f.id','f.name','f.alias','f.codethree','f.numericcode','f.symbol','f.thousands','f.decimalplace','f.decimalsymbol','f.positivestyle','f.negativestyle','f.published','f.access','f.ordering'),
			array('currency_id','currency_name','currency_alias','currency_codethree','currency_numericcode','currency_symbol','currency_thousands','currency_decimalplace','currency_decimalsymbol','currency_positivestyle','currency_negativestyle','currency_published','currency_access','currency_ordering')));
				$query->join('LEFT', ($db->quoteName('#__costbenefitprojection_currency', 'f')) . ' ON (' . $db->quoteName('e.currency') . ' = ' . $db->quoteName('f.codethree') . ')');
				$query->where('a.id = ' . (int) $pk);

				// [2464] Reset the query using our newly populated query object.
				$db->setQuery($query);
				// [2466] Load the results as a stdClass object.
				$data = $db->loadObject();

				if (empty($data))
				{
					// [2477] If no data is found redirect to default page and show warning.
					JError::raiseWarning(500, JText::_('COM_COSTBENEFITPROJECTION_NOT_FOUND_OR_ACCESS_DENIED'));
					JFactory::getApplication()->redirect('index.php?option=com_costbenefitprojection&view=cpanel');
					return false;
				}
				if (CostbenefitprojectionHelper::checkString($data->country_causesrisks))
				{
					// [2103] Decode country_causesrisks
					$data->country_causesrisks = json_decode($data->country_causesrisks, true);
				}
				// [2118] Make sure the content prepare plugins fire on country_publicaddress.
				$data->country_publicaddress = JHtml::_('content.prepare',$data->country_publicaddress);
				// [2120] Checking if country_publicaddress has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($data->country_publicaddress,$this->uikitComp);
				// [2423] set the global causesrisks value.
				$this->a_causesrisks = $data->causesrisks;
				// [2423] set the global datayear value.
				$this->a_datayear = $data->datayear;
				// [2423] set the global datayear value.
				$this->e_datayear = $data->country_datayear;
				// [2423] set the global causesrisks value.
				$this->e_causesrisks = $data->country_causesrisks;
				// [2151] set idCountryHealth_dataB to the $data object.
				$data->idCountryHealth_dataB = $this->getIdCountryHealth_dataDadd_B($data->country);
				// [2151] set causesrisksIdCauseriskG to the $data object.
				$data->causesrisksIdCauseriskG = $this->getCausesrisksIdCauseriskDadd_G($data->causesrisks);
				// [2151] set idCountryHealth_dataBB to the $data object.
				$data->idCountryHealth_dataBB = $this->getIdCountryHealth_dataDadd_BB($data->country);
				// [2151] set causesrisksIdCauseriskGG to the $data object.
				$data->causesrisksIdCauseriskGG = $this->getCausesrisksIdCauseriskDadd_GG($data->country_causesrisks);

				// [2571] set data object to item.
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

		// make sure the sum class knows this is a public request
		$this->_item[$pk]->id = 0;
		$this->_item[$pk]->public = true;
		// set the male/female number
		$employees = $this->input->get('employees', 0, 'INT');
		if ($employees)
		{
			$this->_item[$pk]->males = round($employees / 2);
			$this->_item[$pk]->females = round($employees / 2);
		}
		// set total salary
		$this->_item[$pk]->total_salary = $this->input->get('salary', 0, 'INT');

		return $this->_item[$pk];
	} 

	/**
	* Method to get an array of Health_data Objects.
	*
	* @return mixed  An array of Health_data Objects on success, false on failure.
	*
	*/
	public function getIdCountryHealth_dataDadd_B($id)
	{
		// [2831] Get a db connection.
		$db = JFactory::getDbo();

		// [2833] Create a new query object.
		$query = $db->getQuery(true);

		// [2835] Get from #__costbenefitprojection_health_data as b
		$query->select($db->quoteName(
			array('b.id','b.causerisk','b.year','b.maledeath','b.maleyld','b.femaledeath','b.femaleyld','b.published'),
			array('id','causerisk','year','maledeath','maleyld','femaledeath','femaleyld','published')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data', 'b'));
		$query->where('b.country = ' . $db->quote($id));
				// [2233] Check if $this->a_causesrisks is an array with values.
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

		// [2889] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [2892] check if there was data returned
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
	public function getCausesrisksIdCauseriskDadd_G($causesrisks)
	{
		// [2831] Get a db connection.
		$db = JFactory::getDbo();

		// [2833] Create a new query object.
		$query = $db->getQuery(true);

		// [2835] Get from #__costbenefitprojection_causerisk as g
		$query->select($db->quoteName(
			array('g.id','g.name','g.ref','g.alias','g.description'),
			array('id','name','ref','alias','description')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'g'));

		// [2841] Check if $causesrisks is an array with values.
		$array = $causesrisks;
		if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
		{
			$query->where('g.id IN (' . implode(',', $array) . ')');
		}
		else
		{
			return false;
		}

		// [2889] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [2892] check if there was data returned
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
	public function getIdCountryHealth_dataDadd_BB($id)
	{
		// [2831] Get a db connection.
		$db = JFactory::getDbo();

		// [2833] Create a new query object.
		$query = $db->getQuery(true);

		// [2835] Get from #__costbenefitprojection_health_data as bb
		$query->select($db->quoteName(
			array('bb.id','bb.asset_id','bb.causerisk','bb.year','bb.country','bb.maledeath','bb.maleyld','bb.femaledeath','bb.femaleyld','bb.published','bb.created_by','bb.modified_by','bb.created','bb.modified','bb.version','bb.hits','bb.ordering'),
			array('id','asset_id','causerisk','year','country','maledeath','maleyld','femaledeath','femaleyld','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data', 'bb'));
		$query->where('bb.country = ' . $db->quote($id));
				// [2233] Check if $this->e_causesrisks is an array with values.
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

		// [2889] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [2892] check if there was data returned
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
	public function getCausesrisksIdCauseriskDadd_GG($causesrisks)
	{
		// [2831] Get a db connection.
		$db = JFactory::getDbo();

		// [2833] Create a new query object.
		$query = $db->getQuery(true);

		// [2835] Get from #__costbenefitprojection_causerisk as gg
		$query->select($db->quoteName(
			array('gg.id','gg.name','gg.ref','gg.alias','gg.description'),
			array('id','name','ref','alias','description')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'gg'));

		// [2841] Check if $causesrisks is an array with values.
		$array = $causesrisks;
		if (isset($array) && CostbenefitprojectionHelper::checkArray($array))
		{
			$query->where('gg.id IN (' . implode(',', $array) . ')');
		}
		else
		{
			return false;
		}

		// [2889] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [2892] check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// [2945] Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// [2118] Make sure the content prepare plugins fire on description.
				$item->description = JHtml::_('content.prepare',$item->description);
				// [2120] Checking if description has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->description,$this->uikitComp);
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
}
