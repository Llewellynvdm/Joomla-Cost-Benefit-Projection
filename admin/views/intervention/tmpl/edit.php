<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			17th December, 2015
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

	<?php echo JLayoutHelper::render('intervention.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'interventionTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'interventionTab', 'details', JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('intervention.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'interventionTab', 'settings', JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_SETTINGS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('intervention.settings_left', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('intervention.settings_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'interventionTab', 'publishing', JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('intervention.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('intervention.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'interventionTab', 'permissions', JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_PERMISSION', true)); ?>
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
		<input type="hidden" name="task" value="intervention.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('intervention.details_under', $this); ?>
</form>

<script type="text/javascript">

// #jform_type listeners for type_hhqVuoH function
jQuery('#jform_type').on('keyup',function()
{
	var type_hhqVuoH = jQuery("#jform_type input[type='radio']:checked").val();
	hhqVuoH(type_hhqVuoH);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_hhqVuoH = jQuery("#jform_type input[type='radio']:checked").val();
	hhqVuoH(type_hhqVuoH);

});

// #jform_type listeners for type_LZHLLUr function
jQuery('#jform_type').on('keyup',function()
{
	var type_LZHLLUr = jQuery("#jform_type input[type='radio']:checked").val();
	LZHLLUr(type_LZHLLUr);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_LZHLLUr = jQuery("#jform_type input[type='radio']:checked").val();
	LZHLLUr(type_LZHLLUr);

});


jQuery('#adminForm').on('change', '#jform_interventions',function (e)
{
	e.preventDefault();
	var cluster = jQuery("#jform_interventions").val();
	getClusterData(cluster,'jform_interventions');

});

jQuery('#adminForm').on('change', '.clusterintervention',function (e)
{
	jQuery('.clusterintervention').keyup(function () { 
		this.value = this.value.replace(/[^0-9-& \.]/g,'');
	});
	changeFieldValue(this.id,this.value);

});

jQuery('input.form-field-repeatable').on('value-update', function(e, value){
	if (value)
	{
		getBuildTable(value,e.currentTarget.id,'ne');
	}
});

jQuery('input.form-field-repeatable').on('row-add', function(e, row) {
	setSelection();
	updateSelection(row);
});

var causerisk = new Array;
function setSelection()
{
	causerisk.length = 0;
	<?php $fieldNrs = range(1,99,1); ?>
	<?php foreach($fieldNrs as $fieldNr): ?>
	// get options
	var causerisk_<?php echo $fieldNr ?> = jQuery("#jform_intervention_fields_causerisk-<?php echo $fieldNr ?> option:selected").val();
	if (causerisk_<?php echo $fieldNr ?>)
	{
		causerisk.push(causerisk_<?php echo $fieldNr ?>);
	}
	<?php endforeach; ?>
}
</script>
