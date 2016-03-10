<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		datayear.php
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
 * Datayear Form Field class for the Costbenefitprojection component
 */
class JFormFieldDatayear extends JFormFieldList
{
	/**
	 * The datayear field type.
	 *
	 * @var		string
	 */
	public $type = 'datayear'; 
	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// see if we should add buttons
		$setButton = $this->getAttribute('button');
		// get html
		$html = parent::getInput();
		// if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// only add if user allowed to create health_data
			if ($user->authorise('health_data.create', 'com_costbenefitprojection'))
			{
				// get the input from url
				$jinput = JFactory::getApplication()->input;
				// get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_costbenefitprojection&amp;view=health_data&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_COSTBENEFITPROJECTION_NEW') . '</a>';
				// return the button attached to input field
				return $html . $button;
			}
		}
		return $html;
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	public function getOptions()
	{
		$jinput = JFactory::getApplication()->input;
		$client = $jinput->get('id', 0, 'INT');
		$countries = CostbenefitprojectionHelper::hisCountries(null,$client,'company');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.year','a.country'),array('year','country')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		if (CostbenefitprojectionHelper::checkArray($countries)) 
		{
			$query->where($db->quoteName('a.country') . ' IN (' . implode(',',$countries) . ')');
		}
		$query->order('a.country ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$years = array();
			foreach($items as $item)
			{
				if (!CostbenefitprojectionHelper::checkArray($years) || !in_array($item->year.'_'.$item->country,$years))
				{
					if (!CostbenefitprojectionHelper::checkArray($countries) || $client == 0)
					{
						$countryName = ' ('. CostbenefitprojectionHelper::getCountryName($item->country).')';
					}
					else
					{
						$countryName = '';
					}
					$options[] = JHtml::_('select.option', $item->year, $item->year .$countryName);
					$years[$item->year] = $item->year.'_'.$item->country;
				}
			}
		}
		return $options;
	}
}
