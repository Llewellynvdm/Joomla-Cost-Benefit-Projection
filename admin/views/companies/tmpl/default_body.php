<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.5.x
	@build			27th May, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_body.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$edit = "index.php?option=com_costbenefitprojection&view=companies&task=company.edit";

?>
<?php foreach ($this->items as $i => $item): ?>
	<?php
		$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = CostbenefitprojectionHelper::getActions('company',$item,'companies');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="order nowrap center hidden-phone">
		<?php if ($canDo->get('company.edit.state')): ?>
			<?php
				$iconClass = '';
				if (!$this->saveOrder)
				{
					$iconClass = ' inactive tip-top" hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
				}
			?>
			<span class="sortable-handler<?php echo $iconClass; ?>">
				<i class="icon-menu"></i>
			</span>
			<?php if ($this->saveOrder) : ?>
				<input type="text" style="display:none" name="order[]" size="5"
				value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
			<?php endif; ?>
		<?php else: ?>
			&#8942;
		<?php endif; ?>
		</td>
		<td class="nowrap center">
		<?php if ($canDo->get('company.edit')): ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<?php else: ?>
						&#9633;
					<?php endif; ?>
				<?php else: ?>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				<?php endif; ?>
		<?php else: ?>
			&#9633;
		<?php endif; ?>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if ($canDo->get('company.edit')): ?>
					<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>"><?php echo $this->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'companies.', $canCheckin); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
			</div>
			<div class="btn-group">
			<?php if ($canDo->get('companyresults.access')): ?>
				<a class="hasTooltip btn btn-mini" href="index.php?option=com_costbenefitprojection&view=companyresults&id=<?php echo $item->id; ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>" ><span class="icon-chart"></span></a>
			<?php else: ?>
				<a class="hasTooltip btn btn-mini disabled" href="#" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANYRESULTS'); ?>"><span class="icon-chart"></span></a>
			<?php endif; ?>
			</div>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if ($this->user->authorise('core.edit', 'com_users')): ?>
					<a href="index.php?option=com_users&task=user.edit&id=<?php echo (int) $item->user ?>"><?php echo JFactory::getUser((int)$item->user)->name; ?></a>
				<?php else: ?>
					<?php echo JFactory::getUser((int)$item->user)->name; ?>
				<?php endif; ?>
			</div>
		</td>
		<td class="hidden-phone">
			<?php echo JText::_($item->department); ?>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if ($this->user->authorise('country.edit', 'com_costbenefitprojection.country.' . (int) $item->country)): ?>
					<a href="index.php?option=com_costbenefitprojection&view=countries&task=country.edit&id=<?php echo $item->country; ?>&return=<?php echo $this->return_here; ?>"><?php echo $this->escape($item->country_name); ?></a>
				<?php else: ?>
					<?php echo $this->escape($item->country_name); ?>
				<?php endif; ?>
			</div>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if ($this->user->authorise('service_provider.edit', 'com_costbenefitprojection.service_provider.' . (int) $item->service_provider)): ?>
					<a href="index.php?option=com_costbenefitprojection&view=service_providers&task=service_provider.edit&id=<?php echo $item->service_provider; ?>&return=<?php echo $this->return_here; ?>"><?php echo JFactory::getUser((int)$item->service_provider_user)->name; ?></a>
				<?php else: ?>
					<?php echo JFactory::getUser((int)$item->service_provider_user)->name; ?>
				<?php endif; ?>
			</div>
		</td>
		<td class="hidden-phone">
			<?php echo JText::_($item->per); ?>
		</td>
		<td class="center">
		<?php if ($canDo->get('company.edit.state')) : ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'companies.', true, 'cb'); ?>
					<?php else: ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'companies.', false, 'cb'); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'companies.', true, 'cb'); ?>
				<?php endif; ?>
		<?php else: ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'companies.', false, 'cb'); ?>
		<?php endif; ?>
		</td>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>