<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.2.0
	@build			12th January, 2016
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
 * Costbenefitprojection View class for the Interventions
 */
class CostbenefitprojectionViewInterventions extends JViewLegacy
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

		// Check for errors.
		if (count($errors = $this->get('Errors')))
                {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign data to the view
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->user 		= JFactory::getUser();
		$this->listOrder	= $this->escape($this->state->get('list.ordering'));
		$this->listDirn		= $this->escape($this->state->get('list.direction'));
		$this->saveOrder	= $this->listOrder == 'ordering';
                // get global action permissions
		$this->canDo		= CostbenefitprojectionHelper::getActions('intervention');
		$this->canEdit		= $this->canDo->get('intervention.edit');
		$this->canState		= $this->canDo->get('intervention.edit.state');
		$this->canCreate	= $this->canDo->get('intervention.create');
		$this->canDelete	= $this->canDo->get('intervention.delete');
		$this->canBatch	= $this->canDo->get('core.batch');

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
                $help_url = CostbenefitprojectionHelper::getHelpUrl('interventions');
                if (CostbenefitprojectionHelper::checkString($help_url))
                {
                        JToolbarHelper::help('COM_COSTBENEFITPROJECTION_HELP_MANAGER', false, $help_url);
                }

                // add the options comp button
                if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
                {
                        JToolBarHelper::preferences('com_costbenefitprojection');
                }

                if ($this->canState)
                {
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
                        // only load if batch allowed
                        if ($this->canBatch)
                        {
                            JHtmlBatch_::addListSelection(
                                JText::_('COM_COSTBENEFITPROJECTION_KEEP_ORIGINAL_STATE'),
                                'batch[published]',
                                JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
                            );
                        }
		}

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
                                JText::_('COM_COSTBENEFITPROJECTION_KEEP_ORIGINAL_ACCESS'),
                                'batch[access]',
                                JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text')
			);
                }  

		// [9406] Set Company Name Selection
		$this->companyNameOptions = JFormHelper::loadFieldType('Company')->getOptions();
		if ($this->companyNameOptions)
		{
			// [9410] Company Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL').' -',
				'filter_company',
				JHtml::_('select.options', $this->companyNameOptions, 'value', 'text', $this->state->get('filter.company'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [9419] Company Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL').' -',
					'batch[company]',
					JHtml::_('select.options', $this->companyNameOptions, 'value', 'text')
				);
			}
		}

		// [9440] Set Type Selection
		$this->typeOptions = $this->getTheTypeSelections();
		if ($this->typeOptions)
		{
			// [9444] Type Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL').' -',
				'filter_type',
				JHtml::_('select.options', $this->typeOptions, 'value', 'text', $this->state->get('filter.type'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [9453] Type Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL').' -',
					'batch[type]',
					JHtml::_('select.options', $this->typeOptions, 'value', 'text')
				);
			}
		}

		// [9440] Set Coverage Selection
		$this->coverageOptions = $this->getTheCoverageSelections();
		if ($this->coverageOptions)
		{
			// [9444] Coverage Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL').' -',
				'filter_coverage',
				JHtml::_('select.options', $this->coverageOptions, 'value', 'text', $this->state->get('filter.coverage'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [9453] Coverage Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL').' -',
					'batch[coverage]',
					JHtml::_('select.options', $this->coverageOptions, 'value', 'text')
				);
			}
		}

		// [9440] Set Duration Selection
		$this->durationOptions = $this->getTheDurationSelections();
		if ($this->durationOptions)
		{
			// [9444] Duration Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL').' -',
				'filter_duration',
				JHtml::_('select.options', $this->durationOptions, 'value', 'text', $this->state->get('filter.duration'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [9453] Duration Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL').' -',
					'batch[duration]',
					JHtml::_('select.options', $this->durationOptions, 'value', 'text')
				);
			}
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_COSTBENEFITPROJECTION_INTERVENTIONS'));
		$document->addStyleSheet(JURI::root() . "administrator/components/com_costbenefitprojection/assets/css/interventions.css");
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
			'a.sorting' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NAME_LABEL'),
			'g.name' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL'),
			'a.type' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL'),
			'a.coverage' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL'),
			'a.duration' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL'),
			'a.description' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DESCRIPTION_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	} 

	protected function getTheTypeSelections()
	{
		// [9316] Get a db connection.
		$db = JFactory::getDbo();

		// [9318] Create a new query object.
		$query = $db->getQuery(true);

		// [9320] Select the text.
		$query->select($db->quoteName('type'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('type') . ' ASC');

		// [9324] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [9332] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $type)
			{
				// [9343] Translate the type selection
				$text = $model->selectionTranslation($type,'type');
				// [9345] Now add the type and its text to the options array
				$filter[] = JHtml::_('select.option', $type, JText::_($text));
			}
			return $filter;
		}
		return false;
	}

	protected function getTheCoverageSelections()
	{
		// [9316] Get a db connection.
		$db = JFactory::getDbo();

		// [9318] Create a new query object.
		$query = $db->getQuery(true);

		// [9320] Select the text.
		$query->select($db->quoteName('coverage'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('coverage') . ' ASC');

		// [9324] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $coverage)
			{
				// [9350] Now add the coverage and its text to the options array
				$filter[] = JHtml::_('select.option', $coverage, $coverage);
			}
			return $filter;
		}
		return false;
	}

	protected function getTheDurationSelections()
	{
		// [9316] Get a db connection.
		$db = JFactory::getDbo();

		// [9318] Create a new query object.
		$query = $db->getQuery(true);

		// [9320] Select the text.
		$query->select($db->quoteName('duration'));
		$query->from($db->quoteName('#__costbenefitprojection_intervention'));
		$query->order($db->quoteName('duration') . ' ASC');

		// [9324] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $duration)
			{
				// [9350] Now add the duration and its text to the options array
				$filter[] = JHtml::_('select.option', $duration, $duration);
			}
			return $filter;
		}
		return false;
	}
}
