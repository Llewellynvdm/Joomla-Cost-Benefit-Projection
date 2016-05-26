<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.1
	@build			26th May, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		causesrisks.php
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
 * Causesrisks Form Field class for the Costbenefitprojection component
 */
class JFormFieldCausesrisks extends JFormFieldList
{
	/**
	 * The causesrisks field type.
	 *
	 * @var		string
	 */
	public $type = 'causesrisks'; 
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
			// only add if user allowed to create causerisk
			if ($user->authorise('causerisk.create', 'com_costbenefitprojection'))
			{
				// build Create button
				$button[] = '<a id="'.$buttonName.'Create" class="btn btn-small btn-success hasTooltip" title="'.JText::sprintf('COM_COSTBENEFITPROJECTION_CREATE_NEW_S', CostbenefitprojectionHelper::safeString($buttonName, 'W')).'" style="border-radius: 0px 4px 4px 0px; padding: 4px 4px 4px 7px;"
					href="index.php?option=com_costbenefitprojection&amp;view=causerisk&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span></a>';
			}
			// only add if user allowed to edit causerisk
			if (($buttonName == 'causerisk' || $buttonName == 'causesrisks') && $user->authorise('causerisk.edit', 'com_costbenefitprojection'))
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
							var url = 'index.php?option=com_costbenefitprojection&view=causesrisks&task=causerisk.edit&id='+value+'".$refJ."';
							jQuery('#".$buttonName."Edit').attr('href', url);
						} else {
							// show the create button
							jQuery('#".$buttonName."Create').show();
							// hide edit button
							jQuery('#".$buttonName."Edit').hide();
						}
					}";
			}
			// check if button was created for causerisk field.
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
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.name','a.ref'),array('id','causerisk_name','ref')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.ref ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			foreach($items as $item)
			{
				$ref = explode('.',$item->ref);
				$key = explode('.0',$item->ref);
				$key = implode('.',$key);
				$spacer = array();
				foreach ($ref as $nr => $space)
				{
					if ($nr > 1)
					{
						$spacer[] = '|&mdash;';
					}
				}
				if (CostbenefitprojectionHelper::checkArray($spacer))
				{
					$options[] = JHtml::_('select.option', $item->id, implode('',$spacer).' '.$item->causerisk_name.'&nbsp;('.$key.')');
				}
				else
				{
					$options[] = JHtml::_('select.option', $item->id, $item->causerisk_name.'&nbsp;('.$key.')');
				}
			}
		}
		return $options;
	}
}
