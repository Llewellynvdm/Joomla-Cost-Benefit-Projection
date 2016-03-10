<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		interventions_fullwidth.php
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
$items	= $displayData->vwfinterventions;
$user	= JFactory::getUser();
$id	= $displayData->item->id;
$edit	= "index.php?option=com_costbenefitprojection&view=interventions&task=intervention.edit";
$ref	= ($id) ? "&ref=country&refid=".$id : "";
$new	= "index.php?option=com_costbenefitprojection&view=intervention&layout=edit".$ref;
$can	= CostbenefitprojectionHelper::getActions('intervention');

?>
<div class="form-vertical">
<?php if ($can->get('intervention.create')): ?>
	<a class="btn btn-small btn-success" href="<?php echo $new; ?>"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_COSTBENEFITPROJECTION_NEW'); ?></a><br /><br />
<?php endif; ?>
<?php if (CostbenefitprojectionHelper::checkArray($items)): ?>
<table class="footable table data interventions metro-blue" data-filter="#filter_interventions" data-page-size="20">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NAME_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COMPANY_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_TYPE_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DESCRIPTION_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = CostbenefitprojectionHelper::getActions('intervention',$item,'interventions');
	?>
	<tr>
		<td class="nowrap">
			<?php if ($canDo->get('intervention.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>&ref=country&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'interventions.', $canCheckin); ?>
					<?php endif; ?>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->name); ?></div>
			<?php endif; ?>
		</td>
		<td class="nowrap">
			<?php if ($user->authorise('company.edit', 'com_costbenefitprojection.company.' . (int)$item->company)): ?>
				<a href="index.php?option=com_costbenefitprojection&view=companies&task=company.edit&id=<?php echo $item->company; ?>&ref=country&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->company_name); ?></a>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->company_name); ?></div>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JText::_($item->type); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->coverage); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->description); ?>
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
		<td colspan="7">
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
