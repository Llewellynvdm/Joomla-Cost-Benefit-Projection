<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		scaling_factors_fullwidth.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

// set the defaults
$items	= $displayData->vwcscaling_factors;
$user	= JFactory::getUser();
$id	= $displayData->item->id;
$edit	= "index.php?option=com_costbenefitprojection&view=scaling_factors&task=scaling_factor.edit";

?>
<div class="form-vertical">
<?php if (CostbenefitprojectionHelper::checkArray($items)): ?>
<table class="footable table data scaling_factors metro-blue" data-filter="#filter_scaling_factors" data-page-size="20">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_CAUSERISK_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_COMPANY_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_YLD_SCALING_FACTOR_MALES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_YLD_SCALING_FACTOR_FEMALES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_MORTALITY_SCALING_FACTOR_MALES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_MORTALITY_SCALING_FACTOR_FEMALES_LABEL'); ?>
		</th>
		<th data-hide="all">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_PRESENTEEISM_SCALING_FACTOR_MALES_LABEL'); ?>
		</th>
		<th data-hide="all">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_PRESENTEEISM_SCALING_FACTOR_FEMALES_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTOR_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = CostbenefitprojectionHelper::getActions('scaling_factor',$item,'scaling_factors');
	?>
	<tr>
		<td class="nowrap">
			<?php if ($canDo->get('scaling_factor.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>&ref=company&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->causerisk_name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'scaling_factors.', $canCheckin); ?>
					<?php endif; ?>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->causerisk_name); ?></div>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->company_name); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->yld_scaling_factor_males); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->yld_scaling_factor_females); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->mortality_scaling_factor_males); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->mortality_scaling_factor_females); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->presenteeism_scaling_factor_males); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->presenteeism_scaling_factor_females); ?>
		</td>
		<?php if ($item->published == 1):?>
			<td class="center"  data-value="1">
				<span class="status-metro status-published" title="<?php echo JText::_('PUBLISHED');  ?>">
					<?php echo JText::_('PUBLISHED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 0):?>
			<td class="center"  data-value="2">
				<span class="status-metro status-inactive" title="<?php echo JText::_('INACTIVE');  ?>">
					<?php echo JText::_('INACTIVE'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 2):?>
			<td class="center"  data-value="3">
				<span class="status-metro status-archived" title="<?php echo JText::_('ARCHIVED');  ?>">
					<?php echo JText::_('ARCHIVED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == -2):?>
			<td class="center"  data-value="4">
				<span class="status-metro status-trashed" title="<?php echo JText::_('ARCHIVED');  ?>">
					<?php echo JText::_('ARCHIVED'); ?>
				</span>
			</td>
		<?php endif; ?>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
<tfoot class="hide-if-no-paging">
	<tr>
		<td colspan="10">
			<div class="pagination pagination-centered"></div>
		</td>
	</tr>
</tfoot>
</table>
<?php else: ?>
	<div class="alert alert-no-items">
		<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php endif; ?>
</div>
