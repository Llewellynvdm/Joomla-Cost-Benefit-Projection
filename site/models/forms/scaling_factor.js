/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			2nd March, 2022
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		scaling_factor.js
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvvxvwi_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var company_vvvvvvx = jQuery("#jform_company").val();
	vvvvvvx(company_vvvvvvx);
});

// the vvvvvvx function
function vvvvvvx(company_vvvvvvx)
{
	if (isSet(company_vvvvvvx) && company_vvvvvvx.constructor !== Array)
	{
		var temp_vvvvvvx = company_vvvvvvx;
		var company_vvvvvvx = [];
		company_vvvvvvx.push(temp_vvvvvvx);
	}
	else if (!isSet(company_vvvvvvx))
	{
		var company_vvvvvvx = [];
	}
	var company = company_vvvvvvx.some(company_vvvvvvx_SomeFunc);


	// set this function logic
	if (company)
	{
		jQuery('#jform_country').closest('.control-group').show();
		// add required attribute to country field
		if (jform_vvvvvvxvwi_required)
		{
			updateFieldRequired('country',0);
			jQuery('#jform_country').prop('required','required');
			jQuery('#jform_country').attr('aria-required',true);
			jQuery('#jform_country').addClass('required');
			jform_vvvvvvxvwi_required = false;
		}
	}
	else
	{
		jQuery('#jform_country').closest('.control-group').hide();
		// remove required attribute from country field
		if (!jform_vvvvvvxvwi_required)
		{
			updateFieldRequired('country',1);
			jQuery('#jform_country').removeAttr('required');
			jQuery('#jform_country').removeAttr('aria-required');
			jQuery('#jform_country').removeClass('required');
			jform_vvvvvvxvwi_required = true;
		}
	}
}

// the vvvvvvx Some function
function company_vvvvvvx_SomeFunc(company_vvvvvvx)
{
	// set the function logic
	if (company_vvvvvvx == 0)
	{
		return true;
	}
	return false;
}

// update fields required
function updateFieldRequired(name, status) {
	// check if not_required exist
	if (jQuery('#jform_not_required').length > 0) {
		var not_required = jQuery('#jform_not_required').val().split(",");

		if(status == 1)
		{
			not_required.push(name);
		}
		else
		{
			not_required = removeFieldFromNotRequired(not_required, name);
		}

		jQuery('#jform_not_required').val(fixNotRequiredArray(not_required).toString());
	}
}

// remove field from not_required
function removeFieldFromNotRequired(array, what) {
	return array.filter(function(element){
		return element !== what;
	});
}

// fix not required array
function fixNotRequiredArray(array) {
	var seen = {};
	return removeEmptyFromNotRequiredArray(array).filter(function(item) {
		return seen.hasOwnProperty(item) ? false : (seen[item] = true);
	});
}

// remove empty from not_required array
function removeEmptyFromNotRequiredArray(array) {
	return array.filter(function (el) {
		// remove ( 一_一) as well - lol
		return (el.length > 0 && '一_一' !== el);
	});
}

// the isSet function
function isSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
} 
