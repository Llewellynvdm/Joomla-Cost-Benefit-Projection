<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		ajax.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

/**
 * Costbenefitprojection Ajax Model
 */
class CostbenefitprojectionModelAjax extends JModelList
{
	protected $app_params;

	public function __construct()
	{
		parent::__construct();
		// get params
		$this->app_params = JComponentHelper::getParams('com_costbenefitprojection');

	}

	// Used in company
	public function getCalculatedResult($id,$data)
	{
		return CostbenefitprojectionHelper::calculate($id,$data);
	}

	// Used in intervention
public function getInterventionBuildTable($idName,$oject,$cluster)
	{
		if (CostbenefitprojectionHelper::isJson($oject) && CostbenefitprojectionHelper::checkString($idName))
		{
			$array = json_decode($oject, true);
			$targetHeaders = array(
				'causerisk' => JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK'),
				'cpe' => JText::_('COM_COSTBENEFITPROJECTION_COST_PER_EMPLOYEE'),
				'mbr' => JText::_('COM_COSTBENEFITPROJECTION_MORBIDITY_REDUCTION'),
				'mtr' => JText::_('COM_COSTBENEFITPROJECTION_MORTALITY_REDUCTION'));
			if (CostbenefitprojectionHelper::checkArray($array))
			{
				$table 		= '<table id="table_'.$idName.'" class="table" style="margin: 5px 0 20px;"><thead><tr>';
				$rows		= array();
				foreach ($array as $header => $values)
				{
					$table .= '<th style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);" scope="col">'.$targetHeaders[$header].'</th>';
					if (CostbenefitprojectionHelper::checkArray($values))
					{
						foreach ($values as $nr => $value)
						{
							if ($header == 'causerisk')
							{
								$value = CostbenefitprojectionHelper::getId('causerisk',$value,'id','name');
							}
							elseif ($cluster == 'ja')
							{
								$vc = $header.'_'.$nr;
								if (strpos($value, '&') !== false)
								{
									$value = '<input style="width:100px; color:red;"  class="clusterintervention required eRrOr" id="'.$vc.'" placeholder="Only A Number" value="'.$value.'">';
								}
								else
								{
									$value = '<input style="width:100px;"  class="clusterintervention required" id="'.$vc.'" placeholder="Only A Number" value="'.$value.'">';
								}
							}
							// build rows
							if (!isset($rows[$nr]))
							{
								$rows[$nr] = '<td style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);">'.$value.'</td>';
							}
							else
							{
								$rows[$nr] .= '<td style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);">'.$value.'</td>';
							}
						}
					}
				}
				// close header start body
				$table .= '</tr></thead><tbody>';
				// add rows to table
				if (CostbenefitprojectionHelper::checkArray($rows))
				{
					foreach ($rows as $row)
					{
						$table .= '<tr>'.$row.'</tr>';
					}
				}
				// close the body and table
				$table .= '</tbody></table>';
				// return the table
				return $table;
			}
		}
		return false;
	}

	public function getClusterData($idName,$cluster)
	{
		// we first build the json object from the cluster ids, then pass it to the builder
		$oject = '';
		$oject_table = '';
		if (CostbenefitprojectionHelper::isJson($cluster) && CostbenefitprojectionHelper::checkString($idName))
		{
			$array = json_decode($cluster, true);
			// get te set intervention data
			$interventions = array();
			if (CostbenefitprojectionHelper::checkArray($array))
			{
				foreach ($array as $intervention)
				{
					$interventions[$intervention] = CostbenefitprojectionHelper::getVar('intervention', $intervention, 'id', 'intervention');
				}
			}
			// sort the data
			$bucket = array();
			if (CostbenefitprojectionHelper::checkArray($interventions))
			{
				foreach ($interventions as $inter => $set)
				{
					if (CostbenefitprojectionHelper::isJson($set))
					{
						$set = json_decode($set, true);
						if (CostbenefitprojectionHelper::checkArray($set))
						{
							foreach ($set as $option => $values)
							{
								foreach ($values as $nr => $value)
								{
									$bucket[$inter][$nr][$option] = $value;
								}
							}
						}
					}
				}
			}
			// combine the data
			$combine = array();
			if (CostbenefitprojectionHelper::checkArray($bucket))
			{
				foreach ($bucket as $pool)
				{
					if (CostbenefitprojectionHelper::checkArray($pool))
					{
						foreach ($pool as $headers)
						{
							if (CostbenefitprojectionHelper::checkArray($headers))
							{
								// check if this cause is already targeted
								if (isset($combine[$headers['causerisk']]))
								{
									// combine
									$temp = $combine[$headers['causerisk']];
									$temp['cpe'] = $this->combineValues($temp['cpe'], $headers['cpe']);
									$temp['mbr'] = $this->combineValues($temp['mbr'], $headers['mbr']);
									$temp['mtr'] = $this->combineValues($temp['mtr'], $headers['mtr']);
									// update the data
									$combine[$headers['causerisk']] = $temp;
								}
								else
								{
									// set for first time
									$combine[$headers['causerisk']] = array(
										'cpe' => $headers['cpe'],
										'mbr' => $headers['mbr'],
										'mtr' => $headers['mtr']
										);
								}
							}

						}
					}
				}
			}
			// setup the object
			if (CostbenefitprojectionHelper::checkArray($combine))
			{
				$oject = array();
				foreach ($combine as $causerisk => $vals)
				{
					if (CostbenefitprojectionHelper::checkArray($vals))
					{
						$oject['causerisk'][] = $causerisk;
						foreach ($vals as $header => $v)
						{
							// set placeholder
							$oject[$header][] = $v;
						}
					}
				}
				// done at last
				$oject = json_encode($oject);
			}
		}
		// return the table and values
		return array('table' => $this->getInterventionBuildTable($idName,$oject,'ja'), 'values' => $oject);
	}
	
	protected function combineValues($old,$new)
	{
		$old = trim($old);
		$new = trim($new);
		if ($old != $new)
		{
			if (strpos($old, '&') !== false)
			{
				$oldArray = array_map('trim',explode('&', $old));
				foreach ($oldArray as $nr => $oldValue)
				{
					if ($oldValue == $new)
					{
						unset($oldArray[$nr]);
					}
				}
				$old = implode(' & ', $oldArray);
			}
			return $old.' & '.$new;
		}
		return $new;
	}
}
