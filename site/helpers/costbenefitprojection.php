<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
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

/**
 * Costbenefitprojection component helper
 */
abstract class CostbenefitprojectionHelper
{ 

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
	 *	get group name
	 */
	public static function getGroupName($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(array('a.title'));
		$query->from('#__usergroups AS a');
		$query->where('a.id = '.$id);
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if($found)
       		{
			return $db->loadResult();
		}
		return $id . '- not found';
	}

	public static function jsonToString($value)
	{
		// check if string is JSON
		$result = json_decode($value, true);
		if (json_last_error() === JSON_ERROR_NONE) {
			// is JSON
				if (self::checkArray($result))
				{
					$value = '';
					$counter = 0;
					foreach ($result as $string)
					{
						if ($counter)
						{
							$value .= ", ".$string;
						}
						else
						{
							$value .= $string;
						}
						$counter++;
					}
				}
			return json_decode($value);
		}
		return $value;
	}
	
	/**
	*	Load the Component xml manifest.
	**/
        public static function manifest()
        {
                $manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/costbenefitprojection.xml";
                return simplexml_load_file($manifestUrl);
	}

	/**
	*	Load the Contributors details.
	**/
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
	*	Get any component's model
	**/
	public static function getModel($name, $path = JPATH_COMPONENT_SITE, $component = 'costbenefitprojection')
	{
		// load some joomla helpers
		JLoader::import('joomla.application.component.model');
		// load the model file
		JLoader::import( $name, $path . '/models' );
		// return instance
		return JModelLegacy::getInstance( $name, $component.'Model' );
	}
	
	/**
	*	Add to asset Table
	*/
	public static function setAsset($id,$table)
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
			$this->setError($error);

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
			$rules = self::getDefaultAssetRules('com_costbenefitprojection',$table);
			if ($rules instanceof JAccessRules)
			{
				$asset->rules = (string) $rules;
			}

			if (!$asset->check() || !$asset->store())
			{
				JError::raiseWarning(500, $asset->getError());
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
	 *	Gets the default asset Rules for a component/view.
	 */
	protected static function getDefaultAssetRules($component,$view)
	{
		// Need to find the asset id by the name of the component.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($component));
		$db->setQuery($query);
		$db->execute();
		if ($db->loadRowList())
		{
			// asset alread set so use saved rules
			$assetId = (int) $db->loadResult();
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
					else
					{
						// clear the value since we inherit
						$rule = array();
					}
				}
				// check if there are any view values remaining
				if (count($_result))
				{
					$_result = json_encode($_result);
					$_result = array($_result);
					// Instantiate and return the JAccessRules object for the asset rules.
					$rules = new JAccessRules($_result);

					return $rules;
				}
				return $result;
			}
		}
		return JAccess::getAssetRules(0);
	}

	public static function renderBoolButton()
	{
		$args = func_get_args();

		// get the radio element
		$button = JFormHelper::loadFieldType('radio');

		// setup the properties
		$name	 	= self::htmlEscape($args[0]);
		$additional = isset($args[1]) ? (string) $args[1] : '';
		$value		= $args[2];
		$yes 	 	= isset($args[3]) ? self::htmlEscape($args[3]) : 'JYES';
		$no 	 	= isset($args[4]) ? self::htmlEscape($args[4]) : 'JNO';

		// prepare the xml
		$element = new SimpleXMLElement('<field name="'.$name.'" type="radio" class="btn-group"><option '.$additional.' value="0">'.$no.'</option><option '.$additional.' value="1">'.$yes.'</option></field>');

		// run
		$button->setup($element, $value);

		return $button->input;

	}

	/**
	* 	UIKIT Component Classes
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
	* 	Add UIKIT Components
	**/
	public static $uikit = false;

	/**
	* 	Get UIKIT Components
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
	*/
	public static function createUser($new)
	{
		// load the user component language files if there is an error.
		$lang = JFactory::getLanguage();
		$extension = 'com_users';
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
		// load the user regestration model
		$model = self::getModel('registration', JPATH_ROOT. '/components/com_users', 'Users');
		// make sure no activation is needed
		$useractivation = self::setParams('com_users','useractivation',0);
		// make sure password is send
		$sendpassword = self::setParams('com_users','sendpassword',1);
		// Check if password was set
		if (isset($new['password']) && isset($new['password2']) && self::checkString($new['password']) && self::checkString($new['password2']))
		{
			// Use the users passwords
			$password = $new['password'];
			$password2 = $new['password2'];
		}
		else
		{
			// Set random password
			$password = self::randomkey(8);
			$password2 = $password;
		}
		// set username
		if (isset($new['username']) && self::checkString($new['username']))
		{
			$new['username'] = self::safeString($new['username']);
		}
		else
		{
			$new['username'] = self::safeString($new['name']);			
		}
		// linup new user data
		$data = array(
			'username' => $new['username'],
			'name' => $new['name'],
			'email1' => $new['email'],
			'password1' => $password, // First password field
			'password2' => $password2, // Confirm password field
			'block' => 0 );
		// register the new user
		$userId = $model->register($data);
		// set activation back to default
		self::setParams('com_users','useractivation',$useractivation);
		// set send password back to default
		self::setParams('com_users','sendpassword',$sendpassword);
		// if user is created
		if ($userId > 0)
		{
			return $userId;
		}
		return $model->getError();
	}

	protected static function setParams($component,$target,$value)
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
			$db->query();
		}
		return $was;
	}

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
		$query->from($db->quoteName('#__'.$main.'_'.$table));
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
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);

			$query->select($db->quoteName(array($what)));
			$query->from($db->quoteName('#__'.$main.'_'.$table));
			$query->where($db->quoteName($whereString) . ' '.$operator.' (' . implode(',',$where) . ')');
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
	
	/**
	*	Get the actions permissions
	**/
        public static function getActions($view,&$record = null,$views = null)
	{
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;
		$view	= self::safeString($view);
                if (self::checkString($views))
                {
			$views = self::safeString($views);
                }
		// get all actions from component
		$actions = JAccess::getActions('com_costbenefitprojection', 'component');
                // set acctions only set in component settiongs
                $componentActions = array('core.admin','core.manage','core.options','core.export');
		// loop the actions and set the permissions
		foreach ($actions as $action)
                {
			// set to use component default
			$fallback = true;
			if (self::checkObject($record) && isset($record->id) && $record->id > 0 && !in_array($action->name,$componentActions))
			{
				// The record has been set. Check the record permissions.
				$permission = $user->authorise($action->name, 'com_costbenefitprojection.'.$view.'.' . (int) $record->id);
				if (!$permission && !is_null($permission))
				{
					if ($action->name == 'core.edit' || $action->name == $view.'.edit')
					{
						if ($user->authorise('core.edit.own', 'com_costbenefitprojection.'.$view.'.' . (int) $record->id))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_costbenefitprojection.'.$view.'.' . (int) $record->id))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise('core.edit.own', 'com_costbenefitprojection'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_costbenefitprojection'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
					}
				}
				elseif (self::checkString($views) && isset($record->catid) && $record->catid > 0)
				{
                                        // make sure we use the core. action check for the categories
                                        if (strpos($action->name,$view) !== false && strpos($action->name,'core.') === false ) {
                                                $coreCheck		= explode('.',$action->name);
                                                $coreCheck[0]	= 'core';
                                                $categoryCheck	= implode('.',$coreCheck);
                                        }
                                        else
                                        {
                                                $categoryCheck = $action->name;
                                        }
                                        // The record has a category. Check the category permissions.
					$catpermission = $user->authorise($categoryCheck, 'com_costbenefitprojection.'.$views.'.category.' . (int) $record->catid);
					if (!$catpermission && !is_null($catpermission))
					{
						if ($action->name == 'core.edit' || $action->name == $view.'.edit')
						{
							if ($user->authorise('core.edit.own', 'com_costbenefitprojection.'.$views.'.category.' . (int) $record->catid))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_costbenefitprojection.'.$views.'.category.' . (int) $record->catid))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise('core.edit.own', 'com_costbenefitprojection'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_costbenefitprojection'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
						}
					}
				}
			}
			// if allowed then fallback on component global settings
			if ($fallback)
			{
				$result->set($action->name, $user->authorise($action->name, 'com_costbenefitprojection'));
			}
		}
		return $result;
	}
	
	public static function checkJson($string)
	{
		if (self::checkString($string))
		{
			json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	public static function checkObject($object)
	{
		if (isset($object) && is_object($object) && count($object) > 0)
		{
			return true;
		}
		return false;
	}

	public static function checkArray($array)
	{
		if (isset($array) && is_array($array) && count($array) > 0)
		{
			return true;
		}
		return false;
	}

	public static function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}

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

	public static function sorten($string, $length = 40, $addTip = true)
	{
		if (self::checkString($string))
		{
			$initial = strlen($string);
			$words = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
			$words_count = count($words);

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
				$title = self::sorten($string, 400 , false);
				return '<span class="hasTip" title="'.$title.'" style="cursor:help">'.trim($newString).'...</span>';
			}
			elseif ($initial != $final && !$addTip)
			{
				return trim($newString).'...';
			}
		}
		return $string;
	}

	public static function safeString($string, $type = 'L', $spacer = '_')
	{
		// remove all numbers and replace with english text version (works well only up to a thousand)
		$string = self::replaceNumbers($string);

		if (self::checkString($string))
		{
			// remove all other characters
			$string = trim($string);
			$string = preg_replace('/'.$spacer.'+/', ' ', $string);
			$string = preg_replace('/\s+/', ' ', $string);
			$string = preg_replace("/[^A-Za-z ]/", '', $string);
			// return a string with all first letter of each word uppercase(no undersocre)
			if ($type == 'W')
				    {
			    return ucwords(strtolower($string));
			}
				    elseif ($type == 'w')
				    {
			    return strtolower($string);
			}
				    elseif ($type == 'Ww')
				    {
			    return ucfirst(strtolower($string));
			}
				    elseif ($type == 'WW')
				    {
			    return strtoupper($string);
			}
			elseif ($type == 'U')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return all upper
				return strtoupper($string);
			}
			elseif ($type == 'F')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return with first caracter to upper
				return ucfirst(strtolower($string));
			}
			elseif ($type == 'L')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// default is to return lower
				    return strtolower($string);
			}

			// return string
			return $string;
		}
		// not a string
		return '';
	}

	public static function htmlEscape($var, $charset = 'UTF-8', $sorten = false, $length = 40)
	{
		if (self::checkString($var))
		{
			$filter = new JFilterInput();
			$string = $filter->clean(html_entity_decode(htmlentities($var, ENT_COMPAT, $charset)), 'HTML');
			if ($sorten)
			{
           		return self::sorten($string,$length);
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
	*	Convert an integer into an English word string
	*	Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	*
	*	@input	an int
	*	@returns a string
	**/
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
						$word .= 'and ';
					}
					$w .= self::numberToString($r);
				}
			}
		}
		return $w;
	}

	/**
	*	Random Key
	*
	*	@returns a string
	**/
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

	public static function getCryptKey($type)
	{
		if ('advanced' == $type)
		{
			// Get the global params
			$params = JComponentHelper::getParams('com_costbenefitprojection', true);
			$advanced_key = $params->get('advanced_key', null);
			if ($advanced_key)
			{
				// load the file
				JLoader::import( 'vdm', JPATH_COMPONENT_ADMINISTRATOR);

				$the = new VDM($advanced_key);

				return $the->_key;
			}
		}
		return false;
	}
}
