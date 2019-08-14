<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
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
$items = $displayData->vwecompanies;
$user = JFactory::getUser();
$id = $displayData->item->id;
// set the edit URL
$edit = "index.php?option=com_costbenefitprojection&view=companies&task=company.edit";
// set a return value
$return = ($id) ? "index.php?option=com_costbenefitprojection&view=service_provider&layout=edit&id=" . $id : "";
// check for a return value
$jinput = JFactory::getApplication()->input;
if ($_return = $jinput->get('return', null, 'base64'))
{
	$return .= "&return=" . $_return;
}
// check if return value was set
if (CostbenefitprojectionHelper::checkString($return))
{
	// set the referral values
	$ref = ($id) ? "&ref=service_provider&refid=" . $id . "&return=" . urlencode(base64_encode($return)) : "&return=" . urlencode(base64_encode($return));
}
else
{
	$ref = ($id) ? "&ref=service_provider&refid=" . $id : "";
}
// set the create new URL
$new = "index.php?option=com_costbenefitprojection&view=companies&task=company.edit" . $ref;
// load the action object
$can = CostbenefitprojectionHelper::getActions('company');

?>
<div class="form-vertical">
<?php if ($can->get('company.create')): ?>
	<a class="btn btn-small btn-success" href="<?php echo $new; ?>"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_COSTBENEFITPROJECTION_NEW'); ?></a><br /><br />
<?php endif; ?>
<?php if (CostbenefitprojectionHelper::checkArray($items)): ?>
<table class="footable table data companies metro-blue" data-page-size="20" data-filter="#filter_companies">
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
		<td>
			<?php if ($canDo->get('company.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->name); ?></a>
				<?php if ($item->checked_out): ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'companies.', $canCheckin); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php echo $displayData->escape($item->name); ?>
			<?php endif; ?>
			<div class="btn-group">
			<?php if ($canDo->get('companyresults.access')): ?>
				<a class="hasTooltip btn btn-mini" href="index.php?option=com_costbenefitprojection&view=companyresults&id=<?php echo $item->id; ?><?php echo $ref; ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>" ><span class="icon-chart"></span></a>
			<?php else: ?>
				<a class="hasTooltip btn btn-mini disabled" href="#" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>"><span class="icon-chart"></span></a>
			<?php endif; ?>
			</div>
		</td>
		<td>
			<?php if ($user->authorise('core.edit', 'com_users')): ?>
				<a href="index.php?option=com_users&task=user.edit&id=<?php echo (int) $item->user ?>"><?php echo JFactory::getUser((int)$item->user)->name; ?></a>
			<?php else: ?>
				<?php echo JFactory::getUser((int)$item->user)->name; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JText::_($item->department); ?>
		</td>
		<td>
			<?php if ($user->authorise('country.edit', 'com_costbenefitprojection.country.' . (int)$item->country)): ?>
				<a href="index.php?option=com_costbenefitprojection&view=countries&task=country.edit&id=<?php echo $item->country; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->country_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->country_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JFactory::getUser((int)$item->service_provider_user)->name; ?>
		</td>
		<td>
			<?php echo JText::_($item->per); ?>
		</td>
		<?php if ($item->published == 1):?>
			<td class="center"  data-value="1">
				<span class="status-metro status-published" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?>">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 0):?>
			<td class="center"  data-value="2">
				<span class="status-metro status-inactive" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?>">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 2):?>
			<td class="center"  data-value="3">
				<span class="status-metro status-archived" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?>">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == -2):?>
			<td class="center"  data-value="4">
				<span class="status-metro status-trashed" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?>">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED'); ?>
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
