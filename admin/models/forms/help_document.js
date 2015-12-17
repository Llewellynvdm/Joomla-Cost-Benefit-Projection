/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			17th December, 2015
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
jform_NdVMDpmmRb_required = false;
jform_XRJJvwVycN_required = false;
jform_VAWSUKAsmE_required = false;
jform_VdAPKgebqU_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_cxQpFiW = jQuery("#jform_location input[type='radio']:checked").val();
	cxQpFiW(location_cxQpFiW);

	var location_uMNxJWJ = jQuery("#jform_location input[type='radio']:checked").val();
	uMNxJWJ(location_uMNxJWJ);

	var type_NdVMDpm = jQuery("#jform_type").val();
	NdVMDpm(type_NdVMDpm);

	var type_XRJJvwV = jQuery("#jform_type").val();
	XRJJvwV(type_XRJJvwV);

	var type_VAWSUKA = jQuery("#jform_type").val();
	VAWSUKA(type_VAWSUKA);

	var target_VdAPKge = jQuery("#jform_target input[type='radio']:checked").val();
	VdAPKge(target_VdAPKge);
});

// the cxQpFiW function
function cxQpFiW(location_cxQpFiW)
{
	// [8093] set the function logic
	if (location_cxQpFiW == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the uMNxJWJ function
function uMNxJWJ(location_uMNxJWJ)
{
	// [8093] set the function logic
	if (location_uMNxJWJ == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the NdVMDpm function
function NdVMDpm(type_NdVMDpm)
{
	if (isSet(type_NdVMDpm) && type_NdVMDpm.constructor !== Array)
	{
		var temp_NdVMDpm = type_NdVMDpm;
		var type_NdVMDpm = [];
		type_NdVMDpm.push(temp_NdVMDpm);
	}
	else if (!isSet(type_NdVMDpm))
	{
		var type_NdVMDpm = [];
	}
	var type = type_NdVMDpm.some(type_NdVMDpm_SomeFunc);


	// [8071] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_NdVMDpmmRb_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_NdVMDpmmRb_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_NdVMDpmmRb_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_NdVMDpmmRb_required = true;
		}
	}
}

// the NdVMDpm Some function
function type_NdVMDpm_SomeFunc(type_NdVMDpm)
{
	// [8058] set the function logic
	if (type_NdVMDpm == 3)
	{
		return true;
	}
	return false;
}

// the XRJJvwV function
function XRJJvwV(type_XRJJvwV)
{
	if (isSet(type_XRJJvwV) && type_XRJJvwV.constructor !== Array)
	{
		var temp_XRJJvwV = type_XRJJvwV;
		var type_XRJJvwV = [];
		type_XRJJvwV.push(temp_XRJJvwV);
	}
	else if (!isSet(type_XRJJvwV))
	{
		var type_XRJJvwV = [];
	}
	var type = type_XRJJvwV.some(type_XRJJvwV_SomeFunc);


	// [8071] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_XRJJvwVycN_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_XRJJvwVycN_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_XRJJvwVycN_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_XRJJvwVycN_required = true;
		}
	}
}

// the XRJJvwV Some function
function type_XRJJvwV_SomeFunc(type_XRJJvwV)
{
	// [8058] set the function logic
	if (type_XRJJvwV == 1)
	{
		return true;
	}
	return false;
}

// the VAWSUKA function
function VAWSUKA(type_VAWSUKA)
{
	if (isSet(type_VAWSUKA) && type_VAWSUKA.constructor !== Array)
	{
		var temp_VAWSUKA = type_VAWSUKA;
		var type_VAWSUKA = [];
		type_VAWSUKA.push(temp_VAWSUKA);
	}
	else if (!isSet(type_VAWSUKA))
	{
		var type_VAWSUKA = [];
	}
	var type = type_VAWSUKA.some(type_VAWSUKA_SomeFunc);


	// [8071] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_VAWSUKAsmE_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_VAWSUKAsmE_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_VAWSUKAsmE_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_VAWSUKAsmE_required = true;
		}
	}
}

// the VAWSUKA Some function
function type_VAWSUKA_SomeFunc(type_VAWSUKA)
{
	// [8058] set the function logic
	if (type_VAWSUKA == 2)
	{
		return true;
	}
	return false;
}

// the VdAPKge function
function VdAPKge(target_VdAPKge)
{
	// [8093] set the function logic
	if (target_VdAPKge == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_VdAPKgebqU_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_VdAPKgebqU_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_VdAPKgebqU_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_VdAPKgebqU_required = true;
		}
	}
}

// update required fields
function updateFieldRequired(name,status)
{
	var not_required = jQuery('#jform_not_required').val();

	if(status == 1)
	{
		if (isSet(not_required) && not_required != 0)
		{
			not_required = not_required+','+name;
		}
		else
		{
			not_required = ','+name;
		}
	}
	else
	{
		if (isSet(not_required) && not_required != 0)
		{
			not_required = not_required.replace(','+name,'');
		}
	}

	jQuery('#jform_not_required').val(not_required);
}

// the isSet function
function isSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
} 
