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

	<?php echo JLayoutHelper::render('country.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'countryTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'details', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('country.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('country.details_right', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'public_details', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_PUBLIC_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('country.public_details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'age_groups_percentages', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_AGE_GROUPS_PERCENTAGES', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('country.age_groups_percentages_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('country.age_groups_percentages_right', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'causerisk_selection', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_CAUSERISK_SELECTION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('country.causerisk_selection_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('intervention.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'interventions', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_INTERVENTIONS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('country.interventions_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'health_data_totals', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_HEALTH_DATA_TOTALS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('country.health_data_totals_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('country.health_data_totals_right', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('service_provider.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'service_providers', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_SERVICE_PROVIDERS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('country.service_providers_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('company.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'companies', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_COMPANIES', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('country.companies_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('country.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('country.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'publishing', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('country.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('country.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'countryTab', 'permissions', JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_PERMISSION', true)); ?>
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
		<input type="hidden" name="task" value="country.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>
</form>

<script type="text/javascript">


jQuery('input.form-field-repeatable').on('weready', function(e, value){
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

function updateSelection(row)
{
	var groupId = jQuery(row).find("select:first").attr("id");
	var percentValue = jQuery(row).find(".text_area:first").val();
	var arr = groupId.split('-');
	if (arr[1] != 1)
	{
		var selection = {};
		jQuery(row).find("select:first option").each(function()
		{
			// first get the values and text
			selection[jQuery(this).text()] = jQuery(this).val();
		});
		
		jQuery.each(AgeGroup, function(i, group){
			jQuery(row).find("select:first option[value='"+group+"']").remove();
		});
		if (percentValue)
		{
			var text = jQuery(row).find(".chzn-single:first span").text();
			jQuery(row).find("select:first").append(jQuery('<option>', {
				value: selection[text],
				text: text
			}));
			jQuery(row).find("select:first option:selected").val(selection[text]);
		}
		jQuery(row).find("select:first").trigger("liszt:updated");	
		
		if (percentValue)
		{
			jQuery(row).find("select:first option:selected").val(selection[text]);	
			jQuery(row).find(".chzn-single:first span").text(text);
		}
	}
} 

</script>
