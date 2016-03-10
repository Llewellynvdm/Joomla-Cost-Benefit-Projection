<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_chart_cost_public.php
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
// check if items are set
if(isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)){
	$i =0;
	$rowArray = array();
	foreach ($this->results->items as $key => &$item){
		$rowArray[] = array('c' => array(
			array('v' => $item->details->name), 
			array('v' => $item->{'subtotal_cost_unscaled'}, 'f' => $item->{'subtotal_costmoney_unscaled'})
		));
		$i++;
	}

	usort($rowArray, function($b, $a) {
		return $a['c'][1]['v'] - $b['c'][1]['v'];
	});

	$data = array(
		'cols' => array(
			array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK_FACTOR_NAME'), 'type' => 'string'),
			array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_TOTAL_COST'), 'type' => 'number')
		),
		'rows' => $rowArray
	);

	$height = ($i * 55)+10;
	$title = JText::sprintf('COM_COSTBENEFITPROJECTION_COST_IN_S', $this->item->currency_name); 
	$chart->load(json_encode($data));
	$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
	echo $chart->draw('c_public', $options);
}

?>
<div id="view_c">
	<div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
	<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST'); ?></h1>
	<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
		<!-- This is the container of the content items -->
		<div class="uk-grid">   
			<!-- This is the container of the content items -->
			<div class="uk-width-medium-3-10">
				<?php if ($this->getModules('publicCostVideo')): ?>
					<div class="uk-text-large uk-text-muted uk-animation-slide-left"><i class="uk-icon-lightbulb-o"></i> NOTE <a  href="#video" data-uk-modal>[ help video! ]</a></div>
				<?php endif; ?>
				<div class="uk-panel uk-panel-box uk-animation-slide-left" style="z-index: -1;">
					<?php if ($this->getModules('publicCostNote')): ?>
						<?php echo $this->getModules('publicCostNote'); ?>
					<?php else: ?>
						<p class="uk-text-warning uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PLEASE_PUBLISH_A_MODULE_TO_SPAN_CLASS_UKTEXTBOLD_PUBLICCOSTNOTE_SPAN_POSITION_OF_THIS_PAGE_WITH_YOUR_PUBLIC_NOTICE_TEXT'); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<div class="uk-width-medium-7-10 uk-animation-slide-right">
				<div id="c_public" class="unscaled" style="display: table"></div>
			</div>
			<?php if ($this->getModules('publicCostVideo')): ?>
				<!-- This is the modal -->
				<div id="video" class="uk-modal">
					<div class="uk-modal-dialog">
						<a class="uk-modal-close uk-close"></a>
						<?php echo $this->getModules('publicCostVideo'); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_CAUSERISK_WERE_SET_BY_THE_COUNTRY'); ?></div>
	<?php endif; ?>
	</div>
</div>
