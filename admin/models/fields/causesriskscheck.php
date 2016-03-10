<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		causesriskscheck.php
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
JFormHelper::loadFieldClass('checkboxes');

/**
 * Causesriskscheck Form Field class for the Costbenefitprojection component
 */
class JFormFieldCausesriskscheck extends JFormFieldCheckboxes
{
	/**
	 * The causesriskscheck field type.
	 *
	 * @var		string
	 */
	public $type = 'causesriskscheck';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	public function getOptions()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.name','a.ref'),array('id','causesrisks_name','ref')));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.ref ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array(); $counter = 0;
		if ($items)
		{
			foreach($items as $item)
			{
				$ref	= explode('.',$item->ref);
				$target_id	= implode('-',$ref);
				$key	= explode('.0',$item->ref);
				$key	= implode('.',$key);
				$spacer	= array();
				$sub	= '';
				$sub_	= '';
				foreach ($ref as $nr => $space)
				{
					if ($nr > 1)
					{
						$spacer[] = '<span class=\'gi\'>|&mdash;</span>';
					}
					if ($nr > 2)
					{
						$sub = '<em>';
						$sub_ = '</em>';
					}
				}
				if (CostbenefitprojectionHelper::checkArray($spacer))
				{
					$tmp = array(
						'value'    => $item->id,
						'text'     => '<span id='.$target_id.' style=\'color:'.$color.';\'>&nbsp;&nbsp;'.implode('',$spacer).'&nbsp;'.$sub.$item->causesrisks_name.$sub_.'&nbsp;<small>('.$key.')</small></span>',
						'checked'  => false
					);
					$options[] = (object) $tmp;
				}
				else
				{
					if ( $counter & 1 )
					{
					$color = '#0C5B00';
					}
					else
					{
						$color = '#1A3867';
					}
					$tmp = array(
						'value'    => $item->id,
						'text'     => '<span id='.$target_id.' style=\'color:'.$color.';\'>&nbsp;&nbsp;<strong>'.strtoupper($item->causesrisks_name).'</strong>&nbsp;<small>('.$key.')</small></span>',
						'checked'  => false
					);
					$options[] = (object) $tmp;
					$counter++;
				}
			}
		}
		return $options;
	}
}
