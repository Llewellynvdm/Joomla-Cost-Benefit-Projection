<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.1
	@build			24th May, 2016
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
			$button = array();
			$script = array();
			$buttonName = $this->getAttribute('name');
			// get the input from url
			$jinput = JFactory::getApplication()->input;
			// get the view name & id
			$values = $jinput->getArray(array(
				'id' => 'int',
				'view' => 'word'
			));
			// check if new item
			$ref = '';
			$refJ = '';
			if (!is_null($values['id']) && strlen($values['view']))
			{
				// only load referal if not new item.
				$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				$refJ = '&ref=' . $values['view'] . '&refid=' . $values['id'];
			}
			$user = JFactory::getUser();
			// only add if user allowed to create health_data
			if ($user->authorise('health_data.create', 'com_costbenefitprojection'))
			{
				// build Create button
				$button[] = '<a id="'.$buttonName.'Create" class="btn btn-small btn-success hasTooltip" title="'.JText::sprintf('COM_COSTBENEFITPROJECTION_CREATE_NEW_S', CostbenefitprojectionHelper::safeString($buttonName, 'W')).'" style="border-radius: 0px 4px 4px 0px; padding: 4px 4px 4px 7px;"
					href="index.php?option=com_costbenefitprojection&amp;view=health_data&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span></a>';
			}
			// only add if user allowed to edit health_data
			if (($buttonName == 'health_data' || $buttonName == 'health_data_sets') && $user->authorise('health_data.edit', 'com_costbenefitprojection'))
			{
				// build edit button
				$button[] = '<a id="'.$buttonName.'Edit" class="btn btn-small hasTooltip" title="'.JText::sprintf('COM_COSTBENEFITPROJECTION_EDIT_S', CostbenefitprojectionHelper::safeString($buttonName, 'W')).'" style="display: none; padding: 4px 4px 4px 7px;" href="#" >
					<span class="icon-edit"></span></a>';
				// build script
				$script[] = "
					jQuery(document).ready(function() {
						jQuery('#adminForm').on('change', '#jform_".$buttonName."',function (e) {
							e.preventDefault();
							var ".$buttonName."Value = jQuery('#jform_".$buttonName."').val();
							".$buttonName."Button(".$buttonName."Value);
						});
						var ".$buttonName."Value = jQuery('#jform_".$buttonName."').val();
						".$buttonName."Button(".$buttonName."Value);
					});
					function ".$buttonName."Button(value) {
						if (value > 0) {
							// hide the create button
							jQuery('#".$buttonName."Create').hide();
							// show edit button
							jQuery('#".$buttonName."Edit').show();
							var url = 'index.php?option=com_costbenefitprojection&view=health_data_sets&task=health_data.edit&id='+value+'".$refJ."';
							jQuery('#".$buttonName."Edit').attr('href', url);
						} else {
							// show the create button
							jQuery('#".$buttonName."Create').show();
							// hide edit button
							jQuery('#".$buttonName."Edit').hide();
						}
					}";
			}
			// check if button was created for health_data field.
			if (CostbenefitprojectionHelper::checkArray($button))
			{
				// Add some final script
				$script[] = "
					jQuery(document).ready(function() {
						jQuery('#jform_".$buttonName."').closest('.control-group').addClass('input-append');
					});";
				// Load the needed script.
				$document = JFactory::getDocument();
				$document->addScriptDeclaration(implode(' ',$script));
				// return the button attached to input field.
				return $html . implode('',$button);
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
