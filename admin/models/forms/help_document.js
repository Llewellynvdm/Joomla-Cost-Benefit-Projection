/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			23rd December, 2015
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
jform_dzQOnAtTIF_required = false;
jform_HWQwlmmxDJ_required = false;
jform_fWSNxVStqV_required = false;
jform_vpCnhLJvww_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_rjBFAdG = jQuery("#jform_location input[type='radio']:checked").val();
	rjBFAdG(location_rjBFAdG);

	var location_tedcpcc = jQuery("#jform_location input[type='radio']:checked").val();
	tedcpcc(location_tedcpcc);

	var type_dzQOnAt = jQuery("#jform_type").val();
	dzQOnAt(type_dzQOnAt);

	var type_HWQwlmm = jQuery("#jform_type").val();
	HWQwlmm(type_HWQwlmm);

	var type_fWSNxVS = jQuery("#jform_type").val();
	fWSNxVS(type_fWSNxVS);

	var target_vpCnhLJ = jQuery("#jform_target input[type='radio']:checked").val();
	vpCnhLJ(target_vpCnhLJ);
});

// the rjBFAdG function
function rjBFAdG(location_rjBFAdG)
{
	// [8260] set the function logic
	if (location_rjBFAdG == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the tedcpcc function
function tedcpcc(location_tedcpcc)
{
	// [8260] set the function logic
	if (location_tedcpcc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the dzQOnAt function
function dzQOnAt(type_dzQOnAt)
{
	if (isSet(type_dzQOnAt) && type_dzQOnAt.constructor !== Array)
	{
		var temp_dzQOnAt = type_dzQOnAt;
		var type_dzQOnAt = [];
		type_dzQOnAt.push(temp_dzQOnAt);
	}
	else if (!isSet(type_dzQOnAt))
	{
		var type_dzQOnAt = [];
	}
	var type = type_dzQOnAt.some(type_dzQOnAt_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_dzQOnAtTIF_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_dzQOnAtTIF_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_dzQOnAtTIF_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_dzQOnAtTIF_required = true;
		}
	}
}

// the dzQOnAt Some function
function type_dzQOnAt_SomeFunc(type_dzQOnAt)
{
	// [8225] set the function logic
	if (type_dzQOnAt == 3)
	{
		return true;
	}
	return false;
}

// the HWQwlmm function
function HWQwlmm(type_HWQwlmm)
{
	if (isSet(type_HWQwlmm) && type_HWQwlmm.constructor !== Array)
	{
		var temp_HWQwlmm = type_HWQwlmm;
		var type_HWQwlmm = [];
		type_HWQwlmm.push(temp_HWQwlmm);
	}
	else if (!isSet(type_HWQwlmm))
	{
		var type_HWQwlmm = [];
	}
	var type = type_HWQwlmm.some(type_HWQwlmm_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_HWQwlmmxDJ_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_HWQwlmmxDJ_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_HWQwlmmxDJ_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_HWQwlmmxDJ_required = true;
		}
	}
}

// the HWQwlmm Some function
function type_HWQwlmm_SomeFunc(type_HWQwlmm)
{
	// [8225] set the function logic
	if (type_HWQwlmm == 1)
	{
		return true;
	}
	return false;
}

// the fWSNxVS function
function fWSNxVS(type_fWSNxVS)
{
	if (isSet(type_fWSNxVS) && type_fWSNxVS.constructor !== Array)
	{
		var temp_fWSNxVS = type_fWSNxVS;
		var type_fWSNxVS = [];
		type_fWSNxVS.push(temp_fWSNxVS);
	}
	else if (!isSet(type_fWSNxVS))
	{
		var type_fWSNxVS = [];
	}
	var type = type_fWSNxVS.some(type_fWSNxVS_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_fWSNxVStqV_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_fWSNxVStqV_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_fWSNxVStqV_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_fWSNxVStqV_required = true;
		}
	}
}

// the fWSNxVS Some function
function type_fWSNxVS_SomeFunc(type_fWSNxVS)
{
	// [8225] set the function logic
	if (type_fWSNxVS == 2)
	{
		return true;
	}
	return false;
}

// the vpCnhLJ function
function vpCnhLJ(target_vpCnhLJ)
{
	// [8260] set the function logic
	if (target_vpCnhLJ == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_vpCnhLJvww_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vpCnhLJvww_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vpCnhLJvww_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vpCnhLJvww_required = true;
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
