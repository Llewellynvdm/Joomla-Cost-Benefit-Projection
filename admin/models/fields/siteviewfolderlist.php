<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		siteviewfolderlist.php
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
 * Siteviewfolderlist Form Field class for the Costbenefitprojection component
 */
class JFormFieldSiteviewfolderlist extends JFormFieldList
{
	/**
	 * The siteviewfolderlist field type.
	 *
	 * @var		string
	 */
	public $type = 'siteviewfolderlist';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		// get custom folder files
		$localfolder = JPATH_COMPONENT_SITE.'/views';
		// set the default
		$options = array();
		// import all needed classes
		jimport('joomla.filesystem.folder');
		// now check if there are files in the folder
		if (JFolder::exists($localfolder) && $folders = JFolder::folders($localfolder))
		{
			if ($this->multiple === false)
			{
				$options[] = JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_SELECT_A_SITE_VIEW'));
			}
			foreach ($folders as $folder)
			{
				$options[] = JHtml::_('select.option', $folder, CostbenefitprojectionHelper::safeString($folder, 'W'));
			}
		}
		return $options;
	}
}
