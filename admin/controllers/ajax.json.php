<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		ajax.json.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Costbenefitprojection Ajax Controller
 */
class CostbenefitprojectionControllerAjax extends JControllerLegacy
{
	public function __construct($config)
	{
		parent::__construct($config);
		// make sure all json stuff are set
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="getajax.json"');
		JResponse::setHeader("Access-Control-Allow-Origin", "*");
		// load the tasks 
		$this->registerTask('calculatedResult', 'ajax');
		$this->registerTask('interventionBuildTable', 'ajax');
		$this->registerTask('getClusterData', 'ajax');
	}

	public function ajax()
	{
		$user 		= JFactory::getUser();
		$jinput 	= JFactory::getApplication()->input;
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($token == $call_token)
                {
			$task = $this->getTask();
			switch($task)
                        {
				case 'calculatedResult':
					try
					{
						$idValue = $jinput->get('id', NULL, 'INT');
						$dataValue = $jinput->get('data', NULL, 'BASE64');
						if($idValue && $dataValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->getCalculatedResult($idValue, $dataValue);
						}
						else
						{
							$result = false;
						}
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback'] . "(".json_encode($result).");";
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback']."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'interventionBuildTable':
					try
					{
						$idNameValue = $jinput->get('idName', NULL, 'CMD');
						$ojectValue = $jinput->get('oject', NULL, 'STRING');
						$clusterValue = $jinput->get('cluster', NULL, 'WORD');
						if($idNameValue && $ojectValue && $clusterValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->getInterventionBuildTable($idNameValue, $ojectValue, $clusterValue);
						}
						else
						{
							$result = false;
						}
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback'] . "(".json_encode($result).");";
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback']."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'getClusterData':
					try
					{
						$idNameValue = $jinput->get('idName', NULL, 'CMD');
						$clusterValue = $jinput->get('cluster', NULL, 'STRING');
						if($idNameValue && $clusterValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->getClusterData($idNameValue, $clusterValue);
						}
						else
						{
							$result = false;
						}
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback'] . "(".json_encode($result).");";
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback']."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
			}
		}
                else
                {
			if(array_key_exists('callback',$_GET))
                        {
				echo $_GET['callback']."(".json_encode(false).");";
			}
                        else
                        {
				echo "(".json_encode(false).");";
			}
		}
	}
}
