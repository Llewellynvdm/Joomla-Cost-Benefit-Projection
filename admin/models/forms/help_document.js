/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			6th January, 2021
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvwbvwl_required = false;
jform_vvvvvwcvwm_required = false;
jform_vvvvvwdvwn_required = false;
jform_vvvvvwevwo_required = false;
jform_vvvvvwgvwp_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_vvvvvwb = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwb(location_vvvvvwb);

	var location_vvvvvwc = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwc(location_vvvvvwc);

	var type_vvvvvwd = jQuery("#jform_type").val();
	vvvvvwd(type_vvvvvwd);

	var type_vvvvvwe = jQuery("#jform_type").val();
	vvvvvwe(type_vvvvvwe);

	var type_vvvvvwf = jQuery("#jform_type").val();
	vvvvvwf(type_vvvvvwf);

	var target_vvvvvwg = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvwg(target_vvvvvwg);
});

// the vvvvvwb function
function vvvvvwb(location_vvvvvwb)
{
	// set the function logic
	if (location_vvvvvwb == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
		// add required attribute to admin_view field
		if (jform_vvvvvwbvwl_required)
		{
			updateFieldRequired('admin_view',0);
			jQuery('#jform_admin_view').prop('required','required');
			jQuery('#jform_admin_view').attr('aria-required',true);
			jQuery('#jform_admin_view').addClass('required');
			jform_vvvvvwbvwl_required = false;
		}
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
		// remove required attribute from admin_view field
		if (!jform_vvvvvwbvwl_required)
		{
			updateFieldRequired('admin_view',1);
			jQuery('#jform_admin_view').removeAttr('required');
			jQuery('#jform_admin_view').removeAttr('aria-required');
			jQuery('#jform_admin_view').removeClass('required');
			jform_vvvvvwbvwl_required = true;
		}
	}
}

// the vvvvvwc function
function vvvvvwc(location_vvvvvwc)
{
	// set the function logic
	if (location_vvvvvwc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
		// add required attribute to site_view field
		if (jform_vvvvvwcvwm_required)
		{
			updateFieldRequired('site_view',0);
			jQuery('#jform_site_view').prop('required','required');
			jQuery('#jform_site_view').attr('aria-required',true);
			jQuery('#jform_site_view').addClass('required');
			jform_vvvvvwcvwm_required = false;
		}
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
		// remove required attribute from site_view field
		if (!jform_vvvvvwcvwm_required)
		{
			updateFieldRequired('site_view',1);
			jQuery('#jform_site_view').removeAttr('required');
			jQuery('#jform_site_view').removeAttr('aria-required');
			jQuery('#jform_site_view').removeClass('required');
			jform_vvvvvwcvwm_required = true;
		}
	}
}

// the vvvvvwd function
function vvvvvwd(type_vvvvvwd)
{
	if (isSet(type_vvvvvwd) && type_vvvvvwd.constructor !== Array)
	{
		var temp_vvvvvwd = type_vvvvvwd;
		var type_vvvvvwd = [];
		type_vvvvvwd.push(temp_vvvvvwd);
	}
	else if (!isSet(type_vvvvvwd))
	{
		var type_vvvvvwd = [];
	}
	var type = type_vvvvvwd.some(type_vvvvvwd_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		// add required attribute to url field
		if (jform_vvvvvwdvwn_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwdvwn_required = false;
		}
	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		// remove required attribute from url field
		if (!jform_vvvvvwdvwn_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwdvwn_required = true;
		}
	}
}

// the vvvvvwd Some function
function type_vvvvvwd_SomeFunc(type_vvvvvwd)
{
	// set the function logic
	if (type_vvvvvwd == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvwe function
function vvvvvwe(type_vvvvvwe)
{
	if (isSet(type_vvvvvwe) && type_vvvvvwe.constructor !== Array)
	{
		var temp_vvvvvwe = type_vvvvvwe;
		var type_vvvvvwe = [];
		type_vvvvvwe.push(temp_vvvvvwe);
	}
	else if (!isSet(type_vvvvvwe))
	{
		var type_vvvvvwe = [];
	}
	var type = type_vvvvvwe.some(type_vvvvvwe_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		// add required attribute to article field
		if (jform_vvvvvwevwo_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvwevwo_required = false;
		}
	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		// remove required attribute from article field
		if (!jform_vvvvvwevwo_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvwevwo_required = true;
		}
	}
}

// the vvvvvwe Some function
function type_vvvvvwe_SomeFunc(type_vvvvvwe)
{
	// set the function logic
	if (type_vvvvvwe == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwf function
function vvvvvwf(type_vvvvvwf)
{
	if (isSet(type_vvvvvwf) && type_vvvvvwf.constructor !== Array)
	{
		var temp_vvvvvwf = type_vvvvvwf;
		var type_vvvvvwf = [];
		type_vvvvvwf.push(temp_vvvvvwf);
	}
	else if (!isSet(type_vvvvvwf))
	{
		var type_vvvvvwf = [];
	}
	var type = type_vvvvvwf.some(type_vvvvvwf_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
	}
}

// the vvvvvwf Some function
function type_vvvvvwf_SomeFunc(type_vvvvvwf)
{
	// set the function logic
	if (type_vvvvvwf == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwg function
function vvvvvwg(target_vvvvvwg)
{
	// set the function logic
	if (target_vvvvvwg == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		// add required attribute to groups field
		if (jform_vvvvvwgvwp_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvwgvwp_required = false;
		}
	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		// remove required attribute from groups field
		if (!jform_vvvvvwgvwp_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvwgvwp_required = true;
		}
	}
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
