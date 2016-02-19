/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.2
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
jform_hVGnssptik_required = false;
jform_oCoSRkNohn_required = false;
jform_sPmjArzBrm_required = false;
jform_FynoIBCSbK_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_sZyfKYR = jQuery("#jform_location input[type='radio']:checked").val();
	sZyfKYR(location_sZyfKYR);

	var location_NZsaZHc = jQuery("#jform_location input[type='radio']:checked").val();
	NZsaZHc(location_NZsaZHc);

	var type_hVGnssp = jQuery("#jform_type").val();
	hVGnssp(type_hVGnssp);

	var type_oCoSRkN = jQuery("#jform_type").val();
	oCoSRkN(type_oCoSRkN);

	var type_sPmjArz = jQuery("#jform_type").val();
	sPmjArz(type_sPmjArz);

	var target_FynoIBC = jQuery("#jform_target input[type='radio']:checked").val();
	FynoIBC(target_FynoIBC);
});

// the sZyfKYR function
function sZyfKYR(location_sZyfKYR)
{
	// set the function logic
	if (location_sZyfKYR == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the NZsaZHc function
function NZsaZHc(location_NZsaZHc)
{
	// set the function logic
	if (location_NZsaZHc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the hVGnssp function
function hVGnssp(type_hVGnssp)
{
	if (isSet(type_hVGnssp) && type_hVGnssp.constructor !== Array)
	{
		var temp_hVGnssp = type_hVGnssp;
		var type_hVGnssp = [];
		type_hVGnssp.push(temp_hVGnssp);
	}
	else if (!isSet(type_hVGnssp))
	{
		var type_hVGnssp = [];
	}
	var type = type_hVGnssp.some(type_hVGnssp_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_hVGnssptik_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_hVGnssptik_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_hVGnssptik_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_hVGnssptik_required = true;
		}
	}
}

// the hVGnssp Some function
function type_hVGnssp_SomeFunc(type_hVGnssp)
{
	// set the function logic
	if (type_hVGnssp == 3)
	{
		return true;
	}
	return false;
}

// the oCoSRkN function
function oCoSRkN(type_oCoSRkN)
{
	if (isSet(type_oCoSRkN) && type_oCoSRkN.constructor !== Array)
	{
		var temp_oCoSRkN = type_oCoSRkN;
		var type_oCoSRkN = [];
		type_oCoSRkN.push(temp_oCoSRkN);
	}
	else if (!isSet(type_oCoSRkN))
	{
		var type_oCoSRkN = [];
	}
	var type = type_oCoSRkN.some(type_oCoSRkN_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_oCoSRkNohn_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_oCoSRkNohn_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_oCoSRkNohn_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_oCoSRkNohn_required = true;
		}
	}
}

// the oCoSRkN Some function
function type_oCoSRkN_SomeFunc(type_oCoSRkN)
{
	// set the function logic
	if (type_oCoSRkN == 1)
	{
		return true;
	}
	return false;
}

// the sPmjArz function
function sPmjArz(type_sPmjArz)
{
	if (isSet(type_sPmjArz) && type_sPmjArz.constructor !== Array)
	{
		var temp_sPmjArz = type_sPmjArz;
		var type_sPmjArz = [];
		type_sPmjArz.push(temp_sPmjArz);
	}
	else if (!isSet(type_sPmjArz))
	{
		var type_sPmjArz = [];
	}
	var type = type_sPmjArz.some(type_sPmjArz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_sPmjArzBrm_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_sPmjArzBrm_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_sPmjArzBrm_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_sPmjArzBrm_required = true;
		}
	}
}

// the sPmjArz Some function
function type_sPmjArz_SomeFunc(type_sPmjArz)
{
	// set the function logic
	if (type_sPmjArz == 2)
	{
		return true;
	}
	return false;
}

// the FynoIBC function
function FynoIBC(target_FynoIBC)
{
	// set the function logic
	if (target_FynoIBC == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_FynoIBCSbK_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_FynoIBCSbK_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_FynoIBCSbK_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_FynoIBCSbK_required = true;
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
