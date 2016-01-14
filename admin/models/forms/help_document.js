/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.0
	@build			14th January, 2016
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
jform_WGaqBqHysB_required = false;
jform_plZzXlEQEW_required = false;
jform_mxsaRmrcUb_required = false;
jform_fQAidiyFNO_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_RymCTCN = jQuery("#jform_location input[type='radio']:checked").val();
	RymCTCN(location_RymCTCN);

	var location_uTrizCf = jQuery("#jform_location input[type='radio']:checked").val();
	uTrizCf(location_uTrizCf);

	var type_WGaqBqH = jQuery("#jform_type").val();
	WGaqBqH(type_WGaqBqH);

	var type_plZzXlE = jQuery("#jform_type").val();
	plZzXlE(type_plZzXlE);

	var type_mxsaRmr = jQuery("#jform_type").val();
	mxsaRmr(type_mxsaRmr);

	var target_fQAidiy = jQuery("#jform_target input[type='radio']:checked").val();
	fQAidiy(target_fQAidiy);
});

// the RymCTCN function
function RymCTCN(location_RymCTCN)
{
	// set the function logic
	if (location_RymCTCN == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the uTrizCf function
function uTrizCf(location_uTrizCf)
{
	// set the function logic
	if (location_uTrizCf == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the WGaqBqH function
function WGaqBqH(type_WGaqBqH)
{
	if (isSet(type_WGaqBqH) && type_WGaqBqH.constructor !== Array)
	{
		var temp_WGaqBqH = type_WGaqBqH;
		var type_WGaqBqH = [];
		type_WGaqBqH.push(temp_WGaqBqH);
	}
	else if (!isSet(type_WGaqBqH))
	{
		var type_WGaqBqH = [];
	}
	var type = type_WGaqBqH.some(type_WGaqBqH_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_WGaqBqHysB_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_WGaqBqHysB_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_WGaqBqHysB_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_WGaqBqHysB_required = true;
		}
	}
}

// the WGaqBqH Some function
function type_WGaqBqH_SomeFunc(type_WGaqBqH)
{
	// set the function logic
	if (type_WGaqBqH == 3)
	{
		return true;
	}
	return false;
}

// the plZzXlE function
function plZzXlE(type_plZzXlE)
{
	if (isSet(type_plZzXlE) && type_plZzXlE.constructor !== Array)
	{
		var temp_plZzXlE = type_plZzXlE;
		var type_plZzXlE = [];
		type_plZzXlE.push(temp_plZzXlE);
	}
	else if (!isSet(type_plZzXlE))
	{
		var type_plZzXlE = [];
	}
	var type = type_plZzXlE.some(type_plZzXlE_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_plZzXlEQEW_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_plZzXlEQEW_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_plZzXlEQEW_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_plZzXlEQEW_required = true;
		}
	}
}

// the plZzXlE Some function
function type_plZzXlE_SomeFunc(type_plZzXlE)
{
	// set the function logic
	if (type_plZzXlE == 1)
	{
		return true;
	}
	return false;
}

// the mxsaRmr function
function mxsaRmr(type_mxsaRmr)
{
	if (isSet(type_mxsaRmr) && type_mxsaRmr.constructor !== Array)
	{
		var temp_mxsaRmr = type_mxsaRmr;
		var type_mxsaRmr = [];
		type_mxsaRmr.push(temp_mxsaRmr);
	}
	else if (!isSet(type_mxsaRmr))
	{
		var type_mxsaRmr = [];
	}
	var type = type_mxsaRmr.some(type_mxsaRmr_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_mxsaRmrcUb_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_mxsaRmrcUb_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_mxsaRmrcUb_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_mxsaRmrcUb_required = true;
		}
	}
}

// the mxsaRmr Some function
function type_mxsaRmr_SomeFunc(type_mxsaRmr)
{
	// set the function logic
	if (type_mxsaRmr == 2)
	{
		return true;
	}
	return false;
}

// the fQAidiy function
function fQAidiy(target_fQAidiy)
{
	// set the function logic
	if (target_fQAidiy == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_fQAidiyFNO_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_fQAidiyFNO_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_fQAidiyFNO_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_fQAidiyFNO_required = true;
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
