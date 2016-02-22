/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.5
	@build			22nd February, 2016
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
jform_qDDgGiNYyX_required = false;
jform_wVtDCYhiQV_required = false;
jform_xYrjdyEars_required = false;
jform_FEQkJCtDBS_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_yTJjiqR = jQuery("#jform_location input[type='radio']:checked").val();
	yTJjiqR(location_yTJjiqR);

	var location_dlBWMKL = jQuery("#jform_location input[type='radio']:checked").val();
	dlBWMKL(location_dlBWMKL);

	var type_qDDgGiN = jQuery("#jform_type").val();
	qDDgGiN(type_qDDgGiN);

	var type_wVtDCYh = jQuery("#jform_type").val();
	wVtDCYh(type_wVtDCYh);

	var type_xYrjdyE = jQuery("#jform_type").val();
	xYrjdyE(type_xYrjdyE);

	var target_FEQkJCt = jQuery("#jform_target input[type='radio']:checked").val();
	FEQkJCt(target_FEQkJCt);
});

// the yTJjiqR function
function yTJjiqR(location_yTJjiqR)
{
	// set the function logic
	if (location_yTJjiqR == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the dlBWMKL function
function dlBWMKL(location_dlBWMKL)
{
	// set the function logic
	if (location_dlBWMKL == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the qDDgGiN function
function qDDgGiN(type_qDDgGiN)
{
	if (isSet(type_qDDgGiN) && type_qDDgGiN.constructor !== Array)
	{
		var temp_qDDgGiN = type_qDDgGiN;
		var type_qDDgGiN = [];
		type_qDDgGiN.push(temp_qDDgGiN);
	}
	else if (!isSet(type_qDDgGiN))
	{
		var type_qDDgGiN = [];
	}
	var type = type_qDDgGiN.some(type_qDDgGiN_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_qDDgGiNYyX_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_qDDgGiNYyX_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_qDDgGiNYyX_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_qDDgGiNYyX_required = true;
		}
	}
}

// the qDDgGiN Some function
function type_qDDgGiN_SomeFunc(type_qDDgGiN)
{
	// set the function logic
	if (type_qDDgGiN == 3)
	{
		return true;
	}
	return false;
}

// the wVtDCYh function
function wVtDCYh(type_wVtDCYh)
{
	if (isSet(type_wVtDCYh) && type_wVtDCYh.constructor !== Array)
	{
		var temp_wVtDCYh = type_wVtDCYh;
		var type_wVtDCYh = [];
		type_wVtDCYh.push(temp_wVtDCYh);
	}
	else if (!isSet(type_wVtDCYh))
	{
		var type_wVtDCYh = [];
	}
	var type = type_wVtDCYh.some(type_wVtDCYh_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_wVtDCYhiQV_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_wVtDCYhiQV_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_wVtDCYhiQV_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_wVtDCYhiQV_required = true;
		}
	}
}

// the wVtDCYh Some function
function type_wVtDCYh_SomeFunc(type_wVtDCYh)
{
	// set the function logic
	if (type_wVtDCYh == 1)
	{
		return true;
	}
	return false;
}

// the xYrjdyE function
function xYrjdyE(type_xYrjdyE)
{
	if (isSet(type_xYrjdyE) && type_xYrjdyE.constructor !== Array)
	{
		var temp_xYrjdyE = type_xYrjdyE;
		var type_xYrjdyE = [];
		type_xYrjdyE.push(temp_xYrjdyE);
	}
	else if (!isSet(type_xYrjdyE))
	{
		var type_xYrjdyE = [];
	}
	var type = type_xYrjdyE.some(type_xYrjdyE_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_xYrjdyEars_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_xYrjdyEars_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_xYrjdyEars_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_xYrjdyEars_required = true;
		}
	}
}

// the xYrjdyE Some function
function type_xYrjdyE_SomeFunc(type_xYrjdyE)
{
	// set the function logic
	if (type_xYrjdyE == 2)
	{
		return true;
	}
	return false;
}

// the FEQkJCt function
function FEQkJCt(target_FEQkJCt)
{
	// set the function logic
	if (target_FEQkJCt == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_FEQkJCtDBS_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_FEQkJCtDBS_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_FEQkJCtDBS_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_FEQkJCtDBS_required = true;
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
