<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_table_cost_summary.php
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
<div id="view_cs">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_SUMMARY'); ?></h1>
<?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_COSTS_AND_CONTRIBUTION_OF_MORBIDITY_MORTALITY_AND_RISK_FACTORS'); ?>
<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
	<?php foreach ($scaled as $scale): ?>
		<table id="theTableCS_<?php echo $scale; ?>" class="footable table data metro-blue <?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" data-page-size="50">
			<thead>        
				<tr >
				    <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DISEASERISK_FACTOR'); ?></th>
				    <th width="13%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_MORBIDITY'); ?></th>
				    <th width="13%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_PRESENTEEISM_DUE_TO_MORBIDITY'); ?></th>
				    <th width="13%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_MORTALITY'); ?></th>
				    <th width="13%" data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_COST_PER_DISEASERISK_FACTOR'); ?></th>
				    <th width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PERCENT_OF_TOTAL_COST'); ?></th>
				</tr>        
			</thead>                                    
			<tbody>
			<?php foreach ($this->results->items as $i => &$item): ?>
				<tr>
				    <th data-value='<?php echo $item->details->alias; ?>' scope="row"><?php echo $item->details->name; ?></th>
				    <td data-value='<?php echo $item->{'subtotal_cost_morbidity_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_morbidity_'.$scale}; ?></td>
				    <td data-value='<?php echo $item->{'subtotal_cost_presenteeism_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_presenteeism_'.$scale}; ?></td>
				    <td data-value='<?php echo $item->{'subtotal_cost_mortality_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_mortality_'.$scale}; ?></td>
				    <td data-value='<?php echo $item->{'subtotal_cost_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_'.$scale}; ?></td>
				    <td data-value='<?php echo ($item->{'subtotal_cost_'.$scale} / $this->results->totals->{'total_cost_'.$scale})*100; ?>' ><?php echo round(($item->{'subtotal_cost_'.$scale} / $this->results->totals->{'total_cost_'.$scale})*100,3).'%'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
				    <th scope="row"><?php echo JText::_('TOTALS'); ?></th>

				    <td><?php echo $this->results->totals->{'total_costmoney_morbidity_'.$scale}; ?></td>
				    <td><?php echo $this->results->totals->{'total_costmoney_presenteeism_'.$scale}; ?></td>
				    <td><?php echo $this->results->totals->{'total_costmoney_mortality_'.$scale}; ?></td>
				    <td><?php echo $this->results->totals->{'total_costmoney_'.$scale}; ?></td>
				    <td><?php echo round(($this->results->totals->{'total_cost_'.$scale} / $this->results->totals->{'total_cost_'.$scale})*100,3).'%'; ?></td>
				</tr>
			</tfoot>                                
		</table>
	<?php endforeach; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASERISK_SELECTED'); ?></div>
<?php endif; ?>
</div>
