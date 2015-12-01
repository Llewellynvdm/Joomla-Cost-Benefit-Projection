/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			1st December, 2015
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
jform_NyyUdhGVEZ_required = false;
jform_pmvIgHONIS_required = false;
jform_EaIzsbhXes_required = false;
jform_jRYBNcOmXz_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_KVbOrVT = jQuery("#jform_location input[type='radio']:checked").val();
	KVbOrVT(location_KVbOrVT);

	var location_snHFbml = jQuery("#jform_location input[type='radio']:checked").val();
	snHFbml(location_snHFbml);

	var type_NyyUdhG = jQuery("#jform_type").val();
	NyyUdhG(type_NyyUdhG);

	var type_pmvIgHO = jQuery("#jform_type").val();
	pmvIgHO(type_pmvIgHO);

	var type_EaIzsbh = jQuery("#jform_type").val();
	EaIzsbh(type_EaIzsbh);

	var target_jRYBNcO = jQuery("#jform_target input[type='radio']:checked").val();
	jRYBNcO(target_jRYBNcO);
});

// the KVbOrVT function
function KVbOrVT(location_KVbOrVT)
{
	// [8008] set the function logic
	if (location_KVbOrVT == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the snHFbml function
function snHFbml(location_snHFbml)
{
	// [8008] set the function logic
	if (location_snHFbml == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the NyyUdhG function
function NyyUdhG(type_NyyUdhG)
{
	if (isSet(type_NyyUdhG) && type_NyyUdhG.constructor !== Array)
	{
		var temp_NyyUdhG = type_NyyUdhG;
		var type_NyyUdhG = [];
		type_NyyUdhG.push(temp_NyyUdhG);
	}
	else if (!isSet(type_NyyUdhG))
	{
		var type_NyyUdhG = [];
	}
	var type = type_NyyUdhG.some(type_NyyUdhG_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_NyyUdhGVEZ_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_NyyUdhGVEZ_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_NyyUdhGVEZ_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_NyyUdhGVEZ_required = true;
		}
	}
}

// the NyyUdhG Some function
function type_NyyUdhG_SomeFunc(type_NyyUdhG)
{
	// [7973] set the function logic
	if (type_NyyUdhG == 3)
	{
		return true;
	}
	return false;
}

// the pmvIgHO function
function pmvIgHO(type_pmvIgHO)
{
	if (isSet(type_pmvIgHO) && type_pmvIgHO.constructor !== Array)
	{
		var temp_pmvIgHO = type_pmvIgHO;
		var type_pmvIgHO = [];
		type_pmvIgHO.push(temp_pmvIgHO);
	}
	else if (!isSet(type_pmvIgHO))
	{
		var type_pmvIgHO = [];
	}
	var type = type_pmvIgHO.some(type_pmvIgHO_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_pmvIgHONIS_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_pmvIgHONIS_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_pmvIgHONIS_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_pmvIgHONIS_required = true;
		}
	}
}

// the pmvIgHO Some function
function type_pmvIgHO_SomeFunc(type_pmvIgHO)
{
	// [7973] set the function logic
	if (type_pmvIgHO == 1)
	{
		return true;
	}
	return false;
}

// the EaIzsbh function
function EaIzsbh(type_EaIzsbh)
{
	if (isSet(type_EaIzsbh) && type_EaIzsbh.constructor !== Array)
	{
		var temp_EaIzsbh = type_EaIzsbh;
		var type_EaIzsbh = [];
		type_EaIzsbh.push(temp_EaIzsbh);
	}
	else if (!isSet(type_EaIzsbh))
	{
		var type_EaIzsbh = [];
	}
	var type = type_EaIzsbh.some(type_EaIzsbh_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_EaIzsbhXes_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_EaIzsbhXes_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_EaIzsbhXes_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_EaIzsbhXes_required = true;
		}
	}
}

// the EaIzsbh Some function
function type_EaIzsbh_SomeFunc(type_EaIzsbh)
{
	// [7973] set the function logic
	if (type_EaIzsbh == 2)
	{
		return true;
	}
	return false;
}

// the jRYBNcO function
function jRYBNcO(target_jRYBNcO)
{
	// [8008] set the function logic
	if (target_jRYBNcO == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_jRYBNcOmXz_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_jRYBNcOmXz_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_jRYBNcOmXz_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_jRYBNcOmXz_required = true;
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
