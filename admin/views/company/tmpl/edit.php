<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
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

	<?php echo JLayoutHelper::render('company.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'companyTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'details', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('company.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('company.details_right', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'age_groups_percentages', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_AGE_GROUPS_PERCENTAGES', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('company.age_groups_percentages_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('company.age_groups_percentages_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('company.age_groups_percentages_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'causerisk_selection', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_CAUSERISK_SELECTION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('company.causerisk_selection_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('scaling_factor.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'scaling_factors', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_SCALING_FACTORS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('company.scaling_factors_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('intervention.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'interventions', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_INTERVENTIONS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('company.interventions_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('company.delete') || $this->canDo->get('company.edit.created_by') || $this->canDo->get('company.edit.state') || $this->canDo->get('company.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'publishing', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('company.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('company.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'companyTab', 'permissions', JText::_('COM_COSTBENEFITPROJECTION_COMPANY_PERMISSION', true)); ?>
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
		<input type="hidden" name="task" value="company.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('company.details_under', $this); ?>
</form>

<script type="text/javascript">

// #jform_department listeners for department_vvvvvvv function
jQuery('#jform_department').on('keyup',function()
{
	var department_vvvvvvv = jQuery("#jform_department input[type='radio']:checked").val();
	vvvvvvv(department_vvvvvvv);

});
jQuery('#adminForm').on('change', '#jform_department',function (e)
{
	e.preventDefault();
	var department_vvvvvvv = jQuery("#jform_department input[type='radio']:checked").val();
	vvvvvvv(department_vvvvvvv);

});

// #jform_department listeners for department_vvvvvvw function
jQuery('#jform_department').on('keyup',function()
{
	var department_vvvvvvw = jQuery("#jform_department input[type='radio']:checked").val();
	vvvvvvw(department_vvvvvvw);

});
jQuery('#adminForm').on('change', '#jform_department',function (e)
{
	e.preventDefault();
	var department_vvvvvvw = jQuery("#jform_department input[type='radio']:checked").val();
	vvvvvvw(department_vvvvvvw);

});



jQuery('#adminForm').on('change', '#jform_causesrisks',function (e)
{
	// first we build the checked array
	checkedArray = [];
	jQuery('#jform_causesrisks input[type=checkbox]').each(function()
	{
		if (jQuery(this).is(':checked'))
		{
      		checkedArray.push(jQuery(jQuery("label[for='"+jQuery(this).prop('id')+"']").html()).prop('id'));
		}
	});
	// now we check if child is checked and uncheck perant
	jQuery('#jform_causesrisks input[type=checkbox]').each(function()
	{
		if (jQuery(this).is(':checked'))
		{
      		var checing = jQuery(jQuery("label[for='"+jQuery(this).prop('id')+"']").html()).prop('id');
			// first remove this checkd item from array
			checkedArrayChecker = jQuery.grep(checkedArray, function(value) {
				return value != checing;
			});
			// now uncheck the perant checkboxes
			jQuery.each( checkedArrayChecker,function(index,value)
			{
				if (checing.indexOf(value) >= 0)
				{
					var block = jQuery('label > span#'+value).closest('li').find('input').prop('id');
					jQuery('#'+block).prop('checked', false);
				}
			});
		}
	});
});

// add the calculator button
var cal_button = ' <a class="btn btn-small btn-success" href="https://www.staffhealthcbp.com/download/Productivity_Losses_Calculator_V1.xlsx"> <span class="icon-download icon-white"></span> Calculator </a>';
jQuery('#jform_productivity_losses').closest('.controls').append(cal_button);jQuery('input.form-field-repeatable').on('weready', function(e, value){
	if ("jform_percentmale" == e.currentTarget.id)
	{
		calPercent('male');
	}
	else if("jform_percentfemale" == e.currentTarget.id)
	{
		calPercent('female');
	}
});

jQuery('input.form-field-repeatable').on('value-update', function(e, value){
	if (value)
	{
		buildTable(value,e.currentTarget.id);
	}
});

jQuery('input.form-field-repeatable').on('row-add', function(e, row) {
	if ("jform_percentmale" == e.currentTarget.id)
	{
		setSelection('male');
		updateSelection(row);
	}
	else if("jform_percentfemale" == e.currentTarget.id)
	{
		setSelection('female');
		updateSelection(row);
	}
});

jQuery('input.form-field-repeatable').on('row-remove', function(e, row) {
	if ("jform_percentmale" == e.currentTarget.id)
	{
		calPercent('male');
	}
	else if("jform_percentfemale" == e.currentTarget.id)
	{
		calPercent('female');
	}
});

var AgeGroup = new Array;
function setSelection(gender)
{
	AgeGroup.length = 0;
	<?php $fieldNrs = range(1,9,1); ?>
	<?php foreach($fieldNrs as $fieldNr): ?>
		// get options
		var age_<?php echo $fieldNr ?> = jQuery("#jform_percent"+gender+"_fields_age-<?php echo $fieldNr ?> option:selected").val();
		if (age_<?php echo $fieldNr ?>)
		{
			AgeGroup.push(age_<?php echo $fieldNr ?>);
		}
	<?php endforeach; ?>
}

<?php $fieldNrs = range(1,10,1); ?>
jQuery('#jform_percentmale_modal').on('show.bs.modal', function (e) {
	<?php foreach($fieldNrs as $fieldNr): ?>jQuery('#jform_percentmale_modal').on('change', '#jform_percentmale_fields_percent-<?php echo $fieldNr ?>',function () {
		// calculate
		calPercent('male');
	});
	jQuery('#jform_percentmale_fields_percent-<?php echo $fieldNr ?>').on('keyup',function() {
		// calculate
		calPercent('male');
	});
	<?php endforeach; ?>
});
jQuery('#jform_percentfemale_modal').on('show.bs.modal', function (e) {
	<?php foreach($fieldNrs as $fieldNr): ?>jQuery('#jform_percentfemale_modal').on('change', '#jform_percentfemale_fields_percent-<?php echo $fieldNr ?>',function () {
		// calculate
		calPercent('female');
	});
	jQuery('#jform_percentfemale_fields_percent-<?php echo $fieldNr ?>').on('keyup',function() {
		// calculate
		calPercent('female');
	});
	<?php endforeach; ?>
});

var Percent = new Array;
function calPercent(gender)
{
	Percent.length = 0;
	<?php foreach($fieldNrs as $fieldNr): ?>
	// get options
	var age_<?php echo $fieldNr ?> = jQuery("#jform_percent"+gender+"_fields_percent-<?php echo $fieldNr ?>").val();
	if (age_<?php echo $fieldNr ?> && age_<?php echo $fieldNr ?>.match(/^\d+$/))
	{
		Percent.push(age_<?php echo $fieldNr ?>);
	}
	else
	{
		jQuery("#jform_percent"+gender+"_fields_percent-<?php echo $fieldNr ?>").val('');
	}
	<?php endforeach; ?>
	var total = 0;
	for (var i = 0; i < Percent.length; i++) {
		total += Percent[i] << 0;
	}
	if (total != 100)
	{
		jQuery('#jform_percent'+gender+'_modal .save-modal-data').hide();
		jQuery('#jform_percent'+gender+'_total').remove();
		jQuery('#jform_percent'+gender+'_modal .modal-footer').append('<span id="jform_percent'+gender+'_total" style="color:red;"><small>(Must be 100%)</small> Total: '+total+'%</span>');
	}
	else
	{
		jQuery('#jform_percent'+gender+'_total').remove();
		jQuery('.save-modal-data').text('Done 100%');
		jQuery('#jform_percent'+gender+'_modal .save-modal-data').show();
	}
}
</script>
