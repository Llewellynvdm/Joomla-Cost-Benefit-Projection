<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companies_fullwidth.php
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
$items	= $displayData->vwhcompanies;
$user	= JFactory::getUser();
$id	= $displayData->item->id;
$edit	= "index.php?option=com_costbenefitprojection&view=companies&task=company.edit";
$ref	= ($id) ? "&ref=country&refid=".$id : "";
$new	= "index.php?option=com_costbenefitprojection&view=company&layout=edit".$ref;
$can	= CostbenefitprojectionHelper::getActions('company');

?>
<div class="form-vertical">
<?php if ($can->get('company.create')): ?>
	<a class="btn btn-small btn-success" href="<?php echo $new; ?>"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_COSTBENEFITPROJECTION_NEW'); ?></a><br /><br />
<?php endif; ?>
<?php if (CostbenefitprojectionHelper::checkArray($items)): ?>
<table class="footable table data companies metro-blue" data-filter="#filter_companies" data-page-size="20">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_NAME_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_USER_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_DEPARTMENT_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_COUNTRY_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_SERVICE_PROVIDER_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_PER_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = CostbenefitprojectionHelper::getActions('company',$item,'companies');
	?>
	<tr>
		<td class="nowrap">
			<?php if ($canDo->get('company.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>&ref=country&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'companies.', $canCheckin); ?>
					<?php endif; ?>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->name); ?></div>
			<?php endif; ?>
			<div class="btn-group">
			<?php if ($canDo->get('companyresults.access')): ?>
				<a class="hasTooltip btn btn-mini" href="index.php?option=com_costbenefitprojection&view=companyresults&id=<?php echo $item->id; ?>&ref=country&refid=<?php echo $id; ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>" ><span class="icon-chart"></span></a>
			<?php else: ?>
				<a class="hasTooltip btn btn-mini disabled" href="#" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>"><span class="icon-chart"></span></a>
			<?php endif; ?>
			</div>
		</td>
		<?php $_user = JFactory::getUser($item->user); ?>
		<td class="nowrap">
			<?php if ($user->authorise('core.edit', 'com_users')): ?>
				<a href="index.php?option=com_users&task=user.edit&id=<?php echo (int) $item->user ?>"><?php echo $_user->name; ?></a>
			<?php else: ?>
				<?php echo $_user->name; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JText::_($item->department); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->country_name); ?>
		</td>
		<td class="nowrap">
			<?php if ($user->authorise('service_provider.edit', 'com_costbenefitprojection.service_provider.' . (int)$item->service_provider)): ?>
				<a href="index.php?option=com_costbenefitprojection&view=service_providers&task=service_provider.edit&id=<?php echo $item->service_provider; ?>&ref=country&refid=<?php echo $id; ?>"><?php echo JFactory::getUser((int)$item->service_provider_user)->name; ?></a>
			<?php else: ?>
				<div class="name"><?php echo JFactory::getUser((int)$item->service_provider_user)->name; ?></div>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JText::_($item->per); ?>
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
		<td colspan="8">
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
