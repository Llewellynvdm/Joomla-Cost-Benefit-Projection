<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			17th December, 2015
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
		// [7776] see if we should add buttons
		$setButton = $this->getAttribute('button');
		// [7778] get html
		$html = parent::getInput();
		// [7780] if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// [7784] only add if user allowed to create causerisk
			if ($user->authorise('causerisk.create', 'com_costbenefitprojection'))
			{
				// [7802] get the input from url
				$jinput = JFactory::getApplication()->input;
				// [7804] get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// [7809] check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// [7813] only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// [7816] build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_costbenefitprojection&amp;view=causerisk&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_COSTBENEFITPROJECTION_NEW') . '</a>';
				// [7820] return the button attached to input field
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
