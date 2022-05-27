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
 * Service_provider Html View class
 */
class CostbenefitprojectionViewService_provider extends HtmlView
{
	/**
	 * display method of View
	 * @return void
	 */
	public function display($tpl = null)
	{
		// set params
		$this->params = JComponentHelper::getParams('com_costbenefitprojection');
		// Assign the variables
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		$this->state = $this->get('State');
		// get action permissions
		$this->canDo = CostbenefitprojectionHelper::getActions('service_provider', $this->item);
		// get input
		$jinput = JFactory::getApplication()->input;
		$this->ref = $jinput->get('ref', 0, 'word');
		$this->refid = $jinput->get('refid', 0, 'int');
		$return = $jinput->get('return', null, 'base64');
		// set the referral string
		$this->referral = '';
		if ($this->refid && $this->ref)
		{
			// return to the item that referred to this item
			$this->referral = '&ref=' . (string)$this->ref . '&refid=' . (int)$this->refid;
		}
		elseif($this->ref)
		{
			// return to the list view that referred to this item
			$this->referral = '&ref=' . (string)$this->ref;
		}
		// check return value
		if (!is_null($return))
		{
			// add the return value
			$this->referral .= '&return=' . (string)$return;
		}

		// Get Linked view data
		$this->vwecompanies = $this->get('Vwecompanies');

		// Set the toolbar
		$this->addToolBar();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}


	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		JToolbarHelper::title( JText::_($isNew ? 'COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_NEW' : 'COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (CostbenefitprojectionHelper::checkString($this->referral))
		{
			if ($this->canDo->get('service_provider.create') && $isNew)
			{
				// We can create the record.
				JToolBarHelper::save('service_provider.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('service_provider.edit'))
			{
				// We can save the record.
				JToolBarHelper::save('service_provider.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not creat but cancel.
				JToolBarHelper::cancel('service_provider.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				JToolBarHelper::cancel('service_provider.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('service_provider.create'))
				{
					JToolBarHelper::apply('service_provider.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('service_provider.save', 'JTOOLBAR_SAVE');
					JToolBarHelper::custom('service_provider.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				JToolBarHelper::cancel('service_provider.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('service_provider.edit'))
				{
					// We can save the new record
					JToolBarHelper::apply('service_provider.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('service_provider.save', 'JTOOLBAR_SAVE');
					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($this->canDo->get('service_provider.create'))
					{
						JToolBarHelper::custom('service_provider.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('service_provider.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('service_provider.edit') && $canVersion)
				{
					JToolbarHelper::versions('com_costbenefitprojection.service_provider', $this->item->id);
				}
				if ($this->canDo->get('service_provider.create'))
				{
					JToolBarHelper::custom('service_provider.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				JToolBarHelper::cancel('service_provider.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		JToolbarHelper::divider();
		// set help url for this view if found
		$this->help_url = CostbenefitprojectionHelper::getHelpUrl('service_provider');
		if (CostbenefitprojectionHelper::checkString($this->help_url))
		{
			JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $this->help_url);
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
		if(strlen($var) > 30)
		{
    		// use the helper htmlEscape method instead and shorten the string
			return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset, true, 30);
		}
		// use the helper htmlEscape method instead.
		return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_($isNew ? 'COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_NEW' : 'COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_EDIT'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_costbenefitprojection/assets/css/service_provider.css", (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');

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

		$footable = "jQuery(document).ready(function() { jQuery(function () { jQuery('.footable').footable(); }); jQuery('.nav-tabs').on('click', 'li', function() { setTimeout(tableFix, 10); }); }); function tableFix() { jQuery('.footable').trigger('footable_resize'); }";
		$this->document->addScriptDeclaration($footable);

		$this->document->addScript(JURI::root() . $this->script, (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript');
		$this->document->addScript(JURI::root() . "administrator/components/com_costbenefitprojection/views/service_provider/submitbutton.js", (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript'); 
		JText::script('view not acceptable. Error');
	}
}
