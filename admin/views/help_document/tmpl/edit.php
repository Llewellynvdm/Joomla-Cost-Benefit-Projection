<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.7
	@build			24th February, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		edit.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_costbenefitprojection');
?>

<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('help_document.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'help_documentTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'details', JText::_('COM_COSTBENEFITPROJECTION_HELP_DOCUMENT_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.details_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('help_document.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('help_document.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('help_document.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'publishing', JText::_('COM_COSTBENEFITPROJECTION_HELP_DOCUMENT_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'permissions', JText::_('COM_COSTBENEFITPROJECTION_HELP_DOCUMENT_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="help_document.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('help_document.details_under', $this); ?>
</form>

<script type="text/javascript">

// #jform_location listeners for location_qtYTlEy function
jQuery('#jform_location').on('keyup',function()
{
	var location_qtYTlEy = jQuery("#jform_location input[type='radio']:checked").val();
	qtYTlEy(location_qtYTlEy);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_qtYTlEy = jQuery("#jform_location input[type='radio']:checked").val();
	qtYTlEy(location_qtYTlEy);

});

// #jform_location listeners for location_mpuFgRj function
jQuery('#jform_location').on('keyup',function()
{
	var location_mpuFgRj = jQuery("#jform_location input[type='radio']:checked").val();
	mpuFgRj(location_mpuFgRj);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_mpuFgRj = jQuery("#jform_location input[type='radio']:checked").val();
	mpuFgRj(location_mpuFgRj);

});

// #jform_type listeners for type_PBsVlip function
jQuery('#jform_type').on('keyup',function()
{
	var type_PBsVlip = jQuery("#jform_type").val();
	PBsVlip(type_PBsVlip);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_PBsVlip = jQuery("#jform_type").val();
	PBsVlip(type_PBsVlip);

});

// #jform_type listeners for type_sdAEsHf function
jQuery('#jform_type').on('keyup',function()
{
	var type_sdAEsHf = jQuery("#jform_type").val();
	sdAEsHf(type_sdAEsHf);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_sdAEsHf = jQuery("#jform_type").val();
	sdAEsHf(type_sdAEsHf);

});

// #jform_type listeners for type_LwKoEeF function
jQuery('#jform_type').on('keyup',function()
{
	var type_LwKoEeF = jQuery("#jform_type").val();
	LwKoEeF(type_LwKoEeF);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_LwKoEeF = jQuery("#jform_type").val();
	LwKoEeF(type_LwKoEeF);

});

// #jform_target listeners for target_wfYKZjP function
jQuery('#jform_target').on('keyup',function()
{
	var target_wfYKZjP = jQuery("#jform_target input[type='radio']:checked").val();
	wfYKZjP(target_wfYKZjP);

});
jQuery('#adminForm').on('change', '#jform_target',function (e)
{
	e.preventDefault();
	var target_wfYKZjP = jQuery("#jform_target input[type='radio']:checked").val();
	wfYKZjP(target_wfYKZjP);

});

</script>
