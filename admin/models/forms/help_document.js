/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.4
	@build			19th February, 2016
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
jform_uejVBuHOGA_required = false;
jform_iaDOoTnQCH_required = false;
jform_noYjlmHfWk_required = false;
jform_artksODZGn_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_vBwsUmZ = jQuery("#jform_location input[type='radio']:checked").val();
	vBwsUmZ(location_vBwsUmZ);

	var location_UsqRLKW = jQuery("#jform_location input[type='radio']:checked").val();
	UsqRLKW(location_UsqRLKW);

	var type_uejVBuH = jQuery("#jform_type").val();
	uejVBuH(type_uejVBuH);

	var type_iaDOoTn = jQuery("#jform_type").val();
	iaDOoTn(type_iaDOoTn);

	var type_noYjlmH = jQuery("#jform_type").val();
	noYjlmH(type_noYjlmH);

	var target_artksOD = jQuery("#jform_target input[type='radio']:checked").val();
	artksOD(target_artksOD);
});

// the vBwsUmZ function
function vBwsUmZ(location_vBwsUmZ)
{
	// set the function logic
	if (location_vBwsUmZ == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the UsqRLKW function
function UsqRLKW(location_UsqRLKW)
{
	// set the function logic
	if (location_UsqRLKW == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the uejVBuH function
function uejVBuH(type_uejVBuH)
{
	if (isSet(type_uejVBuH) && type_uejVBuH.constructor !== Array)
	{
		var temp_uejVBuH = type_uejVBuH;
		var type_uejVBuH = [];
		type_uejVBuH.push(temp_uejVBuH);
	}
	else if (!isSet(type_uejVBuH))
	{
		var type_uejVBuH = [];
	}
	var type = type_uejVBuH.some(type_uejVBuH_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_uejVBuHOGA_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_uejVBuHOGA_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_uejVBuHOGA_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_uejVBuHOGA_required = true;
		}
	}
}

// the uejVBuH Some function
function type_uejVBuH_SomeFunc(type_uejVBuH)
{
	// set the function logic
	if (type_uejVBuH == 3)
	{
		return true;
	}
	return false;
}

// the iaDOoTn function
function iaDOoTn(type_iaDOoTn)
{
	if (isSet(type_iaDOoTn) && type_iaDOoTn.constructor !== Array)
	{
		var temp_iaDOoTn = type_iaDOoTn;
		var type_iaDOoTn = [];
		type_iaDOoTn.push(temp_iaDOoTn);
	}
	else if (!isSet(type_iaDOoTn))
	{
		var type_iaDOoTn = [];
	}
	var type = type_iaDOoTn.some(type_iaDOoTn_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_iaDOoTnQCH_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_iaDOoTnQCH_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_iaDOoTnQCH_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_iaDOoTnQCH_required = true;
		}
	}
}

// the iaDOoTn Some function
function type_iaDOoTn_SomeFunc(type_iaDOoTn)
{
	// set the function logic
	if (type_iaDOoTn == 1)
	{
		return true;
	}
	return false;
}

// the noYjlmH function
function noYjlmH(type_noYjlmH)
{
	if (isSet(type_noYjlmH) && type_noYjlmH.constructor !== Array)
	{
		var temp_noYjlmH = type_noYjlmH;
		var type_noYjlmH = [];
		type_noYjlmH.push(temp_noYjlmH);
	}
	else if (!isSet(type_noYjlmH))
	{
		var type_noYjlmH = [];
	}
	var type = type_noYjlmH.some(type_noYjlmH_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_noYjlmHfWk_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_noYjlmHfWk_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_noYjlmHfWk_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_noYjlmHfWk_required = true;
		}
	}
}

// the noYjlmH Some function
function type_noYjlmH_SomeFunc(type_noYjlmH)
{
	// set the function logic
	if (type_noYjlmH == 2)
	{
		return true;
	}
	return false;
}

// the artksOD function
function artksOD(target_artksOD)
{
	// set the function logic
	if (target_artksOD == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_artksODZGn_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_artksODZGn_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_artksODZGn_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_artksODZGn_required = true;
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
