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
 * Costbenefitprojection Html View class for the Interventions
 */
class CostbenefitprojectionViewInterventions extends HtmlView
{
	/**
	 * Interventions view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			CostbenefitprojectionHelper::addSubmenu('interventions');
		}

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		// Add the list ordering clause.
		$this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
		$this->listDirn = $this->escape($this->state->get('list.direction', 'DESC'));
		$this->saveOrder = $this->listOrder == 'a.ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) JUri::getInstance()));
		// get global action permissions
		$this->canDo = CostbenefitprojectionHelper::getActions('intervention');
		$this->canEdit = $this->canDo->get('intervention.edit');
		$this->canState = $this->canDo->get('intervention.edit.state');
		$this->canCreate = $this->canDo->get('intervention.create');
		$this->canDelete = $this->canDo->get('intervention.delete');
		$this->canBatch = $this->canDo->get('core.batch');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
			// load the batch html
			if ($this->canCreate && $this->canEdit && $this->canState)
			{
				$this->batchDisplay = JHtmlBatch_::render();
			}
		}
		
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
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_INTERVENTIONS'), 'wand');
		JHtmlSidebar::setAction('index.php?option=com_costbenefitprojection&view=interventions');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('intervention.add');
		}

		// Only load if there are items
		if (CostbenefitprojectionHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('intervention.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('interventions.publish');
				JToolBarHelper::unpublishList('interventions.unpublish');
				JToolBarHelper::archiveList('interventions.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('interventions.checkin');
				}
			}

			// Add a batch button
			if ($this->canBatch && $this->canCreate && $this->canEdit && $this->canState)
			{
				// Get the toolbar object instance
				$bar = JToolBar::getInstance('toolbar');
				// set the batch button name
				$title = JText::_('JTOOLBAR_BATCH');
				// Instantiate a new JLayoutFile instance and render the batch button
				$layout = new JLayoutFile('joomla.toolbar.batch');
				// add the button to the page
				$dhtml = $layout->render(array('title' => $title));
				$bar->appendButton('Custom', $dhtml, 'batch');
			}

			if ($this->state->get('filter.published') == -2 && ($this->canState && $this->canDelete))
			{
				JToolbarHelper::deleteList('', 'interventions.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('interventions.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('intervention.export'))
			{
				JToolBarHelper::custom('interventions.exportData', 'download', '', 'COM_COSTBENEFITPROJECTION_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('intervention.import'))
		{
			JToolBarHelper::custom('interventions.importData', 'upload', '', 'COM_COSTBENEFITPROJECTION_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$this->help_url = CostbenefitprojectionHelper::getHelpUrl('interventions');
		if (CostbenefitprojectionHelper::checkString($this->help_url))
		{
				JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $this->help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			JToolBarHelper::preferences('com_costbenefitprojection');
		}

		// Only load publish filter if state change is allowed
		if ($this->canState)
		{
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
		}

		// Set Company Name Selection
		$this->companyNameOptions = JFormHelper::loadFieldType('Company')->options;
		// We do some sanitation for Company Name filter
		if (CostbenefitprojectionHelper::checkArray($this->companyNameOptions) &&
			isset($this->companyNameOptions[0]->value) &&
			!CostbenefitprojectionHelper::checkString($this->companyNameOptions[0]->value))
		{
			unset($this->companyNameOptions[0]);
		}
		// Only load Company Name filter if it has values
		if (CostbenefitprojectionHelper::checkArray($this->companyNameOptions))
		{
			// Company Name Filter
			JHtmlSidebar::addFilter(
				'- Select ' . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL') . ' -',
				'filter_company',
				JHtml::_('select.options', $this->companyNameOptions, 'value', 'text', $this->state->get('filter.company'))
			);
		}

		// Set Type Selection
		$this->typeOptions = $this->getTheTypeSelections();
		// We do some sanitation for Type filter
		if (CostbenefitprojectionHelper::checkArray($this->typeOptions) &&
			isset($this->typeOptions[0]->value) &&
			!CostbenefitprojectionHelper::checkString($this->typeOptions[0]->value))
		{
			unset($this->typeOptions[0]);
		}
		// Only load Type filter if it has values
		if (CostbenefitprojectionHelper::checkArray($this->typeOptions))
		{
			// Type Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL').' -',
				'filter_type',
				JHtml::_('select.options', $this->typeOptions, 'value', 'text', $this->state->get('filter.type'))
			);
		}

		// Set Coverage Selection
		$this->coverageOptions = $this->getTheCoverageSelections();
		// We do some sanitation for Coverage filter
		if (CostbenefitprojectionHelper::checkArray($this->coverageOptions) &&
			isset($this->coverageOptions[0]->value) &&
			!CostbenefitprojectionHelper::checkString($this->coverageOptions[0]->value))
		{
			unset($this->coverageOptions[0]);
		}
		// Only load Coverage filter if it has values
		if (CostbenefitprojectionHelper::checkArray($this->coverageOptions))
		{
			// Coverage Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL').' -',
				'filter_coverage',
				JHtml::_('select.options', $this->coverageOptions, 'value', 'text', $this->state->get('filter.coverage'))
			);
		}

		// Set Duration Selection
		$this->durationOptions = $this->getTheDurationSelections();
		// We do some sanitation for Duration filter
		if (CostbenefitprojectionHelper::checkArray($this->durationOptions) &&
			isset($this->durationOptions[0]->value) &&
			!CostbenefitprojectionHelper::checkString($this->durationOptions[0]->value))
		{
			unset($this->durationOptions[0]);
		}
		// Only load Duration filter if it has values
		if (CostbenefitprojectionHelper::checkArray($this->durationOptions))
		{
			// Duration Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL').' -',
				'filter_duration',
				JHtml::_('select.options', $this->durationOptions, 'value', 'text', $this->state->get('filter.duration'))
			);
		}

		// Only load published batch if state and batch is allowed
		if ($this->canState && $this->canBatch)
		{
			JHtmlBatch_::addListSelection(
				JText::_('COM_COSTBENEFITPROJECTION_KEEP_ORIGINAL_STATE'),
				'batch[published]',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
			);
		}

		// Only load Company Name batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Company Name Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL').' -',
				'batch[company]',
				JHtml::_('select.options', $this->companyNameOptions, 'value', 'text')
			);
		}

		// Only load Type batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Type Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL').' -',
				'batch[type]',
				JHtml::_('select.options', $this->typeOptions, 'value', 'text')
			);
		}

		// Only load Coverage batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Coverage Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL').' -',
				'batch[coverage]',
				JHtml::_('select.options', $this->coverageOptions, 'value', 'text')
			);
		}

		// Only load Duration batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Duration Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL').' -',
				'batch[duration]',
				JHtml::_('select.options', $this->durationOptions, 'value', 'text')
			);
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_('COM_COSTBENEFITPROJECTION_INTERVENTIONS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_costbenefitprojection/assets/css/interventions.css", (CostbenefitprojectionHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
		if(strlen($var) > 50)
		{
			// use the helper htmlEscape method instead and shorten the string
			return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset, true);
		}
		// use the helper htmlEscape method instead.
		return CostbenefitprojectionHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NAME_LABEL'),
			'g.name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL'),
			'a.type' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL'),
			'a.coverage' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL'),
			'a.description' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DESCRIPTION_LABEL'),
			'a.duration' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheTypeSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('type'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('type') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();
		$_filter = array();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			foreach ($results as $type)
			{
				// Translate the type selection
				$text = $model->selectionTranslation($type,'type');
				// Now add the type and its text to the options array
				$_filter[] = JHtml::_('select.option', $type, JText::_($text));
			}
		}
		return $_filter;
	}

	protected function getTheCoverageSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('coverage'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('coverage') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();
		$_filter = array();

		if ($results)
		{
			$results = array_unique($results);
			foreach ($results as $coverage)
			{
				// Now add the coverage and its text to the options array
				$_filter[] = JHtml::_('select.option', $coverage, $coverage);
			}
		}
		return $_filter;
	}

	protected function getTheDurationSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('duration'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('duration') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();
		$_filter = array();

		if ($results)
		{
			$results = array_unique($results);
			foreach ($results as $duration)
			{
				// Now add the duration and its text to the options array
				$_filter[] = JHtml::_('select.option', $duration, $duration);
			}
		}
		return $_filter;
	}
}
