<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_chart_intervention_cost_benefit.php
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

$scaled = array('unscaled','scaled');

if(isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions)){
	$intervention_number = 0;
	foreach ($this->results->interventions as $intervention){
		if (isset($intervention->items))
		{
			foreach ($scaled as $scale){
			$i =0;
			$rowArray = array();
			if(is_array($intervention->items) || is_object($intervention->items)){
				foreach ($intervention->items as $key => &$item){
					$rowArray[] = array('c' => array(
							array('v' => $item->name),
							array('v' => round($item->{'cost_of_problem_'.$scale}), 'f' => $item->{'costmoney_of_problem_'.$scale}), 
							array('v' => $item->annual_cost, 'f' => $item->annual_costmoney), 
							array('v' => $item->{'annual_benefit_'.$scale}, 'f' => $item->{'annualmoney_benefit_'.$scale}), 
							array('v' => $item->{'net_benefit_'.$scale}, 'f' => $item->{'netmoney_benefit_'.$scale})
					));
					$i++;
				}
			}
			usort($rowArray, function($b, $a) {
				return $a['c'][4]['v'] - $b['c'][4]['v'];
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

			$options =	array( 'title' => $main_title, 'colors' => array('#cc0000', '#ff9933', '#0070c0', '#70ad47'), 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));

			echo $chart->draw('icb_'.$intervention_number.'_'.$scale, $options);
			$intervention_number++;
			}
		}
	}
}

?>
<div id="view_icb">
    <div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_BENEFIT'); ?></h1>
        <?php if (isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions)) : ?>
		<?php $intervention_number = 0; ?>
		<?php foreach ($this->results->interventions as $intervention): ?>
			<?php if (isset($intervention->items)): ?>
				<?php foreach ($scaled as $scale) :?>
				    <div id="icb_<?php echo $intervention_number; ?>_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'box' : 'none'; ?>;"></div>
				    <?php $intervention_number++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
        <?php else: ?>
            <div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></div>
	<?php endif; ?>
    </div>   
</div>
