<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_chart_cost.php
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
			$rowArray[] = array('c' => array(
				array('v' => $item->details->name), 
				array('v' => $item->{'subtotal_cost_morbidity_'.$scale}, 'f' => $item->{'subtotal_costmoney_morbidity_'.$scale}), 
				array('v' => $item->{'subtotal_cost_presenteeism_'.$scale}, 'f' => $item->{'subtotal_costmoney_presenteeism_'.$scale}), 
				array('v' => $item->{'subtotal_cost_mortality_'.$scale}, 'f' => $item->{'subtotal_costmoney_mortality_'.$scale}), 
				array('v' => $item->{'subtotal_cost_'.$scale}, 'f' => $item->{'subtotal_costmoney_'.$scale})
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
				array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_TOTAL_COST'), 'type' => 'number')
			),
			'rows' => $rowArray
		);
		
		$height = ($i * 110)+10;
		$title = JText::sprintf('COM_COSTBENEFITPROJECTION_COST_IN_S', $this->item->currency_name); 
		$chart->load(json_encode($data));
		$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		echo $chart->draw('c_'.$scale, $options);
	}
}

?>
<div id="view_c">
	<div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
	<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST'); ?></h1>

	<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
		<?php foreach ($scaled as $scale) :?>
			<div id="c_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;"></div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_CAUSERISK_SELECTED'); ?></div>
	<?php endif; ?>
	</div>
</div>
