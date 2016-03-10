/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
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
jform_vvvvvwdvwl_required = false;
jform_vvvvvwevwm_required = false;
jform_vvvvvwfvwn_required = false;
jform_vvvvvwgvwo_required = false;

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
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the vvvvvwc function
function vvvvvwc(location_vvvvvwc)
{
	// set the function logic
	if (location_vvvvvwc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
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
		if (jform_vvvvvwdvwl_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwdvwl_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_vvvvvwdvwl_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwdvwl_required = true;
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
		if (jform_vvvvvwevwm_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvwevwm_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_vvvvvwevwm_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvwevwm_required = true;
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
		if (jform_vvvvvwfvwn_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_vvvvvwfvwn_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_vvvvvwfvwn_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_vvvvvwfvwn_required = true;
		}
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
		if (jform_vvvvvwgvwo_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvwgvwo_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vvvvvwgvwo_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvwgvwo_required = true;
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
