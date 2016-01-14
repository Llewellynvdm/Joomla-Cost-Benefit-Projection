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
jform_TOXvRExNHV_required = false;
jform_yNpeHRjZzq_required = false;
jform_LICWaSFazo_required = false;
jform_gtffPVJpEk_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_mBfGmql = jQuery("#jform_location input[type='radio']:checked").val();
	mBfGmql(location_mBfGmql);

	var location_mjJaAFh = jQuery("#jform_location input[type='radio']:checked").val();
	mjJaAFh(location_mjJaAFh);

	var type_TOXvREx = jQuery("#jform_type").val();
	TOXvREx(type_TOXvREx);

	var type_yNpeHRj = jQuery("#jform_type").val();
	yNpeHRj(type_yNpeHRj);

	var type_LICWaSF = jQuery("#jform_type").val();
	LICWaSF(type_LICWaSF);

	var target_gtffPVJ = jQuery("#jform_target input[type='radio']:checked").val();
	gtffPVJ(target_gtffPVJ);
});

// the mBfGmql function
function mBfGmql(location_mBfGmql)
{
	// set the function logic
	if (location_mBfGmql == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the mjJaAFh function
function mjJaAFh(location_mjJaAFh)
{
	// set the function logic
	if (location_mjJaAFh == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the TOXvREx function
function TOXvREx(type_TOXvREx)
{
	if (isSet(type_TOXvREx) && type_TOXvREx.constructor !== Array)
	{
		var temp_TOXvREx = type_TOXvREx;
		var type_TOXvREx = [];
		type_TOXvREx.push(temp_TOXvREx);
	}
	else if (!isSet(type_TOXvREx))
	{
		var type_TOXvREx = [];
	}
	var type = type_TOXvREx.some(type_TOXvREx_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_TOXvRExNHV_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_TOXvRExNHV_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_TOXvRExNHV_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_TOXvRExNHV_required = true;
		}
	}
}

// the TOXvREx Some function
function type_TOXvREx_SomeFunc(type_TOXvREx)
{
	// set the function logic
	if (type_TOXvREx == 3)
	{
		return true;
	}
	return false;
}

// the yNpeHRj function
function yNpeHRj(type_yNpeHRj)
{
	if (isSet(type_yNpeHRj) && type_yNpeHRj.constructor !== Array)
	{
		var temp_yNpeHRj = type_yNpeHRj;
		var type_yNpeHRj = [];
		type_yNpeHRj.push(temp_yNpeHRj);
	}
	else if (!isSet(type_yNpeHRj))
	{
		var type_yNpeHRj = [];
	}
	var type = type_yNpeHRj.some(type_yNpeHRj_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_yNpeHRjZzq_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_yNpeHRjZzq_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_yNpeHRjZzq_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_yNpeHRjZzq_required = true;
		}
	}
}

// the yNpeHRj Some function
function type_yNpeHRj_SomeFunc(type_yNpeHRj)
{
	// set the function logic
	if (type_yNpeHRj == 1)
	{
		return true;
	}
	return false;
}

// the LICWaSF function
function LICWaSF(type_LICWaSF)
{
	if (isSet(type_LICWaSF) && type_LICWaSF.constructor !== Array)
	{
		var temp_LICWaSF = type_LICWaSF;
		var type_LICWaSF = [];
		type_LICWaSF.push(temp_LICWaSF);
	}
	else if (!isSet(type_LICWaSF))
	{
		var type_LICWaSF = [];
	}
	var type = type_LICWaSF.some(type_LICWaSF_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_LICWaSFazo_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_LICWaSFazo_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_LICWaSFazo_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_LICWaSFazo_required = true;
		}
	}
}

// the LICWaSF Some function
function type_LICWaSF_SomeFunc(type_LICWaSF)
{
	// set the function logic
	if (type_LICWaSF == 2)
	{
		return true;
	}
	return false;
}

// the gtffPVJ function
function gtffPVJ(target_gtffPVJ)
{
	// set the function logic
	if (target_gtffPVJ == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_gtffPVJpEk_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_gtffPVJpEk_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_gtffPVJpEk_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_gtffPVJpEk_required = true;
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
