<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		sum.php
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
*	Calculation Class
**/

// uncomment for dev
error_reporting(0);

// make sure to include the negative finder file
include_once 'negativefinder.php';

class Sum
{
	public $totals;
	public $items;
	public $interventions;
	
	// set some basic defaults
	protected $company;
	protected $genders		= array('male' => 'males','female' => 'females');
	protected $ages			= array('15-19','20-24','25-29','30-34','35-39','40-44','45-49','50-54','55-59','60-64');
		
	public function __construct($data = null)
	{
		// set data to class
		if ($data)
		{
			$this->company = $data;
			// first set the user
			if ($this->setCompany())
			{
				// do the calculation for days lost
				if($this->setDaysLost())
				{
					// do the calculation for cost
					if($this->setCost())
					{
						// do the calculation for interventions
						if($this->setInterventions())
						{
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	public function setCompany()
	{
		if(CostbenefitprojectionHelper::checkObject($this->company))
		{
			// main switch
			$usecountry = false;
			// public switch
			if (isset($this->company->public))
			{
				$usecountry = true;
				$cKey = 'id';
			}
			else
			{
				$cKey = 'country';
			}
			// set array of json objects to convert to array
			$jsonObjects = array('percentmale','percentfemale','country_percentmale','country_percentfemale','country_maledeath','country_femaledeath','country_maleyld','country_femaleyld');
			$removeArray = array(
				'asset_id','not_required','published','created_by','country_created_by','country_created','country_version','country_hits','country_ordering',
				'modified_by','country_asset_id','created','modified','version','hits','ordering','country_published','country_modified_by','country_modified','country_maledeath','country_femaledeath','country_maleyld','country_femaleyld',
				'idCompanyScaling_factorC',$cKey.'CountryHealth_dataB',$cKey.'CountryHealth_dataBB','causesrisksIdCauseriskG','causesrisksIdCauseriskGG','idCompanyInterventionD','countryCountryInterventionDD','idCountryInterventionDD');
			foreach ($jsonObjects as $jsonObject)
			{
				if (isset($this->company->$jsonObject) && CostbenefitprojectionHelper::isJson($this->company->$jsonObject))
				{
					// convert to array
					$array = json_decode($this->company->$jsonObject,true);
					if (CostbenefitprojectionHelper::checkArray($array))
					{
						$this->company->$jsonObject = array();
						foreach ($array as $option => $values)
						{
							if (CostbenefitprojectionHelper::checkArray($values))
							{
								foreach ($values as $nr => $value)
								{
									$this->company->{$jsonObject}[$nr][$option] = $value;
								}
							}
						}
					}
				}
			}
			// if company has not causerisks selected fall back on country
			if ($usecountry || (isset($this->company->causesrisks) && !CostbenefitprojectionHelper::checkArray($this->company->causesrisks)) || $this->company->department == 1)
			{
				$this->company->causesrisks			= $this->company->country_causesrisks;
				$this->company->medical_turnovers_males		= $this->company->males * ($this->company->country_medical_turnovers/100000);
				$this->company->medical_turnovers_females	= $this->company->females * ($this->company->country_medical_turnovers/100000);
				$this->company->sick_leave_males		= $this->company->males * $this->company->country_sick_leave;
				$this->company->sick_leave_females		= $this->company->females * $this->company->country_sick_leave;
				$this->company->datayear			= $this->company->country_datayear;
				$this->company->total_healthcare		= ($this->company->country_healthcare/100) * $this->company->total_salary;
				$this->company->working_days			= $this->company->country_working_days;
				$this->company->productivity_losses		= $this->company->country_productivity_losses;
				
				$usecountry = true;
			}
			// percent sorting
			$percentSort = array('percentmale','percentfemale','country_percentmale','country_percentfemale');
			foreach ($percentSort as $sort)
			{
				$point = $sort;
				if (isset($this->company->$sort) && !CostbenefitprojectionHelper::checkArray($this->company->$sort) && strpos($sort, 'country') === false)
				{
					$sort = 'country_'.$sort;
				}
				if (isset($this->company->$sort) && CostbenefitprojectionHelper::checkArray($this->company->$sort))
				{
					$bucket = array();
					foreach ($this->company->$sort as $value)
					{
						$buket[$value['age']] = $value['percent'];
					}
					$this->company->$point = $buket;
				}
			}
			// country totals sorting
			$countrySort = array(
				'country_maledeath' => array('gender' => 'male', 'type' => 'death'),
				'country_femaledeath' => array('gender' => 'female', 'type' => 'death'),
				'country_maleyld' => array('gender' => 'male', 'type' => 'yld'),
				'country_femaleyld' => array('gender' => 'female', 'type' => 'yld'));
			$this->company->country_yld = 0;
			$this->company->country_death = 0;
			foreach ($countrySort as $sort => $tar)
			{
				if (isset($this->company->$sort) && CostbenefitprojectionHelper::checkArray($this->company->$sort))
				{ 
					foreach ($this->company->$sort as $value)
					{
						if ($this->company->datayear == $value['year'])
						{
							$this->company->{'country_'.$tar['type']} += $value['number'];
						}
					}
				}
			}
			// country health totals sorting
			$specialSort = array('maledeath','maleyld','femaledeath','femaleyld');
			$keepData = array();
			if (isset($this->company->{$cKey.'CountryHealth_dataB'}) && CostbenefitprojectionHelper::checkArray($this->company->{$cKey.'CountryHealth_dataB'}) && !$usecountry)
			{
				$healthBucket = array();
				foreach ($this->company->{$cKey.'CountryHealth_dataB'} as $healthData)
				{
					$healthBucket[$healthData->causerisk] = new stdClass();
					foreach ($specialSort as $sort)
					{
						if (isset($healthData->$sort) && CostbenefitprojectionHelper::isJson($healthData->$sort))
						{
							// convert to array
							$array = json_decode($healthData->$sort,true);
							if (CostbenefitprojectionHelper::checkArray($array))
							{
								foreach ($array as $option => $values)
								{
									if (CostbenefitprojectionHelper::checkArray($values))
									{
										foreach ($values as $nr => $value)
										{
											$healthBucket[$healthData->causerisk]->{$sort}[$nr][$option] = $value;
										}
									}
								}
							}
						}
					}
					// number sorting
					foreach ($specialSort as $sort)
					{
						if (isset($healthBucket[$healthData->causerisk]->$sort) && CostbenefitprojectionHelper::checkArray($healthBucket[$healthData->causerisk]->$sort))
						{
							$bucket = array();
							foreach ($healthBucket[$healthData->causerisk]->$sort as $value)
							{
								if (CostbenefitprojectionHelper::checkArray($value))
								{
									$buket[$value['age']] = $value['number'];
								}
							}
							$healthBucket[$healthData->causerisk]->$sort = $buket;
						}
					}
					// check if we should keep any other data
					if (CostbenefitprojectionHelper::checkArray($keepData))
					{
						// set data to keep
						foreach ($keepData as $keep)
						{
							$healthBucket[$healthData->causerisk]->$keep = $healthData->$keep;
						}
					}
				}
			}
			elseif (isset($this->company->{$cKey.'CountryHealth_dataBB'}) && CostbenefitprojectionHelper::checkArray($this->company->{$cKey.'CountryHealth_dataBB'}) && $usecountry)
			{
				$healthBucket = array();
				foreach ($this->company->{$cKey.'CountryHealth_dataBB'} as $healthData)
				{
					$healthBucket[$healthData->causerisk] = new stdClass();
					foreach ($specialSort as $sort)
					{
						if (isset($healthData->$sort) && CostbenefitprojectionHelper::isJson($healthData->$sort))
						{
							// convert to array
							$array = json_decode($healthData->$sort,true);
							if (CostbenefitprojectionHelper::checkArray($array))
							{
								foreach ($array as $option => $values)
								{
									if (CostbenefitprojectionHelper::checkArray($values))
									{
										foreach ($values as $nr => $value)
										{
											$healthBucket[$healthData->causerisk]->{$sort}[$nr][$option] = $value;
										}
									}
								}
							}
						}
					}
					// number sorting
					foreach ($specialSort as $sort)
					{
						if (isset($healthBucket[$healthData->causerisk]->$sort) && CostbenefitprojectionHelper::checkArray($healthBucket[$healthData->causerisk]->$sort))
						{
							$bucket = array();
							foreach ($healthBucket[$healthData->causerisk]->$sort as $value)
							{
								if (CostbenefitprojectionHelper::checkArray($value))
								{
									$buket[$value['age']] = $value['number'];
								}
							}
							$healthBucket[$healthData->causerisk]->$sort = $buket;
						}
					}
					// check if we should keep any other data
					if (CostbenefitprojectionHelper::checkArray($keepData))
					{
						// set data to keep
						foreach ($keepData as $keep)
						{
							$healthBucket[$healthData->causerisk]->$keep = $healthData->$keep;
						}
					}
				}
			}
			// set health data
			$this->company->healthData = $healthBucket;
			unset($healthBucket);
			// set company scaling
			$keepData = array(
				'yld_scaling_factor_males',
				'yld_scaling_factor_females',
				'mortality_scaling_factor_males',
				'mortality_scaling_factor_females',
				'presenteeism_scaling_factor_males',
				'presenteeism_scaling_factor_females',
				'health_scaling_factor'
				);
			$scalingBucket = array();
			if (isset($this->company->idCompanyScaling_factorC) && CostbenefitprojectionHelper::checkArray($this->company->idCompanyScaling_factorC))
			{
				// use the compony set values
				foreach ($this->company->idCompanyScaling_factorC as $scalingFactor)
				{
					$scalingBucket[$scalingFactor->causerisk] = new stdClass();
					foreach ($keepData as $keep)
					{
						$scalingBucket[$scalingFactor->causerisk]->$keep = $scalingFactor->$keep;
					}
				}
			}
			else
			{
				// set defaults
				foreach ($this->company->causesrisks as $scalingFactor)
				{
					$scalingBucket[$scalingFactor] = new stdClass();
					foreach ($keepData as $keep)
					{
						$scalingBucket[$scalingFactor]->$keep = 1;
					}
				}
			}
			// set scaling factors
			$this->company->scalingFactors = $scalingBucket;
			unset($scalingBucket);
			// set Cause/Risk Details
			$keepData = array(
				'id',
				'ref',
				'name',
				'alias',
				'category',
				'description'
				);
			if (isset($this->company->causesrisksIdCauseriskG) && CostbenefitprojectionHelper::checkArray($this->company->causesrisksIdCauseriskG) && !$usecountry)
			{
				foreach ($this->company->causesrisksIdCauseriskG as $causesrisks)
				{
					$this->items[$causesrisks->id] = new stdClass();
					$this->items[$causesrisks->id]->details = new stdClass();
					foreach ($keepData as $keep)
					{
						$this->items[$causesrisks->id]->details->$keep = $causesrisks->$keep;
					}
				}
			}
			elseif (isset($this->company->causesrisksIdCauseriskGG) && CostbenefitprojectionHelper::checkArray($this->company->causesrisksIdCauseriskGG) && $usecountry)
			{
				foreach ($this->company->causesrisksIdCauseriskGG as $causesrisks)
				{
					$this->items[$causesrisks->id] = new stdClass();
					$this->items[$causesrisks->id]->details = new stdClass();
					foreach ($keepData as $keep)
					{
						$this->items[$causesrisks->id]->details->$keep = $causesrisks->$keep;
					}
				}
			}
			// set company insterventions
			$keepData = array(
				'id',
				'name',
				'coverage',
				'duration',
				'description',
				'reference',
				'share',
				'type'
				);
			$insterventionBucket = array();
			if (isset($this->company->idCompanyInterventionD) && CostbenefitprojectionHelper::checkArray($this->company->idCompanyInterventionD))
			{
				foreach ($this->company->idCompanyInterventionD as $key => $intervention)
				{
					$insterventionBucket[$key] = new stdClass();
					foreach ($keepData as $keep)
					{
						$insterventionBucket[$key]->$keep = $intervention->$keep;
					}
					// load the most important part, the actual intervention data
					$array = json_decode($intervention->intervention,true);
					if (CostbenefitprojectionHelper::checkArray($array))
					{
						$insterventionBucket[$key]->data = array();
						foreach ($array as $option => $values)
						{
							if (CostbenefitprojectionHelper::checkArray($values))
							{
								foreach ($values as $nr => $value)
								{
									if ('causerisk' == $option)
									{
										$insterventionBucket[$key]->data[$nr]['id'] = $value;
										$insterventionBucket[$key]->data[$nr]['allias'] = CostbenefitprojectionHelper::getVar('causerisk', $value, 'id', 'alias');
									}
									else
									{
										// set values
										$insterventionBucket[$key]->data[$nr][$option] = $value;
									}
								}
							}
						}
					}
				}
			}
			elseif ($usecountry || (isset($this->company->{$cKey.'CountryInterventionDD'}) && CostbenefitprojectionHelper::checkArray($this->company->{$cKey.'CountryInterventionDD'})))
			{
				foreach ($this->company->{$cKey.'CountryInterventionDD'} as $key => $intervention)
				{
					$insterventionBucket[$key] = new stdClass();
					foreach ($keepData as $keep)
					{
						$insterventionBucket[$key]->$keep = $intervention->$keep;
					}
					// load the most important part, the actual intervention data
					$array = json_decode($intervention->intervention,true);
					if (CostbenefitprojectionHelper::checkArray($array))
					{
						$insterventionBucket[$key]->data = array();
						foreach ($array as $option => $values)
						{
							if (CostbenefitprojectionHelper::checkArray($values))
							{
								foreach ($values as $nr => $value)
								{
									if ('causerisk' == $option)
									{
										$insterventionBucket[$key]->data[$nr]['id'] = $value;
										$insterventionBucket[$key]->data[$nr]['allias'] = CostbenefitprojectionHelper::getVar('causerisk', $value, 'id', 'alias');
									}
									else
									{
										// set values
										$insterventionBucket[$key]->data[$nr][$option] = $value;
									}
								}
							}
						}
					}
				}
			}			
			// set intervention to company data
			$this->company->interventions = $insterventionBucket;
			unset($insterventionBucket);
			// remove undeeded values
			if (CostbenefitprojectionHelper::checkArray($removeArray))
			{
				foreach ($removeArray as $remove)
				{
					// remove value if set
					if (isset($this->company->$remove))
					{
						unset($this->company->$remove);
					}
				}
			}
			return true;
		}
		return false;
	}
		
	/**
	 * Do calculation for days lost.
	 *
	 * @return array
	 *
	 */
	protected function setDaysLost()
	{
		if(CostbenefitprojectionHelper::checkArray($this->company->causesrisks))
		{
			// set global totals morbidity
			$this->totals['total_morbidity_raw'] 					= 0;
			$this->totals['total_morbidity_interim'] 				= 0;
			$this->totals['total_morbidity_scaled'] 				= 0;
			$this->totals['total_morbidity_unscaled'] 				= 0;
			// set global totals mortality
			$this->totals['total_mortality_raw'] 					= 0;
			$this->totals['total_mortality_interim']				= 0;
			$this->totals['total_mortality_scaled'] 				= 0;
			$this->totals['total_mortality_unscaled'] 				= 0;
			// set global totals presenteesim		
			$this->totals['total_presenteeism_scaled'] 				= 0;
			$this->totals['total_presenteeism_unscaled']				= 0;
			// set global totals day_lost_mortality
			$this->totals['total_days_lost_mortality_scaled']			= 0;
			$this->totals['total_days_lost_mortality_unscaled']			= 0;
			// set global totals day_lost
			$this->totals['total_days_lost_scaled']					= 0;
			$this->totals['total_days_lost_unscaled']				= 0;
			// set global totals estimated_burden
			$this->totals['total_estimated_burden']					= 0;
			// work with each gender
			foreach($this->genders as $gender => $genders)
			{
				// gender totals morbidity raw
				$this->totals[$genders.'_morbidity_raw'] 				= 0;
				$this->totals[$genders.'_mortality_raw'] 				= 0;
				$this->totals[$genders.'_morbidity_interim'] 				= 0;
				$this->totals[$genders.'_mortality_interim'] 				= 0;
				// gender totals morbidity
				$this->totals[$genders.'_morbidity_scaled'] 				= 0;
				$this->totals[$genders.'_morbidity_unscaled'] 				= 0;
				// gender totals mortality
				$this->totals[$genders.'_mortality_scaled'] 				= 0;
				$this->totals[$genders.'_mortality_unscaled'] 				= 0;
				// gender totals presenteeism
				$this->totals[$genders.'_presenteeism_scaled'] 				= 0;
				$this->totals[$genders.'_presenteeism_unscaled'] 			= 0;
				// gender totals day_lost_mortality
				$this->totals[$genders.'_days_lost_mortality_scaled']			= 0;
				$this->totals[$genders.'_days_lost_mortality_unscaled']			= 0;
				// gender totals day_lost
				$this->totals[$genders.'_days_lost_scaled'] 				= 0;
				$this->totals[$genders.'_days_lost_unscaled'] 				= 0;
			}
			
			///////////////////////////////////////////////////////
			//  ------ set morbidity for each cause/risk ------  //
			///////////////////////////////////////////////////////
		
			// set morbidity_raw
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					$this->items[$id]->$gender = new stdClass();
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group
							$this->items[$id]->$gender->$age = new stdClass();
							// continue only if we have a value
							$goodToGo = true;
							// fix missing values
							if(!isset($this->company->healthData[$id]->{$gender.'yld'}[$age]))
							{
								$this->company->healthData[$id]->{$gender.'yld'}[$age] = 0;
								$goodToGo = false;
							}
							// set the YLD
							$this->items[$id]->$gender->$age->yld				= $this->company->healthData[$id]->{$gender.'yld'}[$age];
							if ($goodToGo)
							{
								// set the estimated_burden of this age group & gender & cause
								$this->items[$id]->$gender->$age->estimated_burden	= ($this->items[$id]->$gender->$age->yld / $this->company->country_yld)*100;
								if (!isset($this->items[$id]->subtotal_estimated_burden))
								{
									$this->items[$id]->subtotal_estimated_burden 	= $this->items[$id]->$gender->$age->estimated_burden;
								}
								else
								{
									$this->items[$id]->subtotal_estimated_burden 	+= $this->items[$id]->$gender->$age->estimated_burden;	
								}
								// set the totals for estimated_burden
								$this->totals['total_estimated_burden']			= $this->totals['total_estimated_burden'] + $this->items[$id]->$gender->$age->estimated_burden;
							}
							else
							{
								$this->items[$id]->subtotal_estimated_burden = 0;
							}
							// set the morbidity raw
							$this->items[$id]->$gender->$age->morbidity_raw 	= ( $this->company->healthData[$id]->{$gender.'yld'}[$age]/100000 ) 
														* $this->company->$genders * ($this->company->{'percent'.$gender}[$age]/100);
							// set total for morbidity_raw
							$this->totals['total_morbidity_raw'] 			= $this->totals['total_morbidity_raw'] + $this->items[$id]->$gender->$age->morbidity_raw;
							$this->totals[$genders.'_morbidity_raw'] 		= $this->totals[$genders.'_morbidity_raw'] + $this->items[$id]->$gender->$age->morbidity_raw;
						}
					}
				}
			}
			// check estimated burden
			if (100 < $this->totals['total_estimated_burden'])
			{
				foreach ($this->items as $item)
				{
					if (isset($item->subtotal_estimated_burden) && 0 != $item->subtotal_estimated_burden)
					{
						// adjust the subtotal to 100 %
						$item->subtotal_estimated_burden = ($item->subtotal_estimated_burden * 100) / $this->totals['total_estimated_burden'];
					}
				}
				// bring down the estimate to 100 %
				$this->totals['total_estimated_burden'] = 100;
			}
			// set morbidity_unscaled & morbidity_interim
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group morbidity_unscaled
							$this->items[$id]->$gender->$age->morbidity_unscaled 	= $this->items[$id]->$gender->$age->morbidity_raw 
														* $this->company->{'sick_leave_'.$genders} / $this->totals[$genders.'_morbidity_raw'];
							// set total each cause/risk morbidity_unscaled
							if (!isset($this->items[$id]->subtotal_morbidity_unscaled))
							{
								$this->items[$id]->subtotal_morbidity_unscaled 		= $this->items[$id]->$gender->$age->morbidity_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_morbidity_unscaled 		+= $this->items[$id]->$gender->$age->morbidity_unscaled;	
							}
							// set total each cause/risk per gender morbidity_unscaled
							if (!isset($this->items[$id]->{$gender.'_morbidity_unscaled'}))
							{
								$this->items[$id]->{$gender.'_morbidity_unscaled'} 	= $this->items[$id]->$gender->$age->morbidity_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_morbidity_unscaled'}	+= $this->items[$id]->$gender->$age->morbidity_unscaled;
							}

							// set total for morbidity_unscaled per gender
							$this->totals[$genders.'_morbidity_unscaled'] 			= $this->totals[$genders.'_morbidity_unscaled'] + $this->items[$id]->$gender->$age->morbidity_unscaled;
							// set total for morbidity_unscaled
							$this->totals['total_morbidity_unscaled'] 			= $this->totals['total_morbidity_unscaled'] + $this->items[$id]->$gender->$age->morbidity_unscaled;
							// set the basic department
							if($this->company->department == 2)
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->morbidity_interim 	= $this->items[$id]->$gender->$age->morbidity_unscaled * $this->company->scalingFactors[$id]->{'yld_scaling_factor_'.$genders};
							}
							else
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->morbidity_interim 	= $this->items[$id]->$gender->$age->morbidity_unscaled * 1;
							}
							// set total for morbidity_interim
							$this->totals['total_morbidity_interim'] 			= $this->totals['total_morbidity_interim'] + $this->items[$id]->$gender->$age->morbidity_interim;
							$this->totals[$genders.'_morbidity_interim'] 			= $this->totals[$genders.'_morbidity_interim'] + $this->items[$id]->$gender->$age->morbidity_interim;
							// set the basic department
							if($this->company->department == 2)
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->presenteeism_unscaled 	= $this->items[$id]->$gender->$age->morbidity_unscaled 
																* $this->company->country_presenteeism;
							}
							else
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->presenteeism_unscaled 	= $this->items[$id]->$gender->$age->morbidity_unscaled 
																* $this->company->country_presenteeism * 1;
							}
							// set total each cause/risk presenteeism_unscaled
							if (!isset($this->items[$id]->subtotal_presenteeism_unscaled))
							{
								$this->items[$id]->subtotal_presenteeism_unscaled 	= $this->items[$id]->$gender->$age->presenteeism_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_presenteeism_unscaled 	+= $this->items[$id]->$gender->$age->presenteeism_unscaled;
							}
							// set total each cause/risk per gender morbidity_unscaled
							if (!isset($this->items[$id]->{$gender.'_presenteeism_unscaled'}))
							{
								$this->items[$id]->{$gender.'_presenteeism_unscaled'} 	= $this->items[$id]->$gender->$age->presenteeism_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_presenteeism_unscaled'} 	+= $this->items[$id]->$gender->$age->presenteeism_unscaled;
							}

							// set total for presenteeism_unscaled per gender
							$this->totals[$genders.'_presenteeism_unscaled'] 		= $this->totals[$genders.'_presenteeism_unscaled'] + $this->items[$id]->$gender->$age->presenteeism_unscaled;
							// set total for presenteeism_unscaled
							$this->totals['total_presenteeism_unscaled'] 			= $this->totals['total_presenteeism_unscaled'] + $this->items[$id]->$gender->$age->presenteeism_unscaled;
						}
					}
				}
			}
			// set morbidity_scaled
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group
							$this->items[$id]->$gender->$age->morbidity_scaled 		= $this->items[$id]->$gender->$age->morbidity_interim 
															* $this->company->{'sick_leave_'.$genders} / $this->totals[$genders.'_morbidity_interim'];
							// set total each cause/risk
							if (!isset($this->items[$id]->subtotal_morbidity_scaled))
							{
								$this->items[$id]->subtotal_morbidity_scaled		= $this->items[$id]->$gender->$age->morbidity_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_morbidity_scaled 		+= $this->items[$id]->$gender->$age->morbidity_scaled;
							}
							// set total each cause/risk per gender
							if (!isset($this->items[$id]->{$gender.'_morbidity_scaled'}))
							{
								$this->items[$id]->{$gender.'_morbidity_scaled'} 	= $this->items[$id]->$gender->$age->morbidity_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_morbidity_scaled'} 	+= $this->items[$id]->$gender->$age->morbidity_scaled;
							}
							// set total for morbidity_scaled per gender
							$this->totals[$genders.'_morbidity_scaled'] 			= $this->totals[$genders.'_morbidity_scaled'] + $this->items[$id]->$gender->$age->morbidity_scaled;
							// set total for morbidity_scaled
							$this->totals['total_morbidity_scaled'] 			= $this->totals['total_morbidity_scaled'] + $this->items[$id]->$gender->$age->morbidity_scaled;
							// set the basic department
							if($this->company->department == 2)
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->presenteeism_scaled 	= $this->items[$id]->$gender->$age->morbidity_scaled 
															* $this->company->country_presenteeism * $this->company->scalingFactors[$id]->{'presenteeism_scaling_factor_'.$genders};
							}
							else
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->presenteeism_scaled 	= $this->items[$id]->$gender->$age->morbidity_scaled 
															* $this->company->country_presenteeism * 1;
							}
							// set total each cause/risk presenteeism_scaled
							if (!isset($this->items[$id]->subtotal_presenteeism_scaled))
							{
								$this->items[$id]->subtotal_presenteeism_scaled 	= $this->items[$id]->$gender->$age->presenteeism_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_presenteeism_scaled 	+= $this->items[$id]->$gender->$age->presenteeism_scaled;
							}
							// set total each cause/risk per gender morbidity_scaled
							if (!isset($this->items[$id]->{$gender.'_presenteeism_scaled'}))
							{
								$this->items[$id]->{$gender.'_presenteeism_scaled'}	= $this->items[$id]->$gender->$age->presenteeism_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_presenteeism_scaled'}	+= $this->items[$id]->$gender->$age->presenteeism_scaled;
							}
							// set total for presenteeism_scaled per gender
							$this->totals[$genders.'_presenteeism_scaled'] 			= $this->totals[$genders.'_presenteeism_scaled'] + $this->items[$id]->$gender->$age->presenteeism_scaled;
							// set total for presenteeism_scaled
							$this->totals['total_presenteeism_scaled'] 			= $this->totals['total_presenteeism_scaled'] + $this->items[$id]->$gender->$age->presenteeism_scaled;
						}
					}
				}
			}
			
			///////////////////////////////////////////////////////
			//  ------ set mortality for each cause/risk ------  //
			///////////////////////////////////////////////////////
			
			// set mortality_raw
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group
							// fix missing values
							if(!isset($this->company->healthData[$id]->{$gender.'death'}[$age]))
							{
								$this->company->healthData[$id]->{$gender.'death'}[$age] = 0;
							}
							$this->items[$id]->$gender->$age->death 		= $this->company->healthData[$id]->{$gender.'death'}[$age];
							$this->items[$id]->$gender->$age->mortality_raw 	= ( $this->company->healthData[$id]->{$gender.'death'}[$age]/100000 ) 
														* $this->company->$genders * ($this->company->{'percent'.$gender}[$age]/100);
							// set total for mortality_raw
							$this->totals['total_mortality_raw'] 			= $this->totals['total_mortality_raw'] + $this->items[$id]->$gender->$age->mortality_raw;
							$this->totals[$genders.'_mortality_raw'] 		= $this->totals[$genders.'_mortality_raw'] + $this->items[$id]->$gender->$age->mortality_raw;
						}
					}
				}
			}
			// set mortality_unscaled & mortality_interim
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group
							$this->items[$id]->$gender->$age->mortality_unscaled 	= $this->items[$id]->$gender->$age->mortality_raw 
														*  $this->company->{'medical_turnovers_'.$genders} / $this->totals[$genders.'_mortality_raw'];
							// set total each cause/risk
							if(!isset($this->items[$id]->subtotal_mortality_unscaled))
							{
								$this->items[$id]->subtotal_mortality_unscaled	= $this->items[$id]->$gender->$age->mortality_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_mortality_unscaled	+= $this->items[$id]->$gender->$age->mortality_unscaled;	
							}							
							// set total each cause/risk per gender							
							if(!isset($this->items[$id]->{$gender.'_mortality_unscaled'}))
							{
								$this->items[$id]->{$gender.'_mortality_unscaled'}	= $this->items[$id]->$gender->$age->mortality_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_mortality_unscaled'} 	+= $this->items[$id]->$gender->$age->mortality_unscaled;								
							}
							// set total for mortality_unscaled per gender
							$this->totals[$genders.'_mortality_unscaled'] 			= $this->totals[$genders.'_mortality_unscaled'] + $this->items[$id]->$gender->$age->mortality_unscaled;
							// set total for mortality_unscaled
							$this->totals['total_mortality_unscaled'] 			= $this->totals['total_mortality_unscaled']  + $this->items[$id]->$gender->$age->mortality_unscaled;
							// set the basic department
							if($this->company->department == 2)
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->mortality_interim 	= $this->items[$id]->$gender->$age->mortality_unscaled * $this->company->scalingFactors[$id]->{'mortality_scaling_factor_'.$genders};
							}
							else
							{
								// set each gender and age group
								$this->items[$id]->$gender->$age->mortality_interim 	= $this->items[$id]->$gender->$age->mortality_unscaled * 1;
							}
							// set total for mortality_interim
							$this->totals['total_mortality_interim'] 			= $this->totals['total_mortality_interim'] + $this->items[$id]->$gender->$age->mortality_interim;
							$this->totals[$genders.'_mortality_interim'] 			= $this->totals[$genders.'_mortality_interim'] + $this->items[$id]->$gender->$age->mortality_interim;

							// set each gender and age group days_lost_mortality_unscaled
							$this->items[$id]->$gender->$age->days_lost_mortality_unscaled 		= $this->items[$id]->$gender->$age->mortality_unscaled 
																* $this->company->working_days * $this->company->productivity_losses;
							// set total each cause/risk days_lost_mortality_unscaled
							if(!isset($this->items[$id]->subtotal_days_lost_mortality_unscaled))
							{
								$this->items[$id]->subtotal_days_lost_mortality_unscaled	= $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_days_lost_mortality_unscaled	+= $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
							}
							// set total each cause/risk per gender days_lost_mortality_unscaled
							if(!isset($this->items[$id]->{$gender.'_days_lost_mortality_unscaled'}))
							{
								$this->items[$id]->{$gender.'_days_lost_mortality_unscaled'}	= $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_days_lost_mortality_unscaled'}	+= $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
							}
							// set total for days_lost_mortality_unscaled per gender
							$this->totals[$genders.'_days_lost_mortality_unscaled'] = $this->totals[$genders.'_days_lost_mortality_unscaled'] + $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
							// set total for days_lost_mortality_unscaled
							$this->totals['total_days_lost_mortality_unscaled'] 	= $this->totals['total_days_lost_mortality_unscaled'] + $this->items[$id]->$gender->$age->days_lost_mortality_unscaled;
						}
					}
				}
			}
			// set mortality_scaled
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group
							$this->items[$id]->$gender->$age->mortality_scaled 	= $this->items[$id]->$gender->$age->mortality_interim 
														* $this->company->{'medical_turnovers_'.$genders} / $this->totals[$genders.'_mortality_interim'];
							// set total each cause/risk
							if(!isset($this->items[$id]->subtotal_mortality_scaled))
							{
								$this->items[$id]->subtotal_mortality_scaled 	= $this->items[$id]->$gender->$age->mortality_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_mortality_scaled 	+= $this->items[$id]->$gender->$age->mortality_scaled;
							}
							// set total each cause/risk per gender
							if(!isset($this->items[$id]->{$gender.'_mortality_scaled'}))
							{
								$this->items[$id]->{$gender.'_mortality_scaled'} = $this->items[$id]->$gender->$age->mortality_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_mortality_scaled'} += $this->items[$id]->$gender->$age->mortality_scaled;
							}
							// set total for mortality_scaled per gender
							$this->totals[$genders.'_mortality_scaled'] 		= $this->totals[$genders.'_mortality_scaled'] + $this->items[$id]->$gender->$age->mortality_scaled;
							// set total for mortality_scaled
							$this->totals['total_mortality_scaled'] 		= $this->totals['total_mortality_scaled'] + $this->items[$id]->$gender->$age->mortality_scaled;

							// set each gender and age group days_lost_mortality_scaled
							$this->items[$id]->$gender->$age->days_lost_mortality_scaled 	= $this->items[$id]->$gender->$age->mortality_scaled 
															* $this->company->working_days * $this->company->productivity_losses;
							// set total each cause/risk days_lost_mortality_scaled
							if(!isset($this->items[$id]->subtotal_days_lost_mortality_scaled))
							{
								$this->items[$id]->subtotal_days_lost_mortality_scaled	= $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_days_lost_mortality_scaled	+= $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
							}							
							// set total each cause/risk per gender days_lost_mortality_scaled
							if(!isset($this->items[$id]->{$gender.'_days_lost_mortality_scaled'}))
							{
								$this->items[$id]->{$gender.'_days_lost_mortality_scaled'}	= $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_days_lost_mortality_scaled'}	+= $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
							}
							// set total for days_lost_mortality_scaled per gender
							$this->totals[$genders.'_days_lost_mortality_scaled'] 	= $this->totals[$genders.'_days_lost_mortality_scaled'] + $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
							// set total for days_lost_mortality_scaled
							$this->totals['total_days_lost_mortality_scaled'] 	= $this->totals['total_days_lost_mortality_scaled'] + $this->items[$id]->$gender->$age->days_lost_mortality_scaled;
						}
					}
				
					// set gender days lost unscaled per cause/risk
					$this->items[$id]->{$gender.'_days_lost_unscaled'} 	= $this->items[$id]->{$gender.'_morbidity_unscaled'}
												+ $this->items[$id]->{$gender.'_days_lost_mortality_unscaled'} 
												+ $this->items[$id]->{$gender.'_presenteeism_unscaled'};
					// set gender days lost scaled per cause/risk
					$this->items[$id]->{$gender.'_days_lost_scaled'} 		= $this->items[$id]->{$gender.'_morbidity_scaled'}
													+ $this->items[$id]->{$gender.'_days_lost_mortality_scaled'} 
													+ $this->items[$id]->subtotal_presenteeism_scaled;
					// set gender global total days lost unscaled
					$this->totals[$genders.'_days_lost_unscaled'] 	= $this->totals[$genders.'_days_lost_unscaled']
											+ $this->items[$id]->{$gender.'_days_lost_unscaled'};
					// set gender global total days lost scaled
					$this->totals[$genders.'_days_lost_scaled'] 	= $this->totals[$genders.'_days_lost_scaled']
											+ $this->items[$id]->{$gender.'_days_lost_scaled'};
				}
				
				// set subtotal days lost unscaled per cause/risk
				$this->items[$id]->{'subtotal_days_lost_unscaled'} 	= $this->items[$id]->subtotal_morbidity_unscaled
											+ $this->items[$id]->subtotal_days_lost_mortality_unscaled 
											+ $this->items[$id]->subtotal_presenteeism_unscaled;
				// set subtotal days lost scaled per cause/risk
				$this->items[$id]->{'subtotal_days_lost_scaled'} 		= $this->items[$id]->subtotal_morbidity_scaled
												+ $this->items[$id]->subtotal_days_lost_mortality_scaled 
												+ $this->items[$id]->subtotal_presenteeism_scaled;
																	
				// set global total days lost unscaled
				$this->totals['total_days_lost_unscaled'] 	= $this->totals['total_days_lost_scaled']
										+ $this->items[$id]->{'subtotal_days_lost_unscaled'};
				// set global total days lost scaled
				$this->totals['total_days_lost_scaled'] 	= $this->totals['total_days_lost_scaled']
										+ $this->items[$id]->{'subtotal_days_lost_scaled'};
			} return true;
		} return false;
	}
	
	/**
	 * Do calculation for cost.
	 *
	 * @return array
	 *
	 */
	protected function setCost()
	{
		if(CostbenefitprojectionHelper::checkArray($this->company->causesrisks))
		{
			// the total cost in Money
			$this->totals['total_costmoney_scaled']			= 0;
			$this->totals['total_costmoney_unscaled']		= 0;
			// set global totals morbidity
			$this->totals['total_costmoney_morbidity_scaled'] 	= 0;
			$this->totals['total_costmoney_morbidity_unscaled'] 	= 0;
			// set global totals mortality
			$this->totals['total_costmoney_mortality_scaled'] 	= 0;
			$this->totals['total_costmoney_mortality_unscaled'] 	= 0;
			// set global totals presenteesim		
			$this->totals['total_costmoney_presenteeism_scaled'] 	= 0;
			$this->totals['total_costmoney_presenteeism_unscaled'] 	= 0;
			// the total cost in Values only
			$this->totals['total_cost_scaled']				= 0;
			$this->totals['total_cost_unscaled']				= 0;
			// set global totals morbidity
			$this->totals['total_cost_morbidity_scaled'] 			= 0;
			$this->totals['total_cost_morbidity_unscaled'] 			= 0;
			// set global totals mortality
			$this->totals['total_cost_mortality_scaled'] 			= 0;
			$this->totals['total_cost_mortality_unscaled'] 			= 0;
			// set global totals presenteesim		
			$this->totals['total_cost_presenteeism_scaled'] 		= 0;
			$this->totals['total_cost_presenteeism_unscaled'] 		= 0;
			// work with each gender
			foreach($this->genders as $gender => $genders)
			{
				// gender totals morbidity
				$this->totals[$genders.'_costmoney_morbidity_scaled'] 		= 0;
				$this->totals[$genders.'_costmoney_morbidity_unscaled'] 	= 0;
				// gender totals mortality
				$this->totals[$genders.'_costmoney_mortality_scaled'] 		= 0;
				$this->totals[$genders.'_costmoney_mortality_unscaled'] 	= 0;
				// gender totals presenteeism
				$this->totals[$genders.'_costmoney_presenteeism_scaled'] 	= 0;
				$this->totals[$genders.'_costmoney_presenteeism_unscaled'] 	= 0;
				// gender totals
				$this->totals[$genders.'_costmoney_scaled'] 			= 0;
				$this->totals[$genders.'_costmoney_unscaled'] 			= 0;
				// gender totals morbidity
				$this->totals[$genders.'_cost_morbidity_scaled'] 		= 0;
				$this->totals[$genders.'_cost_morbidity_unscaled'] 		= 0;
				// gender totals mortality
				$this->totals[$genders.'_cost_mortality_scaled'] 		= 0;
				$this->totals[$genders.'_cost_mortality_unscaled'] 		= 0;
				// gender totals presenteeism
				$this->totals[$genders.'_cost_presenteeism_scaled'] 		= 0;
				$this->totals[$genders.'_cost_presenteeism_unscaled'] 		= 0;
				// gender totals
				$this->totals[$genders.'_cost_scaled'] 				= 0;
				$this->totals[$genders.'_cost_unscaled'] 			= 0;
			}
			
			//////////////////////////////////////////////////
			//  ------ set cost for each cause/risk ------  //
			//////////////////////////////////////////////////
			
			// set cost_morbidity
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// unscaled ///////////////
							// set each gender and age group morbidity_unscaled
							$this->items[$id]->$gender->$age->cost_morbidity_unscaled 	= $this->items[$id]->$gender->$age->morbidity_unscaled
															* (( $this->company->total_salary / ($this->company->males + $this->company->females) 
															/ $this->company->working_days )
															+ $this->company->total_healthcare / $this->totals['total_morbidity_unscaled']);
							// set total each cause/risk cost_morbidity_unscaled
							if(!isset($this->items[$id]->subtotal_cost_morbidity_unscaled))
							{
								$this->items[$id]->subtotal_cost_morbidity_unscaled	= $this->items[$id]->$gender->$age->cost_morbidity_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_morbidity_unscaled	+= $this->items[$id]->$gender->$age->cost_morbidity_unscaled;
							}
							// set total each cause/risk per gender cost_morbidity_unscaled
							if(!isset($this->items[$id]->{$gender.'_cost_morbidity_unscaled'}))
							{
								$this->items[$id]->{$gender.'_cost_morbidity_unscaled'}	= $this->items[$id]->$gender->$age->cost_morbidity_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_morbidity_unscaled'}	+= $this->items[$id]->$gender->$age->cost_morbidity_unscaled;
							}
							// set total for cost_morbidity_unscaled
							$this->totals['total_cost_morbidity_unscaled'] 			= $this->totals['total_cost_morbidity_unscaled'] + $this->items[$id]->$gender->$age->cost_morbidity_unscaled;
							// set total for cost_morbidity_unscaled per gender
							$this->totals[$genders.'_cost_morbidity_unscaled'] 		= $this->totals[$genders.'_cost_morbidity_unscaled'] + $this->items[$id]->$gender->$age->cost_morbidity_unscaled;

							// scaled /////////////////
							// set each gender and age group morbidity_scaled
							$this->items[$id]->$gender->$age->cost_morbidity_scaled 	= $this->items[$id]->$gender->$age->morbidity_scaled
															* (( $this->company->total_salary / ($this->company->males + $this->company->females) 
															/ $this->company->working_days )
															+ $this->company->total_healthcare / $this->totals['total_morbidity_scaled']);
							// set total each cause/risk cost_morbidity_scaled
							if(!isset($this->items[$id]->subtotal_cost_morbidity_scaled))
							{
								$this->items[$id]->subtotal_cost_morbidity_scaled	= $this->items[$id]->$gender->$age->cost_morbidity_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_morbidity_scaled	+= $this->items[$id]->$gender->$age->cost_morbidity_scaled;
							}
							// set total each cause/risk per gender cost_morbidity_scaled
							if(!isset($this->items[$id]->{$gender.'_cost_morbidity_scaled'}))
							{
								$this->items[$id]->{$gender.'_cost_morbidity_scaled'} 	= $this->items[$id]->$gender->$age->cost_morbidity_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_morbidity_scaled'} 	+= $this->items[$id]->$gender->$age->cost_morbidity_scaled;
							}
							// set total for cost_morbidity_scaled
							$this->totals['total_cost_morbidity_scaled'] 			= $this->totals['total_cost_morbidity_scaled'] + $this->items[$id]->$gender->$age->cost_morbidity_scaled;
							// set total for cost_morbidity_scaled per gender
							$this->totals[$genders.'_cost_morbidity_scaled'] 		= $this->totals[$genders.'_cost_morbidity_scaled'] + $this->items[$id]->$gender->$age->cost_morbidity_scaled;
						}
						
					}
				}
			}
			// set cost_presenteeism
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// unscaled ///////////////
							// set each gender and age group cost_presenteeism_unscaled
							$this->items[$id]->$gender->$age->cost_presenteeism_unscaled 	= $this->items[$id]->$gender->$age->presenteeism_unscaled
															* ( $this->company->total_salary / (( $this->company->males + $this->company->females) 
															* $this->company->working_days));
							// set total each cause/risk cost_presenteeism_unscaled
							if(!isset($this->items[$id]->subtotal_cost_presenteeism_unscaled))
							{
								$this->items[$id]->subtotal_cost_presenteeism_unscaled 	= $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_presenteeism_unscaled 	+= $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;
							}
							// set total each cause/risk per gender cost_presenteeism_unscaled
							if(!isset($this->items[$id]->{$gender.'_cost_presenteeism_unscaled'}))
							{
								$this->items[$id]->{$gender.'_cost_presenteeism_unscaled'} 	= $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_presenteeism_unscaled'} 	+= $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;
							}
							// set total for cost_presenteeism_unscaled
							$this->totals['total_cost_presenteeism_unscaled'] 			= $this->totals['total_cost_presenteeism_unscaled'] 
																+ $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;
							// set total for cost_presenteeism_unscaled per gender
							$this->totals[$genders.'_cost_presenteeism_unscaled'] 			= $this->totals[$genders.'_cost_presenteeism_unscaled'] 
																+ $this->items[$id]->$gender->$age->cost_presenteeism_unscaled;

							// scaled /////////////////
							// set each gender and age group cost_presenteeism_scaled
							$this->items[$id]->$gender->$age->cost_presenteeism_scaled 		= $this->items[$id]->$gender->$age->presenteeism_scaled
																* ( $this->company->total_salary / (( $this->company->males + $this->company->females) 
																* $this->company->working_days));
							// set total each cause/risk cost_presenteeism_scaled
							if(!isset($this->items[$id]->subtotal_cost_presenteeism_scaled))
							{
								$this->items[$id]->subtotal_cost_presenteeism_scaled 		= $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_presenteeism_scaled 		+= $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
							}
							// set total each cause/risk per gender cost_presenteeism_scaled
							if(!isset($this->items[$id]->{$gender.'_cost_presenteeism_scaled'}))
							{
								$this->items[$id]->{$gender.'_cost_presenteeism_scaled'}	= $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_presenteeism_scaled'}	+= $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
							}
							// set total for cost_presenteeism_scaled
							$this->totals['total_cost_presenteeism_scaled'] 			= $this->totals['total_cost_presenteeism_scaled'] 
																+ $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
							// set total for cost_presenteeism_scaled per gender
							$this->totals[$genders.'_cost_presenteeism_scaled'] 			= $this->totals[$genders.'_cost_presenteeism_scaled'] 
																+ $this->items[$id]->$gender->$age->cost_presenteeism_scaled;
						}
					}
				}
			}
			// set Cost_mortality
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// unscaled ///////////////
							// set each gender and age group cost_mortality_unscaled
							$this->items[$id]->$gender->$age->cost_mortality_unscaled 	= $this->items[$id]->$gender->$age->days_lost_mortality_unscaled
															* ( $this->company->total_salary / (( $this->company->males + $this->company->females) 
															* $this->company->working_days));
							// set total each cause/risk cost_mortality_unscaled
							if(!isset($this->items[$id]->subtotal_cost_mortality_unscaled))
							{
								$this->items[$id]->subtotal_cost_mortality_unscaled 	= $this->items[$id]->$gender->$age->cost_mortality_unscaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_mortality_unscaled 	+= $this->items[$id]->$gender->$age->cost_mortality_unscaled;
							}
							// set total each cause/risk per gender cost_mortality_unscaled
							if(!isset($this->items[$id]->{$gender.'_cost_mortality_unscaled'}))
							{
								$this->items[$id]->{$gender.'_cost_mortality_unscaled'} = $this->items[$id]->$gender->$age->cost_mortality_unscaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_mortality_unscaled'} += $this->items[$id]->$gender->$age->cost_mortality_unscaled;
							}
							// set total for cost_mortality_unscaled
							$this->totals['total_cost_mortality_unscaled'] 			= $this->totals['total_cost_mortality_unscaled'] 
															+ $this->items[$id]->$gender->$age->cost_mortality_unscaled;
							// set total for cost_mortality_unscaled per gender
							$this->totals[$genders.'_cost_mortality_unscaled'] 		= $this->totals[$genders.'_cost_mortality_unscaled'] 
															+ $this->items[$id]->$gender->$age->cost_mortality_unscaled;

							// scaled /////////////////
							// set each gender and age group cost_mortality_scaled
							$this->items[$id]->$gender->$age->cost_mortality_scaled 	= $this->items[$id]->$gender->$age->days_lost_mortality_scaled
															* ( $this->company->total_salary / (( $this->company->males + $this->company->females) 
															* $this->company->working_days));
							// set total each cause/risk cost_mortality_scaled
							if(!isset($this->items[$id]->subtotal_cost_mortality_scaled))
							{
								$this->items[$id]->subtotal_cost_mortality_scaled 	= $this->items[$id]->$gender->$age->cost_mortality_scaled;
							}
							else
							{
								$this->items[$id]->subtotal_cost_mortality_scaled 	+= $this->items[$id]->$gender->$age->cost_mortality_scaled;
							}
							// set total each cause/risk per gender cost_mortality_scaled
							if(!isset($this->items[$id]->{$gender.'_cost_mortality_scaled'}))
							{
								$this->items[$id]->{$gender.'_cost_mortality_scaled'} 	= $this->items[$id]->$gender->$age->cost_mortality_scaled;
							}
							else
							{
								$this->items[$id]->{$gender.'_cost_mortality_scaled'} 	+= $this->items[$id]->$gender->$age->cost_mortality_scaled;
							}
							// set total for cost_mortality_scaled
							$this->totals['total_cost_mortality_scaled'] 	= $this->totals['total_cost_mortality_scaled'] 
													+ $this->items[$id]->$gender->$age->cost_mortality_scaled;
							// set total for cost_mortality_scaled per gender
							$this->totals[$genders.'_cost_mortality_scaled'] = $this->totals[$genders.'_cost_mortality_scaled'] 
													+ $this->items[$id]->$gender->$age->cost_mortality_scaled;
						}
					}
					
					// unscaled ///////////////
					$this->items[$id]->{$gender.'_cost_unscaled'}	= $this->items[$id]->{$gender.'_cost_morbidity_unscaled'}
											+ $this->items[$id]->{$gender.'_cost_mortality_unscaled'} 
											+ $this->items[$id]->{$gender.'_cost_presenteeism_unscaled'};
					// scaled ///////////////
					$this->items[$id]->{$gender.'_cost_scaled'} 	= $this->items[$id]->{$gender.'_cost_morbidity_scaled'} 
											+ $this->items[$id]->{$gender.'_cost_mortality_scaled'} 
											+ $this->items[$id]->{$gender.'_cost_presenteeism_scaled'};
																
					// set total for gender cost_unscaled
					$this->totals[$genders.'_cost_unscaled'] 	= $this->totals[$genders.'_cost_unscaled'] 
											+ $this->items[$id]->{$gender.'_cost_unscaled'};
																
					// set total for gender cost_scaled
					$this->totals[$genders.'_cost_scaled'] 	= $this->totals[$genders.'_cost_scaled'] 
										+ $this->items[$id]->{$gender.'_cost_scaled'};
				}
				// unscaled ///////////////
				$this->items[$id]->subtotal_cost_unscaled = $this->items[$id]->subtotal_cost_morbidity_unscaled 
									+ $this->items[$id]->subtotal_cost_mortality_unscaled 
									+ $this->items[$id]->subtotal_cost_presenteeism_unscaled;
				// scaled ///////////////
				$this->items[$id]->subtotal_cost_scaled 	= $this->items[$id]->subtotal_cost_morbidity_scaled 
										+ $this->items[$id]->subtotal_cost_mortality_scaled 
										+ $this->items[$id]->subtotal_cost_presenteeism_scaled;
				
				// set global total cost
				$this->totals['total_cost_unscaled']	= $this->totals['total_cost_unscaled'] + $this->items[$id]->subtotal_cost_unscaled;
				$this->totals['total_cost_scaled']	= $this->totals['total_cost_scaled'] + $this->items[$id]->subtotal_cost_scaled;
			}
			// turn values into money
			foreach($this->company->causesrisks as $id)
			{
				// work with each gender
				foreach($this->genders as $gender => $genders)
				{
					// work with each age group
					foreach($this->ages as $age)
					{
						if (isset($this->company->{'percent'.$gender}[$age]))
						{
							// set each gender and age group costmoney_morbidity_unscaled
							$this->items[$id]->$gender->$age->costmoney_morbidity_unscaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_morbidity_unscaled);
							// set each gender and age group costmoney_morbidity_scaled
							$this->items[$id]->$gender->$age->costmoney_morbidity_scaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_morbidity_scaled);

							// set each gender and age group costmoney_presenteeism_unscaled
							$this->items[$id]->$gender->$age->costmoney_presenteeism_unscaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_presenteeism_unscaled);
							// set each gender and age group costmoney_presenteeism_scaled
							$this->items[$id]->$gender->$age->costmoney_presenteeism_scaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_presenteeism_scaled);

							// set each gender and age group costmoney_mortality_unscaled
							$this->items[$id]->$gender->$age->costmoney_mortality_unscaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_mortality_unscaled);
							// set each gender and age group costmoney_mortality_scaled
							$this->items[$id]->$gender->$age->costmoney_mortality_scaled 	= $this->makeMoney((float)$this->items[$id]->$gender->$age->cost_mortality_scaled);
						}
					}
					//// --- add all cost per gender --- ////						
					// set total each cause/risk per gender costmoney_morbidity_unscaled
					$this->items[$id]->{$gender.'_costmoney_morbidity_unscaled'} 		= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_morbidity_unscaled'});
					// set total each cause/risk per gender costmoney_morbidity_scaled
					$this->items[$id]->{$gender.'_costmoney_morbidity_scaled'} 		= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_morbidity_scaled'});
					
					// set total each cause/risk per gender costmoney_mortality_unscaled
					$this->items[$id]->{$gender.'_costmoney_mortality_unscaled'} 		= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_mortality_unscaled'});
					// set total each cause/risk per gender costmoney_mortality_scaled
					$this->items[$id]->{$gender.'_costmoney_mortality_scaled'} 		= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_mortality_scaled'});
					
					// set total each cause/risk per gender costmoney_presenteeism_unscaled
					$this->items[$id]->{$gender.'_costmoney_presenteeism_unscaled'} 	= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_presenteeism_unscaled'});
					// set total each cause/risk per gender costmoney_presenteeism_scaled
					$this->items[$id]->{$gender.'_costmoney_presenteeism_scaled'} 		= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_presenteeism_scaled'});										
																
					// set total each subtotal_costmoney_unscale
					$this->items[$id]->{$gender.'_costmoney_unscaled'} 	= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_unscaled'});
					// set total each subtotal_costmoney_scaled
					$this->items[$id]->{$gender.'_costmoney_scaled'} 	= $this->makeMoney((float)$this->items[$id]->{$gender.'_cost_scaled'});	
						
					// gender totals morbidity
					$this->totals[$genders.'_costmoney_morbidity_scaled'] 		= $this->makeMoney((float)$this->totals[$genders.'_cost_morbidity_scaled']);
					$this->totals[$genders.'_costmoney_morbidity_unscaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_morbidity_unscaled']);
					// gender totals mortality
					$this->totals[$genders.'_costmoney_mortality_scaled'] 		= $this->makeMoney((float)$this->totals[$genders.'_cost_mortality_scaled']);
					$this->totals[$genders.'_costmoney_mortality_unscaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_mortality_unscaled']);
					// gender total presenteeism
					$this->totals[$genders.'_costmoney_presenteeism_unscaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_presenteeism_unscaled']);
					$this->totals[$genders.'_costmoney_presenteeism_scaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_presenteeism_scaled']);										
																
					// set total gender costmoney
					$this->totals[$genders.'_costmoney_unscaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_unscaled']);
					$this->totals[$genders.'_costmoney_scaled'] 	= $this->makeMoney((float)$this->totals[$genders.'_cost_scaled']);				
				}
					
				// set total each cause/risk costmoney_morbidity_unscaled
				$this->items[$id]->subtotal_costmoney_morbidity_unscaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_morbidity_unscaled);
				// set total each cause/risk costmoney_morbidity_scaled
				$this->items[$id]->subtotal_costmoney_morbidity_scaled 		= $this->makeMoney((float)$this->items[$id]->subtotal_cost_morbidity_scaled);
				
				// set total each cause/risk costmoney_mortality_unscaled
				$this->items[$id]->subtotal_costmoney_mortality_unscaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_mortality_unscaled);
				// set total each cause/risk costmoney_mortality_scaled
				$this->items[$id]->subtotal_costmoney_mortality_scaled 		= $this->makeMoney((float)$this->items[$id]->subtotal_cost_mortality_scaled);
				
				// set total each cause/risk costmoney_presenteeism_unscaled
				$this->items[$id]->subtotal_costmoney_presenteeism_unscaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_presenteeism_unscaled);
				// set total each cause/risk costmoney_presenteeism_scaled
				$this->items[$id]->subtotal_costmoney_presenteeism_scaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_presenteeism_scaled);
																			
				// set total each subtotal_costmoney_unscale
				$this->items[$id]->subtotal_costmoney_unscaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_unscaled);
				// set total each subtotal_costmoney_scaled
				$this->items[$id]->subtotal_costmoney_scaled 	= $this->makeMoney((float)$this->items[$id]->subtotal_cost_scaled);
			}
			
			// set global totals morbidity
			$this->totals['total_costmoney_morbidity_scaled'] 	= $this->makeMoney((float)$this->totals['total_cost_morbidity_scaled']);
			$this->totals['total_costmoney_morbidity_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_morbidity_unscaled']);
			// set global totals mortality
			$this->totals['total_costmoney_mortality_scaled'] 	= $this->makeMoney((float)$this->totals['total_cost_mortality_scaled']);
			$this->totals['total_costmoney_mortality_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_mortality_unscaled']);
			// set global totals presenteesim		
			$this->totals['total_costmoney_presenteeism_scaled']	= $this->makeMoney((float)$this->totals['total_cost_presenteeism_scaled']);
			$this->totals['total_costmoney_presenteeism_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_presenteeism_unscaled']);
			// set global totals COST	
			$this->totals['total_costmoney_scaled']			= $this->makeMoney((float)$this->totals['total_cost_scaled']);
			$this->totals['total_costmoney_unscaled'] 		= $this->makeMoney((float)$this->totals['total_cost_unscaled']);
			return true;
		} return false;
	}
	
	/**
	 * Set the interventions related to this Company.
	 *
	 * @return array
	 *
	 */
	protected function setInterventions()
	{
		// now set the result set
		if(CostbenefitprojectionHelper::checkArray($this->company->causesrisks) && isset($this->company->interventions) && CostbenefitprojectionHelper::checkArray($this->company->interventions)){
			$i = 0;
			foreach ($this->company->interventions as $mainkey => $item){
				$this->interventions[$i]["id"]			= $item->id;
				$this->interventions[$i]["name"] 		= $item->name;
				$this->interventions[$i]["description"] 	= $item->description;
				$this->interventions[$i]["duration"] 		= $item->duration;
				$this->interventions[$i]["coverage"] 		= $item->coverage;
				$this->interventions[$i]["type"] 		= $item->type;
				$this->interventions[$i]['found']		= array();
				$this->interventions[$i]['not_found']		= array();
				// set totals
				$this->interventions[$i]['totals']['annual_cost_per_employee']			= 0;
				$this->interventions[$i]['totals']['annual_costmoney_per_employee']		= 0;
				$this->interventions[$i]['totals']['cost_of_problem_scaled'] 			= 0;
				$this->interventions[$i]['totals']['cost_of_problem_unscaled'] 			= 0;
				$this->interventions[$i]['totals']['costmoney_of_problem_scaled'] 		= 0;
				$this->interventions[$i]['totals']['costmoney_of_problem_unscaled'] 		= 0;
				$this->interventions[$i]['totals']['contribution_to_cost_scaled'] 		= 0;
				$this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 		= 0;
				$this->interventions[$i]['totals']['annual_cost'] 				= 0;
				$this->interventions[$i]['totals']['annual_costmoney'] 				= 0;
				$this->interventions[$i]['totals']['annual_benefit_scaled'] 			= 0;
				$this->interventions[$i]['totals']['annual_benefit_unscaled']			= 0;
				$this->interventions[$i]['totals']['annualmoney_benefit_scaled'] 		= 0;
				$this->interventions[$i]['totals']['annualmoney_benefit_unscaled']		= 0;
				$this->interventions[$i]['totals']['net_benefit_scaled']			= 0;
				$this->interventions[$i]['totals']['net_benefit_unscaled']			= 0;
				$this->interventions[$i]['totals']['netmoney_benefit_scaled']			= 0;
				$this->interventions[$i]['totals']['netmoney_benefit_unscaled']			= 0;
				// now load the cause/risk that are linked to this user
				$a = 0;
				// set values
				foreach ($item->data as $key => $value){
					if(in_array($value['id'],$this->company->causesrisks)){
						// set array of causes/risk ids
						$this->interventions[$i]['found'][$value['id']]				= $this->items[$value['id']]->details->name;
						// set local cause/risk values
						$this->interventions[$i]['items'][$a] 					= $value;
						$this->interventions[$i]['items'][$a]['name'] 				= $this->items[$value['id']]->details->name;
						$this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'] 	= $this->items[$value['id']]->subtotal_cost_unscaled;
						$this->interventions[$i]['items'][$a]['cost_of_problem_scaled'] 	= $this->items[$value['id']]->subtotal_cost_scaled;
						$this->interventions[$i]['items'][$a]['costmoney_of_problem_unscaled'] 	= $this->items[$value['id']]->subtotal_costmoney_unscaled;
						$this->interventions[$i]['items'][$a]['costmoney_of_problem_scaled'] 	= $this->items[$value['id']]->subtotal_costmoney_scaled;

						$this->interventions[$i]['items'][$a]['annual_cost']	= ($this->interventions[$i]["coverage"] /100) * $value['cpe']
													* ($this->company->males + $this->company->females);
						// turn into money													
						$this->interventions[$i]['items'][$a]['annual_costmoney'] 	= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_cost']);

						$this->interventions[$i]['items'][$a]['annual_benefit_unscaled']	= ($this->interventions[$i]["coverage"] /100) * ($value['mbr'] /100)
															* ($this->items[$value['id']]->subtotal_cost_morbidity_unscaled 
															+ $this->items[$value['id']]->subtotal_cost_presenteeism_unscaled)
															+ ($value['mtr']/100) * $this->items[$value['id']]->subtotal_cost_mortality_unscaled;

						$this->interventions[$i]['items'][$a]['annual_benefit_scaled']	= ($this->interventions[$i]["coverage"] /100) * ($value['mbr'] /100) 
														* ($this->items[$value['id']]->subtotal_cost_morbidity_scaled 
														+ $this->items[$value['id']]->subtotal_cost_presenteeism_scaled)
														+ ($value['mtr']/100) * $this->items[$value['id']]->subtotal_cost_mortality_scaled;
						// turn into money													
						$this->interventions[$i]['items'][$a]['annualmoney_benefit_unscaled'] 	= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_benefit_unscaled']);
						$this->interventions[$i]['items'][$a]['annualmoney_benefit_scaled']	= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_benefit_scaled']);

						$this->interventions[$i]['items'][$a]['net_benefit_unscaled'] 		= $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'] 
															- $this->interventions[$i]['items'][$a]['annual_cost'];
						$this->interventions[$i]['items'][$a]['net_benefit_scaled']		= $this->interventions[$i]['items'][$a]['annual_benefit_scaled'] 
															- $this->interventions[$i]['items'][$a]['annual_cost'];
						// turn into money		
						$this->interventions[$i]['items'][$a]['netmoney_benefit_unscaled']		= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['net_benefit_unscaled']);
						$this->interventions[$i]['items'][$a]['netmoney_benefit_scaled']		= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['net_benefit_scaled']);
						// set ratio
						$this->interventions[$i]['items'][$a]['benefit_ratio_unscaled'] 		= $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'] 
																/ $this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'];	
						$this->interventions[$i]['items'][$a]['benefit_ratio_scaled']			= $this->interventions[$i]['items'][$a]['annual_benefit_scaled'] 
																/ $this->interventions[$i]['items'][$a]['cost_of_problem_scaled'];

						$this->interventions[$i]['items'][$a]['annual_costmoney_per_employee']		= $this->makeMoney((float)$value['cpe']);

						// set totals
						$this->interventions[$i]['totals']['annual_cost']				= $this->interventions[$i]['totals']['annual_cost']
																+ $this->interventions[$i]['items'][$a]['annual_cost'];
						$this->interventions[$i]['totals']['annual_benefit_scaled'] 			= $this->interventions[$i]['totals']['annual_benefit_scaled'] 
																+ $this->interventions[$i]['items'][$a]['annual_benefit_scaled'];
						$this->interventions[$i]['totals']['annual_benefit_unscaled'] 			= $this->interventions[$i]['totals']['annual_benefit_unscaled'] 
																+ $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'];
						$this->interventions[$i]['totals']['net_benefit_scaled'] 			= $this->interventions[$i]['totals']['net_benefit_scaled'] 
																+ $this->interventions[$i]['items'][$a]['net_benefit_scaled'];
						$this->interventions[$i]['totals']['net_benefit_unscaled'] 			= $this->interventions[$i]['totals']['net_benefit_unscaled'] 
																+ $this->interventions[$i]['items'][$a]['net_benefit_unscaled'];
						$this->interventions[$i]['totals']['annual_cost_per_employee'] 			= $this->interventions[$i]['totals']['annual_cost_per_employee'] + $value['cpe'];

						$this->interventions[$i]['totals']['cost_of_problem_scaled'] 			= $this->interventions[$i]['totals']['cost_of_problem_scaled'] 
																+ $this->items[$value['id']]->subtotal_cost_scaled;
						$this->interventions[$i]['totals']['cost_of_problem_unscaled'] 			= $this->interventions[$i]['totals']['cost_of_problem_unscaled'] 
																+ $this->items[$value['id']]->subtotal_cost_unscaled;
					} else {
						$this->interventions[$i]['not_found'][$value['id']] 				= CostbenefitprojectionHelper::getVar('causerisk', $value['id'], 'id', 'name');
					}
					$a++;
				}
				// contribution_to_cost
				$a = 0;
				foreach ($item->data as $key => $value){
					if(in_array($value['id'],$this->company->causesrisks)){

						$this->interventions[$i]['items'][$a]['contribution_to_cost_unscaled']		= ($this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'] 
																/ $this->interventions[$i]['totals']['cost_of_problem_unscaled']) * 100;
						$this->interventions[$i]['items'][$a]['contribution_to_cost_scaled']		= ($this->interventions[$i]['items'][$a]['cost_of_problem_scaled'] 
																/ $this->interventions[$i]['totals']['cost_of_problem_scaled']) * 100;
						// set totals
						$this->interventions[$i]['totals']['contribution_to_cost_scaled'] 		= $this->interventions[$i]['totals']['contribution_to_cost_scaled'] 
																+ $this->interventions[$i]['items'][$a]['contribution_to_cost_scaled'];
						$this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 		= $this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 
																+ $this->interventions[$i]['items'][$a]['contribution_to_cost_unscaled'];
					}
					$a++;
				}
				// set total money
				$this->interventions[$i]['totals']['annual_costmoney_per_employee']		= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_cost_per_employee']);
				$this->interventions[$i]['totals']['costmoney_of_problem_scaled'] 		= $this->makeMoney((float)$this->interventions[$i]['totals']['cost_of_problem_scaled']);
				$this->interventions[$i]['totals']['costmoney_of_problem_unscaled'] 		= $this->makeMoney((float)$this->interventions[$i]['totals']['cost_of_problem_unscaled']);
				$this->interventions[$i]['totals']['annual_costmoney'] 				= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_cost']);
				$this->interventions[$i]['totals']['annualmoney_benefit_scaled'] 		= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_benefit_scaled']);
				$this->interventions[$i]['totals']['annualmoney_benefit_unscaled']		= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_benefit_unscaled']);
				$this->interventions[$i]['totals']['netmoney_benefit_scaled']			= $this->makeMoney((float)$this->interventions[$i]['totals']['net_benefit_scaled']);
				$this->interventions[$i]['totals']['netmoney_benefit_unscaled']			= $this->makeMoney((float)$this->interventions[$i]['totals']['net_benefit_unscaled']);
				if (isset($this->interventions[$i]['items']))
				{
					$this->interventions[$i]['nr_found']					= sizeof($this->interventions[$i]['items']);
				}
				else
				{
					$this->interventions[$i]['nr_found']					= 0;
				}
				if (isset($this->interventions[$i]['not_found']))
				{
					$this->interventions[$i]['nr_not_found']				= sizeof($this->interventions[$i]['not_found']);
				}
				else
				{
					$this->interventions[$i]['nr_not_found']				= 0;
				}
				$i++;
			} return true;
		} $this->interventions = false;
	}
	
	public function makeMoney($number)
	{
		if (is_numeric($number))
		{
			$negativeFinderObj = new NegativeFinder(new Expression("$number"));
			$negative = $negativeFinderObj->isItNegative() ? TRUE : FALSE;
		}
		else
		{
			throw new Exception('ERROR! ('.$number.') is not a number!');
		}
		
		if (!$negative)
		{
			$format = $this->company->currency_positivestyle;
			$sign = '+';
		}
		else 
		{
			$format = $this->company->currency_negativestyle;
			$sign = '-';
			$number = abs($number);
		}
		$setupNumber = number_format((float)$number, (int)$this->company->currency_decimalplace, $this->company->currency_decimalsymbol, ' '); //$this->company->currency_thousands TODO);
		$search = array('{sign}', '{number}', '{symbol}');
		$replace = array($sign, $setupNumber, $this->company->currency_symbol);
		$moneyMade = str_replace ($search,$replace,$format);
		
		return $moneyMade;
	}
	
}
