<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_chart_intervention_cost_benefit_public.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// load chart builder
$chart = new Chartbuilder('BarChart');

if(isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions)){
	$intervention_number = 0;
	foreach ($this->results->interventions as $intervention){
		if (isset($intervention->items))
		{
			$i =0;
			$rowArray = array();
			if(is_array($intervention->items) || is_object($intervention->items)){
				foreach ($intervention->items as $key => &$item){
					$rowArray[] = array('c' => array(
							array('v' => $item->name),
							array('v' => round($item->cost_of_problem_unscaled), 'f' => $item->costmoney_of_problem_unscaled), 
							array('v' => $item->annual_cost, 'f' => $item->annual_costmoney), 
							array('v' => $item->annual_benefit_unscaled, 'f' => $item->annualmoney_benefit_unscaled), 
							array('v' => $item->net_benefit_unscaled, 'f' => $item->netmoney_benefit_unscaled)
					));
					$i++;
				}
			}
			usort($rowArray, function($b, $a) {
				return $a['c'][3]['v'] - $b['c'][3]['v'];
			});

			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK_FACTOR'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_COST_OF_PROBLEM'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_COST_OF_INTERVENTION'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_BENEFIT'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_NET_BENEFIT'), 'type' => 'number')
					),
					'rows' => $rowArray
			);

			$height = ($i * 80)+100;
			$chart->load(json_encode($data));
			$options = array();
			$main_title = JText::sprintf("COM_COSTBENEFITPROJECTION_INTERVENTIONS_NAME_S", $intervention->name);
			$title =  '';
			if($intervention->duration > 1){
				$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_DURATION_S_YEARS', $intervention->duration); 
			} else {
				$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_DURATION_S_YEAR', $intervention->duration);
			}  
			$title .= ' | ' . JText::sprintf('COM_COSTBENEFITPROJECTION_COVERAGE_S', round($intervention->coverage)). '%';

			$options =	array( 'title' => $main_title, 'colors' => array('#cc0000', '#ff9933', '#0070c0', '#70ad47'), 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => 800, 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));

			echo $chart->draw('public_int_'.$intervention_number.'_unscaled', $options);
			$intervention_number++;
		}
		else
		{
			$no_intervention[] = $intervention->name;
		}
	}
}

?>
<div id="view_icb">
    <div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_BENEFIT'); ?></h1>
        <?php if (isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions)) : ?>
		<div class="uk-grid">
		    <div class="uk-width-medium-1-1">
			<div class="uk-panel uk-animation-slide-left" style="z-index: -1;"><p class="uk-text-large uk-text-center">Select Workplace Health and Wellness Interventions and see the projected annual benefit on the workplace.</p></div>
		    </div>
		</div>
		<div class="uk-grid">
			<div class="uk-width-medium-2-10 uk-push-8-10">
			<div class="uk-panel uk-animation-slide-right">
			    <p class="uk-text-small" style="z-index: -1;">Intervention Options</p>
			    <ul id="int_select" data-uk-tab="{connect:'#tab-left-intervention'}" class="uk-tab uk-tab-right">
				<?php $i = 0; foreach ($this->results->interventions as $intervention) :?>
				<?php if(isset($intervention->items)) : ?>
				    <?php if ($i == 0) : ?>
				        <li class="uk-active"><a class="uk-icon-check-square-o" href="#"> <?php echo $intervention->name; ?></a></li>
				    <?php else: ?>
				        <li><a class="uk-icon-square-o" href="#"> <?php echo $intervention->name; ?></a></li>
				    <?php endif; ?>
				    <?php $i++; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			    </ul>
			 </div>

		    </div>
		    <div class="uk-width-medium-8-10 uk-pull-2-10 uk-animation-slide-left">

			<ul class="uk-switcher" id="tab-left-intervention">
			    <!-- This is the container of the content items -->
			    <?php $intervention_number = 0; foreach ($this->results->interventions as $intervention) :?>
			    <?php if(isset($intervention->items)) : ?>
				<?php if ($intervention_number == 0) : ?>
				    <li class="uk-active">
				<?php else: ?>
				    <li>
				<?php endif; ?>
					<p class="uk-text-success uk-text-center">Potential Benefit of Workplace Health and Wellness Interventions</p>
				    	<div id="public_int_<?php echo $intervention_number; ?>_unscaled" class="chart" style="height:100%; width:100%;"></div>
				    </li>
				<?php $intervention_number++; ?>
			    <?php endif; ?>
			    <?php endforeach; ?>
			</ul>
	
		    </div>
		</div>
		<div class="uk-grid uk-animation-slide-right">    
		    <div class="uk-width-medium-1-1 ">
		    	<div class="uk-panel">
				<p class="uk-text-info uk-text-center">Having seen the health priorities for your workforce, the tool outputs projections for how the interventions – which <b>you have designed</b>  - are expected to benefit the company financially.</p>
				<p class="uk-text-info uk-text-center">Benefits are calculated based on the model projecting reductions in workdays lost due to sickness, presenteeism and death.</p>
			</div>
		    </div>   
		</div>
        <?php else: ?>
            <div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SET_BY_THE_COUNTRY'); ?></div>
	<?php endif; ?>
    </div>   
</div>
<br /><br />
