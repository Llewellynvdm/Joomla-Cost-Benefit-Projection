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
jform_CXVoNoaTUi_required = false;
jform_RlScCywppj_required = false;
jform_GRMmOLOHJk_required = false;
jform_lqhjMXyMSm_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_JYStDvN = jQuery("#jform_location input[type='radio']:checked").val();
	JYStDvN(location_JYStDvN);

	var location_VZDPdtL = jQuery("#jform_location input[type='radio']:checked").val();
	VZDPdtL(location_VZDPdtL);

	var type_CXVoNoa = jQuery("#jform_type").val();
	CXVoNoa(type_CXVoNoa);

	var type_RlScCyw = jQuery("#jform_type").val();
	RlScCyw(type_RlScCyw);

	var type_GRMmOLO = jQuery("#jform_type").val();
	GRMmOLO(type_GRMmOLO);

	var target_lqhjMXy = jQuery("#jform_target input[type='radio']:checked").val();
	lqhjMXy(target_lqhjMXy);
});

// the JYStDvN function
function JYStDvN(location_JYStDvN)
{
	// set the function logic
	if (location_JYStDvN == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the VZDPdtL function
function VZDPdtL(location_VZDPdtL)
{
	// set the function logic
	if (location_VZDPdtL == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the CXVoNoa function
function CXVoNoa(type_CXVoNoa)
{
	if (isSet(type_CXVoNoa) && type_CXVoNoa.constructor !== Array)
	{
		var temp_CXVoNoa = type_CXVoNoa;
		var type_CXVoNoa = [];
		type_CXVoNoa.push(temp_CXVoNoa);
	}
	else if (!isSet(type_CXVoNoa))
	{
		var type_CXVoNoa = [];
	}
	var type = type_CXVoNoa.some(type_CXVoNoa_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_CXVoNoaTUi_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_CXVoNoaTUi_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_CXVoNoaTUi_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_CXVoNoaTUi_required = true;
		}
	}
}

// the CXVoNoa Some function
function type_CXVoNoa_SomeFunc(type_CXVoNoa)
{
	// set the function logic
	if (type_CXVoNoa == 3)
	{
		return true;
	}
	return false;
}

// the RlScCyw function
function RlScCyw(type_RlScCyw)
{
	if (isSet(type_RlScCyw) && type_RlScCyw.constructor !== Array)
	{
		var temp_RlScCyw = type_RlScCyw;
		var type_RlScCyw = [];
		type_RlScCyw.push(temp_RlScCyw);
	}
	else if (!isSet(type_RlScCyw))
	{
		var type_RlScCyw = [];
	}
	var type = type_RlScCyw.some(type_RlScCyw_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_RlScCywppj_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_RlScCywppj_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_RlScCywppj_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_RlScCywppj_required = true;
		}
	}
}

// the RlScCyw Some function
function type_RlScCyw_SomeFunc(type_RlScCyw)
{
	// set the function logic
	if (type_RlScCyw == 1)
	{
		return true;
	}
	return false;
}

// the GRMmOLO function
function GRMmOLO(type_GRMmOLO)
{
	if (isSet(type_GRMmOLO) && type_GRMmOLO.constructor !== Array)
	{
		var temp_GRMmOLO = type_GRMmOLO;
		var type_GRMmOLO = [];
		type_GRMmOLO.push(temp_GRMmOLO);
	}
	else if (!isSet(type_GRMmOLO))
	{
		var type_GRMmOLO = [];
	}
	var type = type_GRMmOLO.some(type_GRMmOLO_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_GRMmOLOHJk_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_GRMmOLOHJk_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_GRMmOLOHJk_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_GRMmOLOHJk_required = true;
		}
	}
}

// the GRMmOLO Some function
function type_GRMmOLO_SomeFunc(type_GRMmOLO)
{
	// set the function logic
	if (type_GRMmOLO == 2)
	{
		return true;
	}
	return false;
}

// the lqhjMXy function
function lqhjMXy(target_lqhjMXy)
{
	// set the function logic
	if (target_lqhjMXy == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_lqhjMXyMSm_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_lqhjMXyMSm_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_lqhjMXyMSm_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_lqhjMXyMSm_required = true;
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
