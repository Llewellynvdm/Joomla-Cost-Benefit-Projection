<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		createaccount.php
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
 * Costbenefitprojection Model for Createaccount
 */
class CostbenefitprojectionModelCreateaccount extends JModelList
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
		// Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_country as a
		$query->select($db->quoteName(
			array('a.id','a.user','a.name','a.publicname','a.publicemail','a.publicnumber','a.publicaddress'),
			array('id','user','name','publicname','publicemail','publicnumber','publicaddress')));
		$query->from($db->quoteName('#__costbenefitprojection_country', 'a'));
		$query->where('CHAR_LENGTH(a.causesrisks) > 5');
		$query->where('CHAR_LENGTH(a.percentfemale) > 5');
		$query->where('CHAR_LENGTH(a.percentmale) > 5');
		$query->where('CHAR_LENGTH(a.datayear) > 3');
		$query->where('CHAR_LENGTH(a.productivity_losses) > 0');
		$query->where('CHAR_LENGTH(a.sick_leave) > 0');
		$query->where('CHAR_LENGTH(a.medical_turnovers) > 0');
		$query->where('a.published = 1');
		$query->order('a.name ASC');

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
                if (!$user->authorise('site.createaccount.access', 'com_costbenefitprojection'))
                {
			JError::raiseWarning(500, JText::_('Not authorised!'));
			// redirect away if not a correct (TODO for now we go to default view)
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_costbenefitprojection&view=cpanel'));
			return false;
                } 
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_costbenefitprojection', true);

		// Convert the parameter fields into objects.
		foreach ($items as $nr => &$item)
		{
			// Always create a slug for sef URL's
			$item->slug = (isset($item->alias)) ? $item->id.':'.$item->alias : $item->id;
			// Make sure the content prepare plugins fire on publicaddress.
			$item->publicaddress = JHtml::_('content.prepare',$item->publicaddress);
			// Checking if publicaddress has uikit components that must be loaded.
			$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->publicaddress,$this->uikitComp);
			// set idCountryService_providerB to the $item object.
			$item->idCountryService_providerB = $this->getIdCountryService_providerCace_B($item->id);
		} 

		// return items
		return $items;
	} 

	/**
	* Method to get an array of Service_provider Objects.
	*
	* @return mixed  An array of Service_provider Objects on success, false on failure.
	*
	*/
	public function getIdCountryService_providerCace_B($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__costbenefitprojection_service_provider as b
		$query->select($db->quoteName(
			array('b.id','b.user','b.publicname','b.publicemail','b.publicnumber','b.publicaddress'),
			array('id','user','publicname','publicemail','publicnumber','publicaddress')));
		$query->from($db->quoteName('#__costbenefitprojection_service_provider', 'b'));
		$query->where('b.country = ' . $db->quote($id));
		$query->where('b.published = 1');
		$query->order('b.publicname ASC');

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
				// Make sure the content prepare plugins fire on publicaddress.
				$item->publicaddress = JHtml::_('content.prepare',$item->publicaddress);
				// Checking if publicaddress has uikit components that must be loaded.
				$this->uikitComp = CostbenefitprojectionHelper::getUikitComp($item->publicaddress,$this->uikitComp);
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
