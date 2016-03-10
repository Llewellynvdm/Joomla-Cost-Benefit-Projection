<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		script.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

/**
 * Script File of Costbenefitprojection Component
 */
class com_costbenefitprojectionInstallerScript
{
	/**
	 * method to install the component
	 *
	 *
	 * @return void
	 */
	function install($parent)
	{

	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// Get Application object
		$app = JFactory::getApplication();

		// Get The Database object
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Company alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.company') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$company_found = $db->getNumRows();
		// Now check if there were any rows
		if ($company_found)
		{
			// Since there are load the needed  company type ids
			$company_ids = $db->loadColumn();
			// Remove Company from the content type table
			$company_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.company') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($company_condition);
			$db->setQuery($query);
			// Execute the query to remove Company items
			$company_done = $db->execute();
			if ($company_done);
			{
				// If succesfully remove Company add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.company) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Company items from the contentitem tag map table
			$company_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.company') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($company_condition);
			$db->setQuery($query);
			// Execute the query to remove Company items
			$company_done = $db->execute();
			if ($company_done);
			{
				// If succesfully remove Company add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.company) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Company items from the ucm content table
			$company_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.company') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($company_condition);
			$db->setQuery($query);
			// Execute the query to remove Company items
			$company_done = $db->execute();
			if ($company_done);
			{
				// If succesfully remove Company add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.company) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Company items are cleared from DB
			foreach ($company_ids as $company_id)
			{
				// Remove Company items from the ucm base table
				$company_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $company_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($company_condition);
				$db->setQuery($query);
				// Execute the query to remove Company items
				$db->execute();

				// Remove Company items from the ucm history table
				$company_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $company_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($company_condition);
				$db->setQuery($query);
				// Execute the query to remove Company items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Service_provider alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.service_provider') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$service_provider_found = $db->getNumRows();
		// Now check if there were any rows
		if ($service_provider_found)
		{
			// Since there are load the needed  service_provider type ids
			$service_provider_ids = $db->loadColumn();
			// Remove Service_provider from the content type table
			$service_provider_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.service_provider') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($service_provider_condition);
			$db->setQuery($query);
			// Execute the query to remove Service_provider items
			$service_provider_done = $db->execute();
			if ($service_provider_done);
			{
				// If succesfully remove Service_provider add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.service_provider) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Service_provider items from the contentitem tag map table
			$service_provider_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.service_provider') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($service_provider_condition);
			$db->setQuery($query);
			// Execute the query to remove Service_provider items
			$service_provider_done = $db->execute();
			if ($service_provider_done);
			{
				// If succesfully remove Service_provider add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.service_provider) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Service_provider items from the ucm content table
			$service_provider_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.service_provider') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($service_provider_condition);
			$db->setQuery($query);
			// Execute the query to remove Service_provider items
			$service_provider_done = $db->execute();
			if ($service_provider_done);
			{
				// If succesfully remove Service_provider add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.service_provider) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Service_provider items are cleared from DB
			foreach ($service_provider_ids as $service_provider_id)
			{
				// Remove Service_provider items from the ucm base table
				$service_provider_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $service_provider_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($service_provider_condition);
				$db->setQuery($query);
				// Execute the query to remove Service_provider items
				$db->execute();

				// Remove Service_provider items from the ucm history table
				$service_provider_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $service_provider_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($service_provider_condition);
				$db->setQuery($query);
				// Execute the query to remove Service_provider items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Country alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.country') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$country_found = $db->getNumRows();
		// Now check if there were any rows
		if ($country_found)
		{
			// Since there are load the needed  country type ids
			$country_ids = $db->loadColumn();
			// Remove Country from the content type table
			$country_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.country') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($country_condition);
			$db->setQuery($query);
			// Execute the query to remove Country items
			$country_done = $db->execute();
			if ($country_done);
			{
				// If succesfully remove Country add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.country) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Country items from the contentitem tag map table
			$country_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.country') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($country_condition);
			$db->setQuery($query);
			// Execute the query to remove Country items
			$country_done = $db->execute();
			if ($country_done);
			{
				// If succesfully remove Country add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.country) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Country items from the ucm content table
			$country_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.country') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($country_condition);
			$db->setQuery($query);
			// Execute the query to remove Country items
			$country_done = $db->execute();
			if ($country_done);
			{
				// If succesfully remove Country add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.country) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Country items are cleared from DB
			foreach ($country_ids as $country_id)
			{
				// Remove Country items from the ucm base table
				$country_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $country_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($country_condition);
				$db->setQuery($query);
				// Execute the query to remove Country items
				$db->execute();

				// Remove Country items from the ucm history table
				$country_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $country_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($country_condition);
				$db->setQuery($query);
				// Execute the query to remove Country items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Causerisk alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.causerisk') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$causerisk_found = $db->getNumRows();
		// Now check if there were any rows
		if ($causerisk_found)
		{
			// Since there are load the needed  causerisk type ids
			$causerisk_ids = $db->loadColumn();
			// Remove Causerisk from the content type table
			$causerisk_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.causerisk') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($causerisk_condition);
			$db->setQuery($query);
			// Execute the query to remove Causerisk items
			$causerisk_done = $db->execute();
			if ($causerisk_done);
			{
				// If succesfully remove Causerisk add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.causerisk) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Causerisk items from the contentitem tag map table
			$causerisk_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.causerisk') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($causerisk_condition);
			$db->setQuery($query);
			// Execute the query to remove Causerisk items
			$causerisk_done = $db->execute();
			if ($causerisk_done);
			{
				// If succesfully remove Causerisk add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.causerisk) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Causerisk items from the ucm content table
			$causerisk_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.causerisk') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($causerisk_condition);
			$db->setQuery($query);
			// Execute the query to remove Causerisk items
			$causerisk_done = $db->execute();
			if ($causerisk_done);
			{
				// If succesfully remove Causerisk add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.causerisk) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Causerisk items are cleared from DB
			foreach ($causerisk_ids as $causerisk_id)
			{
				// Remove Causerisk items from the ucm base table
				$causerisk_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $causerisk_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($causerisk_condition);
				$db->setQuery($query);
				// Execute the query to remove Causerisk items
				$db->execute();

				// Remove Causerisk items from the ucm history table
				$causerisk_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $causerisk_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($causerisk_condition);
				$db->setQuery($query);
				// Execute the query to remove Causerisk items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Health_data alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.health_data') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$health_data_found = $db->getNumRows();
		// Now check if there were any rows
		if ($health_data_found)
		{
			// Since there are load the needed  health_data type ids
			$health_data_ids = $db->loadColumn();
			// Remove Health_data from the content type table
			$health_data_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.health_data') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($health_data_condition);
			$db->setQuery($query);
			// Execute the query to remove Health_data items
			$health_data_done = $db->execute();
			if ($health_data_done);
			{
				// If succesfully remove Health_data add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.health_data) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Health_data items from the contentitem tag map table
			$health_data_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.health_data') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($health_data_condition);
			$db->setQuery($query);
			// Execute the query to remove Health_data items
			$health_data_done = $db->execute();
			if ($health_data_done);
			{
				// If succesfully remove Health_data add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.health_data) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Health_data items from the ucm content table
			$health_data_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.health_data') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($health_data_condition);
			$db->setQuery($query);
			// Execute the query to remove Health_data items
			$health_data_done = $db->execute();
			if ($health_data_done);
			{
				// If succesfully remove Health_data add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.health_data) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Health_data items are cleared from DB
			foreach ($health_data_ids as $health_data_id)
			{
				// Remove Health_data items from the ucm base table
				$health_data_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $health_data_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($health_data_condition);
				$db->setQuery($query);
				// Execute the query to remove Health_data items
				$db->execute();

				// Remove Health_data items from the ucm history table
				$health_data_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $health_data_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($health_data_condition);
				$db->setQuery($query);
				// Execute the query to remove Health_data items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Scaling_factor alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.scaling_factor') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$scaling_factor_found = $db->getNumRows();
		// Now check if there were any rows
		if ($scaling_factor_found)
		{
			// Since there are load the needed  scaling_factor type ids
			$scaling_factor_ids = $db->loadColumn();
			// Remove Scaling_factor from the content type table
			$scaling_factor_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.scaling_factor') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($scaling_factor_condition);
			$db->setQuery($query);
			// Execute the query to remove Scaling_factor items
			$scaling_factor_done = $db->execute();
			if ($scaling_factor_done);
			{
				// If succesfully remove Scaling_factor add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.scaling_factor) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Scaling_factor items from the contentitem tag map table
			$scaling_factor_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.scaling_factor') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($scaling_factor_condition);
			$db->setQuery($query);
			// Execute the query to remove Scaling_factor items
			$scaling_factor_done = $db->execute();
			if ($scaling_factor_done);
			{
				// If succesfully remove Scaling_factor add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.scaling_factor) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Scaling_factor items from the ucm content table
			$scaling_factor_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.scaling_factor') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($scaling_factor_condition);
			$db->setQuery($query);
			// Execute the query to remove Scaling_factor items
			$scaling_factor_done = $db->execute();
			if ($scaling_factor_done);
			{
				// If succesfully remove Scaling_factor add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.scaling_factor) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Scaling_factor items are cleared from DB
			foreach ($scaling_factor_ids as $scaling_factor_id)
			{
				// Remove Scaling_factor items from the ucm base table
				$scaling_factor_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $scaling_factor_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($scaling_factor_condition);
				$db->setQuery($query);
				// Execute the query to remove Scaling_factor items
				$db->execute();

				// Remove Scaling_factor items from the ucm history table
				$scaling_factor_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $scaling_factor_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($scaling_factor_condition);
				$db->setQuery($query);
				// Execute the query to remove Scaling_factor items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Intervention alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.intervention') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$intervention_found = $db->getNumRows();
		// Now check if there were any rows
		if ($intervention_found)
		{
			// Since there are load the needed  intervention type ids
			$intervention_ids = $db->loadColumn();
			// Remove Intervention from the content type table
			$intervention_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.intervention') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($intervention_condition);
			$db->setQuery($query);
			// Execute the query to remove Intervention items
			$intervention_done = $db->execute();
			if ($intervention_done);
			{
				// If succesfully remove Intervention add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.intervention) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Intervention items from the contentitem tag map table
			$intervention_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.intervention') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($intervention_condition);
			$db->setQuery($query);
			// Execute the query to remove Intervention items
			$intervention_done = $db->execute();
			if ($intervention_done);
			{
				// If succesfully remove Intervention add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.intervention) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Intervention items from the ucm content table
			$intervention_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.intervention') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($intervention_condition);
			$db->setQuery($query);
			// Execute the query to remove Intervention items
			$intervention_done = $db->execute();
			if ($intervention_done);
			{
				// If succesfully remove Intervention add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.intervention) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Intervention items are cleared from DB
			foreach ($intervention_ids as $intervention_id)
			{
				// Remove Intervention items from the ucm base table
				$intervention_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $intervention_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($intervention_condition);
				$db->setQuery($query);
				// Execute the query to remove Intervention items
				$db->execute();

				// Remove Intervention items from the ucm history table
				$intervention_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $intervention_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($intervention_condition);
				$db->setQuery($query);
				// Execute the query to remove Intervention items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Currency alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.currency') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$currency_found = $db->getNumRows();
		// Now check if there were any rows
		if ($currency_found)
		{
			// Since there are load the needed  currency type ids
			$currency_ids = $db->loadColumn();
			// Remove Currency from the content type table
			$currency_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.currency') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($currency_condition);
			$db->setQuery($query);
			// Execute the query to remove Currency items
			$currency_done = $db->execute();
			if ($currency_done);
			{
				// If succesfully remove Currency add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.currency) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Currency items from the contentitem tag map table
			$currency_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.currency') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($currency_condition);
			$db->setQuery($query);
			// Execute the query to remove Currency items
			$currency_done = $db->execute();
			if ($currency_done);
			{
				// If succesfully remove Currency add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.currency) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Currency items from the ucm content table
			$currency_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.currency') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($currency_condition);
			$db->setQuery($query);
			// Execute the query to remove Currency items
			$currency_done = $db->execute();
			if ($currency_done);
			{
				// If succesfully remove Currency add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.currency) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Currency items are cleared from DB
			foreach ($currency_ids as $currency_id)
			{
				// Remove Currency items from the ucm base table
				$currency_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $currency_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($currency_condition);
				$db->setQuery($query);
				// Execute the query to remove Currency items
				$db->execute();

				// Remove Currency items from the ucm history table
				$currency_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $currency_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($currency_condition);
				$db->setQuery($query);
				// Execute the query to remove Currency items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Help_document alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.help_document') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$help_document_found = $db->getNumRows();
		// Now check if there were any rows
		if ($help_document_found)
		{
			// Since there are load the needed  help_document type ids
			$help_document_ids = $db->loadColumn();
			// Remove Help_document from the content type table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.help_document) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Help_document items from the contentitem tag map table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.help_document) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Help_document items from the ucm content table
			$help_document_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_costbenefitprojection.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_costbenefitprojection.help_document) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Help_document items are cleared from DB
			foreach ($help_document_ids as $help_document_id)
			{
				// Remove Help_document items from the ucm base table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// Execute the query to remove Help_document items
				$db->execute();

				// Remove Help_document items from the ucm history table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// Execute the query to remove Help_document items
				$db->execute();
			}
		}

		// If All related items was removed queued success message.
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_base</b> table'));
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_history</b> table'));

		// Remove costbenefitprojection assets from the assets table
		$costbenefitprojection_condition = array( $db->quoteName('name') . ' LIKE ' . $db->quote('com_costbenefitprojection%') );

		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__assets'));
		$query->where($costbenefitprojection_condition);
		$db->setQuery($query);
		$help_document_done = $db->execute();
		if ($help_document_done);
		{
			// If succesfully remove costbenefitprojection add queued success message.
			$app->enqueueMessage(JText::_('All related items was removed from the <b>#__assets</b> table'));
		}

		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:llewellyn@vdm.io">llewellyn@vdm.io</a>.
		<br />We at Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="http://www.vdm.io" target="_blank">http://www.vdm.io</a> today!</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		if ($type == 'uninstall')
		{        	
			return true;
		}
		
		$app = JFactory::getApplication();
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.4.1'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.4.1 before continuing!', 'error');
			return false;
		}
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// set the default component settings
		if ($type == 'install')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the company content type object.
			$company = new stdClass();
			$company->type_title = 'Costbenefitprojection Company';
			$company->type_alias = 'com_costbenefitprojection.company';
			$company->table = '{"special": {"dbtable": "#__costbenefitprojection_company","key": "id","type": "Company","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$company->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","email":"email","user":"user","department":"department","country":"country","service_provider":"service_provider","per":"per","medical_turnovers_females":"medical_turnovers_females","females":"females","sick_leave_males":"sick_leave_males","causesrisks":"causesrisks","datayear":"datayear","medical_turnovers_males":"medical_turnovers_males","working_days":"working_days","turnover_comment":"turnover_comment","total_salary":"total_salary","sick_leave_females":"sick_leave_females","total_healthcare":"total_healthcare","productivity_losses":"productivity_losses","males":"males","not_required":"not_required"}}';
			$company->router = 'CostbenefitprojectionHelperRoute::getCompanyRoute';
			$company->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/company.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","department","country","service_provider","per","working_days","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "service_provider","targetTable": "#__costbenefitprojection_service_provider","targetColumn": "id","displayColumn": "user"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"}]}';

			// Set the object into the content types table.
			$company_Inserted = $db->insertObject('#__content_types', $company);

			// Create the service_provider content type object.
			$service_provider = new stdClass();
			$service_provider->type_title = 'Costbenefitprojection Service_provider';
			$service_provider->type_alias = 'com_costbenefitprojection.service_provider';
			$service_provider->table = '{"special": {"dbtable": "#__costbenefitprojection_service_provider","key": "id","type": "Service_provider","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$service_provider->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "user","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"user":"user","country":"country","publicname":"publicname","publicemail":"publicemail","publicnumber":"publicnumber","publicaddress":"publicaddress"}}';
			$service_provider->router = 'CostbenefitprojectionHelperRoute::getService_providerRoute';
			$service_provider->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/service_provider.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$service_provider_Inserted = $db->insertObject('#__content_types', $service_provider);

			// Create the country content type object.
			$country = new stdClass();
			$country->type_title = 'Costbenefitprojection Country';
			$country->type_alias = 'com_costbenefitprojection.country';
			$country->table = '{"special": {"dbtable": "#__costbenefitprojection_country","key": "id","type": "Country","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$country->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","user":"user","currency":"currency","codethree":"codethree","codetwo":"codetwo","working_days":"working_days","productivity_losses":"productivity_losses","datayear":"datayear","worldzone":"worldzone","publicaddress":"publicaddress","publicemail":"publicemail","alias":"alias","publicname":"publicname","publicnumber":"publicnumber","presenteeism":"presenteeism","medical_turnovers":"medical_turnovers","causesrisks":"causesrisks","sick_leave":"sick_leave","healthcare":"healthcare"}}';
			$country->router = 'CostbenefitprojectionHelperRoute::getCountryRoute';
			$country->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/country.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","working_days"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "currency","targetTable": "#__costbenefitprojection_currency","targetColumn": "codethree","displayColumn": "name"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$country_Inserted = $db->insertObject('#__content_types', $country);

			// Create the causerisk content type object.
			$causerisk = new stdClass();
			$causerisk->type_title = 'Costbenefitprojection Causerisk';
			$causerisk->type_alias = 'com_costbenefitprojection.causerisk';
			$causerisk->table = '{"special": {"dbtable": "#__costbenefitprojection_causerisk","key": "id","type": "Causerisk","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$causerisk->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","ref":"ref","importname":"importname","description":"description","alias":"alias"}}';
			$causerisk->router = 'CostbenefitprojectionHelperRoute::getCauseriskRoute';
			$causerisk->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/causerisk.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$causerisk_Inserted = $db->insertObject('#__content_types', $causerisk);

			// Create the health_data content type object.
			$health_data = new stdClass();
			$health_data->type_title = 'Costbenefitprojection Health_data';
			$health_data->type_alias = 'com_costbenefitprojection.health_data';
			$health_data->table = '{"special": {"dbtable": "#__costbenefitprojection_health_data","key": "id","type": "Health_data","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$health_data->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","year":"year","country":"country"}}';
			$health_data->router = 'CostbenefitprojectionHelperRoute::getHealth_dataRoute';
			$health_data->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/health_data.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","causerisk","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "causerisk","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$health_data_Inserted = $db->insertObject('#__content_types', $health_data);

			// Create the scaling_factor content type object.
			$scaling_factor = new stdClass();
			$scaling_factor->type_title = 'Costbenefitprojection Scaling_factor';
			$scaling_factor->type_alias = 'com_costbenefitprojection.scaling_factor';
			$scaling_factor->table = '{"special": {"dbtable": "#__costbenefitprojection_scaling_factor","key": "id","type": "Scaling_factor","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$scaling_factor->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","company":"company","yld_scaling_factor_males":"yld_scaling_factor_males","yld_scaling_factor_females":"yld_scaling_factor_females","mortality_scaling_factor_males":"mortality_scaling_factor_males","mortality_scaling_factor_females":"mortality_scaling_factor_females","presenteeism_scaling_factor_males":"presenteeism_scaling_factor_males","presenteeism_scaling_factor_females":"presenteeism_scaling_factor_females","reference":"reference","country":"country","health_scaling_factor":"health_scaling_factor"}}';
			$scaling_factor->router = 'CostbenefitprojectionHelperRoute::getScaling_factorRoute';
			$scaling_factor->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/scaling_factor.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","causerisk","company","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "causerisk","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$scaling_factor_Inserted = $db->insertObject('#__content_types', $scaling_factor);

			// Create the intervention content type object.
			$intervention = new stdClass();
			$intervention->type_title = 'Costbenefitprojection Intervention';
			$intervention->type_alias = 'com_costbenefitprojection.intervention';
			$intervention->table = '{"special": {"dbtable": "#__costbenefitprojection_intervention","key": "id","type": "Intervention","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$intervention->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","company":"company","type":"type","coverage":"coverage","description":"description","reference":"reference","country":"country","share":"share","interventions":"interventions","duration":"duration","not_required":"not_required"}}';
			$intervention->router = 'CostbenefitprojectionHelperRoute::getInterventionRoute';
			$intervention->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/intervention.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","duration","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","company","type","coverage","country","share","duration","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "interventions","targetTable": "#__costbenefitprojection_intervention","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$intervention_Inserted = $db->insertObject('#__content_types', $intervention);

			// Create the currency content type object.
			$currency = new stdClass();
			$currency->type_title = 'Costbenefitprojection Currency';
			$currency->type_alias = 'com_costbenefitprojection.currency';
			$currency->table = '{"special": {"dbtable": "#__costbenefitprojection_currency","key": "id","type": "Currency","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$currency->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","codethree":"codethree","numericcode":"numericcode","symbol":"symbol","alias":"alias","positivestyle":"positivestyle","thousands":"thousands","decimalsymbol":"decimalsymbol","decimalplace":"decimalplace","negativestyle":"negativestyle"}}';
			$currency->router = 'CostbenefitprojectionHelperRoute::getCurrencyRoute';
			$currency->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/currency.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","numericcode","decimalplace"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$currency_Inserted = $db->insertObject('#__content_types', $currency);

			// Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Costbenefitprojection Help_document';
			$help_document->type_alias = 'com_costbenefitprojection.help_document';
			$help_document->table = '{"special": {"dbtable": "#__costbenefitprojection_help_document","key": "id","type": "Help_document","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","target":"target","content":"content","alias":"alias","article":"article","url":"url","not_required":"not_required"}}';
			$help_document->router = 'CostbenefitprojectionHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","target","article","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// Set the object into the content types table.
			$help_document_Inserted = $db->insertObject('#__content_types', $help_document);


			// Install the global extenstion params.
			$query = $db->getQuery(true);

			// Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' . $db->quote('{"autorName":"Llewellyn van der Merwe","autorEmail":"llewellyn@vdm.io","check_in":"-1 day","save_history":"1","history_limit":"10","titleContributor1":"Health Economist","nameContributor1":"Patrick Hanlon, M.Sc. PH","emailContributor1":"Patrick.Hanlon@unibas.ch","linkContributor1":"http://www.swisstph.ch/about-us/staff/detailview.html?tx_x4epersdb_pi1[showUid]=2267&amp;cHash=1b1c5db0808e04d3f1afe0f3a3f67998","useContributor1":"2","showContributor1":"3","titleContributor2":"Development Advisor","nameContributor2":"Matthew Black","emailContributor2":"matthew.black@giz.de","linkContributor2":"http://www.giz.de","useContributor2":"2","showContributor2":"3","titleContributor3":"Associate Expert","nameContributor3":"Dr. Pascal Geldsetzer","emailContributor3":"pascal.geldsetzer@giz.de","linkContributor3":"http://www.giz.de","useContributor3":"2","showContributor3":"1","memberuser":["2"],"serviceprovideruser":["2"],"countryuser":["2"],"uikit_load":"1","uikit_min":"","uikit_style":"","admin_chartbackground":"#F7F7FA","admin_mainwidth":"1000","admin_chartareatop":"20","admin_chartarealeft":"20","admin_chartareawidth":"170","admin_legendtextstylefontcolor":"10","admin_legendtextstylefontsize":"20","admin_vaxistextstylefontcolor":"#63B1F2","admin_haxistextstylefontcolor":"#63B1F2","admin_haxistitletextstylefontcolor":"#63B1F2","site_chartbackground":"#F7F7FA","site_mainwidth":"1000","site_chartareatop":"20","site_chartarealeft":"20","site_chartareawidth":"170","site_legendtextstylefontcolor":"10","site_legendtextstylefontsize":"20","site_vaxistextstylefontcolor":"#63B1F2","site_haxistextstylefontcolor":"#63B1F2","site_haxistitletextstylefontcolor":"#63B1F2"}'),
			);

			// Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_costbenefitprojection')
			);

			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();
			echo '<a target="_blank" href="http://www.vdm.io" title="Cost Benefit Projection">
				<img src="components/com_costbenefitprojection/assets/images/component-300.png"/>
				</a>';
		}
		// do any updates needed
		if ($type == 'update')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the company content type object.
			$company = new stdClass();
			$company->type_title = 'Costbenefitprojection Company';
			$company->type_alias = 'com_costbenefitprojection.company';
			$company->table = '{"special": {"dbtable": "#__costbenefitprojection_company","key": "id","type": "Company","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$company->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","email":"email","user":"user","department":"department","country":"country","service_provider":"service_provider","per":"per","medical_turnovers_females":"medical_turnovers_females","females":"females","sick_leave_males":"sick_leave_males","causesrisks":"causesrisks","datayear":"datayear","medical_turnovers_males":"medical_turnovers_males","working_days":"working_days","turnover_comment":"turnover_comment","total_salary":"total_salary","sick_leave_females":"sick_leave_females","total_healthcare":"total_healthcare","productivity_losses":"productivity_losses","males":"males","not_required":"not_required"}}';
			$company->router = 'CostbenefitprojectionHelperRoute::getCompanyRoute';
			$company->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/company.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","department","country","service_provider","per","working_days","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "service_provider","targetTable": "#__costbenefitprojection_service_provider","targetColumn": "id","displayColumn": "user"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"}]}';

			// Check if company type is already in content_type DB.
			$company_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($company->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$company->type_id = $db->loadResult();
				$company_Updated = $db->updateObject('#__content_types', $company, 'type_id');
			}
			else
			{
				$company_Inserted = $db->insertObject('#__content_types', $company);
			}

			// Create the service_provider content type object.
			$service_provider = new stdClass();
			$service_provider->type_title = 'Costbenefitprojection Service_provider';
			$service_provider->type_alias = 'com_costbenefitprojection.service_provider';
			$service_provider->table = '{"special": {"dbtable": "#__costbenefitprojection_service_provider","key": "id","type": "Service_provider","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$service_provider->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "user","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"user":"user","country":"country","publicname":"publicname","publicemail":"publicemail","publicnumber":"publicnumber","publicaddress":"publicaddress"}}';
			$service_provider->router = 'CostbenefitprojectionHelperRoute::getService_providerRoute';
			$service_provider->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/service_provider.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Check if service_provider type is already in content_type DB.
			$service_provider_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($service_provider->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$service_provider->type_id = $db->loadResult();
				$service_provider_Updated = $db->updateObject('#__content_types', $service_provider, 'type_id');
			}
			else
			{
				$service_provider_Inserted = $db->insertObject('#__content_types', $service_provider);
			}

			// Create the country content type object.
			$country = new stdClass();
			$country->type_title = 'Costbenefitprojection Country';
			$country->type_alias = 'com_costbenefitprojection.country';
			$country->table = '{"special": {"dbtable": "#__costbenefitprojection_country","key": "id","type": "Country","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$country->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","user":"user","currency":"currency","codethree":"codethree","codetwo":"codetwo","working_days":"working_days","productivity_losses":"productivity_losses","datayear":"datayear","worldzone":"worldzone","publicaddress":"publicaddress","publicemail":"publicemail","alias":"alias","publicname":"publicname","publicnumber":"publicnumber","presenteeism":"presenteeism","medical_turnovers":"medical_turnovers","causesrisks":"causesrisks","sick_leave":"sick_leave","healthcare":"healthcare"}}';
			$country->router = 'CostbenefitprojectionHelperRoute::getCountryRoute';
			$country->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/country.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","working_days"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "currency","targetTable": "#__costbenefitprojection_currency","targetColumn": "codethree","displayColumn": "name"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"}]}';

			// Check if country type is already in content_type DB.
			$country_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($country->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$country->type_id = $db->loadResult();
				$country_Updated = $db->updateObject('#__content_types', $country, 'type_id');
			}
			else
			{
				$country_Inserted = $db->insertObject('#__content_types', $country);
			}

			// Create the causerisk content type object.
			$causerisk = new stdClass();
			$causerisk->type_title = 'Costbenefitprojection Causerisk';
			$causerisk->type_alias = 'com_costbenefitprojection.causerisk';
			$causerisk->table = '{"special": {"dbtable": "#__costbenefitprojection_causerisk","key": "id","type": "Causerisk","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$causerisk->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","ref":"ref","importname":"importname","description":"description","alias":"alias"}}';
			$causerisk->router = 'CostbenefitprojectionHelperRoute::getCauseriskRoute';
			$causerisk->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/causerisk.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Check if causerisk type is already in content_type DB.
			$causerisk_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($causerisk->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$causerisk->type_id = $db->loadResult();
				$causerisk_Updated = $db->updateObject('#__content_types', $causerisk, 'type_id');
			}
			else
			{
				$causerisk_Inserted = $db->insertObject('#__content_types', $causerisk);
			}

			// Create the health_data content type object.
			$health_data = new stdClass();
			$health_data->type_title = 'Costbenefitprojection Health_data';
			$health_data->type_alias = 'com_costbenefitprojection.health_data';
			$health_data->table = '{"special": {"dbtable": "#__costbenefitprojection_health_data","key": "id","type": "Health_data","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$health_data->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","year":"year","country":"country"}}';
			$health_data->router = 'CostbenefitprojectionHelperRoute::getHealth_dataRoute';
			$health_data->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/health_data.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","causerisk","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "causerisk","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Check if health_data type is already in content_type DB.
			$health_data_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($health_data->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$health_data->type_id = $db->loadResult();
				$health_data_Updated = $db->updateObject('#__content_types', $health_data, 'type_id');
			}
			else
			{
				$health_data_Inserted = $db->insertObject('#__content_types', $health_data);
			}

			// Create the scaling_factor content type object.
			$scaling_factor = new stdClass();
			$scaling_factor->type_title = 'Costbenefitprojection Scaling_factor';
			$scaling_factor->type_alias = 'com_costbenefitprojection.scaling_factor';
			$scaling_factor->table = '{"special": {"dbtable": "#__costbenefitprojection_scaling_factor","key": "id","type": "Scaling_factor","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$scaling_factor->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","company":"company","yld_scaling_factor_males":"yld_scaling_factor_males","yld_scaling_factor_females":"yld_scaling_factor_females","mortality_scaling_factor_males":"mortality_scaling_factor_males","mortality_scaling_factor_females":"mortality_scaling_factor_females","presenteeism_scaling_factor_males":"presenteeism_scaling_factor_males","presenteeism_scaling_factor_females":"presenteeism_scaling_factor_females","reference":"reference","country":"country","health_scaling_factor":"health_scaling_factor"}}';
			$scaling_factor->router = 'CostbenefitprojectionHelperRoute::getScaling_factorRoute';
			$scaling_factor->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/scaling_factor.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","causerisk","company","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "causerisk","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Check if scaling_factor type is already in content_type DB.
			$scaling_factor_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($scaling_factor->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$scaling_factor->type_id = $db->loadResult();
				$scaling_factor_Updated = $db->updateObject('#__content_types', $scaling_factor, 'type_id');
			}
			else
			{
				$scaling_factor_Inserted = $db->insertObject('#__content_types', $scaling_factor);
			}

			// Create the intervention content type object.
			$intervention = new stdClass();
			$intervention->type_title = 'Costbenefitprojection Intervention';
			$intervention->type_alias = 'com_costbenefitprojection.intervention';
			$intervention->table = '{"special": {"dbtable": "#__costbenefitprojection_intervention","key": "id","type": "Intervention","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$intervention->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","company":"company","type":"type","coverage":"coverage","description":"description","reference":"reference","country":"country","share":"share","interventions":"interventions","duration":"duration","not_required":"not_required"}}';
			$intervention->router = 'CostbenefitprojectionHelperRoute::getInterventionRoute';
			$intervention->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/intervention.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","duration","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","company","type","coverage","country","share","duration","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "interventions","targetTable": "#__costbenefitprojection_intervention","targetColumn": "id","displayColumn": "name"}]}';

			// Check if intervention type is already in content_type DB.
			$intervention_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($intervention->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$intervention->type_id = $db->loadResult();
				$intervention_Updated = $db->updateObject('#__content_types', $intervention, 'type_id');
			}
			else
			{
				$intervention_Inserted = $db->insertObject('#__content_types', $intervention);
			}

			// Create the currency content type object.
			$currency = new stdClass();
			$currency->type_title = 'Costbenefitprojection Currency';
			$currency->type_alias = 'com_costbenefitprojection.currency';
			$currency->table = '{"special": {"dbtable": "#__costbenefitprojection_currency","key": "id","type": "Currency","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$currency->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","codethree":"codethree","numericcode":"numericcode","symbol":"symbol","alias":"alias","positivestyle":"positivestyle","thousands":"thousands","decimalsymbol":"decimalsymbol","decimalplace":"decimalplace","negativestyle":"negativestyle"}}';
			$currency->router = 'CostbenefitprojectionHelperRoute::getCurrencyRoute';
			$currency->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/currency.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","numericcode","decimalplace"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Check if currency type is already in content_type DB.
			$currency_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($currency->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$currency->type_id = $db->loadResult();
				$currency_Updated = $db->updateObject('#__content_types', $currency, 'type_id');
			}
			else
			{
				$currency_Inserted = $db->insertObject('#__content_types', $currency);
			}

			// Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Costbenefitprojection Help_document';
			$help_document->type_alias = 'com_costbenefitprojection.help_document';
			$help_document->table = '{"special": {"dbtable": "#__costbenefitprojection_help_document","key": "id","type": "Help_document","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","target":"target","content":"content","alias":"alias","article":"article","url":"url","not_required":"not_required"}}';
			$help_document->router = 'CostbenefitprojectionHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","target","article","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// Check if help_document type is already in content_type DB.
			$help_document_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($help_document->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$help_document->type_id = $db->loadResult();
				$help_document_Updated = $db->updateObject('#__content_types', $help_document, 'type_id');
			}
			else
			{
				$help_document_Inserted = $db->insertObject('#__content_types', $help_document);
			}


			echo '<a target="_blank" href="http://www.vdm.io" title="Cost Benefit Projection">
				<img src="components/com_costbenefitprojection/assets/images/component-300.png"/>
				</a>
				<h3>Upgrade to Version 3.3.8 Was Successful! Let us know if anything is not working as expected.</h3>';
		}
	}
}
