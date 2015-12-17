<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			17th December, 2015
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
 * Costbenefitprojection View class for the Cpanel
 */
class CostbenefitprojectionViewCpanel extends JViewLegacy
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
		// [3083] Initialise variables.
		$this->items	= $this->get('Items');

		// [3112] Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// [3129] Set the toolbar
		$this->addToolBar();

		// [3131] set the document
		$this->_prepareDocument();

		parent::display($tpl);
	}

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// [3485] always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// [3487] Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// [3489] Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// [3494] Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// [3496] Set script size.
		$size = $this->params->get('uikit_min');
		// [3498] Set css style.
		$style = $this->params->get('uikit_style');

		// [3501] The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/uikit'.$style.$size.'.css');
		}
		// [3506] The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/uikit'.$size.'.js');
		}

		// [3515] Load the script to find all uikit components needed.
		if ($uikit != 2)
		{
			// [3518] Set the default uikit components in this view.
			$uikitComp = array();
			$uikitComp[] = 'data-uk-tooltip';
			$uikitComp[] = 'data-uk-grid';
			$uikitComp[] = 'uk-accordion';
			$uikitComp[] = 'data-uk-lightbox';

			// [3527] Get field uikit components needed in this view.
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

		// [3543] Load the needed uikit components in this view.
		if ($uikit != 2 && isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
		{
			// [3546] load just in case.
			jimport('joomla.filesystem.file');
			// [3548] loading...
			foreach ($uikitComp as $class)
			{
				foreach (CostbenefitprojectionHelper::$uk_components[$class] as $name)
				{
					// [3553] check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// [3556] load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// [3559] check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js'))
					{
						// [3562] load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		} 

		// [3467] add the google chart builder class.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/chartbuilder.php';
		// [3469] load the google chart js.
		$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/rgbcolor.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/canvg.js'); 

		// [6599] Add the CSS for Footable.
		$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.core.min.css');

		// [6601] Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.metro.min.css');
		}
		// [6606] Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.standalone.min.css');
		}

		// [6611] Add the JavaScript for Footable
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.sort.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.filter.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.paginate.js'); 
		// [3443] load the meta description
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// [3448] load the key words if set
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// [3453] check the robot params
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		} 
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_costbenefitprojection/assets/css/cpanel.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = CostbenefitprojectionHelper::getHelpUrl('cpanel');
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
