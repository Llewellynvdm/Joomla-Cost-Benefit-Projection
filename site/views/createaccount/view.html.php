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
jimport('joomla.application.module.helper');

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Filesystem\File;

/**
 * Costbenefitprojection Html View class for the Createaccount
 */
class CostbenefitprojectionViewCreateaccount extends HtmlView
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

		// Load the script to find all uikit components needed.
		if ($uikit != 2)
		{
			// Set the default uikit components in this view.
			$uikitComp = array();
			$uikitComp[] = 'uk-accordion';
			$uikitComp[] = 'data-uk-grid';

			// Get field uikit components needed in this view.
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

		// Load the needed uikit components in this view.
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
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_costbenefitprojection/assets/css/createaccount.css', (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		
		// set help url for this view if found
		$this->help_url = CostbenefitprojectionHelper::getHelpUrl('createaccount');
		if (CostbenefitprojectionHelper::checkString($this->help_url))
		{
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $this->help_url);
		}
		// now initiate the toolbar
		$this->toolbar = JToolbar::getInstance();
	}

	/**
	 * Get the modules published in a position
	 */
	public function getModules($position, $seperator = '', $class = '')
	{
		// set default
		$found = false;
		// check if we aleady have these modules loaded
		if (isset($this->setModules[$position]))
		{
			$found = true;
		}
		else
		{
			// this is where you want to load your module position
			$modules = JModuleHelper::getModules($position);
			if (CostbenefitprojectionHelper::checkArray($modules, true))
			{
				// set the place holder
				$this->setModules[$position] = array();
				foreach($modules as $module)
				{
					$this->setModules[$position][] = JModuleHelper::renderModule($module);
				}
				$found = true;
			}
		}
		// check if modules were found
		if ($found && isset($this->setModules[$position]) && CostbenefitprojectionHelper::checkArray($this->setModules[$position]))
		{
			// set class
			if (CostbenefitprojectionHelper::checkString($class))
			{
				$class = ' class="'.$class.'" ';
			}
			// set seperating return values
			switch($seperator)
			{
				case 'none':
					return implode('', $this->setModules[$position]);
					break;
				case 'div':
					return '<div'.$class.'>'.implode('</div><div'.$class.'>', $this->setModules[$position]).'</div>';
					break;
				case 'list':
					return '<ul'.$class.'><li>'.implode('</li><li>', $this->setModules[$position]).'</li></ul>';
					break;
				case 'array':
				case 'Array':
					return $this->setModules[$position];
					break;
				default:
					return implode('<br />', $this->setModules[$position]);
					break;
				
			}
		}
		return false;
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
