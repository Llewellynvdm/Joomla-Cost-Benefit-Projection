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
 * Costbenefitprojection View class for the Health_data_sets
 */
class CostbenefitprojectionViewHealth_data_sets extends JViewLegacy
{
	/**
	 * Health_data_sets view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			CostbenefitprojectionHelper::addSubmenu('health_data_sets');
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
		$this->canDo		= CostbenefitprojectionHelper::getActions('health_data');
		$this->canEdit		= $this->canDo->get('health_data.edit');
		$this->canState		= $this->canDo->get('health_data.edit.state');
		$this->canCreate	= $this->canDo->get('health_data.create');
		$this->canDelete	= $this->canDo->get('health_data.delete');
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
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_SETS'), 'health');
		JHtmlSidebar::setAction('index.php?option=com_costbenefitprojection&view=health_data_sets');
                JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
                {
			JToolBarHelper::addNew('health_data.add');
		}

                // Only load if there are items
                if (CostbenefitprojectionHelper::checkArray($this->items))
		{
                        if ($this->canEdit)
                        {
                            JToolBarHelper::editList('health_data.edit');
                        }

                        if ($this->canState)
                        {
                            JToolBarHelper::publishList('health_data_sets.publish');
                            JToolBarHelper::unpublishList('health_data_sets.unpublish');
                            JToolBarHelper::archiveList('health_data_sets.archive');

                            if ($this->canDo->get('core.admin'))
                            {
                                JToolBarHelper::checkin('health_data_sets.checkin');
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
                            JToolbarHelper::deleteList('', 'health_data_sets.delete', 'JTOOLBAR_EMPTY_TRASH');
                        }
                        elseif ($this->canState && $this->canDelete)
                        {
                                JToolbarHelper::trash('health_data_sets.trash');
                        }

			if ($this->canDo->get('core.export') && $this->canDo->get('health_data.export'))
			{
				JToolBarHelper::custom('health_data_sets.exportData', 'download', '', 'COM_COSTBENEFITPROJECTION_EXPORT_DATA', true);
			}
                }

		if ($this->canDo->get('core.import') && $this->canDo->get('health_data.import'))
		{
			JToolBarHelper::custom('health_data_sets.importData', 'upload', '', 'COM_COSTBENEFITPROJECTION_IMPORT_DATA', false);
		}

                // set help url for this view if found
                $help_url = CostbenefitprojectionHelper::getHelpUrl('health_data_sets');
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

		// Set Causerisk Name Selection
		$this->causeriskNameOptions = JFormHelper::loadFieldType('Causesrisks')->getOptions();
		if ($this->causeriskNameOptions)
		{
			// Causerisk Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_CAUSERISK_LABEL').' -',
				'filter_causerisk',
				JHtml::_('select.options', $this->causeriskNameOptions, 'value', 'text', $this->state->get('filter.causerisk'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Causerisk Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_CAUSERISK_LABEL').' -',
					'batch[causerisk]',
					JHtml::_('select.options', $this->causeriskNameOptions, 'value', 'text')
				);
			}
		}

		// Set Year Selection
		$this->yearOptions = $this->getTheYearSelections();
		if ($this->yearOptions)
		{
			// Year Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_YEAR_LABEL').' -',
				'filter_year',
				JHtml::_('select.options', $this->yearOptions, 'value', 'text', $this->state->get('filter.year'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Year Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_YEAR_LABEL').' -',
					'batch[year]',
					JHtml::_('select.options', $this->yearOptions, 'value', 'text')
				);
			}
		}

		// Set Country Name Selection
		$this->countryNameOptions = JFormHelper::loadFieldType('Countries')->getOptions();
		if ($this->countryNameOptions)
		{
			// Country Name Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_COUNTRY_LABEL').' -',
				'filter_country',
				JHtml::_('select.options', $this->countryNameOptions, 'value', 'text', $this->state->get('filter.country'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Country Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_COUNTRY_LABEL').' -',
					'batch[country]',
					JHtml::_('select.options', $this->countryNameOptions, 'value', 'text')
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
		$document->setTitle(JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_SETS'));
		$document->addStyleSheet(JURI::root() . "administrator/components/com_costbenefitprojection/assets/css/health_data_sets.css");
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
			'g.name' => JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_CAUSERISK_LABEL'),
			'a.year' => JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_YEAR_LABEL'),
			'h.name' => JText::_('COM_COSTBENEFITPROJECTION_HEALTH_DATA_COUNTRY_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	} 

	protected function getTheYearSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('year'));
		$query->from($db->quoteName('#__costbenefitprojection_health_data'));
		$query->order($db->quoteName('year') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $year)
			{
				// Translate the year selection
				$text = $model->selectionTranslation($year,'year');
				// Now add the year and its text to the options array
				$filter[] = JHtml::_('select.option', $year, JText::_($text));
			}
			return $filter;
		}
		return false;
	}
}
