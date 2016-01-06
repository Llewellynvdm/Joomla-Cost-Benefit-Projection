<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			6th January, 2016
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
 * Costbenefitprojection View class for the Combinedresults
 */
class CostbenefitprojectionViewCombinedresults extends JViewLegacy
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
		// [3169] Initialise variables.
		$this->items	= $this->get('Items');

		// [3198] Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
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

		// [3215] Set the toolbar
		$this->addToolBar();

		// [3217] set the document
		$this->_prepareDocument();

		parent::display($tpl);
	}

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// [3575] always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// [3577] Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// [3579] Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// [3584] Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// [3586] Set script size.
		$size = $this->params->get('uikit_min');
		// [3588] Set css style.
		$style = $this->params->get('uikit_style');

		// [3591] The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/uikit'.$style.$size.'.css');
		}
		// [3596] The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/uikit'.$size.'.js');
		}

		// [3661] Load the needed uikit components in this view.
		$uikitComp = $this->get('UikitComp');
		if ($uikit != 2 && isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
		{
			// [3665] load just in case.
			jimport('joomla.filesystem.file');
			// [3667] loading...
			foreach ($uikitComp as $class)
			{
				foreach (CostbenefitprojectionHelper::$uk_components[$class] as $name)
				{
					// [3672] check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// [3675] load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// [3678] check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js'))
					{
						// [3681] load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		} 

		// [3557] add the google chart builder class.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/chartbuilder.php';
		// [3559] load the google chart js.
		$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/rgbcolor.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/canvg.js'); 

		// [6775] Add the CSS for Footable.
		$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.core.min.css');

		// [6777] Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.metro.min.css');
		}
		// [6782] Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.standalone.min.css');
		}

		// [6787] Add the JavaScript for Footable
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.sort.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.filter.js');
		$this->document->addScript(JURI::root() .'media/com_costbenefitprojection/footable/js/footable.paginate.js');  
		// add custom css
		$this->document->addStyleSheet(JURI::root(true) ."/administrator/components/com_costbenefitprojection/assets/css/dashboard.css");
		// add custom js
		$this->document->addScript(JURI::root(true)  .'/media/com_costbenefitprojection/js/chartMenu.js');
		// set chart stuff
		$this->Chart['backgroundColor'] = $this->params->get('admin_chartbackground');
		$this->Chart['width'] = $this->params->get('admin_mainwidth');
		$this->Chart['chartArea'] = array('top' => $this->params->get('admin_chartareatop'), 'left' => $this->params->get('admin_chartarealeft'), 'width' => $this->params->get('site_chartareawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('site_legendtextstylefontsize'), 'color' => $this->params->get('site_legendtextstylefontcolor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('site_vaxistextstylefontcolor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('site_haxistextstylefontcolor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('site_haxistitletextstylefontcolor'));
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_costbenefitprojection/assets/css/combinedresults.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = CostbenefitprojectionHelper::getHelpUrl('combinedresults');
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
