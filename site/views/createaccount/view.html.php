<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			23rd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Costbenefitprojection View class for the Createaccount
 */
class CostbenefitprojectionViewCreateaccount extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get combined params of both component and menu
		$this->app = JFactory::getApplication();
		$this->params = $this->app->getParams();
		$this->menu = $this->app->getMenu()->getActive();
		// get the user object
		$this->user = JFactory::getUser();
		// [3164] Initialise variables.
		$this->items	= $this->get('Items');

		// [3193] Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// [3210] Set the toolbar
		$this->addToolBar();

		// [3212] set the document
		$this->_prepareDocument();

		parent::display($tpl);
	}

	public function buildDetails($item)
	{
		// ensure we have reset the buckets
		$list = array();
		$details = array();
		$bucket = array();
		$user = JFactory::getUser($item->user);
		// get the name set
		if (!isset($item->name))
		{
			$item->name = $user->name;
		}
		// build the list
		$list = $item->name;
		// build the details
		$name		= (CostbenefitprojectionHelper::checkString($item->publicname)) ? $item->publicname : $item->name;
		$number		= (CostbenefitprojectionHelper::checkString($item->publicnumber)) ? $item->publicnumber : false;
		$email		= (CostbenefitprojectionHelper::checkString($item->publicemail)) ? $item->publicemail : $user->email;
		$address	= (CostbenefitprojectionHelper::checkString($item->publicaddress)) ? $item->publicaddress : false;
		// load to the bucket
		$bucket[] = '<b>'.JText::_('COM_COSTBENEFITPROJECTION_NAME').':</b> '.$name;
		if ($number)
		{
			$bucket[] = '<b>'.JText::_('COM_COSTBENEFITPROJECTION_NUMBER').':</b> '.$number;
		}
		if ($email)
		{
			$bucket[] = '<b>'.JText::_('COM_COSTBENEFITPROJECTION_EMAIL').':</b> '.$email;
		}
		if ($address)
		{
			$bucket[] = '<dl class="uk-description-list-horizontal"><dt>'.JText::_('COM_COSTBENEFITPROJECTION_ADDRESS').':</dt><dd>'.$address.'</dd></dl>';
		}
		// build details list
		$details = '<ul class="uk-list uk-list-striped"><li>'.implode('</li><li>', $bucket).'</li></ul>';

		return array('list' => $list, 'details' => $details);
	}

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// [3566] always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// [3568] Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// [3570] Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// [3575] Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// [3577] Set script size.
		$size = $this->params->get('uikit_min');
		// [3579] Set css style.
		$style = $this->params->get('uikit_style');

		// [3582] The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/uikit'.$style.$size.'.css');
		}
		// [3587] The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/uikit'.$size.'.js');
		}

		// [3596] Load the script to find all uikit components needed.
		if ($uikit != 2)
		{
			// [3599] Set the default uikit components in this view.
			$uikitComp = array();
			$uikitComp[] = 'uk-accordion';
			$uikitComp[] = 'data-uk-grid';

			// [3608] Get field uikit components needed in this view.
			$uikitFieldComp = $this->get('UikitComp');
			if (isset($uikitFieldComp) && CostbenefitprojectionHelper::checkArray($uikitFieldComp))
			{
				if (isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
				{
					$uikitComp = array_merge($uikitComp, $uikitFieldComp);
					$uikitComp = array_unique($uikitComp);
				}
				else
				{
					$uikitComp = $uikitFieldComp;
				}
			}
		}

		// [3624] Load the needed uikit components in this view.
		if ($uikit != 2 && isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
		{
			// [3627] load just in case.
			jimport('joomla.filesystem.file');
			// [3629] loading...
			foreach ($uikitComp as $class)
			{
				foreach (CostbenefitprojectionHelper::$uk_components[$class] as $name)
				{
					// [3634] check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// [3637] load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// [3640] check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js'))
					{
						// [3643] load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		}    
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_costbenefitprojection/assets/css/createaccount.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = CostbenefitprojectionHelper::getHelpUrl('createaccount');
		if (CostbenefitprojectionHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $help_url);
		}
		// now initiate the toolbar
		$this->toolbar = JToolbar::getInstance();
	}

        /**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var, $sorten = false, $length = 40)
	{
                // use the helper htmlEscape method instead.
		return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset, $sorten, $length);
	}
}
