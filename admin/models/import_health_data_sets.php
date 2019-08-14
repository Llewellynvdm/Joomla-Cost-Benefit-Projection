<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		import_health_data_sets.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Costbenefitprojection Import_health_data_sets Model
 */
class CostbenefitprojectionModelImport_health_data_sets extends JModelLegacy
{
	// set uploading values
	protected $use_streams = false;
	protected $allow_unsafe = false;
	protected $safeFileOptions = array();
	
	/**
	 * @var object JTable object
	 */
	protected $_table = null;

	/**
	 * @var object JTable object
	 */
	protected $_url = null;

	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_costbenefitprojection.import_health_data_sets';
	
	/**
	 * Import Settings
	 */
	protected $getType = NULL;
	protected $dataType = NULL;
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		$this->setState('message', $app->getUserState('com_costbenefitprojection.message'));
		$app->setUserState('com_costbenefitprojection.message', '');

		// Recall the 'Import from Directory' path.
		$path = $app->getUserStateFromRequest($this->_context . '.import_directory', 'import_directory', $app->get('tmp_path'));
		$this->setState('import.directory', $path);
		parent::populateState();
	}
	
	/**
	 * Import an spreadsheet from either folder, url or upload.
	 *
	 * @return  boolean result of import
	 *
	 */
	public function import()
	{
		$this->setState('action', 'import');
		$app 			= JFactory::getApplication();
		$session 			= JFactory::getSession();
		$package 			= null;
		$continue			= false;
		// get import type
		$this->getType 		= $app->input->getString('gettype', NULL);
		// get import type
		$this->dataType		= $session->get('dataType_VDM_IMPORTINTO', NULL);

		if ($package === null)
		{
			switch ($this->getType)
			{
				case 'folder':
					// Remember the 'Import from Directory' path.
					$app->getUserStateFromRequest($this->_context . '.import_directory', 'import_directory');
					$package = $this->_getPackageFromFolder();
					break;

				case 'upload':
					$package = $this->_getPackageFromUpload();
					break;

				case 'url':
					$package = $this->_getPackageFromUrl();
					break;

				case 'continue-basic':
				case 'continue-adv':
					$continue 	= true;
					$package	= $session->get('package', null);
					$package	= json_decode($package, true);
					// clear session
					$session->clear('package');
					$session->clear('dataType');
					$session->clear('hasPackage');
					break;

				default:
					$app->setUserState('com_costbenefitprojection.message', JText::_('COM_COSTBENEFITPROJECTION_IMPORT_NO_IMPORT_TYPE_FOUND'));
					return false;
					break;
			}
		}
		// Was the package valid?
		if (!$package || !$package['type'])
		{
			if (in_array($this->getType, array('upload', 'url')))
			{
				$this->remove($package['packagename']);
			}

			$app->setUserState('com_costbenefitprojection.message', JText::_('COM_COSTBENEFITPROJECTION_IMPORT_UNABLE_TO_FIND_IMPORT_PACKAGE'));
			return false;
		}
		
		// first link data to table headers
		if(!$continue){
			$package	= json_encode($package);
			$session->set('package', $package);
			$session->set('dataType', $this->dataType);
			$session->set('hasPackage', true);
			return true;
		}
		// set the data
		$headerList = json_decode($session->get($this->dataType.'_VDM_IMPORTHEADERS', false), true);
		if (!$this->setData($package,$headerList))
		{
			// There was an error importing the package
			$msg = JText::_('COM_COSTBENEFITPROJECTION_IMPORT_ERROR');
			$back = $session->get('backto_VDM_IMPORT', NULL);
			if ($back)
			{
				$app->setUserState('com_costbenefitprojection.redirect_url', 'index.php?option=com_costbenefitprojection&view='.$back);
				$session->clear('backto_VDM_IMPORT');
			}
			$result = false;
		}
		else
		{
			// Package imported sucessfully
			$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_SUCCESS', $package['packagename']);
			$back = $session->get('backto_VDM_IMPORT', NULL);
			if ($back)
			{
			    $app->setUserState('com_costbenefitprojection.redirect_url', 'index.php?option=com_costbenefitprojection&view='.$back);
			    $session->clear('backto_VDM_IMPORT');
			}
			$result = true;
		}

		// Set some model state values
		$app->enqueueMessage($msg);

		// remove file after import
		$this->remove($package['packagename']);
		$session->clear($this->getType.'_VDM_IMPORTHEADERS');
		return $result;
	} 

	/**
	 * Works out an importation spreadsheet from a HTTP upload
	 *
	 * @return spreadsheet definition or false on failure
	 */
	protected function _getPackageFromUpload()
	{		
		// Get the uploaded file information
		$app = JFactory::getApplication();
		$input = $app->input;

		// Do not change the filter type 'raw'. We need this to let files containing PHP code to upload. See JInputFiles::get.
		$userfile = $input->files->get('import_package', null, 'raw');
		
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads'))
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_WARNIMPORTFILE'), 'warning');
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile))
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_NO_FILE_SELECTED'), 'warning');
			return false;
		}

		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_WARNIMPORTUPLOADERROR'), 'warning');
			return false;
		}

		// Build the appropriate paths
		$config = JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path') . '/' . $userfile['name'];
		$tmp_src = $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$p_file = JFile::upload($tmp_src, $tmp_dest, $this->use_streams, $this->allow_unsafe, $this->safeFileOptions);

		// Was the package downloaded?
		if (!$p_file)
		{
			$session = JFactory::getSession();
			$session->clear('package');
			$session->clear('dataType');
			$session->clear('hasPackage');
			// was not uploaded
			return false;
		}

		// check that this is a valid spreadsheet
		$package = $this->check($userfile['name']);

		return $package;
	}

	/**
	 * Import an spreadsheet from a directory
	 *
	 * @return  array  Spreadsheet details or false on failure
	 *
	 */
	protected function _getPackageFromFolder()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		// Get the path to the package to import
		$p_dir = $input->getString('import_directory');
		$p_dir = JPath::clean($p_dir);
		// Did you give us a valid path?
		if (!file_exists($p_dir))
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_PLEASE_ENTER_A_PACKAGE_DIRECTORY'), 'warning');
			return false;
		}

		// Detect the package type
		$type = $this->getType;

		// Did you give us a valid package?
		if (!$type)
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_PATH_DOES_NOT_HAVE_A_VALID_PACKAGE'), 'warning');
		}
		
		// check the extention
		if(!$this->checkExtension($p_dir))
		{
			// set error message
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_DOES_NOT_HAVE_A_VALID_FILE_TYPE'), 'warning');
			return false;
		}
		
		$package['packagename'] = null;
		$package['dir'] = $p_dir;
		$package['type'] = $type;

		return $package;
	}

	/**
	 * Import an spreadsheet from a URL
	 *
	 * @return  Package details or false on failure
	 *
	 */
	protected function _getPackageFromUrl()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		// Get the URL of the package to import
		$url = $input->getString('import_url');

		// Did you give us a URL?
		if (!$url)
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_ENTER_A_URL'), 'warning');
			return false;
		}

		// Download the package at the URL given
		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file)
		{
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_INVALID_URL'), 'warning');
			return false;
		}

		// check that this is a valid spreadsheet
		$package = $this->check($p_file);

		return $package;
	}
	
	/**
	 * Check a file and verifies it as a spreadsheet file
	 * Supports .csv .xlsx .xls and .ods
	 *
	 * @param   string  $p_filename  The uploaded package filename or import directory
	 *
	 * @return  array  of elements
	 *
	 */
	protected function check($archivename)
	{
		$app = JFactory::getApplication();
		// Clean the name
		$archivename = JPath::clean($archivename);
		
		// check the extention
		if(!$this->checkExtension($archivename))
		{
			// Cleanup the import files
			$this->remove($archivename);
			$app->enqueueMessage(JText::_('COM_COSTBENEFITPROJECTION_IMPORT_MSG_DOES_NOT_HAVE_A_VALID_FILE_TYPE'), 'warning');
			return false;
		}
		
		$config = JFactory::getConfig();
		// set Package Name
		$check['packagename'] = $archivename;
		
		// set directory
		$check['dir'] = $config->get('tmp_path'). '/' .$archivename;
		
		// set type
		$check['type'] = $this->getType;
		
		return $check;
	}
	
	/**
	 * Check the extension
	 *
	 * @param   string  $file    Name of the uploaded file
	 *
	 * @return  boolean  True on success
	 *
	 */
	protected function checkExtension($file)
	{
		// check the extention
		switch(strtolower(pathinfo($file, PATHINFO_EXTENSION)))
		{
			case 'xls':
			case 'ods':
			case 'csv':
			return true;
			break;
		}
		return false;
	} 
	
	/**
	 * Clean up temporary uploaded spreadsheet
	 *
	 * @param   string  $package    Name of the uploaded spreadsheet file
	 *
	 * @return  boolean  True on success
	 *
	 */
	protected function remove($package)
	{
		jimport('joomla.filesystem.file');
		
		$config = JFactory::getConfig();
		$package = $config->get('tmp_path'). '/' .$package;

		// Is the package file a valid file?
		if (is_file($package))
		{
			JFile::delete($package);
		}
		elseif (is_file(JPath::clean($package)))
		{
			// It might also be just a base filename
			JFile::delete(JPath::clean($package));
		}
	}
	
	/**
	* Set the data from the spreadsheet to the database
	*
	* @param string  $package Paths to the uploaded package file
	*
	* @return  boolean false on failure
	*
	**/
	protected function setData($package,$target_headers)
	{
		$jinput = JFactory::getApplication()->input;
		// set the data based on the type of import being done
		if ('continue-basic' == $this->getType && CostbenefitprojectionHelper::checkArray($target_headers))
		{
			foreach($target_headers as $header)
			{
				$data['target_headers'][$header] = $jinput->getString($header, null);
			}
			// make sure the file is loaded		
			JLoader::import('PHPExcel', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
			// set the data
			if(isset($package['dir']))
			{
				$inputFileType = PHPExcel_IOFactory::identify($package['dir']);
				$excelReader = PHPExcel_IOFactory::createReader($inputFileType);
				$excelReader->setReadDataOnly(true);
				$excelObj = $excelReader->load($package['dir']);
				$data['array'] = $excelObj->getActiveSheet()->toArray(null, true,true,true);
				$excelObj->disconnectWorksheets();
				unset($excelObj);
				return $this->saveBasic($data);
			}
		}
		elseif ('continue-adv' == $this->getType)
		{
			// Get a db connection.
			$db = JFactory::getDbo();
			// get list of cause & risks
			// Create a new query object.
			$query = $db->getQuery(true);
			// Select all disease names.
			// Order it by the disease_name field.
			$query->select($db->quoteName('import_id'));
			$query->from($db->quoteName('#__costbenefitprojection_causerisk'));
			$query->order('id ASC');
			 
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			// set global for import
			$get_list 				= $db->loadColumn();
			// ages to get
			$this->ageIds 		= array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17);
			$this->ageKeys 		= array(8 => 0, 9 => 1, 10 => 2, 11 => 3, 12 => 4, 13 => 5, 14 => 6, 15 => 7, 16 => 8, 17 => 9);
			$this->ageKeyNames 	= array(8 => "15-19", 9 => "20-24",10 => "25-29",11 => "30-34",12 => "35-39",13 => "40-44",14 =>"45-49",15 =>"50-54",16 =>"55-59",17 => "60-64");
			$this->ageNames 		= array("15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64");
			// the gender
			$this->genderIds 	= array(1, 2);
			$this->genderKeys 	= array(1 => "male", 2 => "female");
			// the import years
			$this->selection_years 	= array(2010, 2013);
			// to get the totals
			$this->idTotal		= array(array("key" => "cause_name", "val" => "All causes"),array("key" => "risk", "val" => 0));
			// to check cause
			$this->idCause		= array(array("key" => "risk", "val" => 0));
			// to check risk
			$this->idRisk		= array(array("key" => "cause_name", "val" => "All causes"));
			// id Death
			$this->idDeath 		= array(array("key" => "metric", "val" => 1));
			// id Yld
			$this->idYld 		= array(array("key" => "metric", "val" => 3));
			// set the headers
			$target_headers	= array(
					"location_name",
					"year",
					"cause",
					"cause_name",
					"risk",
					"risk_name",
					"age",
					"age_name",
					"sex",
					"sex_name",
					"rt_mean",
					"metric",
					"metric_name");
			// set the data
			if(isset($package['dir']))
			{
				if(($handle = fopen($package['dir'], 'r')) !== false)
				{
					$this->data = array();
					$this->totals = array();
					// remove the first row, which contains the column-titles
					$header = fgetcsv($handle);
					// find the pointers
					foreach($target_headers as $header)
					{
						$this->{$header} = $jinput->getInt('adv_'.$header);
					}
					// set the values
					if(CostbenefitprojectionHelper::checkArray($get_list))
					{
						// loop through the file line-by-line
						while(($data = fgetcsv($handle)) !== false)
						{
							if (($type =$this->loadValue($data,$get_list)) !== false)
							{
								// set the dataset
								$this->setValue($data, $type);
							}
							unset($data);
						}
					}
					fclose($handle);
				}
				return $this->saveAdv($db);
			}
		}
		return false;
	}

	protected function setValue(&$data,&$type)
	{
		// set the year
		$year = $data[$this->year];
		if (isset($data['import_id']))
		{
			// set import ID
			$id = $data['import_id'];
			// load the year always first
			if (!isset($this->data[$year]))
			{
				$this->data[$year] 			= array();
			}
			// set id of the cause/risk
			if (!isset($this->data[$year][$id]['causerisk']))
			{
				$this->data[$year][$id]['causerisk'] 	= CostbenefitprojectionHelper::getVar('causerisk', $id, 'import_id', 'id');
				$this->data[$year][$id]['year'] 		= $year;
				$this->data[$year][$id]['country'] 		= CostbenefitprojectionHelper::getVar('country', $data[$this->location_name], 'name', 'id');
			}
			// insure the type is loaded
			if (!isset($this->data[$year][$id][$type]))
			{
				$this->data[$year][$id][$type]			= array();
				$this->data[$year][$id][$type]['age'] 		= $this->ageNames;
				$this->data[$year][$id][$type]['number'] 	= array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null, 8 => null, 9 => null);
			}
			// now loading the value
			$this->data[$year][$id][$type]['number'][$this->ageKeys[$data[$this->age]]] = (float)$data[$this->rt_mean];
		}
		elseif (isset($data['country_name']))
		{
			// set the country name
			if (!isset($this->totals['name']))
			{
				$this->totals['name']	= $data['country_name'];
				$this->totals['id'] 		= CostbenefitprojectionHelper::getVar('country', $data['country_name'], 'name', 'id');
			}
			// always first set the basic columns
			if (!isset($this->totals[$type]))
			{
				$this->totals[$type] = array();
				$this->totals[$type]['age'] = array();
				$this->totals[$type]['number'] = array();
				$this->totals[$type]['year'] = array();
			}
			// load basics if type year not set
			if (!in_array($year,$this->totals[$type]['year']))
			{
				// load this year
				foreach ($this->ageNames as $nr => $age)
				{
					$this->totals[$type]['age'][] 		= $age;
					$this->totals[$type]['number'][] 	= null;
					$this->totals[$type]['year'][]		= (int) $year;
				}
			}
			// set the value
			$this->setCountryValue($type,$year,$this->ageKeyNames[$data[$this->age]],$data[$this->rt_mean]);
		}
	}

	protected function setCountryValue(&$type,&$year,&$age,&$value)
	{
		// get the keys of this year
		$keys = array_keys($this->totals[$type]['year'], $year);
		foreach ($keys as $key)
		{
			// get the right age key
			if ($this->totals[$type]['age'][$key] == $age)
			{
				// set the value now
				$this->totals[$type]['number'][$key] = (float)$value;
				break;
			}
		}
	}

	protected function loadValue(&$data, &$get_list)
	{
		if (	in_array($data[$this->year], $this->selection_years) && 
				in_array($data[$this->age], $this->ageIds) && 
				in_array($data[$this->sex], $this->genderIds))
		{
			// what value type is this
			if ($data[$this->cause] > 0 && in_array($data[$this->cause], $get_list) && $this->identify($data,'Cause'))
			{
				// this is a cause
				$data['import_id'] = $data[$this->cause];
				$data['import_name'] = $data[$this->cause_name];
				return $this->getType($data);
			}
			elseif ($data[$this->risk] > 0 && in_array($data[$this->risk], $get_list) && $this->identify($data,'Risk'))
			{
				// this is a risk
				$data['import_id'] = $data[$this->risk];
				$data['import_name'] = $data[$this->risk_name];
				return $this->getType($data);
			}
			elseif ($this->identify($data,'Total'))
			{
				// load the totals
				$data['country_name'] = $data[$this->location_name];
				return $this->getType($data);	
			}
		}
		return false;
	}

	protected function getType(&$data)
	{
		$type = false;
		// what data type of import is this
		if ($this->identify($data,'Death'))
		{
			// is a Death
			$type = $this->genderKeys[$data[$this->sex]].'death';
		}
		elseif ($this->identify($data,'Yld'))
		{
			// is a YLD
			$type = $this->genderKeys[$data[$this->sex]].'yld';
		}
		// set to type bucket for latter use
		$this->typeBucket[$type] = $type;
		// return the type
		return $type;
	}

	protected function identify(&$data, $type)
	{
		$found = false;
		if (isset($this->{'id'.$type}) && CostbenefitprojectionHelper::checkArray($this->{'id'.$type}))
		{
			foreach ($this->{'id'.$type} as $check)
			{
				if (isset($check['key']) && isset($check['val']) && $data[$this->$check['key']] == $check['val'])
				{
					$found = true;
				}
				else
				{
					$found = false;
					break;
				}
			}
		}
		return $found;
	} 
	
	/**
	* Save the data from the file to the database
	*
	* @return  boolean false on failure
	*
	**/
	protected function saveAdv(&$db)
	{
		// todays date
		$this->dateSql 	= JFactory::getDate()->toSql();
		// set this user
		$this->user 		= JFactory::getUser();
		// save the country health data
		if (isset($this->data) && CostbenefitprojectionHelper::checkArray($this->data))
		{
			// get global action permissions
			$canDo			= CostbenefitprojectionHelper::getActions('health_data');
			$this->canEdit	= $canDo->get('core.edit');
			$this->canCreate	= $canDo->get('core.create');
			foreach ($this->data as $year => &$values)
			{
				if (CostbenefitprojectionHelper::checkArray($values) && CostbenefitprojectionHelper::checkArray($this->typeBucket))
				{
					foreach ($values as $import_id => &$healthData)
					{
						foreach ($this->typeBucket as $type)
						{
							if (isset($healthData[$type]))
							{
								$healthData[$type] = json_encode($healthData[$type]);
							}
						}
						if (!$this->saveObject( $db, (object) $healthData,'health_data'))
						{
							return false;
						}
					}
				}
			}
			$doneData = true;
		}
		// save the country totals
		if (isset($this->totals) && CostbenefitprojectionHelper::checkArray($this->totals))
		{
			// get global action permissions
			$canDo			= CostbenefitprojectionHelper::getActions('country');
			$this->canEdit	= $canDo->get('core.edit');
			if (isset($this->typeBucket) && CostbenefitprojectionHelper::checkArray($this->typeBucket))
			{
				foreach ($this->typeBucket as $type)
				{
					if (isset($this->totals[$type]))
					{
						$this->totals[$type] = json_encode($this->totals[$type]);
					}
				}
			}
			if (!$this->saveObject( $db, (object) $this->totals,'country'))
			{
				return false;
			}
			$doneCountry = true;
		}
		if (isset($doneData) && isset($doneCountry) && ($doneData && $doneCountry))
		{
			return true;
		}
		return false;
	}

	/**
	* Save the data from the file to the database
	*
	* @param array  $data The values to save
	*
	* @return  boolean false on failure
	*
	**/
	protected function saveObject(&$db, $data, $type)
	{
		if ($type == 'health_data')
		{
			// first we check if the data is already set
			$id = $this->getHealthDataId($db, $data->causerisk,$data->year,$data->country);
		}
		// if new then insert data
		if ($type == 'health_data' && !$id && $this->canCreate)
		{
			// build some extra data
			$data->created_by = $this->user->id;
			$data->created = $this->dateSql;
			$data->version = 1;
			$data->published = 1;
			$data->access = 1;
			$done = $db->insertObject('#__costbenefitprojection_'.$type, $data);
			if ($done)
			{
				$aId = $db->insertid();
				// make sure the access of asset is set
				return CostbenefitprojectionHelper::setAsset($aId,$type);
			}
		}
		elseif ($this->canEdit)
		{
			// build some extra data
			if ($type == 'health_data' && $id > 0)
			{
				$data->id = $id;
			}
			if ($data->id > 0 )
			{
				$version = CostbenefitprojectionHelper::getVar($type, $data->id, 'id', 'version');
				$data->version = $version + 1;
				$data->modified_by = $this->user->id;
				$data->modified = $this->dateSql;
				return $db->updateObject('#__costbenefitprojection_'.$type, $data, 'id');
			}
		}
		return false;						
	}

	protected function getHealthDataId($db, $causerisk,$year,$country)
	{
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__costbenefitprojection_health_data'));
		$query->where($db->quoteName('causerisk') . ' = '.(int) $causerisk);
		$query->where($db->quoteName('year') . ' = '. (int) $year);
		$query->where($db->quoteName('country') . ' ='. (int) $country);
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}

	/**
	* Save the data from the file to the database
	*
	* @param array  $data The values to save
	*
	* @return  boolean false on failure
	*
	**/
	protected function saveBasic($data)
	{
		// import the data if there is any
		if(CostbenefitprojectionHelper::checkArray($data['array']))
		{
			// get user object
			$user  		= JFactory::getUser();
			// remove header if it has headers
			$id_key 	= $data['target_headers']['id'];
			$published_key 	= $data['target_headers']['published'];
			$ordering_key 	= $data['target_headers']['ordering'];
			// get the first array set
			$firstSet = reset($data['array']);

			// check if first array is a header array and remove if true
			if($firstSet[$id_key] == 'id' || $firstSet[$published_key] == 'published' || $firstSet[$ordering_key] == 'ordering')
			{
				array_shift($data['array']);
			}
			
			// make sure there is still values in array and that it was not only headers
			if(CostbenefitprojectionHelper::checkArray($data['array']) && $user->authorise($this->dataType.'.import', 'com_costbenefitprojection') && $user->authorise('core.import', 'com_costbenefitprojection'))
			{
				// set target.
				$target	= array_flip($data['target_headers']);
				// Get a db connection.
				$db = JFactory::getDbo();
				// set some defaults
				$todayDate		= JFactory::getDate()->toSql();
				// get global action permissions
				$canDo			= CostbenefitprojectionHelper::getActions($this->dataType);
				$canEdit		= $canDo->get('core.edit');
				$canState		= $canDo->get('core.edit.state');
				$canCreate		= $canDo->get('core.create');
				$hasAlias		= $this->getAliasesUsed($this->dataType);
				// prosses the data
				foreach($data['array'] as $row)
				{
					$found = false;
					if (isset($row[$id_key]) && is_numeric($row[$id_key]) && $row[$id_key] > 0)
					{
						// raw items import & update!
						$query = $db->getQuery(true);
						$query
							->select('version')
							->from($db->quoteName('#__costbenefitprojection_'.$this->dataType))
							->where($db->quoteName('id') . ' = '. $db->quote($row[$id_key]));
						// Reset the query using our newly populated query object.
						$db->setQuery($query);
						$db->execute();
						$found = $db->getNumRows();
					}
					
					if($found && $canEdit)
					{
						// update item
						$id 		= $row[$id_key];
						$version	= $db->loadResult();
						// reset all buckets
						$query 		= $db->getQuery(true);
						$fields 	= array();
						// Fields to update.
						foreach($row as $key => $cell)
						{
							// ignore column
							if ('IGNORE' == $target[$key])
							{
								continue;
							}
							// update modified
							if ('modified_by' == $target[$key])
							{
								continue;
							}
							// update modified
							if ('modified' == $target[$key])
							{
								continue;
							}
							// update version
							if ('version' == $target[$key])
							{
								$cell = (int) $version + 1;
							}
							// verify publish authority
							if ('published' == $target[$key] && !$canState)
							{
								continue;
							}
							// set to update array
							if(in_array($key, $data['target_headers']) && is_numeric($cell))
							{
								$fields[] = $db->quoteName($target[$key]) . ' = ' . $cell;
							}
							elseif(in_array($key, $data['target_headers']) && is_string($cell))
							{
								$fields[] = $db->quoteName($target[$key]) . ' = ' . $db->quote($cell);
							}
							elseif(in_array($key, $data['target_headers']) && is_null($cell))
							{
								// if import data is null then set empty
								$fields[] = $db->quoteName($target[$key]) . " = ''";
							}
						}
						// load the defaults
						$fields[]	= $db->quoteName('modified_by') . ' = ' . $db->quote($user->id);
						$fields[]	= $db->quoteName('modified') . ' = ' . $db->quote($todayDate);
						// Conditions for which records should be updated.
						$conditions = array(
							$db->quoteName('id') . ' = ' . $id
						);
						
						$query->update($db->quoteName('#__costbenefitprojection_'.$this->dataType))->set($fields)->where($conditions);
						$db->setQuery($query);
						$db->execute();
					}
					elseif ($canCreate)
					{
						// insert item
						$query = $db->getQuery(true);
						// reset all buckets
						$columns 	= array();
						$values 	= array();
						$version	= false;
						// Insert columns. Insert values.
						foreach($row as $key => $cell)
						{
							// ignore column
							if ('IGNORE' == $target[$key])
							{
								continue;
							}
							// remove id
							if ('id' == $target[$key])
							{
								continue;
							}
							// update created
							if ('created_by' == $target[$key])
							{
								continue;
							}
							// update created
							if ('created' == $target[$key])
							{
								continue;
							}
							// Make sure the alias is incremented
							if ('alias' == $target[$key])
							{
								$cell = $this->getAlias($cell,$this->dataType);
							}
							// update version
							if ('version' == $target[$key])
							{
								$cell = 1;
								$version = true;
							}
							// set to insert array
							if(in_array($key, $data['target_headers']) && is_numeric($cell))
							{
								$columns[] 	= $target[$key];
								$values[] 	= $cell;
							}
							elseif(in_array($key, $data['target_headers']) && is_string($cell))
							{
								$columns[] 	= $target[$key];
								$values[] 	= $db->quote($cell);
							}
							elseif(in_array($key, $data['target_headers']) && is_null($cell))
							{
								// if import data is null then set empty
								$columns[] 	= $target[$key];
								$values[] 	= "''";
							}
						}
						// load the defaults
						$columns[] 	= 'created_by';
						$values[] 	= $db->quote($user->id);
						$columns[] 	= 'created';
						$values[] 	= $db->quote($todayDate);
						if (!$version)
						{
							$columns[] 	= 'version';
							$values[] 	= 1;
						}
						// Prepare the insert query.
						$query
							->insert($db->quoteName('#__costbenefitprojection_'.$this->dataType))
							->columns($db->quoteName($columns))
							->values(implode(',', $values));
						// Set the query using our newly populated query object and execute it.
						$db->setQuery($query);
						$done = $db->execute();
						if ($done)
						{
							$aId = $db->insertid();
							// make sure the access of asset is set
							CostbenefitprojectionHelper::setAsset($aId,$this->dataType);
						}
					}
					else
					{
						return false;
					}
				}
				return true;
			}
		}
		return false;
	}
	
	protected function getAlias($name,$type = false)
	{
		// sanitize the name to an alias
		if (JFactory::getConfig()->get('unicodeslugs') == 1)
		{
			$alias = JFilterOutput::stringURLUnicodeSlug($name);
		}
		else
		{
			$alias = JFilterOutput::stringURLSafe($name);
		}
		// must be a uniqe alias
		if ($type)
		{
			return $this->getUniqe($alias,'alias',$type);
		}
		return $alias;
	}
	
	/**
	 * Method to generate a uniqe value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 * @param   string  $type table.
	 *
	 * @return  string  New value.
	 */
	protected function getUniqe($value,$field,$type)
	{
		// insure the filed is always uniqe
		while (isset($this->uniqeValueArray[$type][$field][$value]))
		{
			$value = JString::increment($value, 'dash');
		}
		$this->uniqeValueArray[$type][$field][$value] = $value;
		return $value;
	}
	
	protected function getAliasesUsed($table)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// first we check if there is a alias column
		$columns = $db->getTableColumns('#__costbenefitprojection_'.$table);
		if(isset($columns['alias'])){
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('alias')));
			$query->from($db->quoteName('#__costbenefitprojection_'.$table));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$aliases = $db->loadColumn();
				foreach($aliases as $alias)
				{
					$this->uniqeValueArray[$table]['alias'][$alias] = $alias;
				}
			}
			return true;
		}
		return false;
	}
}
