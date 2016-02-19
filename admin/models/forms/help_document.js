/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.3
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
jform_NZvaKPvRvv_required = false;
jform_VXbzrvHLWl_required = false;
jform_jFtkKQzHmd_required = false;
jform_pbdHcHxEDb_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_ztxDBdb = jQuery("#jform_location input[type='radio']:checked").val();
	ztxDBdb(location_ztxDBdb);

	var location_kwiRtLE = jQuery("#jform_location input[type='radio']:checked").val();
	kwiRtLE(location_kwiRtLE);

	var type_NZvaKPv = jQuery("#jform_type").val();
	NZvaKPv(type_NZvaKPv);

	var type_VXbzrvH = jQuery("#jform_type").val();
	VXbzrvH(type_VXbzrvH);

	var type_jFtkKQz = jQuery("#jform_type").val();
	jFtkKQz(type_jFtkKQz);

	var target_pbdHcHx = jQuery("#jform_target input[type='radio']:checked").val();
	pbdHcHx(target_pbdHcHx);
});

// the ztxDBdb function
function ztxDBdb(location_ztxDBdb)
{
	// set the function logic
	if (location_ztxDBdb == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the kwiRtLE function
function kwiRtLE(location_kwiRtLE)
{
	// set the function logic
	if (location_kwiRtLE == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the NZvaKPv function
function NZvaKPv(type_NZvaKPv)
{
	if (isSet(type_NZvaKPv) && type_NZvaKPv.constructor !== Array)
	{
		var temp_NZvaKPv = type_NZvaKPv;
		var type_NZvaKPv = [];
		type_NZvaKPv.push(temp_NZvaKPv);
	}
	else if (!isSet(type_NZvaKPv))
	{
		var type_NZvaKPv = [];
	}
	var type = type_NZvaKPv.some(type_NZvaKPv_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_NZvaKPvRvv_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_NZvaKPvRvv_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_NZvaKPvRvv_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_NZvaKPvRvv_required = true;
		}
	}
}

// the NZvaKPv Some function
function type_NZvaKPv_SomeFunc(type_NZvaKPv)
{
	// set the function logic
	if (type_NZvaKPv == 3)
	{
		return true;
	}
	return false;
}

// the VXbzrvH function
function VXbzrvH(type_VXbzrvH)
{
	if (isSet(type_VXbzrvH) && type_VXbzrvH.constructor !== Array)
	{
		var temp_VXbzrvH = type_VXbzrvH;
		var type_VXbzrvH = [];
		type_VXbzrvH.push(temp_VXbzrvH);
	}
	else if (!isSet(type_VXbzrvH))
	{
		var type_VXbzrvH = [];
	}
	var type = type_VXbzrvH.some(type_VXbzrvH_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_VXbzrvHLWl_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_VXbzrvHLWl_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_VXbzrvHLWl_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_VXbzrvHLWl_required = true;
		}
	}
}

// the VXbzrvH Some function
function type_VXbzrvH_SomeFunc(type_VXbzrvH)
{
	// set the function logic
	if (type_VXbzrvH == 1)
	{
		return true;
	}
	return false;
}

// the jFtkKQz function
function jFtkKQz(type_jFtkKQz)
{
	if (isSet(type_jFtkKQz) && type_jFtkKQz.constructor !== Array)
	{
		var temp_jFtkKQz = type_jFtkKQz;
		var type_jFtkKQz = [];
		type_jFtkKQz.push(temp_jFtkKQz);
	}
	else if (!isSet(type_jFtkKQz))
	{
		var type_jFtkKQz = [];
	}
	var type = type_jFtkKQz.some(type_jFtkKQz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_jFtkKQzHmd_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_jFtkKQzHmd_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_jFtkKQzHmd_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_jFtkKQzHmd_required = true;
		}
	}
}

// the jFtkKQz Some function
function type_jFtkKQz_SomeFunc(type_jFtkKQz)
{
	// set the function logic
	if (type_jFtkKQz == 2)
	{
		return true;
	}
	return false;
}

// the pbdHcHx function
function pbdHcHx(target_pbdHcHx)
{
	// set the function logic
	if (target_pbdHcHx == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_pbdHcHxEDb_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_pbdHcHxEDb_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_pbdHcHxEDb_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_pbdHcHxEDb_required = true;
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
