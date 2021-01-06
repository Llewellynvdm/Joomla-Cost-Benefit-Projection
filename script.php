<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			6th January, 2021
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

/**
 * Script File of Costbenefitprojection Component
 */
class com_costbenefitprojectionInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function __construct(JAdapterInstance $parent) {}

	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(JAdapterInstance $parent) {}

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $parent)
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
			if ($company_done)
			{
				// If successfully remove Company add queued success message.
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
			if ($company_done)
			{
				// If successfully remove Company add queued success message.
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
			if ($company_done)
			{
				// If successfully removed Company add queued success message.
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
			if ($service_provider_done)
			{
				// If successfully remove Service_provider add queued success message.
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
			if ($service_provider_done)
			{
				// If successfully remove Service_provider add queued success message.
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
			if ($service_provider_done)
			{
				// If successfully removed Service_provider add queued success message.
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
			if ($country_done)
			{
				// If successfully remove Country add queued success message.
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
			if ($country_done)
			{
				// If successfully remove Country add queued success message.
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
			if ($country_done)
			{
				// If successfully removed Country add queued success message.
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
			if ($causerisk_done)
			{
				// If successfully remove Causerisk add queued success message.
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
			if ($causerisk_done)
			{
				// If successfully remove Causerisk add queued success message.
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
			if ($causerisk_done)
			{
				// If successfully removed Causerisk add queued success message.
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
			if ($health_data_done)
			{
				// If successfully remove Health_data add queued success message.
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
			if ($health_data_done)
			{
				// If successfully remove Health_data add queued success message.
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
			if ($health_data_done)
			{
				// If successfully removed Health_data add queued success message.
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
			if ($scaling_factor_done)
			{
				// If successfully remove Scaling_factor add queued success message.
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
			if ($scaling_factor_done)
			{
				// If successfully remove Scaling_factor add queued success message.
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
			if ($scaling_factor_done)
			{
				// If successfully removed Scaling_factor add queued success message.
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
			if ($intervention_done)
			{
				// If successfully remove Intervention add queued success message.
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
			if ($intervention_done)
			{
				// If successfully remove Intervention add queued success message.
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
			if ($intervention_done)
			{
				// If successfully removed Intervention add queued success message.
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
			if ($currency_done)
			{
				// If successfully remove Currency add queued success message.
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
			if ($currency_done)
			{
				// If successfully remove Currency add queued success message.
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
			if ($currency_done)
			{
				// If successfully removed Currency add queued success message.
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
			if ($help_document_done)
			{
				// If successfully remove Help_document add queued success message.
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
			if ($help_document_done)
			{
				// If successfully remove Help_document add queued success message.
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
			if ($help_document_done)
			{
				// If successfully removed Help_document add queued success message.
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
		if ($help_document_done)
		{
			// If successfully removed costbenefitprojection add queued success message.
			$app->enqueueMessage(JText::_('All related items was removed from the <b>#__assets</b> table'));
		}

		// Get the biggest rule column in the assets table at this point.
		$get_rule_length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
		$db->setQuery($get_rule_length);
		if ($db->execute())
		{
			$rule_length = $db->loadResult();
			// Check the size of the rules column
			if ($rule_length < 5120)
			{
				// Revert the assets table rules column back to the default
				$revert_rule = "ALTER TABLE `#__assets` CHANGE `rules` `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.';";
				$db->setQuery($revert_rule);
				$db->execute();
				$app->enqueueMessage(JText::_('Reverted the <b>#__assets</b> table rules column back to its default size of varchar(5120)'));
			}
			else
			{

				$app->enqueueMessage(JText::_('Could not revert the <b>#__assets</b> table rules column back to its default size of varchar(5120), since there is still one or more components that still requires the column to be larger.'));
			}
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection from the action_logs_extensions table
		$costbenefitprojection_action_logs_extensions = array( $db->quoteName('extension') . ' = ' . $db->quote('com_costbenefitprojection') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_logs_extensions'));
		$query->where($costbenefitprojection_action_logs_extensions);
		$db->setQuery($query);
		// Execute the query to remove Costbenefitprojection
		$costbenefitprojection_removed_done = $db->execute();
		if ($costbenefitprojection_removed_done)
		{
			// If successfully remove Costbenefitprojection add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection extension was removed from the <b>#__action_logs_extensions</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Company from the action_log_config table
		$company_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.company') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($company_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.company
		$company_action_log_config_done = $db->execute();
		if ($company_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Company add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.company type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Service_provider from the action_log_config table
		$service_provider_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.service_provider') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($service_provider_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.service_provider
		$service_provider_action_log_config_done = $db->execute();
		if ($service_provider_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Service_provider add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.service_provider type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Country from the action_log_config table
		$country_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.country') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($country_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.country
		$country_action_log_config_done = $db->execute();
		if ($country_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Country add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.country type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Causerisk from the action_log_config table
		$causerisk_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.causerisk') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($causerisk_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.causerisk
		$causerisk_action_log_config_done = $db->execute();
		if ($causerisk_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Causerisk add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.causerisk type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Health_data from the action_log_config table
		$health_data_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.health_data') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($health_data_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.health_data
		$health_data_action_log_config_done = $db->execute();
		if ($health_data_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Health_data add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.health_data type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Scaling_factor from the action_log_config table
		$scaling_factor_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.scaling_factor') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($scaling_factor_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.scaling_factor
		$scaling_factor_action_log_config_done = $db->execute();
		if ($scaling_factor_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Scaling_factor add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.scaling_factor type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Intervention from the action_log_config table
		$intervention_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.intervention') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($intervention_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.intervention
		$intervention_action_log_config_done = $db->execute();
		if ($intervention_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Intervention add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.intervention type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Currency from the action_log_config table
		$currency_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.currency') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($currency_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.currency
		$currency_action_log_config_done = $db->execute();
		if ($currency_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Currency add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.currency type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Costbenefitprojection Help_document from the action_log_config table
		$help_document_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_costbenefitprojection.help_document') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($help_document_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_costbenefitprojection.help_document
		$help_document_action_log_config_done = $db->execute();
		if ($help_document_action_log_config_done)
		{
			// If successfully removed Costbenefitprojection Help_document add queued success message.
			$app->enqueueMessage(JText::_('The com_costbenefitprojection.help_document type alias was removed from the <b>#__action_log_config</b> table'));
		}
		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:joomla@vdm.io">joomla@vdm.io</a>.
		<br />We at Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="http://www.vdm.io" target="_blank">http://www.vdm.io</a> today!</p>';
	}

	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(JAdapterInstance $parent){}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, JAdapterInstance $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// is redundant or so it seems ...hmmm let me know if it works again
		if ($type === 'uninstall')
		{
			return true;
		}
		// the default for both install and update
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.8.0'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.8.0 before continuing!', 'error');
			return false;
		}
		// do any updates needed
		if ($type === 'update')
		{
		}
		// do any install needed
		if ($type === 'install')
		{
		}
		// check if the PHPExcel stuff is still around
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_costbenefitprojection/helpers/PHPExcel.php'))
		{
			// We need to remove this old PHPExcel folder
			$this->removeFolder(JPATH_ADMINISTRATOR . '/components/com_costbenefitprojection/helpers/PHPExcel');
			// We need to remove this old PHPExcel file
			JFile::delete(JPATH_ADMINISTRATOR . '/components/com_costbenefitprojection/helpers/PHPExcel.php');
		}
		return true;
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, JAdapterInstance $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// We check if we have dynamic folders to copy
		$this->setDynamicF0ld3rs($app, $parent);
		// set the default component settings
		if ($type === 'install')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the company content type object.
			$company = new stdClass();
			$company->type_title = 'Costbenefitprojection Company';
			$company->type_alias = 'com_costbenefitprojection.company';
			$company->table = '{"special": {"dbtable": "#__costbenefitprojection_company","key": "id","type": "Company","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$company->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"email":"email","name":"name","user":"user","department":"department","country":"country","service_provider":"service_provider","per":"per","males":"males","working_days":"working_days","datayear":"datayear","sick_leave_males":"sick_leave_males","sick_leave_females":"sick_leave_females","total_salary":"total_salary","turnover_comment":"turnover_comment","causesrisks":"causesrisks","not_required":"not_required","productivity_losses":"productivity_losses","total_healthcare":"total_healthcare","females":"females","medical_turnovers_males":"medical_turnovers_males","medical_turnovers_females":"medical_turnovers_females"}}';
			$company->router = 'CostbenefitprojectionHelperRoute::getCompanyRoute';
			$company->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/company.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","department","country","service_provider","per","working_days","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "service_provider","targetTable": "#__costbenefitprojection_service_provider","targetColumn": "id","displayColumn": "user"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$company_Inserted = $db->insertObject('#__content_types', $company);

			// Create the service_provider content type object.
			$service_provider = new stdClass();
			$service_provider->type_title = 'Costbenefitprojection Service_provider';
			$service_provider->type_alias = 'com_costbenefitprojection.service_provider';
			$service_provider->table = '{"special": {"dbtable": "#__costbenefitprojection_service_provider","key": "id","type": "Service_provider","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$service_provider->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "user","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"user":"user","country":"country","publicname":"publicname","publicemail":"publicemail","publicnumber":"publicnumber","publicaddress":"publicaddress","testcompanies":"testcompanies"}}';
			$service_provider->router = 'CostbenefitprojectionHelperRoute::getService_providerRoute';
			$service_provider->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/service_provider.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "testcompanies","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$service_provider_Inserted = $db->insertObject('#__content_types', $service_provider);

			// Create the country content type object.
			$country = new stdClass();
			$country->type_title = 'Costbenefitprojection Country';
			$country->type_alias = 'com_costbenefitprojection.country';
			$country->table = '{"special": {"dbtable": "#__costbenefitprojection_country","key": "id","type": "Country","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$country->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","user":"user","currency":"currency","codethree":"codethree","codetwo":"codetwo","working_days":"working_days","publicemail":"publicemail","publicnumber":"publicnumber","productivity_losses":"productivity_losses","publicname":"publicname","alias":"alias","publicaddress":"publicaddress","datayear":"datayear","worldzone":"worldzone","presenteeism":"presenteeism","causesrisks":"causesrisks","medical_turnovers":"medical_turnovers","sick_leave":"sick_leave","healthcare":"healthcare"}}';
			$country->router = 'CostbenefitprojectionHelperRoute::getCountryRoute';
			$country->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/country.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","working_days"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "currency","targetTable": "#__costbenefitprojection_currency","targetColumn": "codethree","displayColumn": "name"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$country_Inserted = $db->insertObject('#__content_types', $country);

			// Create the causerisk content type object.
			$causerisk = new stdClass();
			$causerisk->type_title = 'Costbenefitprojection Causerisk';
			$causerisk->type_alias = 'com_costbenefitprojection.causerisk';
			$causerisk->table = '{"special": {"dbtable": "#__costbenefitprojection_causerisk","key": "id","type": "Causerisk","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$causerisk->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","ref":"ref","importname":"importname","import_id":"import_id","description":"description","alias":"alias"}}';
			$causerisk->router = 'CostbenefitprojectionHelperRoute::getCauseriskRoute';
			$causerisk->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/causerisk.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","import_id"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

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
			$scaling_factor->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","company":"company","yld_scaling_factor_males":"yld_scaling_factor_males","yld_scaling_factor_females":"yld_scaling_factor_females","mortality_scaling_factor_males":"mortality_scaling_factor_males","mortality_scaling_factor_females":"mortality_scaling_factor_females","presenteeism_scaling_factor_males":"presenteeism_scaling_factor_males","presenteeism_scaling_factor_females":"presenteeism_scaling_factor_females","health_scaling_factor":"health_scaling_factor","reference":"reference","country":"country"}}';
			$scaling_factor->router = 'CostbenefitprojectionHelperRoute::getScaling_factorRoute';
			$scaling_factor->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/scaling_factor.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","causerisk","company","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "causerisk","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$scaling_factor_Inserted = $db->insertObject('#__content_types', $scaling_factor);

			// Create the intervention content type object.
			$intervention = new stdClass();
			$intervention->type_title = 'Costbenefitprojection Intervention';
			$intervention->type_alias = 'com_costbenefitprojection.intervention';
			$intervention->table = '{"special": {"dbtable": "#__costbenefitprojection_intervention","key": "id","type": "Intervention","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$intervention->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","company":"company","type":"type","coverage":"coverage","description":"description","duration":"duration","not_required":"not_required","interventions":"interventions","reference":"reference","share":"share","country":"country"}}';
			$intervention->router = 'CostbenefitprojectionHelperRoute::getInterventionRoute';
			$intervention->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/intervention.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","company","type","coverage","duration","not_required","share","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "interventions","targetTable": "#__costbenefitprojection_intervention","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$intervention_Inserted = $db->insertObject('#__content_types', $intervention);

			// Create the currency content type object.
			$currency = new stdClass();
			$currency->type_title = 'Costbenefitprojection Currency';
			$currency->type_alias = 'com_costbenefitprojection.currency';
			$currency->table = '{"special": {"dbtable": "#__costbenefitprojection_currency","key": "id","type": "Currency","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$currency->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","codethree":"codethree","numericcode":"numericcode","symbol":"symbol","alias":"alias","negativestyle":"negativestyle","positivestyle":"positivestyle","decimalsymbol":"decimalsymbol","decimalplace":"decimalplace","thousands":"thousands"}}';
			$currency->router = 'CostbenefitprojectionHelperRoute::getCurrencyRoute';
			$currency->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/currency.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","numericcode","decimalplace"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$currency_Inserted = $db->insertObject('#__content_types', $currency);

			// Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Costbenefitprojection Help_document';
			$help_document->type_alias = 'com_costbenefitprojection.help_document';
			$help_document->table = '{"special": {"dbtable": "#__costbenefitprojection_help_document","key": "id","type": "Help_document","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","alias":"alias","content":"content","article":"article","url":"url","target":"target"}}';
			$help_document->router = 'CostbenefitprojectionHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","article","target"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// Set the object into the content types table.
			$help_document_Inserted = $db->insertObject('#__content_types', $help_document);


			// Install the global extenstion params.
			$query = $db->getQuery(true);
			// Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' . $db->quote('{"autorName":"Llewellyn van der Merwe","autorEmail":"joomla@vdm.io","check_in":"-1 day","save_history":"1","history_limit":"10","memberuser":["2"],"serviceprovideruser":["2"],"countryuser":["2"],"uikit_load":"1","uikit_min":"","uikit_style":"","admin_chartbackground":"#F7F7FA","admin_mainwidth":"1000","admin_chartareatop":"20","admin_chartarealeft":"20","admin_chartareawidth":"170","admin_legendtextstylefontcolor":"10","admin_legendtextstylefontsize":"20","admin_vaxistextstylefontcolor":"#63B1F2","admin_haxistextstylefontcolor":"#63B1F2","admin_haxistitletextstylefontcolor":"#63B1F2","site_chartbackground":"#F7F7FA","site_mainwidth":"1000","site_chartareatop":"20","site_chartarealeft":"20","site_chartareawidth":"170","site_legendtextstylefontcolor":"10","site_legendtextstylefontsize":"20","site_vaxistextstylefontcolor":"#63B1F2","site_haxistextstylefontcolor":"#63B1F2","site_haxistitletextstylefontcolor":"#63B1F2"}'),
			);
			// Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_costbenefitprojection')
			);
			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();

			// Get the biggest rule column in the assets table at this point.
			$get_rule_length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
			$db->setQuery($get_rule_length);
			if ($db->execute())
			{
				$rule_length = $db->loadResult();
				// Check the size of the rules column
				if ($rule_length <= 26240)
				{
					// Fix the assets table rules column size
					$fix_rules_size = "ALTER TABLE `#__assets` CHANGE `rules` `rules` TEXT NOT NULL COMMENT 'JSON encoded access control. Enlarged to TEXT by JCB';";
					$db->setQuery($fix_rules_size);
					$db->execute();
					$app->enqueueMessage(JText::_('The <b>#__assets</b> table rules column was resized to the TEXT datatype for the components possible large permission rules.'));
				}
			}
			echo '<a target="_blank" href="http://www.vdm.io" title="Cost Benefit Projection">
				<img src="components/com_costbenefitprojection/assets/images/vdm-component.png"/>
				</a>';

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the costbenefitprojection action logs extensions object.
			$costbenefitprojection_action_logs_extensions = new stdClass();
			$costbenefitprojection_action_logs_extensions->extension = 'com_costbenefitprojection';

			// Set the object into the action logs extensions table.
			$costbenefitprojection_action_logs_extensions_Inserted = $db->insertObject('#__action_logs_extensions', $costbenefitprojection_action_logs_extensions);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the company action log config object.
			$company_action_log_config = new stdClass();
			$company_action_log_config->type_title = 'COMPANY';
			$company_action_log_config->type_alias = 'com_costbenefitprojection.company';
			$company_action_log_config->id_holder = 'id';
			$company_action_log_config->title_holder = 'name';
			$company_action_log_config->table_name = '#__costbenefitprojection_company';
			$company_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$company_Inserted = $db->insertObject('#__action_log_config', $company_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the service_provider action log config object.
			$service_provider_action_log_config = new stdClass();
			$service_provider_action_log_config->type_title = 'SERVICE_PROVIDER';
			$service_provider_action_log_config->type_alias = 'com_costbenefitprojection.service_provider';
			$service_provider_action_log_config->id_holder = 'id';
			$service_provider_action_log_config->title_holder = 'user';
			$service_provider_action_log_config->table_name = '#__costbenefitprojection_service_provider';
			$service_provider_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$service_provider_Inserted = $db->insertObject('#__action_log_config', $service_provider_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the country action log config object.
			$country_action_log_config = new stdClass();
			$country_action_log_config->type_title = 'COUNTRY';
			$country_action_log_config->type_alias = 'com_costbenefitprojection.country';
			$country_action_log_config->id_holder = 'id';
			$country_action_log_config->title_holder = 'name';
			$country_action_log_config->table_name = '#__costbenefitprojection_country';
			$country_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$country_Inserted = $db->insertObject('#__action_log_config', $country_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the causerisk action log config object.
			$causerisk_action_log_config = new stdClass();
			$causerisk_action_log_config->type_title = 'CAUSERISK';
			$causerisk_action_log_config->type_alias = 'com_costbenefitprojection.causerisk';
			$causerisk_action_log_config->id_holder = 'id';
			$causerisk_action_log_config->title_holder = 'name';
			$causerisk_action_log_config->table_name = '#__costbenefitprojection_causerisk';
			$causerisk_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$causerisk_Inserted = $db->insertObject('#__action_log_config', $causerisk_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the health_data action log config object.
			$health_data_action_log_config = new stdClass();
			$health_data_action_log_config->type_title = 'HEALTH_DATA';
			$health_data_action_log_config->type_alias = 'com_costbenefitprojection.health_data';
			$health_data_action_log_config->id_holder = 'id';
			$health_data_action_log_config->title_holder = 'causerisk';
			$health_data_action_log_config->table_name = '#__costbenefitprojection_health_data';
			$health_data_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$health_data_Inserted = $db->insertObject('#__action_log_config', $health_data_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the scaling_factor action log config object.
			$scaling_factor_action_log_config = new stdClass();
			$scaling_factor_action_log_config->type_title = 'SCALING_FACTOR';
			$scaling_factor_action_log_config->type_alias = 'com_costbenefitprojection.scaling_factor';
			$scaling_factor_action_log_config->id_holder = 'id';
			$scaling_factor_action_log_config->title_holder = 'causerisk';
			$scaling_factor_action_log_config->table_name = '#__costbenefitprojection_scaling_factor';
			$scaling_factor_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$scaling_factor_Inserted = $db->insertObject('#__action_log_config', $scaling_factor_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the intervention action log config object.
			$intervention_action_log_config = new stdClass();
			$intervention_action_log_config->type_title = 'INTERVENTION';
			$intervention_action_log_config->type_alias = 'com_costbenefitprojection.intervention';
			$intervention_action_log_config->id_holder = 'id';
			$intervention_action_log_config->title_holder = 'name';
			$intervention_action_log_config->table_name = '#__costbenefitprojection_intervention';
			$intervention_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$intervention_Inserted = $db->insertObject('#__action_log_config', $intervention_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the currency action log config object.
			$currency_action_log_config = new stdClass();
			$currency_action_log_config->type_title = 'CURRENCY';
			$currency_action_log_config->type_alias = 'com_costbenefitprojection.currency';
			$currency_action_log_config->id_holder = 'id';
			$currency_action_log_config->title_holder = 'name';
			$currency_action_log_config->table_name = '#__costbenefitprojection_currency';
			$currency_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$currency_Inserted = $db->insertObject('#__action_log_config', $currency_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the help_document action log config object.
			$help_document_action_log_config = new stdClass();
			$help_document_action_log_config->type_title = 'HELP_DOCUMENT';
			$help_document_action_log_config->type_alias = 'com_costbenefitprojection.help_document';
			$help_document_action_log_config->id_holder = 'id';
			$help_document_action_log_config->title_holder = 'title';
			$help_document_action_log_config->table_name = '#__costbenefitprojection_help_document';
			$help_document_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Set the object into the action log config table.
			$help_document_Inserted = $db->insertObject('#__action_log_config', $help_document_action_log_config);
		}
		// do any updates needed
		if ($type === 'update')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the company content type object.
			$company = new stdClass();
			$company->type_title = 'Costbenefitprojection Company';
			$company->type_alias = 'com_costbenefitprojection.company';
			$company->table = '{"special": {"dbtable": "#__costbenefitprojection_company","key": "id","type": "Company","prefix": "costbenefitprojectionTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$company->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"email":"email","name":"name","user":"user","department":"department","country":"country","service_provider":"service_provider","per":"per","males":"males","working_days":"working_days","datayear":"datayear","sick_leave_males":"sick_leave_males","sick_leave_females":"sick_leave_females","total_salary":"total_salary","turnover_comment":"turnover_comment","causesrisks":"causesrisks","not_required":"not_required","productivity_losses":"productivity_losses","total_healthcare":"total_healthcare","females":"females","medical_turnovers_males":"medical_turnovers_males","medical_turnovers_females":"medical_turnovers_females"}}';
			$company->router = 'CostbenefitprojectionHelperRoute::getCompanyRoute';
			$company->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/company.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","department","country","service_provider","per","working_days","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "service_provider","targetTable": "#__costbenefitprojection_service_provider","targetColumn": "id","displayColumn": "user"},{"sourceColumn": "datayear","targetTable": "#__costbenefitprojection_health_data","targetColumn": "year","displayColumn": "country"},{"sourceColumn": "causesrisks","targetTable": "#__costbenefitprojection_causerisk","targetColumn": "id","displayColumn": "name"}]}';

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
			$service_provider->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "user","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"user":"user","country":"country","publicname":"publicname","publicemail":"publicemail","publicnumber":"publicnumber","publicaddress":"publicaddress","testcompanies":"testcompanies"}}';
			$service_provider->router = 'CostbenefitprojectionHelperRoute::getService_providerRoute';
			$service_provider->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/service_provider.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","user","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "user","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "testcompanies","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"}]}';

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
			$country->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "publicaddress","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","user":"user","currency":"currency","codethree":"codethree","codetwo":"codetwo","working_days":"working_days","publicemail":"publicemail","publicnumber":"publicnumber","productivity_losses":"productivity_losses","publicname":"publicname","alias":"alias","publicaddress":"publicaddress","datayear":"datayear","worldzone":"worldzone","presenteeism":"presenteeism","causesrisks":"causesrisks","medical_turnovers":"medical_turnovers","sick_leave":"sick_leave","healthcare":"healthcare"}}';
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
			$causerisk->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","ref":"ref","importname":"importname","import_id":"import_id","description":"description","alias":"alias"}}';
			$causerisk->router = 'CostbenefitprojectionHelperRoute::getCauseriskRoute';
			$causerisk->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/causerisk.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","import_id"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

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
			$scaling_factor->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "causerisk","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"causerisk":"causerisk","company":"company","yld_scaling_factor_males":"yld_scaling_factor_males","yld_scaling_factor_females":"yld_scaling_factor_females","mortality_scaling_factor_males":"mortality_scaling_factor_males","mortality_scaling_factor_females":"mortality_scaling_factor_females","presenteeism_scaling_factor_males":"presenteeism_scaling_factor_males","presenteeism_scaling_factor_females":"presenteeism_scaling_factor_females","health_scaling_factor":"health_scaling_factor","reference":"reference","country":"country"}}';
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
			$intervention->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","company":"company","type":"type","coverage":"coverage","description":"description","duration":"duration","not_required":"not_required","interventions":"interventions","reference":"reference","share":"share","country":"country"}}';
			$intervention->router = 'CostbenefitprojectionHelperRoute::getInterventionRoute';
			$intervention->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/intervention.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","company","type","coverage","duration","not_required","share","country"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "company","targetTable": "#__costbenefitprojection_company","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "interventions","targetTable": "#__costbenefitprojection_intervention","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__costbenefitprojection_country","targetColumn": "id","displayColumn": "name"}]}';

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
			$currency->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","codethree":"codethree","numericcode":"numericcode","symbol":"symbol","alias":"alias","negativestyle":"negativestyle","positivestyle":"positivestyle","decimalsymbol":"decimalsymbol","decimalplace":"decimalplace","thousands":"thousands"}}';
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
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","alias":"alias","content":"content","article":"article","url":"url","target":"target"}}';
			$help_document->router = 'CostbenefitprojectionHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_costbenefitprojection/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","article","target"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

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
				<img src="components/com_costbenefitprojection/assets/images/vdm-component.png"/>
				</a>
				<h3>Upgrade to Version 3.4.7 Was Successful! Let us know if anything is not working as expected.</h3>';

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the costbenefitprojection action logs extensions object.
			$costbenefitprojection_action_logs_extensions = new stdClass();
			$costbenefitprojection_action_logs_extensions->extension = 'com_costbenefitprojection';

			// Check if costbenefitprojection action log extension is already in action logs extensions DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_logs_extensions'));
			$query->where($db->quoteName('extension') . ' LIKE '. $db->quote($costbenefitprojection_action_logs_extensions->extension));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the action logs extensions table if not found.
			if (!$db->getNumRows())
			{
				$costbenefitprojection_action_logs_extensions_Inserted = $db->insertObject('#__action_logs_extensions', $costbenefitprojection_action_logs_extensions);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the company action log config object.
			$company_action_log_config = new stdClass();
			$company_action_log_config->id = null;
			$company_action_log_config->type_title = 'COMPANY';
			$company_action_log_config->type_alias = 'com_costbenefitprojection.company';
			$company_action_log_config->id_holder = 'id';
			$company_action_log_config->title_holder = 'name';
			$company_action_log_config->table_name = '#__costbenefitprojection_company';
			$company_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if company action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($company_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$company_action_log_config->id = $db->loadResult();
				$company_action_log_config_Updated = $db->updateObject('#__action_log_config', $company_action_log_config, 'id');
			}
			else
			{
				$company_action_log_config_Inserted = $db->insertObject('#__action_log_config', $company_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the service_provider action log config object.
			$service_provider_action_log_config = new stdClass();
			$service_provider_action_log_config->id = null;
			$service_provider_action_log_config->type_title = 'SERVICE_PROVIDER';
			$service_provider_action_log_config->type_alias = 'com_costbenefitprojection.service_provider';
			$service_provider_action_log_config->id_holder = 'id';
			$service_provider_action_log_config->title_holder = 'user';
			$service_provider_action_log_config->table_name = '#__costbenefitprojection_service_provider';
			$service_provider_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if service_provider action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($service_provider_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$service_provider_action_log_config->id = $db->loadResult();
				$service_provider_action_log_config_Updated = $db->updateObject('#__action_log_config', $service_provider_action_log_config, 'id');
			}
			else
			{
				$service_provider_action_log_config_Inserted = $db->insertObject('#__action_log_config', $service_provider_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the country action log config object.
			$country_action_log_config = new stdClass();
			$country_action_log_config->id = null;
			$country_action_log_config->type_title = 'COUNTRY';
			$country_action_log_config->type_alias = 'com_costbenefitprojection.country';
			$country_action_log_config->id_holder = 'id';
			$country_action_log_config->title_holder = 'name';
			$country_action_log_config->table_name = '#__costbenefitprojection_country';
			$country_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if country action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($country_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$country_action_log_config->id = $db->loadResult();
				$country_action_log_config_Updated = $db->updateObject('#__action_log_config', $country_action_log_config, 'id');
			}
			else
			{
				$country_action_log_config_Inserted = $db->insertObject('#__action_log_config', $country_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the causerisk action log config object.
			$causerisk_action_log_config = new stdClass();
			$causerisk_action_log_config->id = null;
			$causerisk_action_log_config->type_title = 'CAUSERISK';
			$causerisk_action_log_config->type_alias = 'com_costbenefitprojection.causerisk';
			$causerisk_action_log_config->id_holder = 'id';
			$causerisk_action_log_config->title_holder = 'name';
			$causerisk_action_log_config->table_name = '#__costbenefitprojection_causerisk';
			$causerisk_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if causerisk action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($causerisk_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$causerisk_action_log_config->id = $db->loadResult();
				$causerisk_action_log_config_Updated = $db->updateObject('#__action_log_config', $causerisk_action_log_config, 'id');
			}
			else
			{
				$causerisk_action_log_config_Inserted = $db->insertObject('#__action_log_config', $causerisk_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the health_data action log config object.
			$health_data_action_log_config = new stdClass();
			$health_data_action_log_config->id = null;
			$health_data_action_log_config->type_title = 'HEALTH_DATA';
			$health_data_action_log_config->type_alias = 'com_costbenefitprojection.health_data';
			$health_data_action_log_config->id_holder = 'id';
			$health_data_action_log_config->title_holder = 'causerisk';
			$health_data_action_log_config->table_name = '#__costbenefitprojection_health_data';
			$health_data_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if health_data action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($health_data_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$health_data_action_log_config->id = $db->loadResult();
				$health_data_action_log_config_Updated = $db->updateObject('#__action_log_config', $health_data_action_log_config, 'id');
			}
			else
			{
				$health_data_action_log_config_Inserted = $db->insertObject('#__action_log_config', $health_data_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the scaling_factor action log config object.
			$scaling_factor_action_log_config = new stdClass();
			$scaling_factor_action_log_config->id = null;
			$scaling_factor_action_log_config->type_title = 'SCALING_FACTOR';
			$scaling_factor_action_log_config->type_alias = 'com_costbenefitprojection.scaling_factor';
			$scaling_factor_action_log_config->id_holder = 'id';
			$scaling_factor_action_log_config->title_holder = 'causerisk';
			$scaling_factor_action_log_config->table_name = '#__costbenefitprojection_scaling_factor';
			$scaling_factor_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if scaling_factor action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($scaling_factor_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$scaling_factor_action_log_config->id = $db->loadResult();
				$scaling_factor_action_log_config_Updated = $db->updateObject('#__action_log_config', $scaling_factor_action_log_config, 'id');
			}
			else
			{
				$scaling_factor_action_log_config_Inserted = $db->insertObject('#__action_log_config', $scaling_factor_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the intervention action log config object.
			$intervention_action_log_config = new stdClass();
			$intervention_action_log_config->id = null;
			$intervention_action_log_config->type_title = 'INTERVENTION';
			$intervention_action_log_config->type_alias = 'com_costbenefitprojection.intervention';
			$intervention_action_log_config->id_holder = 'id';
			$intervention_action_log_config->title_holder = 'name';
			$intervention_action_log_config->table_name = '#__costbenefitprojection_intervention';
			$intervention_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if intervention action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($intervention_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$intervention_action_log_config->id = $db->loadResult();
				$intervention_action_log_config_Updated = $db->updateObject('#__action_log_config', $intervention_action_log_config, 'id');
			}
			else
			{
				$intervention_action_log_config_Inserted = $db->insertObject('#__action_log_config', $intervention_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the currency action log config object.
			$currency_action_log_config = new stdClass();
			$currency_action_log_config->id = null;
			$currency_action_log_config->type_title = 'CURRENCY';
			$currency_action_log_config->type_alias = 'com_costbenefitprojection.currency';
			$currency_action_log_config->id_holder = 'id';
			$currency_action_log_config->title_holder = 'name';
			$currency_action_log_config->table_name = '#__costbenefitprojection_currency';
			$currency_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if currency action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($currency_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$currency_action_log_config->id = $db->loadResult();
				$currency_action_log_config_Updated = $db->updateObject('#__action_log_config', $currency_action_log_config, 'id');
			}
			else
			{
				$currency_action_log_config_Inserted = $db->insertObject('#__action_log_config', $currency_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the help_document action log config object.
			$help_document_action_log_config = new stdClass();
			$help_document_action_log_config->id = null;
			$help_document_action_log_config->type_title = 'HELP_DOCUMENT';
			$help_document_action_log_config->type_alias = 'com_costbenefitprojection.help_document';
			$help_document_action_log_config->id_holder = 'id';
			$help_document_action_log_config->title_holder = 'title';
			$help_document_action_log_config->table_name = '#__costbenefitprojection_help_document';
			$help_document_action_log_config->text_prefix = 'COM_COSTBENEFITPROJECTION';

			// Check if help_document action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($help_document_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$help_document_action_log_config->id = $db->loadResult();
				$help_document_action_log_config_Updated = $db->updateObject('#__action_log_config', $help_document_action_log_config, 'id');
			}
			else
			{
				$help_document_action_log_config_Inserted = $db->insertObject('#__action_log_config', $help_document_action_log_config);
			}
		}
		return true;
	}

	/**
	 * Remove folders with files
	 * 
	 * @param   string   $dir     The path to folder to remove
	 * @param   boolean  $ignore  The folders and files to ignore and not remove
	 *
	 * @return  boolean   True in all is removed
	 * 
	 */
	protected function removeFolder($dir, $ignore = false)
	{
		if (JFolder::exists($dir))
		{
			$it = new RecursiveDirectoryIterator($dir);
			$it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
			// remove ending /
			$dir = rtrim($dir, '/');
			// now loop the files & folders
			foreach ($it as $file)
			{
				if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
				// set file dir
				$file_dir = $file->getPathname();
				// check if this is a dir or a file
				if ($file->isDir())
				{
					$keeper = false;
					if ($this->checkArray($ignore))
					{
						foreach ($ignore as $keep)
						{
							if (strpos($file_dir, $dir.'/'.$keep) !== false)
							{
								$keeper = true;
							}
						}
					}
					if ($keeper)
					{
						continue;
					}
					JFolder::delete($file_dir);
				}
				else
				{
					$keeper = false;
					if ($this->checkArray($ignore))
					{
						foreach ($ignore as $keep)
						{
							if (strpos($file_dir, $dir.'/'.$keep) !== false)
							{
								$keeper = true;
							}
						}
					}
					if ($keeper)
					{
						continue;
					}
					JFile::delete($file_dir);
				}
			}
			// delete the root folder if not ignore found
			if (!$this->checkArray($ignore))
			{
				return JFolder::delete($dir);
			}
			return true;
		}
		return false;
	}

	/**
	 * Check if have an array with a length
	 *
	 * @input	array   The array to check
	 *
	 * @returns bool/int  number of items in array on success
	 */
	protected function checkArray($array, $removeEmptyString = false)
	{
		if (isset($array) && is_array($array) && ($nr = count((array)$array)) > 0)
		{
			// also make sure the empty strings are removed
			if ($removeEmptyString)
			{
				foreach ($array as $key => $string)
				{
					if (empty($string))
					{
						unset($array[$key]);
					}
				}
				return $this->checkArray($array, false);
			}
			return $nr;
		}
		return false;
	}

	/**
	 * Method to set/copy dynamic folders into place (use with caution)
	 *
	 * @return void
	 */
	protected function setDynamicF0ld3rs($app, $parent)
	{
		// get the instalation path
		$installer = $parent->getParent();
		$installPath = $installer->getPath('source');
		// get all the folders
		$folders = JFolder::folders($installPath);
		// check if we have folders we may want to copy
		$doNotCopy = array('media','admin','site'); // Joomla already deals with these
		if (count((array) $folders) > 1)
		{
			foreach ($folders as $folder)
			{
				// Only copy if not a standard folders
				if (!in_array($folder, $doNotCopy))
				{
					// set the source path
					$src = $installPath.'/'.$folder;
					// set the destination path
					$dest = JPATH_ROOT.'/'.$folder;
					// now try to copy the folder
					if (!JFolder::copy($src, $dest, '', true))
					{
						$app->enqueueMessage('Could not copy '.$folder.' folder into place, please make sure destination is writable!', 'error');
					}
				}
			}
		}
	}
}
