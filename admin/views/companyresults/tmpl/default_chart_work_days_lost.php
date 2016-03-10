<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_chart_work_days_lost.php
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
// set scaled array
$scaled = array('unscaled','scaled');
// check if items are set
if(isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)){
	foreach ($scaled as $scale){
			$i =0;
			$rowArray = array();
			foreach ($this->results->items as $key => &$item){
				$rowArray[$i] = array('c' => array(
								array('v' => $item->details->name),
								array('v' => (float)round($item->{'subtotal_morbidity_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_presenteeism_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_days_lost_mortality_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_days_lost_'.$scale}, 2))
						));
				$i++;
			}
			
			usort($rowArray, function($b, $a) {
				return $a['c'][4]['v'] - $b['c'][4]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK_FACTOR'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_MORBIDITY'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_PRESENTEEISM_MORBIDITY'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_MORTALITY'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_TOTAL_DAYS_LOST'), 'type' => 'number')
					),
					'rows' => $rowArray
			);
			
			$height = ($i * 110)+50;
			$chart->load(json_encode($data));
			$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_NUMBER_OF_WORK_DAYS_LOST'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		
		echo $chart->draw('wdl_'.$scale, $options);
	}
}

?>
<div id="view_wdl">
	<div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
		<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST'); ?></h1>
		<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
			<?php foreach ($scaled as $scale) :?>
				<div id="wdl_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'box' : 'none'; ?>;"></div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="uk-alert  uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_CAUSERISK_SELECTED'); ?></div>
		<?php endif; ?>
	</div>
</div>
