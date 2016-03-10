<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_usage_statistics_table.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

?>
<?php if (isset($this->usagedata->items)): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th class="nowrap" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_NAME'); ?></th>
			<?php foreach($this->usagedata->items as $items): ?>
			<?php foreach ($items as $key => $value): ?>
			<?php if ('name' == $key) { break; } elseif (strpos($key,'employees') !== false){ continue; } ?>
			<th class="nowrap center" width="16%"><?php echo CostbenefitprojectionHelper::safeString($key, 'Ww'); ?></th>
			<?php endforeach; ?>
			<?php break;?>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->usagedata->items as $nr => $items): ?>
		<tr>
			<th class="nowrap"><?php echo $items['name']; unset($items['name']); ?></th>
			<?php foreach($items as $key => $value): ?>
			<?php if ('name' == $key) { break; } elseif (strpos($key,'employees') !== false){ continue; } ?>
			<td class="nowrap center"><?php echo (int) $value; ?> <small><em><span style="cursor: help;" class="hasTip" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_EMPLOYEESBR_IN_RELATION_TO_THIS_NUMBER'); ?>">(<?php echo (int) $items[$key.'_employees']; ?>)</span></em></small></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<?php if (count($this->usagedata->items) > 1): ?>
	<tfoot>
		<tr>
			<th class="nowrap"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL'); ?></th>
			<?php foreach($this->usagedata->items as $items): ?>
			<?php foreach ($items as $key => $value): ?>
			<?php if ('name' == $key) { break; } elseif (strpos($key,'employees') !== false){ continue; } ?>
			<th class="nowrap center"><?php echo (int) $this->usagedata->total[$key]; ?> <small><em><span style="cursor: help;" class="hasTip" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_EMPLOYEESBR_IN_RELATION_TO_THIS_TOTAL'); ?>">(<?php echo (int) $this->usagedata->total[$key.'_employees']; ?>)</span></em></small></th>
			<?php endforeach; ?>
			<?php break;?>
			<?php endforeach; ?>
		</tr>
	</tfoot>
	<?php endif; ?>
</table>
<small>* <?php echo JText::_('COM_COSTBENEFITPROJECTION_ALL_NUMBERS_IN_BRACKETS_ARE_THE_RELATED_EMPLOYEES'); ?></small>
<?php else: ?>
	<div class="alert alert-warning">
		<h4 class="alert-heading"><?php echo JText::_('COM_COSTBENEFITPROJECTION_WARNING'); ?></h4>
		<p class="alert-message"><?php echo JText::_('COM_COSTBENEFITPROJECTION_THE_STATISTICAL_DATA_COULD_NOT_BE_RETURNED_PLEASE_CONTACT_YOUR_SYSTEM_ADMINISTRATOR'); ?></p>
	</div>
<?php endif; ?>
