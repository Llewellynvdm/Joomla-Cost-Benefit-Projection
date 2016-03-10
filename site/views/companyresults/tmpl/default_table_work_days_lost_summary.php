<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_table_work_days_lost_summary.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

$scaled = array('unscaled','scaled');

?>
<div id="view_wdls">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST_SUMMARY'); ?></h1>

<?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_DAYS_LOST_AND_CONTRIBUTION_OF_MORBIDITY_MORTALITY_AND_RISK_FACTORS'); ?>
<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
	<?php foreach ($scaled as $scale): ?>
		<table id="theTableWDLS_<?php echo $scale; ?>" class="footable table data metro-blue <?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" data-page-size="50">
			<thead>        
				<tr >
					<th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DISEASERISK_FACTOR'); ?></th>
					<th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DAYS_LOST_MORBIDITY'); ?></th>
					<th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DAYS_LOST_PRESENTEEISM_DUE_TO_MORBIDITY'); ?></th>
					<th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DAYS_LOST_MORTALITY'); ?></th>
					<th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DAYS_LOST_MALE_EMPLOYEES'); ?></th>
					<th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DAYS_LOST_FEMALE_EMPLOYEES'); ?></th>
					<th width="11%" data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_DAYS_LOST_PER_DISEASERISK_FACTOR'); ?></th>
					<th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PERCENT_OF_TOTAL_DAYS_LOST'); ?></th>
					<th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PERCENT_OF_ESTIMATED_BURDEN'); ?></th>
					<th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></th>
				</tr>        
			</thead>                                    
			<tbody>
			<?php foreach ($this->results->items as $i => &$item): ?>
				<tr>
					<th data-value='<?php echo $item->details->alias; ?>' scope="row"><?php echo $item->details->name; ?></th>
					<td data-value='<?php echo $item->{'subtotal_morbidity_'.$scale}; ?>' ><?php echo round($item->{'subtotal_morbidity_'.$scale},3); ?></td>
					<td data-value='<?php echo $item->{'subtotal_presenteeism_'.$scale}; ?>' ><?php echo round($item->{'subtotal_presenteeism_'.$scale},3); ?></td>
					<td data-value='<?php echo $item->{'subtotal_days_lost_mortality_'.$scale}; ?>' ><?php echo round($item->{'subtotal_days_lost_mortality_'.$scale},3); ?></td>
					<td data-value='<?php echo $item->{'male_days_lost_'.$scale}; ?>' ><?php echo round($item->{'male_days_lost_'.$scale},3); ?></td>
					<td data-value='<?php echo $item->{'female_days_lost_'.$scale}; ?>' ><?php echo round($item->{'female_days_lost_'.$scale},3); ?></td>
					<td data-value='<?php echo $item->{'subtotal_days_lost_'.$scale}; ?>' ><?php echo round($item->{'subtotal_days_lost_'.$scale},3); ?></td>
					<td data-value='<?php echo ($item->{'subtotal_days_lost_'.$scale} / $this->results->totals->{'total_days_lost_'.$scale})*100; ?>' ><?php echo round(($item->{'subtotal_days_lost_'.$scale} / $this->results->totals->{'total_days_lost_'.$scale})*100,3).'%'; ?></td>
					<td data-value='<?php echo $item->subtotal_estimated_burden; ?>' ><?php echo round($item->subtotal_estimated_burden,3).'%'; ?></td>
					<td data-value='0' ><?php $item->_tmpType = 'day'; $item->_tmpScale = $scale; echo JLayoutHelper::render('databreakdownmalefemale', $item); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row"><?php echo JText::_('TOTALS'); ?></th>
					<td><?php echo round($this->results->totals->{'total_morbidity_'.$scale},3); ?></td>
					<td><?php echo round($this->results->totals->{'total_presenteeism_'.$scale},3); ?></td>
					<td><?php echo round($this->results->totals->{'total_days_lost_mortality_'.$scale},3); ?></td>
					<td><?php echo round($this->results->totals->{'males_days_lost_'.$scale},3); ?></td>
					<td><?php echo round($this->results->totals->{'females_days_lost_'.$scale},3); ?></td>
					<td><?php echo round($this->results->totals->{'total_days_lost_'.$scale},3); ?></td>
					<td><?php echo round(($this->results->totals->{'total_days_lost_'.$scale} / $this->results->totals->{'total_days_lost_'.$scale})*100,3).'%'; ?></td>
					<td><?php echo round($this->results->totals->total_estimated_burden,3).'%'; ?></td>
					<td>&nbsp;&nbsp;</td>
				</tr>
			</tfoot>                                
		</table>
	<?php endforeach; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASERISK_SELECTED'); ?></div>
<?php endif; ?>
</div>
