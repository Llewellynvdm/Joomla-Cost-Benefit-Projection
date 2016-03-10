<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		countryuser.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('user');

/**
 * Countryuser Form Field class for the Costbenefitprojection component
 */
class JFormFieldCountryuser extends JFormFieldUser
{
	/**
	 * The countryuser field type.
	 *
	 * @var		string
	 */
	public $type = 'countryuser';

	/**
	 * Method to get the filtering groups (null means no filtering)
	 *
	 * @return  mixed  array of filtering groups or null.
	 *
	 * @since   1.6
	 */
	protected function getGroups()
	{
		// set the groups array
		$groups = JComponentHelper::getParams('com_costbenefitprojection')->get('countryuser');
		return $groups;
	}

	/**
	 * Method to get the users to exclude from the list of users
	 *
	 * @return  mixed  Array of users to exclude or null to to not exclude them
	 *
	 * @since   1.6
	 */
	protected function getExcluded()
	{
		// To ensure that there is only one record per user
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select all records from the #__costbenefitprojection_country table from user column
		$query->select($db->quoteName('user'));
		$query->from($db->quoteName('#__costbenefitprojection_country'));
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if ($found)
		{
			// return all users already used
			return array_unique($db->loadColumn());
		}
		return null;
	}
}
