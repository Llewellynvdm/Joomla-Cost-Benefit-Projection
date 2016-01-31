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
jform_HuySipbXMd_required = false;
jform_fXzTOAQdkV_required = false;
jform_gwsGpjJmDB_required = false;
jform_ceQBWWOXTD_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_uJceost = jQuery("#jform_location input[type='radio']:checked").val();
	uJceost(location_uJceost);

	var location_NVhbnMP = jQuery("#jform_location input[type='radio']:checked").val();
	NVhbnMP(location_NVhbnMP);

	var type_HuySipb = jQuery("#jform_type").val();
	HuySipb(type_HuySipb);

	var type_fXzTOAQ = jQuery("#jform_type").val();
	fXzTOAQ(type_fXzTOAQ);

	var type_gwsGpjJ = jQuery("#jform_type").val();
	gwsGpjJ(type_gwsGpjJ);

	var target_ceQBWWO = jQuery("#jform_target input[type='radio']:checked").val();
	ceQBWWO(target_ceQBWWO);
});

// the uJceost function
function uJceost(location_uJceost)
{
	// set the function logic
	if (location_uJceost == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the NVhbnMP function
function NVhbnMP(location_NVhbnMP)
{
	// set the function logic
	if (location_NVhbnMP == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the HuySipb function
function HuySipb(type_HuySipb)
{
	if (isSet(type_HuySipb) && type_HuySipb.constructor !== Array)
	{
		var temp_HuySipb = type_HuySipb;
		var type_HuySipb = [];
		type_HuySipb.push(temp_HuySipb);
	}
	else if (!isSet(type_HuySipb))
	{
		var type_HuySipb = [];
	}
	var type = type_HuySipb.some(type_HuySipb_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_HuySipbXMd_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_HuySipbXMd_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_HuySipbXMd_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_HuySipbXMd_required = true;
		}
	}
}

// the HuySipb Some function
function type_HuySipb_SomeFunc(type_HuySipb)
{
	// set the function logic
	if (type_HuySipb == 3)
	{
		return true;
	}
	return false;
}

// the fXzTOAQ function
function fXzTOAQ(type_fXzTOAQ)
{
	if (isSet(type_fXzTOAQ) && type_fXzTOAQ.constructor !== Array)
	{
		var temp_fXzTOAQ = type_fXzTOAQ;
		var type_fXzTOAQ = [];
		type_fXzTOAQ.push(temp_fXzTOAQ);
	}
	else if (!isSet(type_fXzTOAQ))
	{
		var type_fXzTOAQ = [];
	}
	var type = type_fXzTOAQ.some(type_fXzTOAQ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_fXzTOAQdkV_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_fXzTOAQdkV_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_fXzTOAQdkV_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_fXzTOAQdkV_required = true;
		}
	}
}

// the fXzTOAQ Some function
function type_fXzTOAQ_SomeFunc(type_fXzTOAQ)
{
	// set the function logic
	if (type_fXzTOAQ == 1)
	{
		return true;
	}
	return false;
}

// the gwsGpjJ function
function gwsGpjJ(type_gwsGpjJ)
{
	if (isSet(type_gwsGpjJ) && type_gwsGpjJ.constructor !== Array)
	{
		var temp_gwsGpjJ = type_gwsGpjJ;
		var type_gwsGpjJ = [];
		type_gwsGpjJ.push(temp_gwsGpjJ);
	}
	else if (!isSet(type_gwsGpjJ))
	{
		var type_gwsGpjJ = [];
	}
	var type = type_gwsGpjJ.some(type_gwsGpjJ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_gwsGpjJmDB_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_gwsGpjJmDB_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_gwsGpjJmDB_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_gwsGpjJmDB_required = true;
		}
	}
}

// the gwsGpjJ Some function
function type_gwsGpjJ_SomeFunc(type_gwsGpjJ)
{
	// set the function logic
	if (type_gwsGpjJ == 2)
	{
		return true;
	}
	return false;
}

// the ceQBWWO function
function ceQBWWO(target_ceQBWWO)
{
	// set the function logic
	if (target_ceQBWWO == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_ceQBWWOXTD_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_ceQBWWOXTD_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_ceQBWWOXTD_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_ceQBWWOXTD_required = true;
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
