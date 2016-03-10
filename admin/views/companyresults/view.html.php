<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
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
 * Costbenefitprojection View class for the Companyresults
 */
class CostbenefitprojectionViewCompanyresults extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
                // get component params
		$this->params	= JComponentHelper::getParams('com_costbenefitprojection');
		// get the application
		$this->app	= JFactory::getApplication();
		// get the user object
		$this->user	= JFactory::getUser();
                // get global action permissions
		$this->canDo	= CostbenefitprojectionHelper::getActions('companyresults');
		// Initialise variables.
		$this->item	= $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		// check if the data was returned
		if ($this->item)
		{
			// get results
			$data = base64_encode(serialize($this->item));
			$this->results = CostbenefitprojectionHelper::calculate($this->item->id,$data);
			// set the tab details
			$this->chart_tabs = $this->getChartTabs();
			$this->table_tabs = $this->getTableTabs();
		}
		else
		{
			// int all as false
			$this->results = false;
			// set the tab details
			$this->chart_tabs = false;
			$this->table_tabs = false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// add the tool bar
			$this->addToolBar();
		}

		// set the document
		$this->setDocument();

		parent::display($tpl);
	}

	protected function getChartTabs()
	{				
		// Work Days Lost
		$items[0] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST'), 'view' => 'wdl', 'img' => 'media/com_costbenefitprojection/images/charts.png');
		
		// Work days Lost Percent
		$items[1] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST_PERCENT'), 'view' => 'wdlp', 'img' => 'media/com_costbenefitprojection/images/charts.png');
		
		// Cost
		$items[2] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_COST'), 'view' => 'c', 'img' => 'media/com_costbenefitprojection/images/charts.png');
		
		// Cost Percent
		$items[3] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_COST_PERCENT'), 'view' => 'cp', 'img' => 'media/com_costbenefitprojection/images/charts.png');
		
		// Intervention Cost Benefit
		$items[4] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_BENEFIT'), 'view' => 'icb', 'img' => 'media/com_costbenefitprojection/images/charts.png');
		
		return $items;
	}
		
	protected function getTableTabs()
	{
		// Work Days Lost Summary
		$items[0] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST_SUMMARY'), 'view' => 'wdls', 'img' => 'media/com_costbenefitprojection/images/tables.png');
		
		// Cost Summary
		$items[1] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_COST_SUMMARY'), 'view' => 'cs', 'img' => 'media/com_costbenefitprojection/images/tables.png');
		
		// Calculated Costs in Detail
		$items[2] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_CALCULATED_COSTS_IN_DETAIL'), 'view' => 'ccid', 'img' => 'media/com_costbenefitprojection/images/tables.png');
		
		// Intervention Net Benefit
		$items[3] = array('name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NET_BENEFIT'), 'view' => 'inb', 'img' => 'media/com_costbenefitprojection/images/tables.png');
		
		return $items;
	}

        /**
	 * Prepares the document
	 */
	protected function setDocument()
	{
		// load the meta description
		if (isset($this->item->metadesc) && $this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// load the key words if set
		if (isset($this->item->metakey) && $this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// check the robot params
		if (isset($this->item->robots) && $this->item->robots)
		{
			$this->document->setMetadata('robots', $this->item->robots);
		}
		elseif ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		// check if autor is to be set
		if (isset($this->item->created_by) && $this->params->get('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->item->created_by);
		}
		// check if metadata is available
		if (isset($this->item->metadata) && $this->item->metadata)
		{
			$mdata = json_decode($this->item->metadata,true);
			foreach ($mdata as $k => $v)
			{
				if ($v)
				{
					$this->document->setMetadata($k, $v);
				}
			}
		} 

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// Set script size.
		$size = $this->params->get('uikit_min');
		// Set css style.
		$style = $this->params->get('uikit_style');

		// The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/uikit'.$style.$size.'.css');
		}
		// The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/uikit'.$size.'.js');
		}

		// Load the needed uikit components in this view.
		$uikitComp = $this->get('UikitComp');
		if ($uikit != 2 && isset($uikitComp) && CostbenefitprojectionHelper::checkArray($uikitComp))
		{
			// load just in case.
			jimport('joomla.filesystem.file');
			// loading...
			foreach ($uikitComp as $class)
			{
				foreach (CostbenefitprojectionHelper::$uk_components[$class] as $name)
				{
					// check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_costbenefitprojection/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js'))
					{
						// load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		} 

		// add the google chart builder class.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/chartbuilder.php';
		// load the google chart js.
		$this->document->addScript(JURI::root(true) .'/media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/rgbcolor.js');
		$this->document->addScript('https://canvg.googlecode.com/svn/trunk/canvg.js'); 

		// Add the CSS for Footable.
		$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.core.min.css');

		// Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.metro.min.css');
		}
		// Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_costbenefitprojection/footable/css/footable.standalone.min.css');
		}

		// Add the JavaScript for Footable
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
		$this->Chart['chartArea'] = array('top' => $this->params->get('admin_chartareatop'), 'left' => $this->params->get('admin_chartarealeft'), 'width' => $this->params->get('admin_chartareawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('admin_legendtextstylefontsize'), 'color' => $this->params->get('admin_legendtextstylefontcolor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('admin_vaxistextstylefontcolor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('admin_haxistextstylefontcolor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('admin_haxistitletextstylefontcolor'));
		
		// notice session controller
		$session = JFactory::getSession();
		$this->menuNotice = $session->get( 'CT_SubMenuNotice', 'empty' );
		if ($this->menuNotice == 'empty' ){
			$session->set( 'CT_SubMenuNotice', '1' );
		}
		elseif ($this->menuNotice < 6 ) 
		{
			$this->menuNotice++;
			$session->set( 'CT_SubMenuNotice', $this->menuNotice);
		}
                // add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/administrator/components/com_costbenefitprojection/assets/css/companyresults.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// hide the main menu
		$this->app->input->set('hidemainmenu', true);
		// set the title
		if ($this->item->name)
		{
			$title = $this->item->name;
		}
		// Check for empty title and add view name if param is set
		if (empty($title))
		{
			$title = JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS');
		}
		// add title to the page
		JToolbarHelper::title($title,'chart');
                // add the back button
                // JToolBarHelper::custom('companyresults.back', 'undo-2', '', 'COM_COSTBENEFITPROJECTION_BACK', false);
                // add cpanel button
		JToolBarHelper::custom('companyresults.dashboard', 'grid-2', '', 'COM_COSTBENEFITPROJECTION_DASH', false);
		if ($this->canDo->get('companyresults.companies'))
		{
			// add Companies button.
			JToolBarHelper::custom('companyresults.gotoCompanies', 'vcard', '', 'COM_COSTBENEFITPROJECTION_COMPANIES', false);
		}
		if ($this->canDo->get('companyresults.edit'))
		{
			// add Edit button.
			JToolBarHelper::custom('companyresults.editCompany', 'pencil', '', 'COM_COSTBENEFITPROJECTION_EDIT', false);
		}

		// set help url for this view if found
                $help_url = CostbenefitprojectionHelper::getHelpUrl('companyresults');
                if (CostbenefitprojectionHelper::checkString($help_url))
                {
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $help_url);
                }

                // add the options comp button
                if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_costbenefitprojection');
		}
	}

        /**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
                // use the helper htmlEscape method instead.
		return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset);
	}
}
?>