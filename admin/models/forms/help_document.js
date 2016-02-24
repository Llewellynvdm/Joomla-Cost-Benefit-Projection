/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.7
	@build			24th February, 2016
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
jform_PBsVlipavG_required = false;
jform_sdAEsHfPfg_required = false;
jform_LwKoEeFnqw_required = false;
jform_wfYKZjPwkr_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_qtYTlEy = jQuery("#jform_location input[type='radio']:checked").val();
	qtYTlEy(location_qtYTlEy);

	var location_mpuFgRj = jQuery("#jform_location input[type='radio']:checked").val();
	mpuFgRj(location_mpuFgRj);

	var type_PBsVlip = jQuery("#jform_type").val();
	PBsVlip(type_PBsVlip);

	var type_sdAEsHf = jQuery("#jform_type").val();
	sdAEsHf(type_sdAEsHf);

	var type_LwKoEeF = jQuery("#jform_type").val();
	LwKoEeF(type_LwKoEeF);

	var target_wfYKZjP = jQuery("#jform_target input[type='radio']:checked").val();
	wfYKZjP(target_wfYKZjP);
});

// the qtYTlEy function
function qtYTlEy(location_qtYTlEy)
{
	// set the function logic
	if (location_qtYTlEy == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the mpuFgRj function
function mpuFgRj(location_mpuFgRj)
{
	// set the function logic
	if (location_mpuFgRj == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the PBsVlip function
function PBsVlip(type_PBsVlip)
{
	if (isSet(type_PBsVlip) && type_PBsVlip.constructor !== Array)
	{
		var temp_PBsVlip = type_PBsVlip;
		var type_PBsVlip = [];
		type_PBsVlip.push(temp_PBsVlip);
	}
	else if (!isSet(type_PBsVlip))
	{
		var type_PBsVlip = [];
	}
	var type = type_PBsVlip.some(type_PBsVlip_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_PBsVlipavG_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_PBsVlipavG_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_PBsVlipavG_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_PBsVlipavG_required = true;
		}
	}
}

// the PBsVlip Some function
function type_PBsVlip_SomeFunc(type_PBsVlip)
{
	// set the function logic
	if (type_PBsVlip == 3)
	{
		return true;
	}
	return false;
}

// the sdAEsHf function
function sdAEsHf(type_sdAEsHf)
{
	if (isSet(type_sdAEsHf) && type_sdAEsHf.constructor !== Array)
	{
		var temp_sdAEsHf = type_sdAEsHf;
		var type_sdAEsHf = [];
		type_sdAEsHf.push(temp_sdAEsHf);
	}
	else if (!isSet(type_sdAEsHf))
	{
		var type_sdAEsHf = [];
	}
	var type = type_sdAEsHf.some(type_sdAEsHf_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_sdAEsHfPfg_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_sdAEsHfPfg_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_sdAEsHfPfg_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_sdAEsHfPfg_required = true;
		}
	}
}

// the sdAEsHf Some function
function type_sdAEsHf_SomeFunc(type_sdAEsHf)
{
	// set the function logic
	if (type_sdAEsHf == 1)
	{
		return true;
	}
	return false;
}

// the LwKoEeF function
function LwKoEeF(type_LwKoEeF)
{
	if (isSet(type_LwKoEeF) && type_LwKoEeF.constructor !== Array)
	{
		var temp_LwKoEeF = type_LwKoEeF;
		var type_LwKoEeF = [];
		type_LwKoEeF.push(temp_LwKoEeF);
	}
	else if (!isSet(type_LwKoEeF))
	{
		var type_LwKoEeF = [];
	}
	var type = type_LwKoEeF.some(type_LwKoEeF_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_LwKoEeFnqw_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_LwKoEeFnqw_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_LwKoEeFnqw_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_LwKoEeFnqw_required = true;
		}
	}
}

// the LwKoEeF Some function
function type_LwKoEeF_SomeFunc(type_LwKoEeF)
{
	// set the function logic
	if (type_LwKoEeF == 2)
	{
		return true;
	}
	return false;
}

// the wfYKZjP function
function wfYKZjP(target_wfYKZjP)
{
	// set the function logic
	if (target_wfYKZjP == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_wfYKZjPwkr_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_wfYKZjPwkr_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_wfYKZjPwkr_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_wfYKZjPwkr_required = true;
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
