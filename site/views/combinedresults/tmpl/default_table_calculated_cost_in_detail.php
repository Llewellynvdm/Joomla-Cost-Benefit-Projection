<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			23rd December, 2015
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_table_calculated_cost_in_detail.php
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
<div id="view_ccid">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CALCULATED_COSTS_IN_DETAIL'); ?></h1>

<?php echo JText::_('COM_COSTBENEFITPROJECTION_COSTS_DUE_TO_PRESENTEEISM_LOST_PRODUCTIVITY_DUE_TO_SICKNESSBRDISEASE_COSTS_DUE_TO_PRESENTEEISM'); ?>
<?php if (isset($this->results->items) && CostbenefitprojectionHelper::checkObject($this->results->items)) : ?>
	<?php foreach ($scaled as $scale): ?>
		<table id="theTableCCID_<?php echo $scale; ?>" class="table data metro-blue <?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" data-page-size="50">
			<thead>        
				<tr >
					<th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DISEASERISK_FACTOR'); ?></th>
					<th width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COSTS_MALE_EMPLOYEES'); ?></th>
					<th width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COSTS_FEMALE_EMPLOYEES'); ?></th>
					<th width="25%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_COSTS'); ?></th>
				</tr>        
			</thead>                                    
			<tbody>
			<?php foreach ($this->results->items as $i => &$item): ?>
				<tr>
					<th data-value='<?php echo $item->details->alias; ?>' scope="row"><?php echo $item->details->name; ?></th>
					<td data-value='<?php echo $item->{'male_cost_'.$scale}; ?>' ><?php echo $item->{'male_costmoney_'.$scale}; ?></td>
					<td data-value='<?php echo $item->{'female_cost_'.$scale}; ?>' ><?php echo $item->{'female_costmoney_'.$scale}; ?></td>
					<td data-value='<?php echo $item->{'subtotal_cost_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_'.$scale}; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row"><?php echo JText::_('TOTALS'); ?></th>
					<td><?php echo $this->results->totals->{'males_costmoney_'.$scale}; ?></td>
					<td><?php echo $this->results->totals->{'females_costmoney_'.$scale}; ?></td>
					<td><?php echo $this->results->totals->{'total_costmoney_'.$scale}; ?></td>
				</tr>
			</tfoot>                                
		</table>
	<?php endforeach; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning alert alert-warning"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASERISK_SELECTED'); ?></div>
<?php endif; ?>
</div>
