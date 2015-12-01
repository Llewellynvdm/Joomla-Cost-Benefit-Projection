<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			1st December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		serviceprovider.php
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
 * Serviceprovider Form Field class for the Costbenefitprojection component
 */
class JFormFieldServiceprovider extends JFormFieldList
{
	/**
	 * The serviceprovider field type.
	 *
	 * @var		string
	 */
	public $type = 'serviceprovider'; 
	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// [7691] see if we should add buttons
		$setButton = $this->getAttribute('button');
		// [7693] get html
		$html = parent::getInput();
		// [7695] if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// [7699] only add if user allowed to create service_provider
			if ($user->authorise('service_provider.create', 'com_costbenefitprojection'))
			{
				// [7717] get the input from url
				$jinput = JFactory::getApplication()->input;
				// [7719] get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// [7724] check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// [7728] only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// [7731] build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_costbenefitprojection&amp;view=service_provider&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_COSTBENEFITPROJECTION_NEW') . '</a>';
				// [7735] return the button attached to input field
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
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.user'),array('id','serviceprovider_user')));
		$query->from($db->quoteName('#__costbenefitprojection_service_provider', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		if (!$user->authorise('core.options', 'com_costbenefitprojection'))
		{
			$serviceProviders = CostbenefitprojectionHelper::hisServiceProviders($user->id);
			if (CostbenefitprojectionHelper::checkArray($serviceProviders))
			{
				$serviceProviders = implode(',',$serviceProviders);
				// only load this users service providers
				$query->where('a.id IN (' . $serviceProviders . ')');
			}
			else
			{
				// don't allow user to see any service providers
				$query->where('a.id = -4');
			}
		}
		$query->order('a.user ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, JFactory::getUser($item->serviceprovider_user)->name);
			}
		}
		return $options;
	}
}
