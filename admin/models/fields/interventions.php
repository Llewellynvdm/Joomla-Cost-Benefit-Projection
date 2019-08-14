<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		interventions.php
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
 * Interventions Form Field class for the Costbenefitprojection component
 */
class JFormFieldInterventions extends JFormFieldList
{
	/**
	 * The interventions field type.
	 *
	 * @var		string
	 */
	public $type = 'interventions';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		// get the input from url
		$jinput = JFactory::getApplication()->input;
		// get the view name & id
		$interId = $jinput->getInt('id', 0);
		// Get the user object.
		$user = JFactory::getUser();
		$userIs = CostbenefitprojectionHelper::userIs($user->id);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.name','a.company','a.share'),array('id','interventions_name','company','share')));
		$query->from($db->quoteName('#__costbenefitprojection_intervention', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->where($db->quoteName('a.id') . ' != ' . $interId);
		if (!$user->authorise('core.admin'))
		{
			$companies = CostbenefitprojectionHelper::hisCompanies($user->id);
			if (CostbenefitprojectionHelper::checkArray($companies))
			{
				$companies = implode(',',$companies);
				// only load this users companies
				$query->where('a.company IN (' . $companies . ')');
			}
			else
			{
				// dont allow user to see any companies
				$query->where('a.company = -4');
			}
		}
		$query->order('a.name ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			foreach($items as $item)
			{
				if (!CostbenefitprojectionHelper::checkIntervetionAccess($item->id,$item->share,$item->company))
				{
					continue;
				}
				if (1 == $userIs)
				{
					$options[] = JHtml::_('select.option', $item->id, $item->interventions_name);
				}
				else
				{
					$compName = CostbenefitprojectionHelper::getId('company', $item->company, 'id', 'name');
					$options[] = JHtml::_('select.option', $item->id, $item->interventions_name . ' ('.$compName.')');
				}
			}
		}
		return $options;
	}
}
