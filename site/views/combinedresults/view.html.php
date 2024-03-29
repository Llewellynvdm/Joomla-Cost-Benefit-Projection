<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
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

use Joomla\CMS\MVC\View\HtmlView;

/**
 * Costbenefitprojection Html View class for the Combinedresults
 */
class CostbenefitprojectionViewCombinedresults extends HtmlView
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
		// Initialise variables.
		$this->items = $this->get('Items');
		// check if the data was returned
		if ($this->items)
		{
			// combine the results
			$this->results = CostbenefitprojectionHelper::combine($this->items);
			// set the companies names
			$this->names = $this->results->companiesNames;
			$this->item = new stdClass();
			$this->item->currency_name = $this->results->currencyDetails->currency_name;
		}
		else
		{
			// int all as false
			$this->results = false;
			// set the companies names
			$this->names = JText::_('COM_COSTBENEFITPROJECTION_NONE_LOADED');
			$this->item = new stdClass();
			$this->item->currency_name = '';
		}

		// Set the toolbar
		$this->addToolBar();

		// set the document
		$this->_prepareDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new costbenefitprojectionHeaderCheck;

		// Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// Set script size.
		$size = $this->params->get('uikit_min');
		// Set css style.
		$style = $this->params->get('uikit_style');

		// The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			JHtml::_('stylesheet', 'media/com_costbenefitprojection/uikit-v2/css/uikit'.$style.$size.'.css', ['version' => 'auto']);
		}
		// The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			JHtml::_('script', 'media/com_costbenefitprojection/uikit-v2/js/uikit'.$size.'.js', ['version' => 'auto']);
		}

		// Load the needed uikit components in this view.
		$uikitComp = $this->get('UikitComp');
		if ($uikit != 2 && isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
		{
			// loading...
			foreach ($uikitComp as $class)
			{
				foreach (CostbenefitprojectionHelper::$uk_components[$class] as $name)
				{
					// check if the CSS file exists.
					if (File::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit-v2/css/components/'.$name.$style.$size.'.css'))
					{
						// load the css.
						JHtml::_('stylesheet', 'media/com_costbenefitprojection/uikit-v2/css/components/'.$name.$style.$size.'.css', ['version' => 'auto']);
					}
					// check if the JavaScript file exists.
					if (File::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit-v2/js/components/'.$name.$size.'.js'))
					{
						// load the js.
						JHtml::_('script', 'media/com_costbenefitprojection/uikit-v2/js/components/'.$name.$size.'.js', ['version' => 'auto'], ['type' => 'text/javascript', 'async' => 'async']);
					}
				}
			}
		}

		// add the google chart builder class.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/chartbuilder.php';
		// load the google chart js.
		JHtml::_('script', 'media/com_costbenefitprojection/js/google.jsapi.js', ['version' => 'auto']);
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/rgbcolor.js', ['version' => 'auto']);
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/canvg.js', ['version' => 'auto']);

		// Add the CSS for Footable.
		JHtml::_('stylesheet', 'media/com_costbenefitprojection/footable-v2/css/footable.core.min.css', ['version' => 'auto']);

		// Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			JHtml::_('stylesheet', 'media/com_costbenefitprojection/footable-v2/css/footable.metro.min.css', ['version' => 'auto']);
		}
		// Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			JHtml::_('stylesheet', 'media/com_costbenefitprojection/footable-v2/css/footable.standalone.min.css', ['version' => 'auto']);
		}

		// Add the JavaScript for Footable
		JHtml::_('script', 'media/com_costbenefitprojection/footable-v2/js/footable.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_costbenefitprojection/footable-v2/js/footable.sort.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_costbenefitprojection/footable-v2/js/footable.filter.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_costbenefitprojection/footable-v2/js/footable.paginate.js', ['version' => 'auto']);
		// add custom css
		$this->document->addStyleSheet(JURI::root(true) ."/administrator/components/com_costbenefitprojection/assets/css/dashboard.css");
		// add custom js
		$this->document->addScript(JURI::root(true)  .'/media/com_costbenefitprojection/js/chartMenu.js');
		$this->document->addScript(JURI::root(true)  .'/media/com_costbenefitprojection/js/table2excel.js');
		// set chart stuff
		$this->Chart['backgroundColor'] = $this->params->get('admin_chartbackground');
		$this->Chart['width'] = $this->params->get('admin_mainwidth');
		$this->Chart['chartArea'] = array('top' => $this->params->get('admin_chartareatop'), 'left' => $this->params->get('admin_chartarealeft'), 'width' => $this->params->get('site_chartareawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('site_legendtextstylefontsize'), 'color' => $this->params->get('site_legendtextstylefontcolor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('site_vaxistextstylefontcolor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('site_haxistextstylefontcolor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('site_haxistitletextstylefontcolor'));
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_costbenefitprojection/assets/css/combinedresults.css', (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		
		// set help url for this view if found
		$this->help_url = CostbenefitprojectionHelper::getHelpUrl('combinedresults');
		if (CostbenefitprojectionHelper::checkString($this->help_url))
		{
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $this->help_url);
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
