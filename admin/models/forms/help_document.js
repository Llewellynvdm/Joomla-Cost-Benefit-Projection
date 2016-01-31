/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.0
	@build			31st January, 2016
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
jform_cxrSpAJsEA_required = false;
jform_agEZjiCoqY_required = false;
jform_qIpBDXfuLw_required = false;
jform_RMTlzlGmyo_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_ihDVuyo = jQuery("#jform_location input[type='radio']:checked").val();
	ihDVuyo(location_ihDVuyo);

	var location_QHxQMlw = jQuery("#jform_location input[type='radio']:checked").val();
	QHxQMlw(location_QHxQMlw);

	var type_cxrSpAJ = jQuery("#jform_type").val();
	cxrSpAJ(type_cxrSpAJ);

	var type_agEZjiC = jQuery("#jform_type").val();
	agEZjiC(type_agEZjiC);

	var type_qIpBDXf = jQuery("#jform_type").val();
	qIpBDXf(type_qIpBDXf);

	var target_RMTlzlG = jQuery("#jform_target input[type='radio']:checked").val();
	RMTlzlG(target_RMTlzlG);
});

// the ihDVuyo function
function ihDVuyo(location_ihDVuyo)
{
	// set the function logic
	if (location_ihDVuyo == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the QHxQMlw function
function QHxQMlw(location_QHxQMlw)
{
	// set the function logic
	if (location_QHxQMlw == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the cxrSpAJ function
function cxrSpAJ(type_cxrSpAJ)
{
	if (isSet(type_cxrSpAJ) && type_cxrSpAJ.constructor !== Array)
	{
		var temp_cxrSpAJ = type_cxrSpAJ;
		var type_cxrSpAJ = [];
		type_cxrSpAJ.push(temp_cxrSpAJ);
	}
	else if (!isSet(type_cxrSpAJ))
	{
		var type_cxrSpAJ = [];
	}
	var type = type_cxrSpAJ.some(type_cxrSpAJ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_cxrSpAJsEA_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_cxrSpAJsEA_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_cxrSpAJsEA_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_cxrSpAJsEA_required = true;
		}
	}
}

// the cxrSpAJ Some function
function type_cxrSpAJ_SomeFunc(type_cxrSpAJ)
{
	// set the function logic
	if (type_cxrSpAJ == 3)
	{
		return true;
	}
	return false;
}

// the agEZjiC function
function agEZjiC(type_agEZjiC)
{
	if (isSet(type_agEZjiC) && type_agEZjiC.constructor !== Array)
	{
		var temp_agEZjiC = type_agEZjiC;
		var type_agEZjiC = [];
		type_agEZjiC.push(temp_agEZjiC);
	}
	else if (!isSet(type_agEZjiC))
	{
		var type_agEZjiC = [];
	}
	var type = type_agEZjiC.some(type_agEZjiC_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_agEZjiCoqY_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_agEZjiCoqY_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_agEZjiCoqY_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_agEZjiCoqY_required = true;
		}
	}
}

// the agEZjiC Some function
function type_agEZjiC_SomeFunc(type_agEZjiC)
{
	// set the function logic
	if (type_agEZjiC == 1)
	{
		return true;
	}
	return false;
}

// the qIpBDXf function
function qIpBDXf(type_qIpBDXf)
{
	if (isSet(type_qIpBDXf) && type_qIpBDXf.constructor !== Array)
	{
		var temp_qIpBDXf = type_qIpBDXf;
		var type_qIpBDXf = [];
		type_qIpBDXf.push(temp_qIpBDXf);
	}
	else if (!isSet(type_qIpBDXf))
	{
		var type_qIpBDXf = [];
	}
	var type = type_qIpBDXf.some(type_qIpBDXf_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_qIpBDXfuLw_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_qIpBDXfuLw_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_qIpBDXfuLw_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_qIpBDXfuLw_required = true;
		}
	}
}

// the qIpBDXf Some function
function type_qIpBDXf_SomeFunc(type_qIpBDXf)
{
	// set the function logic
	if (type_qIpBDXf == 2)
	{
		return true;
	}
	return false;
}

// the RMTlzlG function
function RMTlzlG(target_RMTlzlG)
{
	// set the function logic
	if (target_RMTlzlG == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_RMTlzlGmyo_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_RMTlzlGmyo_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_RMTlzlGmyo_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_RMTlzlGmyo_required = true;
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
