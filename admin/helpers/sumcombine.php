<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		sumcombine.php
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
*	Calculation combine Class
**/

class SumCombine
{
	public $totals;
	public $items;
	public $interventions;
	public $currencyDetails;
	public $companiesNames;
	
	// set some basic defaults
	protected $datasets;
	protected $moneyMachine;
		
	public function __construct($items = null)
	{
		// set data to class
		if ($this->setCompaniesInfo($items) && CostbenefitprojectionHelper::checkArray($this->datasets))
		{
			$this->totals = new stdClass();
			$this->items = new stdClass();
			foreach ($this->datasets as $item)
			{
				// join totals
				$this->setTotals($item->totals);
				// join items
				$this->setItems($item->items);
				// join interventions
				$this->setInterventions($item->interventions);
			}
			// check burden levels
			$this->setBurden();
			// set money for totals
			$this->setMoney('totals');
			// set the money for items
			if (CostbenefitprojectionHelper::checkObject($this->items))
			{
				foreach ($this->items as $id => $ignore)
				{
					$this->setMoney('items_'.$id);
				}
			}
			return true;
		}
		return false;
	}

	protected function setCompaniesInfo(&$items)
	{
		if (CostbenefitprojectionHelper::checkArray($items))
		{
			// we must check the currency
			$currency = array(
				'currency_id','currency_name','currency_codethree','currency_numericcode','currency_symbol',
				'currency_thousands','currency_decimalplace','currency_decimalsymbol','currency_positivestyle',
				'currency_negativestyle');
			$names = array();
			$namesnot = array();
			foreach($items as $nr => &$item)
			{
				if (!isset($this->currencyDetails->currency_id))
				{
					$this->currencyDetails = new stdClass();
					foreach ($currency as $key)
					{
						$this->currencyDetails->$key = $item->$key;
					}
				}
				elseif ($item->currency_id !== $this->currencyDetails->currency_id)
				{
					$namesnot[] = $item->name;
					continue;
				}
				$data = base64_encode(serialize($item));
				$this->datasets[] = CostbenefitprojectionHelper::calculate($item->id,$data);
				// set some defaults
				$names[] = $item->name;
			}
			if (CostbenefitprojectionHelper::checkArray($namesnot))
			{
				$companiesNamesNot = '<b>'.implode(', ',$namesnot).'</b>';
				JError::raiseWarning(500, JText::_('Currency mismatch! These were excluded from results: ').$companiesNamesNot);
			}
			if (CostbenefitprojectionHelper::checkArray($names))
			{
				$this->companiesNames = implode(', ',$names);
				return true;
			}
		}
	}
	
	protected function setTotals($totals)
	{
		if (CostbenefitprojectionHelper::checkObject($totals))
		{
			foreach ($totals as $name => $total)
			{
				if (strpos($name, 'money') == false)
				{
					if (isset($this->totals->$name))
					{
						$this->totals->$name += $total;
					}
					else
					{
						$this->totals->$name = $total;
					}
				}
				else
				{
					if (!isset($this->moneyMachine['totals'][$name]))
					{
						// setup money making machine
						$numberString = str_replace('money', '', $name);
						$this->moneyMachine['totals'][$name] = $numberString;
					}
				}
			}
		}
	}
	
	protected function setItems($items)
	{
		if (CostbenefitprojectionHelper::checkObject($items))
		{
			$dont = array('male','female','details');
			foreach ($items as $id => $values)
			{
				if (CostbenefitprojectionHelper::checkObject($values))
				{
					if(!isset($this->items->$id))
					{
						$this->items->$id = new stdClass();
						$this->items->$id->details = $values->details;
					}
					foreach ($values as $name => $value)
					{
						if (!in_array($name,$dont))
						{
							if (strpos($name, 'money') == false)
							{
								if (isset($this->items->$id->$name))
								{
									$this->items->$id->$name += $value;
								}
								else
								{
									$this->items->$id->$name = $value;
								}
							}
							else
							{
								if (!isset($this->moneyMachine['items_'.$id][$name]))
								{
									// setup money making machine
									$numberString = str_replace('money', '', $name);
									$this->moneyMachine['items_'.$id][$name] = $numberString;
								}
							}
						}
					}
				}
			}
		}
	}
	
	protected function setBurden()
	{
		if (100 < $this->totals->total_estimated_burden)
		{
			foreach ($this->items as $item)
			{
				if (isset($item->subtotal_estimated_burden) && 0 != $item->subtotal_estimated_burden)
				{
					// adjust the subtotal to 100 %
					$item->subtotal_estimated_burden = ($item->subtotal_estimated_burden * 100) / $this->totals->total_estimated_burden;
				}
			}
			// bring down the estimate to 100 %
			$this->totals->total_estimated_burden = 100;
		}
	}
	
	protected function setInterventions($interventions)
	{
		if (CostbenefitprojectionHelper::checkArray($interventions))
		{
			foreach ($interventions as $intervention)
			{
				if (isset($intervention->id) && !isset($this->interventions[$intervention->id]))
				{
					$this->interventions[$intervention->id] = $intervention;
				}
			}
			
		}
	}
	
	protected function setMoney($target)
	{
		if (CostbenefitprojectionHelper::checkArray($this->moneyMachine[$target]))
		{
			$targets = false;
			if (strpos($target, '_') !== false)
			{
				$targets = explode('_', $target);
			}
			foreach ($this->moneyMachine[$target] as $store => $value)
			{
				if ($targets && count($targets) == 2)
				{
					$this->$targets[0]->$targets[1]->$store = CostbenefitprojectionHelper::makeMoney($this->$targets[0]->$targets[1]->$value, $this->currencyDetails);
				}
				else
				{
					$this->$target->$store = CostbenefitprojectionHelper::makeMoney($this->$target->$value, $this->currencyDetails);
				}
			}
		}
	}
}
