<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		countries.php
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
JFormHelper::loadFieldClass('list');

/**
 * Countries Form Field class for the Costbenefitprojection component
 */
class JFormFieldCountries extends JFormFieldList
{
	/**
	 * The countries field type.
	 *
	 * @var		string
	 */
	public $type = 'countries';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.name'),array('id','country_name')));
		$query->from($db->quoteName('#__costbenefitprojection_country', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$countries  = CostbenefitprojectionHelper::hisCountries($user->id);
			if (CostbenefitprojectionHelper::checkArray($countries))
			{
				$countries = implode(',',$countries);
				// only load this users companies
				$query->where('a.id IN (' . $countries . ')');
			}
			else
			{
				// dont allow user to see any countries
				$query->where('a.id = -4');
			}
		}
		$query->order('a.name ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Select a country');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->country_name);
			}
		}
		return $options;
	}
}
