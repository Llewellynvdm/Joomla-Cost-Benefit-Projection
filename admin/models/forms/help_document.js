/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.6
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
jform_XBcfcHoKCW_required = false;
jform_TVCOcIzKNT_required = false;
jform_jpykTVkQjz_required = false;
jform_eibemeGwQn_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_MbvtJEY = jQuery("#jform_location input[type='radio']:checked").val();
	MbvtJEY(location_MbvtJEY);

	var location_FqaWrpo = jQuery("#jform_location input[type='radio']:checked").val();
	FqaWrpo(location_FqaWrpo);

	var type_XBcfcHo = jQuery("#jform_type").val();
	XBcfcHo(type_XBcfcHo);

	var type_TVCOcIz = jQuery("#jform_type").val();
	TVCOcIz(type_TVCOcIz);

	var type_jpykTVk = jQuery("#jform_type").val();
	jpykTVk(type_jpykTVk);

	var target_eibemeG = jQuery("#jform_target input[type='radio']:checked").val();
	eibemeG(target_eibemeG);
});

// the MbvtJEY function
function MbvtJEY(location_MbvtJEY)
{
	// set the function logic
	if (location_MbvtJEY == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the FqaWrpo function
function FqaWrpo(location_FqaWrpo)
{
	// set the function logic
	if (location_FqaWrpo == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the XBcfcHo function
function XBcfcHo(type_XBcfcHo)
{
	if (isSet(type_XBcfcHo) && type_XBcfcHo.constructor !== Array)
	{
		var temp_XBcfcHo = type_XBcfcHo;
		var type_XBcfcHo = [];
		type_XBcfcHo.push(temp_XBcfcHo);
	}
	else if (!isSet(type_XBcfcHo))
	{
		var type_XBcfcHo = [];
	}
	var type = type_XBcfcHo.some(type_XBcfcHo_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_XBcfcHoKCW_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_XBcfcHoKCW_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_XBcfcHoKCW_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_XBcfcHoKCW_required = true;
		}
	}
}

// the XBcfcHo Some function
function type_XBcfcHo_SomeFunc(type_XBcfcHo)
{
	// set the function logic
	if (type_XBcfcHo == 3)
	{
		return true;
	}
	return false;
}

// the TVCOcIz function
function TVCOcIz(type_TVCOcIz)
{
	if (isSet(type_TVCOcIz) && type_TVCOcIz.constructor !== Array)
	{
		var temp_TVCOcIz = type_TVCOcIz;
		var type_TVCOcIz = [];
		type_TVCOcIz.push(temp_TVCOcIz);
	}
	else if (!isSet(type_TVCOcIz))
	{
		var type_TVCOcIz = [];
	}
	var type = type_TVCOcIz.some(type_TVCOcIz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_TVCOcIzKNT_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_TVCOcIzKNT_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_TVCOcIzKNT_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_TVCOcIzKNT_required = true;
		}
	}
}

// the TVCOcIz Some function
function type_TVCOcIz_SomeFunc(type_TVCOcIz)
{
	// set the function logic
	if (type_TVCOcIz == 1)
	{
		return true;
	}
	return false;
}

// the jpykTVk function
function jpykTVk(type_jpykTVk)
{
	if (isSet(type_jpykTVk) && type_jpykTVk.constructor !== Array)
	{
		var temp_jpykTVk = type_jpykTVk;
		var type_jpykTVk = [];
		type_jpykTVk.push(temp_jpykTVk);
	}
	else if (!isSet(type_jpykTVk))
	{
		var type_jpykTVk = [];
	}
	var type = type_jpykTVk.some(type_jpykTVk_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_jpykTVkQjz_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_jpykTVkQjz_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_jpykTVkQjz_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_jpykTVkQjz_required = true;
		}
	}
}

// the jpykTVk Some function
function type_jpykTVk_SomeFunc(type_jpykTVk)
{
	// set the function logic
	if (type_jpykTVk == 2)
	{
		return true;
	}
	return false;
}

// the eibemeG function
function eibemeG(target_eibemeG)
{
	// set the function logic
	if (target_eibemeG == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_eibemeGwQn_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_eibemeGwQn_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_eibemeGwQn_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_eibemeGwQn_required = true;
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
