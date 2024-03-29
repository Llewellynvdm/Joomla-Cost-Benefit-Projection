<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		costbenefitprojection.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Language;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * Costbenefitprojection component helper
 */
abstract class CostbenefitprojectionHelper
{
	/**
	 * Composer Switch
	 * 
	 * @var      array
	 */
	protected static $composer = array();

	/**
	 * The Main Active Language
	 * 
	 * @var      string
	 */
	public static $langTag;

	public static function userIs($id = null)
	{
		if($id > 0)
		{
			$user = JFactory::getUser($id);
		}
		else
		{
			$user = JFactory::getUser();
		}
		// get this user groups
		$groups	= (array) $user->getAuthorisedGroups();
		// get params
		$params	= JComponentHelper::getParams('com_costbenefitprojection');
		// get target groups
		$countryGroups 			= (array) $params->get('countryuser');
		$serviceproviderGroups		= (array) $params->get('serviceprovideruser');
		$memberGroups 			= (array) $params->get('memberuser');
		// now check in what group this user belongs
		if (array_intersect($countryGroups, $groups))
		{
			// is country
			return 3;
		}
		elseif (array_intersect($serviceproviderGroups, $groups))
		{
			// is serviceprovider
			return 2;
		}
		elseif (array_intersect($memberGroups, $groups))
		{
			// is member
			return 1;
		}
		return false;
	}

	public static function checkIntervetionAccess($id, $share = null, $comp = null)
	{
                // set share value if not set
                if (!$share)
                {
                        $share = self::getId('intervention', $id, 'id', 'share');
                }
                // based on shared we set needed values
                switch ($share)
                {
                        case 1:
                        case 2:
                                // get this interventions company (owner)
                                if (!$comp)
                                {
                                        $comp = self::getId('intervention', $id, 'id', 'company');
                                }
                                // get his companies
                                $companies = self::hisCompanies();
                                // get user type
                                $userType = self::userIs();
                        break;
                }
                // based on shared value we will respond
                switch ($share)
                {
                        case 1:
                                // if sharing is 1 only owner may see it
                                if ($userType == 1)
                                {
                                        if (!in_array($comp, $companies))
                                        {
                                                return false;
                                        }
                                }
                                else
                                {
                                        return false;
                                }
                        case 2:
                                // if sharing is 2 only owner and service provider may see it
                               if ($userType == 1 || $userType == 2)
                                {
                                        if (!in_array($comp, $companies))
                                        {
                                                return false;
                                        }
                                }
                                else
                                {
                                        return false;
                                }
                        break;
                }
		return true;
	}

	public static function notHisUsers($id = null)
	{
		// first get all the users to keep
		$keepUsers = self::hisUsers($id);
		if (is_array($keepUsers))
		{
			// return those not to keep
			return self::getIds('id',$keepUsers,'users','id','NOT IN','');
		}
		return false;
	}

	public static function hisUsers($id = null)
	{
		if($id > 0)
		{
			$is = self::userIs($id);
		}
		else
		{
			$id = JFactory::getUser()->id;
			$is = self::userIs($id);
		}
		// return in relation
		switch($is)
		{
			// member (only load himself)
			case 1:
			return array($id);
			break;
			// serves provider (only load companies users that belong to the service provider and himself)
			case 2:
			$companies	= self::hisCompanies($id);
			$keep		= self::getIds('id',$companies,'company','user');
			// now check the result
			if (self::checkArray($keep))
			{
				$keep[] = $id;
				return array_unique($keep);
			}
			break;
			// country (only load companies and service providers that belong to the country)
			case 3:
			$companies	= self::hisCompanies($id);
			$keepC		= self::getIds('id',$companies,'company','user');
			$service	= self::hisServiceProviders($id);
			$keepS		= self::getIds('id',$service,'service_provider','user');
			// merge these values
			$keep		= self::mergeArrays(array($keepC,$keepS));
			// now check the result
			if (self::checkArray($keep))
			{
				$keep[] = $id;
				return array_unique($keep);
			}
			break;
		}
		return false;
	}

	public static function hisCompanies($id = null)
	{
		if($id > 0)
		{
			$is = self::userIs($id);
		}
		else
		{
			$id = JFactory::getUser()->id;
			$is = self::userIs($id);
		}
		// return in relation
		switch($is)
		{
			// member (only load companies that belong to the member)
			case 1:
			return self::getIds('user',$id,'company');
			break;
			// serves provider (only load companies that belong to the service provider)
			case 2:
			return self::getIds('service_provider',self::getIds('user',$id,'service_provider'),'company');
			break;
			// country (only load companies that belong to the country)
			case 3:
			return self::getIds('country',self::getIds('user',$id,'country'),'company');
			break;
		}
		return false;
	}

	public static function hisServiceProviders($id = null)
	{
		if($id > 0)
		{
			$is = self::userIs($id);
		}
		else
		{
			$id = JFactory::getUser()->id;
			$is = self::userIs($id);
		}
		// return in relation
		if (1 == $is) // member
		{
			return self::getIds('user',$id,'company','service_provider');
		}
		elseif (2 == $is) // serves provider
		{
			return array(self::getId('service_provider',$id));
		}
		elseif (3 == $is) // country
		{
			return self::getIds('country',self::getIds('user',$id,'country'),'service_provider');
		}
		return false;
	}

	public static function hisCountries($userId = null, $id = null, $is_type = null)
	{
		if($userId > 0)
		{
			$is = self::userIs($userId);
		}
		elseif($id > 0 && $is_type)
		{
			$userId = self::getId($is_type,$id,'id','user');
			$is = self::userIs($userId);
		}
		else
		{
			$userId = JFactory::getUser()->id;
			$is = self::userIs($userId);
		}
		// return in relation
		switch($is)
		{
			// member (only load countries that belong to the member user)
			case 1:
			return self::getIds('user',$userId,'company','country');
			break;
			// serves provider (only load country that belong to the service provider user)
			case 2:
			return array(self::getId('service_provider',$userId,'user','country'));
			break;
			// country (only load contrye that belong to the country user)
			case 3:
			return self::getIds('user',$userId,'country','id');
			break;
		}
		return false;
	}

	public static function hisCurrencies($userId = null, $id = null, $is_type = null)
	{
		$countries = self::hisCountries($userId,$id,$is_type);
		if (self::checkArray($countries))
		{
			$currencies = array();
			foreach ($countries as $country)
			{
				// get currency id
				$currencies[] = self::getVar('currency', self::getVar('country', $country, 'id', 'currency'), 'codethree', 'id');
			}
			if (self::checkArray($currencies))
			{
				return $currencies;
			}
		}
		return false;
	}

	public static function currencyDetails($id = false)
	{
		if (!$id)
		{
			$id = self::hisCurrencies();
		}
		if (self::checkArray($id))
		{
			$ids = array_values($id);
			$id = $id[0];
		}
		if(is_numeric($id))
		{
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);

			$query->select($db->quoteName(
				array(	'a.id','a.name','a.codethree','a.numericcode','a.symbol','a.thousands','a.decimalplace',
					'a.decimalsymbol','a.positivestyle','a.negativestyle'),
				array(	'currency_id','currency_name','currency_codethree','currency_numericcode','currency_symbol',
					'currency_thousands','currency_decimalplace','currency_decimalsymbol','currency_positivestyle',
					'currency_negativestyle')));
			$query->from($db->quoteName('#__costbenefitprojection_currency', 'a'));
			$query->where($db->quoteName('id') . ' = '.(int) $id);
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				return $db->loadObject();
			}
		}
		return false;
	}
	
	public static function makeMoney($number,$currency = false)
	{
		// first check if we have a number
		if (is_numeric($number))
		{
			// make sure to include the negative finder file
			include_once 'negativefinder.php';
			// check if the number is negative
			$negativeFinderObj = new NegativeFinder(new Expression("$number"));
			$negative = $negativeFinderObj->isItNegative() ? TRUE : FALSE;
		}
		else
		{
			throw new Exception('ERROR! ('.$number.') is not a number!');
		}
		// not setup the currency
		if (self::checkObject($currency))
		{
			if(!isset($currency->currency_positivestyle) || !isset($currency->currency_negativestyle) || !isset($currency->currency_decimalplace) || !isset($currency->currency_decimalsymbol) || !isset($currency->currency_symbol))
			{
				if (isset($currency->currency_id))
				{
					$currency = self::currencyDetails($currency->currency_id);
				}
				elseif (isset($currency->id))
				{
					$currency = self::currencyDetails($currency->id);
				}
				else
				{
					$currency = self::currencyDetails();
				}
			}
		}	
		else
		{
			$currency = self::currencyDetails($currency);
		}
		// set the number to currency
		if (self::checkObject($currency))
		{
			if (!$negative)
			{
				$format = $currency->currency_positivestyle;
				$sign = '+';
			}
			else 
			{
				$format = $currency->currency_negativestyle;
				$sign = '-';
				$number = abs($number);
			}
			$setupNumber = number_format((float)$number, (int)$currency->currency_decimalplace, $currency->currency_decimalsymbol, ' '); //$currency->currency_thousands TODO);
			$search = array('{sign}', '{number}', '{symbol}');
			$replace = array($sign, $setupNumber, $currency->currency_symbol);
			$moneyMade = str_replace ($search,$replace,$format);

			return $moneyMade;
		}
		return $number;
	}

	public static function getCauseRiskDetails($id,$details = null)
	{
		if (!self::checkArray($details))
		{
			$details = array('name','alias','description','ref');
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select($db->quoteName($details));
		$query->from($db->quoteName('#__costbenefitprojection_causerisk'));
		$query->where($db->quoteName('id') . ' = '.(int) $id);
		$query->where($db->quoteName('published') . ' = 1');
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadObject();
		}
		return false;
	}

	public static function getId($table, $where = null , $whereString = 'user', $what = 'id')
	{
		if(!$where)
		{
			$where = JFactory::getUser()->id;
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array($what)));
		$query->from($db->quoteName('#__costbenefitprojection_'.$table));
		$query->where($db->quoteName($whereString) . ' = '.(int) $where);
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}

	public static function getIds($whereString,$where,$table,$what = 'id',$operator = 'IN',$main_ = 'costbenefitprojection_')
	{
		if (!self::checkArray($where) && $where > 0)
		{
			$where = array($where);
		}

		if (self::checkArray($where))
		{
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);

			$query->select($db->quoteName(array($what)));
			$query->from($db->quoteName('#__'.$main_.$table));
			$query->where($db->quoteName($whereString) . ' '.$operator.' (' . implode(',',$where) . ')');
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				return array_unique($db->loadColumn());
			}
		}
		return false;
	}

	public static function getCountryName($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('name')));
		$query->from($db->quoteName('#__costbenefitprojection_country'));
		$query->where($db->quoteName('id') . ' = '.(int) $id);
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	public static function combine($items)
	{
		// make sure the sum class is loaded
		JLoader::import('sumcombine', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
		// return the result
		return new SumCombine($items);
	}

	public static function calculate($id,$data)
	{
		if (base64_encode(base64_decode($data)) === $data){
			// we have valid data now check if stored result needs to be updated.
			$fileName	= md5($data);
			$data		= unserialize(base64_decode($data));
		} else {
			// not valid data first get the valid data
			$model		= self::getModel('companydata');
			$data 		= $model->getItem((int)$id);
			// we have valid data now check if stored result needs to be updated.
			$fileName	= md5(base64_encode(serialize($data)));
		}
		// set some defaults
		$params	= JComponentHelper::getParams('com_costbenefitprojection');
		$path	= $params->get('resultpath', JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers');
		// build full path to file
		$fullPath = $path.'/'.$fileName.'.json';
		// check if file exists
		if (file_exists($fullPath) && (($jsonFile = @file_get_contents($fullPath)) !== FALSE))
		{
			// great we are done return results
			return json_decode($jsonFile);
		}
		// do the calculation again
		$result = self::doCalculation($data);
		// did we get a valid result set
		if (self::checkArray($result) || self::checkObject($result))
		{
			// now save for next time
			self::saveJson($result, $fullPath, $path);
			// return result set
			return  json_decode(json_encode($result));
		}
		return false;
	}

	protected static function doCalculation($data)
	{
		// did we get a valid result set
		if (self::checkObject($data))
		{
			// make sure the sum class is loaded
			JLoader::import('sum', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
			// return the result
			return new Sum($data);
		}
		return false;
	}

	public static function saveJson($data, $fullPath, $path = __DIR__)
	{
		// check if path exists
		if (!file_exists($path))
		{
			// if not the make the path
			mkdir($path, 0755, true);
		}
		// check that the string is json
		if (!self::isJson($data))
		{
			// json encode if not json
			$data = json_encode($data);
		}
		// make sure this is a string
		if (self::checkString($data))
		{
			$fp = fopen($fullPath, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return true;
		}
		return false;
	}

	public static function isJson($string)
	{
		if (self::checkString($string))
		{
			json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	/**
	 *	set groups names
	 */
	public static function setGroupNames($groups)
	{
		// change array of group ids to string of group names
		if (self::checkArray($groups))
		{
			$groupsNames = array();
			foreach ($groups as $group)
			{
				$groupsNames[] = self::getGroupName($group);
			}
			return implode(', ',$groupsNames);
		}
		return '';
	}

	/**
	 * Load the Composer Vendors
	 */
	public static function composerAutoload($target)
	{
		// insure we load the composer vendor only once
		if (!isset(self::$composer[$target]))
		{
			// get the function name
			$functionName = self::safeString('compose' . $target);
			// check if method exist
			if (method_exists(__CLASS__, $functionName))
			{
				return self::{$functionName}();
			}
			return false;
		}
		return self::$composer[$target];
	}

	/**
	 * Convert it into a string
	 */
	public static function jsonToString($value, $sperator = ", ", $table = null, $id = 'id', $name = 'name')
	{
		// do some table foot work
		$external = false;
		if (strpos($table, '#__') !== false)
		{
			$external = true;
			$table = str_replace('#__', '', $table);
		}
		// check if string is JSON
		$result = json_decode($value, true);
		if (json_last_error() === JSON_ERROR_NONE)
		{
			// is JSON
			if (self::checkArray($result))
			{
				if (self::checkString($table))
				{
					$names = array();
					foreach ($result as $val)
					{
						if ($external)
						{
							if ($_name = self::getVar(null, $val, $id, $name, '=', $table))
							{
								$names[] = $_name;
							}
						}
						else
						{
							if ($_name = self::getVar($table, $val, $id, $name))
							{
								$names[] = $_name;
							}
						}
					}
					if (self::checkArray($names))
					{
						return (string) implode($sperator,$names);
					}	
				}
				return (string) implode($sperator,$result);
			}
			return (string) json_decode($value);
		}
		return $value;
	}

	/**
	 * Load the Component xml manifest.
	 */
	public static function manifest()
	{
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/costbenefitprojection.xml";
		return simplexml_load_file($manifestUrl);
	}

	/**
	 * Joomla version object
	 */	
	protected static $JVersion;

	/**
	 * set/get Joomla version
	 */
	public static function jVersion()
	{
		// check if set
		if (!self::checkObject(self::$JVersion))
		{
			self::$JVersion = new JVersion();
		}
		return self::$JVersion;
	}

	/**
	 * Load the Contributors details.
	 */
	public static function getContributors()
	{
		// get params
		$params	= JComponentHelper::getParams('com_costbenefitprojection');
		// start contributors array
		$contributors = array();
		// get all Contributors (max 20)
		$searchArray = range('0','20');
		foreach($searchArray as $nr)
		{
			if ((NULL !== $params->get("showContributor".$nr)) && ($params->get("showContributor".$nr) == 2 || $params->get("showContributor".$nr) == 3))
			{
				// set link based of selected option
				if($params->get("useContributor".$nr) == 1)
                                {
					$link_front = '<a href="mailto:'.$params->get("emailContributor".$nr).'" target="_blank">';
					$link_back = '</a>';
				}
                                elseif($params->get("useContributor".$nr) == 2)
                                {
					$link_front = '<a href="'.$params->get("linkContributor".$nr).'" target="_blank">';
					$link_back = '</a>';
				}
                                else
                                {
					$link_front = '';
					$link_back = '';
				}
				$contributors[$nr]['title']	= self::htmlEscape($params->get("titleContributor".$nr));
				$contributors[$nr]['name']	= $link_front.self::htmlEscape($params->get("nameContributor".$nr)).$link_back;
			}
		}
		return $contributors;
	}

	/**
	 *	Load the Component Help URLs.
	 **/
	public static function getHelpUrl($view)
	{
		$user	= JFactory::getUser();
		$groups = $user->get('groups');
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select(array('a.id','a.groups','a.target','a.type','a.article','a.url'));
		$query->from('#__costbenefitprojection_help_document AS a');
		$query->where('a.site_view = '.$db->quote($view));
		$query->where('a.location = 2');
		$query->where('a.published = 1');
		$db->setQuery($query);
		$db->execute();
		if($db->getNumRows())
		{
			$helps = $db->loadObjectList();
			if (self::checkArray($helps))
			{
				foreach ($helps as $nr => $help)
				{
					if ($help->target == 1)
					{
						$targetgroups = json_decode($help->groups, true);
						if (!array_intersect($targetgroups, $groups))
						{
							// if user not in those target groups then remove the item
							unset($helps[$nr]);
							continue;
						}
					}
					// set the return type
					switch ($help->type)
					{
						// set joomla article
						case 1:
							return self::loadArticleLink($help->article);
							break;
						// set help text
						case 2:
							return self::loadHelpTextLink($help->id);
							break;
						// set Link
						case 3:
							return $help->url;
							break;
					}
				}
			}
		}
		return false;
	}

	/**
	 *	Get the Article Link.
	 **/
	protected static function loadArticleLink($id)
	{
		return JURI::root().'index.php?option=com_content&view=article&id='.$id.'&tmpl=component&layout=modal';
	}

	/**
	 *	Get the Help Text Link.
	 **/
	protected static function loadHelpTextLink($id)
	{
		$token = JSession::getFormToken();
		return 'index.php?option=com_costbenefitprojection&task=help.getText&id=' . (int) $id . '&token=' . $token;
	}

	/**
	 * Get any component's model
	 */
	public static function getModel($name, $path = JPATH_COMPONENT_SITE, $Component = 'Costbenefitprojection', $config = array())
	{
		// fix the name
		$name = self::safeString($name);
		// full path to models
		$fullPathModels = $path . '/models';
		// load the model file
		JModelLegacy::addIncludePath($fullPathModels, $Component . 'Model');
		// make sure the table path is loaded
		if (!isset($config['table_path']) || !self::checkString($config['table_path']))
		{
			// This is the JCB default path to tables in Joomla 3.x
			$config['table_path'] = JPATH_ADMINISTRATOR . '/components/com_' . strtolower($Component) . '/tables';
		}
		// get instance
		$model = JModelLegacy::getInstance($name, $Component . 'Model', $config);
		// if model not found (strange)
		if ($model == false)
		{
			jimport('joomla.filesystem.file');
			// get file path
			$filePath = $path . '/' . $name . '.php';
			$fullPathModel = $fullPathModels . '/' . $name . '.php';
			// check if it exists
			if (File::exists($filePath))
			{
				// get the file
				require_once $filePath;
			}
			elseif (File::exists($fullPathModel))
			{
				// get the file
				require_once $fullPathModel;
			}
			// build class names
			$modelClass = $Component . 'Model' . $name;
			if (class_exists($modelClass))
			{
				// initialize the model
				return new $modelClass($config);
			}
		}
		return $model;
	}

	/**
	 * Add to asset Table
	 */
	public static function setAsset($id, $table, $inherit = true)
	{
		$parent = JTable::getInstance('Asset');
		$parent->loadByName('com_costbenefitprojection');
		
		$parentId = $parent->id;
		$name     = 'com_costbenefitprojection.'.$table.'.'.$id;
		$title    = '';

		$asset = JTable::getInstance('Asset');
		$asset->loadByName($name);

		// Check for an error.
		$error = $asset->getError();

		if ($error)
		{
			return false;
		}
		else
		{
			// Specify how a new or moved node asset is inserted into the tree.
			if ($asset->parent_id != $parentId)
			{
				$asset->setLocation($parentId, 'last-child');
			}

			// Prepare the asset to be stored.
			$asset->parent_id = $parentId;
			$asset->name      = $name;
			$asset->title     = $title;
			// get the default asset rules
			$rules = self::getDefaultAssetRules('com_costbenefitprojection', $table, $inherit);
			if ($rules instanceof JAccessRules)
			{
				$asset->rules = (string) $rules;
			}

			if (!$asset->check() || !$asset->store())
			{
				JFactory::getApplication()->enqueueMessage($asset->getError(), 'warning');
				return false;
			}
			else
			{
				// Create an asset_id or heal one that is corrupted.
				$object = new stdClass();

				// Must be a valid primary key value.
				$object->id = $id;
				$object->asset_id = (int) $asset->id;

				// Update their asset_id to link to the asset table.
				return JFactory::getDbo()->updateObject('#__costbenefitprojection_'.$table, $object, 'id');
			}
		}
		return false;
	}

	/**
	 * Gets the default asset Rules for a component/view.
	 */
	protected static function getDefaultAssetRules($component, $view, $inherit = true)
	{
		// if new or inherited
		$assetId = 0;
		// Only get the actual item rules if not inheriting
		if (!$inherit)
		{
			// Need to find the asset id by the name of the component.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . ' = ' . $db->quote($component));
			$db->setQuery($query);
			$db->execute();
			// check that there is a value
			if ($db->getNumRows())
			{
				// asset already set so use saved rules
				$assetId = (int) $db->loadResult();
			}
		}
		// get asset rules
		$result =  JAccess::getAssetRules($assetId);
		if ($result instanceof JAccessRules)
		{
			$_result = (string) $result;
			$_result = json_decode($_result);
			foreach ($_result as $name => &$rule)
			{
				$v = explode('.', $name);
				if ($view !== $v[0])
				{
					// remove since it is not part of this view
					unset($_result->$name);
				}
				elseif ($inherit)
				{
					// clear the value since we inherit
					$rule = array();
				}
			}
			// check if there are any view values remaining
			if (count((array) $_result))
			{
				$_result = json_encode($_result);
				$_result = array($_result);
				// Instantiate and return the JAccessRules object for the asset rules.
				$rules = new JAccessRules($_result);
				// return filtered rules
				return $rules;
			}
		}
		return $result;
	}

	/**
	 * xmlAppend
	 *
	 * @param   SimpleXMLElement   $xml      The XML element reference in which to inject a comment
	 * @param   mixed              $node     A SimpleXMLElement node to append to the XML element reference, or a stdClass object containing a comment attribute to be injected before the XML node and a fieldXML attribute containing a SimpleXMLElement
	 *
	 * @return  null
	 *
	 */
	public static function xmlAppend(&$xml, $node)
	{
		if (!$node)
		{
			// element was not returned
			return;
		}
		switch (get_class($node))
		{
			case 'stdClass':
				if (property_exists($node, 'comment'))
				{
					self::xmlComment($xml, $node->comment);
				}
				if (property_exists($node, 'fieldXML'))
				{
					self::xmlAppend($xml, $node->fieldXML);
				}
				break;
			case 'SimpleXMLElement':
				$domXML = dom_import_simplexml($xml);
				$domNode = dom_import_simplexml($node);
				$domXML->appendChild($domXML->ownerDocument->importNode($domNode, true));
				$xml = simplexml_import_dom($domXML);
				break;
		}
	}

	/**
	 * xmlComment
	 *
	 * @param   SimpleXMLElement   $xml        The XML element reference in which to inject a comment
	 * @param   string             $comment    The comment to inject
	 *
	 * @return  null
	 *
	 */
	public static function xmlComment(&$xml, $comment)
	{
		$domXML = dom_import_simplexml($xml);
		$domComment = new DOMComment($comment);
		$nodeTarget = $domXML->ownerDocument->importNode($domComment, true);
		$domXML->appendChild($nodeTarget);
		$xml = simplexml_import_dom($domXML);
	}

	/**
	 * xmlAddAttributes
	 *
	 * @param   SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $attributes   The attributes to apply to the XML element
	 *
	 * @return  null
	 *
	 */
	public static function xmlAddAttributes(&$xml, $attributes = array())
	{
		foreach ($attributes as $key => $value)
		{
			$xml->addAttribute($key, $value);
		}
	}

	/**
	 * xmlAddOptions
	 *
	 * @param   SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $options      The options to apply to the XML element
	 *
	 * @return  void
	 *
	 */
	public static function xmlAddOptions(&$xml, $options = array())
	{
		foreach ($options as $key => $value)
		{
			$addOption = $xml->addChild('option');
			$addOption->addAttribute('value', $key);
			$addOption[] = $value;
		}
	}

	/**
	 * get the field object
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   string     $default      The default of the field
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  object
	 *
	 */
	public static function getFieldObject(&$attributes, $default = '', $options = null)
	{
		// make sure we have attributes and a type value
		if (self::checkArray($attributes) && isset($attributes['type']))
		{
			// make sure the form helper class is loaded
			if (!method_exists('JFormHelper', 'loadFieldType'))
			{
				jimport('joomla.form.form');
			}
			// get field type
			$field = JFormHelper::loadFieldType($attributes['type'], true);
			// get field xml
			$XML = self::getFieldXML($attributes, $options);
			// setup the field
			$field->setup($XML, $default);
			// return the field object
			return $field;
		}
		return false;
	}

	/**
	 * get the field xml
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  object
	 *
	 */
	public static function getFieldXML(&$attributes, $options = null)
	{
		// make sure we have attributes and a type value
		if (self::checkArray($attributes))
		{
			// start field xml
			$XML = new SimpleXMLElement('<field/>');
			// load the attributes
			self::xmlAddAttributes($XML, $attributes);
			// check if we have options
			if (self::checkArray($options))
			{
				// load the options
				self::xmlAddOptions($XML, $options);
			}
			// return the field xml
			return $XML;
		}
		return false;
	}

	/**
	 * Render Bool Button
	 *
	 * @param   array   $args   All the args for the button
	 *                             0) name
	 *                             1) additional (options class) // not used at this time
	 *                             2) default
	 *                             3) yes (name)
	 *                             4) no (name)
	 *
	 * @return  string    The input html of the button
	 *
	 */
	public static function renderBoolButton()
	{
		$args = func_get_args();
		// check if there is additional button class
		$additional = isset($args[1]) ? (string) $args[1] : ''; // not used at this time
		// button attributes
		$buttonAttributes = array(
			'type' => 'radio',
			'name' => isset($args[0]) ? self::htmlEscape($args[0]) : 'bool_button',
			'label' => isset($args[0]) ? self::safeString(self::htmlEscape($args[0]), 'Ww') : 'Bool Button', // not seen anyway
			'class' => 'btn-group',
			'filter' => 'INT',
			'default' => isset($args[2]) ? (int) $args[2] : 0);
		// set the button options
		$buttonOptions = array(
			'1' => isset($args[3]) ? self::htmlEscape($args[3]) : 'JYES',
			'0' => isset($args[4]) ? self::htmlEscape($args[4]) : 'JNO');
		// return the input
		return self::getFieldObject($buttonAttributes, $buttonAttributes['default'], $buttonOptions)->input;
	}

	/**
	 *  UIKIT Component Classes
	 **/
	public static $uk_components = array(
			'data-uk-grid' => array(
				'grid' ),
			'uk-accordion' => array(
				'accordion' ),
			'uk-autocomplete' => array(
				'autocomplete' ),
			'data-uk-datepicker' => array(
				'datepicker' ),
			'uk-form-password' => array(
				'form-password' ),
			'uk-form-select' => array(
				'form-select' ),
			'data-uk-htmleditor' => array(
				'htmleditor' ),
			'data-uk-lightbox' => array(
				'lightbox' ),
			'uk-nestable' => array(
				'nestable' ),
			'UIkit.notify' => array(
				'notify' ),
			'data-uk-parallax' => array(
				'parallax' ),
			'uk-search' => array(
				'search' ),
			'uk-slider' => array(
				'slider' ),
			'uk-slideset' => array(
				'slideset' ),
			'uk-slideshow' => array(
				'slideshow',
				'slideshow-fx' ),
			'uk-sortable' => array(
				'sortable' ),
			'data-uk-sticky' => array(
				'sticky' ),
			'data-uk-timepicker' => array(
				'timepicker' ),
			'data-uk-tooltip' => array(
				'tooltip' ),
			'uk-placeholder' => array(
				'placeholder' ),
			'uk-dotnav' => array(
				'dotnav' ),
			'uk-slidenav' => array(
				'slidenav' ),
			'uk-form' => array(
				'form-advanced' ),
			'uk-progress' => array(
				'progress' ),
			'upload-drop' => array(
				'upload', 'form-file' )
			);

	/**
	 *  Add UIKIT Components
	 **/
	public static $uikit = false;

	/**
	 *  Get UIKIT Components
	 **/
	public static function getUikitComp($content,$classes = array())
	{
		if (strpos($content,'class="uk-') !== false)
		{
			// reset
			$temp = array();
			foreach (self::$uk_components as $looking => $add)
			{
				if (strpos($content,$looking) !== false)
				{
					$temp[] = $looking;
				}
			}
			// make sure uikit is loaded to config
			if (strpos($content,'class="uk-') !== false)
			{
				self::$uikit = true;
			}
			// sorter
			if (self::checkArray($temp))
			{
				// merger
				if (self::checkArray($classes))
				{
					$newTemp = array_merge($temp,$classes);
					$temp = array_unique($newTemp);
				}
				return $temp;
			}
		}
		if (self::checkArray($classes))
		{
			return $classes;
		}
		return false;
	}

	/**
	 * Greate user and update given table
	 *
	 * @param   array  $credentials  Array('name' => string, 'username' => string, 'email' => string, 'password' => string, 'password2' => string)
	 * @param   int    $autologin
	 * @param   array  $params  Array('useractivation' => int, 'sendpassword' => int, 'allowUserRegistration' => int)
	 * @param   array  $mode 1 = Site Registrations; 0 = Admin Registration; 2 = Custom Helper Method Called registerUser
	 *
	 * @return  int|Error  User ID on success, or an error.
	 */
	public static function createUser($credentials, $autologin = 0,
		$params = array(
			'useractivation' => 0, 'sendpassword' => 1
		), $mode = 1
	)
	{
		// Override mode
		if ($mode == 2 && method_exists(__CLASS__, 'registerUser'))
		{
			// Update params
			$params['autologin'] = $autologin;
			// Now Register User
			return self::registerUser($credentials, $params);
		}
		elseif ($mode == 2)
		{
			// Fallback to Site Registrations
			$mode = 1;
		}
		// load the user component language files if there is an error.
		$lang = JFactory::getLanguage();
		$extension = 'com_users';
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
		// Load the correct user model.
		if ($mode == 1) // 1 = Site Registrations
		{
			// Load the user site-registration model
			$model = self::getModel('registration', $base_dir . '/components/' . $extension, 'Users');
		}
		else // 0 = Admin Registration
		{
			// Load the backend-user model
			$model = self::getModel('user', JPATH_ADMINISTRATOR . '/components/' . $extension, 'Users');
		}
		// Check if we have params/config
		if (self::checkArray($params))
		{
			// Make changes to user config
			foreach ($params as $param => $set)
			{
				// If you know of a better path, let me know
				$params[$param] = self::setParams($extension, $param, $set);
			}
		}
		// Set username to email if not set
		if (!isset($credentials['username']) || !self::checkString($credentials['username']))
		{
			$credentials['username'] = $credentials['email'];
		}
		// Lineup new user data array
		$data = array(
			'username' => $credentials['username'],
			'name' => $credentials['name'],
			'block' => 0 );
		// Added details based on mode
		if ($mode == 1) // 1 = Site-registration mode
		{
			$data['email1'] = $credentials['email'];
		}
		else // 0 = Admin-registration mode
		{
			$data['email'] = $credentials['email'];
			$data['registerDate'] = JFactory::getDate()->toSql();
		}
		// Check if password was set
		if ($mode == 1 && (!isset($credentials['password']) || !isset($credentials['password2']) || !self::checkString($credentials['password']) || !self::checkString($credentials['password2'])))
		{
			// Set random password when empty password was submitted,
			// when using the 1 = site-registration mode
			$credentials['password'] = self::randomkey(8);
			$credentials['password2'] = $credentials['password'];
		}
		// Now Add password if set
		if (isset($credentials['password']) && isset($credentials['password2'])  && self::checkString($credentials['password']) && self::checkString($credentials['password2']))
		{
			if ($mode == 1) // 1 = Site-registration mode
			{
				$data['password1'] = $credentials['password'];
			}
			else // 0 = Admin-registration mode
			{
				$data['password'] = $credentials['password'];
			}
			$data['password2'] = $credentials['password2'];
		}
		// Load the group/s value if set, only for Admin Registration ($mode == 0)
		if ($mode == 0 && isset($credentials['groups']) && self::checkArray($credentials['groups']))
		{
			$data['groups'] = $credentials['groups'];
		}
		// Create the new user
		if ($mode == 1) // 1 = Site-registration mode
		{
			$userId = $model->register($data);
		}
		else // 0 = Admin-registration mode
		{
			$model->save($data);
			$userId = $model->getState('user.id', 0);
		}
		// Check if we have params
		if (self::checkArray($params))
		{
			// Change user params/config back
			foreach ($params as $param => $set)
			{
				// If you know of a better path, let me know
				self::setParams($extension, $param, $set);
			}
		}
		// if user is created
		if ($userId > 0)
		{
			// Auto Login if Needed
			if ($autologin && isset($credentials['password']))
			{
				// Try to login
				try{
					JFactory::getApplication()->login($credentials);
				} catch (Exception $exception){
					// Do noting for now, may want to set redirect.
				}
			}
			// Return ID
			return $userId;
		}
		return $model->getError();
	}

	public static function setParams($component,$target,$value)
	{
		// Get the params and set the new values
		$params = JComponentHelper::getParams($component);
		$was = $params->get($target, null);
		if ($was != $value)
		{
			$params->set($target, $value);
			// Get a new database query instance
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			// Build the query
			$query->update('#__extensions AS a');
			$query->set('a.params = ' . $db->quote((string)$params));
			$query->where('a.element = ' . $db->quote((string)$component));
			
			// Execute the query
			$db->setQuery($query);
			$db->execute();
		}
		return $was;
	}

	/**
	 * Update user values
	 */
	public static function updateUser($new)
	{
		// load the user component language files if there is an error.
		$lang = JFactory::getLanguage();
		$extension = 'com_users';
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
		// load the user model
		$model = self::getModel('user', JPATH_ADMINISTRATOR . '/components/com_users', 'Users');
		// Check if password was set
		if (isset($new['password']) && isset($new['password2']) && self::checkString($new['password']) && self::checkString($new['password2']))
		{
			// Use the users passwords
			$password = $new['password'];
			$password2 = $new['password2'];
		}
		// set username
		if (!isset($new['username']) || !self::checkString($new['username']))
		{
			$new['username'] = $new['email'];
		}
		// lineup update user data
		$data = array(
			'id' => $new['id'],
			'username' => $new['username'],
			'name' => $new['name'],
			'email' => $new['email'],
			'password' => $password, // First password field
			'password2' => $password2, // Confirm password field
			'block' => 0 );
		// set groups if found
		if (isset($new['groups']) && self::checkArray($new['groups']))
		{
			$data['groups'] = $new['groups'];
		}
		// register the new user
		$done = $model->save($data);
		// if user is updated
		if ($done)
		{
			return $new['id'];
		}
		return $model->getError();
	}

	/**
	 * Get a variable 
	 *
	 * @param   string   $table        The table from which to get the variable
	 * @param   string   $where        The value where
	 * @param   string   $whereString  The target/field string where/name
	 * @param   string   $what         The return field
	 * @param   string   $operator     The operator between $whereString/field and $where/value
	 * @param   string   $main         The component in which the table is found
	 *
	 * @return  mix string/int/float
	 *
	 */
	public static function getVar($table, $where = null, $whereString = 'user', $what = 'id', $operator = '=', $main = 'costbenefitprojection')
	{
		if(!$where)
		{
			$where = JFactory::getUser()->id;
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array($what)));		
		if (empty($table))
		{
			$query->from($db->quoteName('#__'.$main));
		}
		else
		{
			$query->from($db->quoteName('#__'.$main.'_'.$table));
		}
		if (is_numeric($where))
		{
			$query->where($db->quoteName($whereString) . ' '.$operator.' '.(int) $where);
		}
		elseif (is_string($where))
		{
			$query->where($db->quoteName($whereString) . ' '.$operator.' '. $db->quote((string)$where));
		}
		else
		{
			return false;
		}
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}

	/**
	 * Get array of variables
	 *
	 * @param   string   $table        The table from which to get the variables
	 * @param   string   $where        The value where
	 * @param   string   $whereString  The target/field string where/name
	 * @param   string   $what         The return field
	 * @param   string   $operator     The operator between $whereString/field and $where/value
	 * @param   string   $main         The component in which the table is found
	 * @param   bool     $unique       The switch to return a unique array
	 *
	 * @return  array
	 *
	 */
	public static function getVars($table, $where = null, $whereString = 'user', $what = 'id', $operator = 'IN', $main = 'costbenefitprojection', $unique = true)
	{
		if(!$where)
		{
			$where = JFactory::getUser()->id;
		}

		if (!self::checkArray($where) && $where > 0)
		{
			$where = array($where);
		}

		if (self::checkArray($where))
		{
			// prep main <-- why? well if $main='' is empty then $table can be categories or users
			if (self::checkString($main))
			{
				$main = '_'.ltrim($main, '_');
			}
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);

			$query->select($db->quoteName(array($what)));
			if (empty($table))
			{
				$query->from($db->quoteName('#__'.$main));
			}
			else
			{
				$query->from($db->quoteName('#_'.$main.'_'.$table));
			}
			// add strings to array search
			if ('IN_STRINGS' === $operator || 'NOT IN_STRINGS' === $operator)
			{
				$query->where($db->quoteName($whereString) . ' ' . str_replace('_STRINGS', '', $operator) . ' ("' . implode('","',$where) . '")');
			}
			else
			{
				$query->where($db->quoteName($whereString) . ' ' . $operator . ' (' . implode(',',$where) . ')');
			}
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				if ($unique)
				{
					return array_unique($db->loadColumn());
				}
				return $db->loadColumn();
			}
		}
		return false;
	} 

	public static function isPublished($id,$type)
	{
		if ($type == 'raw')
		{
			$type = 'item';
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.published'));
		$query->from('#__costbenefitprojection_'.$type.' AS a');
		$query->where('a.id = '. (int) $id);
		$query->where('a.published = 1');
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if($found)
		{
			return true;
		}
		return false;
	}

	public static function getGroupName($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(array('a.title'));
		$query->from('#__usergroups AS a');
		$query->where('a.id = '. (int) $id);
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if($found)
		{
			return $db->loadResult();
		}
		return $id;
	}

	/**
	 * Get the action permissions
	 *
	 * @param  string   $view        The related view name
	 * @param  int      $record      The item to act upon
	 * @param  string   $views       The related list view name
	 * @param  mixed    $target      Only get this permission (like edit, create, delete)
	 * @param  string   $component   The target component
	 * @param  object   $user        The user whose permissions we are loading
	 *
	 * @return  object   The JObject of permission/authorised actions
	 * 
	 */
	public static function getActions($view, &$record = null, $views = null, $target = null, $component = 'costbenefitprojection', $user = 'null')
	{
		// load the user if not given
		if (!self::checkObject($user))
		{
			// get the user object
			$user = JFactory::getUser();
		}
		// load the JObject
		$result = new JObject;
		// make view name safe (just incase)
		$view = self::safeString($view);
		if (self::checkString($views))
		{
			$views = self::safeString($views);
 		}
		// get all actions from component
		$actions = JAccess::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/com_' . $component . '/access.xml',
			"/access/section[@name='component']/"
		);
		// if non found then return empty JObject
		if (empty($actions))
		{
			return $result;
		}
		// get created by if not found
		if (self::checkObject($record) && !isset($record->created_by) && isset($record->id))
		{
			$record->created_by = self::getVar($view, $record->id, 'id', 'created_by', '=', $component);
		}
		// set actions only set in component settings
		$componentActions = array('core.admin', 'core.manage', 'core.options', 'core.export');
		// check if we have a target
		$checkTarget = false;
		if ($target)
		{
			// convert to an array
			if (self::checkString($target))
			{
				$target = array($target);
			}
			// check if we are good to go
			if (self::checkArray($target))
			{
				$checkTarget = true;
			}
		}
		// loop the actions and set the permissions
		foreach ($actions as $action)
		{
			// check target action filter
			if ($checkTarget && self::filterActions($view, $action->name, $target))
			{
				continue;
			}
			// set to use component default
			$fallback = true;
			// reset permission per/action
			$permission = false;
			$catpermission = false;
			// set area
			$area = 'comp';
			// check if the record has an ID and the action is item related (not a component action)
			if (self::checkObject($record) && isset($record->id) && $record->id > 0 && !in_array($action->name, $componentActions) &&
				(strpos($action->name, 'core.') !== false || strpos($action->name, $view . '.') !== false))
			{
				// we are in item
				$area = 'item';
				// The record has been set. Check the record permissions.
				$permission = $user->authorise($action->name, 'com_' . $component . '.' . $view . '.' . (int) $record->id);
				// if no permission found, check edit own
				if (!$permission)
				{
					// With edit, if the created_by matches current user then dig deeper.
					if (($action->name === 'core.edit' || $action->name === $view . '.edit') && $record->created_by > 0 && ($record->created_by == $user->id))
					{
						// the correct target
						$coreCheck = (array) explode('.', $action->name);
						// check that we have both local and global access
						if ($user->authorise($coreCheck[0] . '.edit.own', 'com_' . $component . '.' . $view . '.' . (int) $record->id) &&
							$user->authorise($coreCheck[0]  . '.edit.own', 'com_' . $component))
						{
							// allow edit
							$result->set($action->name, true);
							// set not to use global default
							// because we already validated it
							$fallback = false;
						}
						else
						{
							// do not allow edit
							$result->set($action->name, false);
							$fallback = false;
						}
					}
				}
				elseif (self::checkString($views) && isset($record->catid) && $record->catid > 0)
				{
					// we are in item
					$area = 'category';
					// set the core check
					$coreCheck = explode('.', $action->name);
					$core = $coreCheck[0];
					// make sure we use the core. action check for the categories
					if (strpos($action->name, $view) !== false && strpos($action->name, 'core.') === false )
					{
						$coreCheck[0] = 'core';
						$categoryCheck = implode('.', $coreCheck);
					}
					else
					{
						$categoryCheck = $action->name;
					}
					// The record has a category. Check the category permissions.
					$catpermission = $user->authorise($categoryCheck, 'com_' . $component . '.' . $views . '.category.' . (int) $record->catid);
					if (!$catpermission && !is_null($catpermission))
					{
						// With edit, if the created_by matches current user then dig deeper.
						if (($action->name === 'core.edit' || $action->name === $view . '.edit') && $record->created_by > 0 && ($record->created_by == $user->id))
						{
							// check that we have both local and global access
							if ($user->authorise('core.edit.own', 'com_' . $component . '.' . $views . '.category.' . (int) $record->catid) &&
								$user->authorise($core . '.edit.own', 'com_' . $component))
							{
								// allow edit
								$result->set($action->name, true);
								// set not to use global default
								// because we already validated it
								$fallback = false;
							}
							else
							{
								// do not allow edit
								$result->set($action->name, false);
								$fallback = false;
							}
						}
					}
				}
			}
			// if allowed then fallback on component global settings
			if ($fallback)
			{
				// if item/category blocks access then don't fall back on global
				if ((($area === 'item') && !$permission) || (($area === 'category') && !$catpermission))
				{
					// do not allow
					$result->set($action->name, false);
				}
				// Finally remember the global settings have the final say. (even if item allow)
				// The local item permissions can block, but it can't open and override of global permissions.
				// Since items are created by users and global permissions is set by system admin.
				else
				{
					$result->set($action->name, $user->authorise($action->name, 'com_' . $component));
				}
			}
		}
		return $result;
	}

	/**
	 * Filter the action permissions
	 *
	 * @param  string   $action   The action to check
	 * @param  array    $targets  The array of target actions
	 *
	 * @return  boolean   true if action should be filtered out
	 * 
	 */
	protected static function filterActions(&$view, &$action, &$targets)
	{
		foreach ($targets as $target)
		{
			if (strpos($action, $view . '.' . $target) !== false ||
				strpos($action, 'core.' . $target) !== false)
			{
				return false;
				break;
			}
		}
		return true;
	}

	/**
	 * Check if have an json string
	 *
	 * @input	string   The json string to check
	 *
	 * @returns bool true on success
	 */
	public static function checkJson($string)
	{
		if (self::checkString($string))
		{
			json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	/**
	 * Check if have an object with a length
	 *
	 * @input	object   The object to check
	 *
	 * @returns bool true on success
	 */
	public static function checkObject($object)
	{
		if (isset($object) && is_object($object))
		{
			return count((array)$object) > 0;
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
	public static function checkArray($array, $removeEmptyString = false)
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
				return self::checkArray($array, false);
			}
			return $nr;
		}
		return false;
	}

	/**
	 * Check if have a string with a length
	 *
	 * @input	string   The string to check
	 *
	 * @returns bool true on success
	 */
	public static function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}

	/**
	 * Check if we are connected
	 * Thanks https://stackoverflow.com/a/4860432/1429677
	 *
	 * @returns bool true on success
	 */
	public static function isConnected()
	{
		// If example.com is down, then probably the whole internet is down, since IANA maintains the domain. Right?
		$connected = @fsockopen("www.example.com", 80); 
			// website, port  (try 80 or 443)
		if ($connected)
		{
			//action when connected
			$is_conn = true;
			fclose($connected);
		}
		else
		{
			//action in connection failure
			$is_conn = false;
		}
		return $is_conn;
	}

	/**
	 * Merge an array of array's
	 *
	 * @input	array   The arrays you would like to merge
	 *
	 * @returns array on success
	 */
	public static function mergeArrays($arrays)
	{
		if(self::checkArray($arrays))
		{
			$arrayBuket = array();
			foreach ($arrays as $array)
			{
				if (self::checkArray($array))
				{
					$arrayBuket = array_merge($arrayBuket, $array);
				}
			}
			return $arrayBuket;
		}
		return false;
	}

	// typo sorry!
	public static function sorten($string, $length = 40, $addTip = true)
	{
		return self::shorten($string, $length, $addTip);
	}

	/**
	 * Shorten a string
	 *
	 * @input	string   The you would like to shorten
	 *
	 * @returns string on success
	 */
	public static function shorten($string, $length = 40, $addTip = true)
	{
		if (self::checkString($string))
		{
			$initial = strlen($string);
			$words = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
			$words_count = count((array)$words);

			$word_length = 0;
			$last_word = 0;
			for (; $last_word < $words_count; ++$last_word)
			{
				$word_length += strlen($words[$last_word]);
				if ($word_length > $length)
				{
					break;
				}
			}

			$newString	= implode(array_slice($words, 0, $last_word));
			$final	= strlen($newString);
			if ($initial != $final && $addTip)
			{
				$title = self::shorten($string, 400 , false);
				return '<span class="hasTip" title="'.$title.'" style="cursor:help">'.trim($newString).'...</span>';
			}
			elseif ($initial != $final && !$addTip)
			{
				return trim($newString).'...';
			}
		}
		return $string;
	}

	/**
	 * Making strings safe (various ways)
	 *
	 * @input	string   The you would like to make safe
	 *
	 * @returns string on success
	 */
	public static function safeString($string, $type = 'L', $spacer = '_', $replaceNumbers = true, $keepOnlyCharacters = true)
	{
		if ($replaceNumbers === true)
		{
			// remove all numbers and replace with english text version (works well only up to millions)
			$string = self::replaceNumbers($string);
		}
		// 0nly continue if we have a string
		if (self::checkString($string))
		{
			// create file name without the extention that is safe
			if ($type === 'filename')
			{
				// make sure VDM is not in the string
				$string = str_replace('VDM', 'vDm', $string);
				// Remove anything which isn't a word, whitespace, number
				// or any of the following caracters -_()
				// If you don't need to handle multi-byte characters
				// you can use preg_replace rather than mb_ereg_replace
				// Thanks @Łukasz Rysiak!
				// $string = mb_ereg_replace("([^\w\s\d\-_\(\)])", '', $string);
				$string = preg_replace("([^\w\s\d\-_\(\)])", '', $string);
				// http://stackoverflow.com/a/2021729/1429677
				return preg_replace('/\s+/', ' ', $string);
			}
			// remove all other characters
			$string = trim($string);
			$string = preg_replace('/'.$spacer.'+/', ' ', $string);
			$string = preg_replace('/\s+/', ' ', $string);
			// Transliterate string
			$string = self::transliterate($string);
			// remove all and keep only characters
			if ($keepOnlyCharacters)
			{
				$string = preg_replace("/[^A-Za-z ]/", '', $string);
			}
			// keep both numbers and characters
			else
			{
				$string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
			}
			// select final adaptations
			if ($type === 'L' || $type === 'strtolower')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// default is to return lower
				return strtolower($string);
			}
			elseif ($type === 'W')
			{
				// return a string with all first letter of each word uppercase(no undersocre)
				return ucwords(strtolower($string));
			}
			elseif ($type === 'w' || $type === 'word')
			{
				// return a string with all lowercase(no undersocre)
				return strtolower($string);
			}
			elseif ($type === 'Ww' || $type === 'Word')
			{
				// return a string with first letter of the first word uppercase and all the rest lowercase(no undersocre)
				return ucfirst(strtolower($string));
			}
			elseif ($type === 'WW' || $type === 'WORD')
			{
				// return a string with all the uppercase(no undersocre)
				return strtoupper($string);
			}
			elseif ($type === 'U' || $type === 'strtoupper')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return all upper
				return strtoupper($string);
			}
			elseif ($type === 'F' || $type === 'ucfirst')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return with first caracter to upper
				return ucfirst(strtolower($string));
			}
			elseif ($type === 'cA' || $type === 'cAmel' || $type === 'camelcase')
			{
				// convert all words to first letter uppercase
				$string = ucwords(strtolower($string));
				// remove white space
				$string = preg_replace('/\s+/', '', $string);
				// now return first letter lowercase
				return lcfirst($string);
			}
			// return string
			return $string;
		}
		// not a string
		return '';
	}

	public static function transliterate($string)
	{
		// set tag only once
		if (!self::checkString(self::$langTag))
		{
			// get global value
			self::$langTag = JComponentHelper::getParams('com_costbenefitprojection')->get('language', 'en-GB');
		}
		// Transliterate on the language requested
		$lang = Language::getInstance(self::$langTag);
		return $lang->transliterate($string);
	}

	public static function htmlEscape($var, $charset = 'UTF-8', $shorten = false, $length = 40)
	{
		if (self::checkString($var))
		{
			$filter = new JFilterInput();
			$string = $filter->clean(html_entity_decode(htmlentities($var, ENT_COMPAT, $charset)), 'HTML');
			if ($shorten)
			{
           		return self::shorten($string,$length);
			}
			return $string;
		}
		else
		{
			return '';
		}
	}

	public static function replaceNumbers($string)
	{
		// set numbers array
		$numbers = array();
		// first get all numbers
		preg_match_all('!\d+!', $string, $numbers);
		// check if we have any numbers
		if (isset($numbers[0]) && self::checkArray($numbers[0]))
		{
			foreach ($numbers[0] as $number)
			{
				$searchReplace[$number] = self::numberToString((int)$number);
			}
			// now replace numbers in string
			$string = str_replace(array_keys($searchReplace), array_values($searchReplace),$string);
			// check if we missed any, strange if we did.
			return self::replaceNumbers($string);
		}
		// return the string with no numbers remaining.
		return $string;
	}

	/**
	 * Convert an integer into an English word string
	 * Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	 *
	 * @input	an int
	 * @returns a string
	 */
	public static function numberToString($x)
	{
		$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
			"eight", "nine", "ten", "eleven", "twelve", "thirteen",
			"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
			"nineteen", "twenty", 30 => "thirty", 40 => "forty",
			50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
			90 => "ninety" );

		if(!is_numeric($x))
		{
			$w = $x;
		}
		elseif(fmod($x, 1) != 0)
		{
			$w = $x;
		}
		else
		{
			if($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}
			else
			{
				$w = '';
				// ... now $x is a non-negative integer.
			}

			if($x < 21)   // 0 to 20
			{
				$w .= $nwords[$x];
			}
			elseif($x < 100)  // 21 to 99
			{ 
				$w .= $nwords[10 * floor($x/10)];
				$r = fmod($x, 10);
				if($r > 0)
				{
					$w .= ' '. $nwords[$r];
				}
			}
			elseif($x < 1000)  // 100 to 999
			{
				$w .= $nwords[floor($x/100)] .' hundred';
				$r = fmod($x, 100);
				if($r > 0)
				{
					$w .= ' and '. self::numberToString($r);
				}
			}
			elseif($x < 1000000)  // 1000 to 999999
			{
				$w .= self::numberToString(floor($x/1000)) .' thousand';
				$r = fmod($x, 1000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::numberToString($r);
				}
			} 
			else //  millions
			{    
				$w .= self::numberToString(floor($x/1000000)) .' million';
				$r = fmod($x, 1000000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::numberToString($r);
				}
			}
		}
		return $w;
	}

	/**
	 * Random Key
	 *
	 * @returns a string
	 */
	public static function randomkey($size)
	{
		$bag = "abcefghijknopqrstuwxyzABCDDEFGHIJKLLMMNOPQRSTUVVWXYZabcddefghijkllmmnopqrstuvvwxyzABCEFGHIJKNOPQRSTUWXYZ";
		$key = array();
		$bagsize = strlen($bag) - 1;
		for ($i = 0; $i < $size; $i++)
		{
			$get = rand(0, $bagsize);
			$key[] = $bag[$get];
		}
		return implode($key);
	}

	/**
	 *	Get The Encryption Keys
	 *
	 *	@param  string        $type     The type of key
	 *	@param  string/bool   $default  The return value if no key was found
	 *
	 *	@return  string   On success
	 *
	 **/
	public static function getCryptKey($type, $default = false)
	{
		// Get the global params
		$params = JComponentHelper::getParams('com_costbenefitprojection', true);
		// WHMCS Encryption Type
		if ('whmcs' === $type || 'advanced' === $type)
		{
			$key = $params->get('whmcs_key', $default);
			if (self::checkString($key))
			{
				// load the file
				JLoader::import( 'whmcs', JPATH_COMPONENT_ADMINISTRATOR);

				$the = new WHMCS($key);

				return $the->_key;
			}
		}

		return $default;
	}
}

