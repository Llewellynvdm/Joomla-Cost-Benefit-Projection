<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_table_intervention_net_benefit.php
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
<div id="view_inb">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NET_BENEFIT'); ?></h1>
<?php if(isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions)): ?>
	 <?php foreach ($this->results->interventions as $intervention) :?>
		<?php if (isset($intervention->items)): ?>
			<?php echo JText::sprintf("COM_COSTBENEFITPROJECTION_INTERVENTIONS_NAME_S", $intervention->name); ?> | 
			<?php 
				if($intervention->duration > 1){
					echo JText::sprintf("COM_COSTBENEFITPROJECTION_DURATION_S_YEARS", $intervention->duration); 
				} else {
					echo JText::sprintf('COM_COSTBENEFITPROJECTION_DURATION_S_YEAR', $intervention->duration);
				} 
			?> | 
			<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_COVERAGE_S', round($intervention->coverage)); ?>%

			<?php foreach ($scaled as $scale): ?>
				<table  id="tableINT_<?php echo $intervention->id ?>_<?php echo $scale; ?>"  
					class="footable table data metro-blue <?php echo $scale; ?>" 
					style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" 
					data-page-size="50" >
				    <thead>
					<tr >
					    <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK_FACTOR'); ?></th>
					    <th width="8%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CONTRIBUTION_TO_COSTS'); ?></th>
					    <th width="8%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
					    <th width="5%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_REDUCTION_IN_MORBIDITY_COSTS'); ?></th>
					    <th width="5%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_REDUCTION_IN_MORTALITY_COST'); ?></th>
					    <th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_OF_PROBLEM'); ?></th>
					    <th width="11%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_COST_OF_INTERVENTION'); ?></th>
					    <th width="11%" data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_BENEFIT'); ?></th>
					    <th width="5%" data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_BENEFIT_RATIO'); ?></th>
					    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NET_BENEFIT'); ?></th>
					</tr>
				    </thead>                                    
				    <tbody>
				    <?php if(isset($intervention->items) && is_object($intervention->items) || is_array($intervention->items)):?>
					<?php foreach ($intervention->items as $key => &$item): ?>
					    <tr>
						<th data-value='<?php echo $item->name; ?>' scope="row"><?php echo $item->name; ?></th>
						<td data-value='<?php echo $item->{'contribution_to_cost_'.$scale}; ?>' ><?php echo round($item->{'contribution_to_cost_'.$scale}, 3); ?>%</td>
						<td data-value='<?php echo $item->cpe; ?>' ><?php echo $item->annual_costmoney_per_employee; ?></td>
						<td data-value='<?php echo $item->mbr; ?>' ><?php echo $item->mbr; ?>%</td>
						<td data-value='<?php echo $item->mtr; ?>' ><?php echo $item->mtr; ?>%</td>
						<td data-value='<?php echo $item->{'cost_of_problem_'.$scale}; ?>' ><?php echo $item->{'costmoney_of_problem_'.$scale}; ?></td>
						<td data-value='<?php echo $item->annual_cost; ?>' ><?php echo $item->annual_costmoney; ?></td>
						<td data-value='<?php echo $item->{'annual_benefit_'.$scale}; ?>' ><?php echo $item->{'annualmoney_benefit_'.$scale}; ?></td>
						<td data-value='<?php echo $item->{'benefit_ratio_'.$scale}; ?>' >1:<?php echo round($item->{'benefit_ratio_'.$scale},3); ?></td>
						<td data-value='<?php echo $item->{'net_benefit_'.$scale}; ?>' ><?php echo $item->{'netmoney_benefit_'.$scale}; ?></td>
					    </tr>
					<?php endforeach; ?>
				    <?php endif; ?>                                       
				    </tbody>
				    <tfoot>
					<tr>
					    <th scope="row"><?php echo JText::_('TOTALS'); ?></th>
					    <td><?php echo $intervention->totals->{'contribution_to_cost_'.$scale}; ?>%</td>
					    <td><?php echo $intervention->totals->annual_costmoney_per_employee; ?></td>
					    <td></td>
					    <td></td>
					    <td><?php echo $intervention->totals->{'costmoney_of_problem_'.$scale}; ?></td>
					    <td><?php echo $intervention->totals->annual_costmoney; ?></td>
					    <td><?php echo $intervention->totals->{'annualmoney_benefit_'.$scale}; ?></td>
					    <td></td>
					    <td><?php echo $intervention->totals->{'netmoney_benefit_'.$scale}; ?></td>
					</tr>
				    </tfoot>                                
				</table>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php else: ?> 
	<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></div>
<?php endif; ?>
</div>
