/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.7
	@build			26th February, 2016
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
jform_uaucSuGNjV_required = false;
jform_zvCVyOsKKE_required = false;
jform_nfdzusQTGQ_required = false;
jform_vgRPkIjOvp_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_nXpzqtd = jQuery("#jform_location input[type='radio']:checked").val();
	nXpzqtd(location_nXpzqtd);

	var location_uktrraJ = jQuery("#jform_location input[type='radio']:checked").val();
	uktrraJ(location_uktrraJ);

	var type_uaucSuG = jQuery("#jform_type").val();
	uaucSuG(type_uaucSuG);

	var type_zvCVyOs = jQuery("#jform_type").val();
	zvCVyOs(type_zvCVyOs);

	var type_nfdzusQ = jQuery("#jform_type").val();
	nfdzusQ(type_nfdzusQ);

	var target_vgRPkIj = jQuery("#jform_target input[type='radio']:checked").val();
	vgRPkIj(target_vgRPkIj);
});

// the nXpzqtd function
function nXpzqtd(location_nXpzqtd)
{
	// set the function logic
	if (location_nXpzqtd == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the uktrraJ function
function uktrraJ(location_uktrraJ)
{
	// set the function logic
	if (location_uktrraJ == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the uaucSuG function
function uaucSuG(type_uaucSuG)
{
	if (isSet(type_uaucSuG) && type_uaucSuG.constructor !== Array)
	{
		var temp_uaucSuG = type_uaucSuG;
		var type_uaucSuG = [];
		type_uaucSuG.push(temp_uaucSuG);
	}
	else if (!isSet(type_uaucSuG))
	{
		var type_uaucSuG = [];
	}
	var type = type_uaucSuG.some(type_uaucSuG_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_uaucSuGNjV_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_uaucSuGNjV_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_uaucSuGNjV_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_uaucSuGNjV_required = true;
		}
	}
}

// the uaucSuG Some function
function type_uaucSuG_SomeFunc(type_uaucSuG)
{
	// set the function logic
	if (type_uaucSuG == 3)
	{
		return true;
	}
	return false;
}

// the zvCVyOs function
function zvCVyOs(type_zvCVyOs)
{
	if (isSet(type_zvCVyOs) && type_zvCVyOs.constructor !== Array)
	{
		var temp_zvCVyOs = type_zvCVyOs;
		var type_zvCVyOs = [];
		type_zvCVyOs.push(temp_zvCVyOs);
	}
	else if (!isSet(type_zvCVyOs))
	{
		var type_zvCVyOs = [];
	}
	var type = type_zvCVyOs.some(type_zvCVyOs_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_zvCVyOsKKE_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_zvCVyOsKKE_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_zvCVyOsKKE_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_zvCVyOsKKE_required = true;
		}
	}
}

// the zvCVyOs Some function
function type_zvCVyOs_SomeFunc(type_zvCVyOs)
{
	// set the function logic
	if (type_zvCVyOs == 1)
	{
		return true;
	}
	return false;
}

// the nfdzusQ function
function nfdzusQ(type_nfdzusQ)
{
	if (isSet(type_nfdzusQ) && type_nfdzusQ.constructor !== Array)
	{
		var temp_nfdzusQ = type_nfdzusQ;
		var type_nfdzusQ = [];
		type_nfdzusQ.push(temp_nfdzusQ);
	}
	else if (!isSet(type_nfdzusQ))
	{
		var type_nfdzusQ = [];
	}
	var type = type_nfdzusQ.some(type_nfdzusQ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_nfdzusQTGQ_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_nfdzusQTGQ_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_nfdzusQTGQ_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_nfdzusQTGQ_required = true;
		}
	}
}

// the nfdzusQ Some function
function type_nfdzusQ_SomeFunc(type_nfdzusQ)
{
	// set the function logic
	if (type_nfdzusQ == 2)
	{
		return true;
	}
	return false;
}

// the vgRPkIj function
function vgRPkIj(target_vgRPkIj)
{
	// set the function logic
	if (target_vgRPkIj == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_vgRPkIjOvp_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vgRPkIjOvp_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vgRPkIjOvp_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vgRPkIjOvp_required = true;
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
