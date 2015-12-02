/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.9
	@build			2nd December, 2015
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
jform_CiLvGkzvad_required = false;
jform_TBqocUAltU_required = false;
jform_dQlZimNoYJ_required = false;
jform_BBVrdICKeD_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_jxuQynV = jQuery("#jform_location input[type='radio']:checked").val();
	jxuQynV(location_jxuQynV);

	var location_tcNfMZq = jQuery("#jform_location input[type='radio']:checked").val();
	tcNfMZq(location_tcNfMZq);

	var type_CiLvGkz = jQuery("#jform_type").val();
	CiLvGkz(type_CiLvGkz);

	var type_TBqocUA = jQuery("#jform_type").val();
	TBqocUA(type_TBqocUA);

	var type_dQlZimN = jQuery("#jform_type").val();
	dQlZimN(type_dQlZimN);

	var target_BBVrdIC = jQuery("#jform_target input[type='radio']:checked").val();
	BBVrdIC(target_BBVrdIC);
});

// the jxuQynV function
function jxuQynV(location_jxuQynV)
{
	// [8017] set the function logic
	if (location_jxuQynV == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the tcNfMZq function
function tcNfMZq(location_tcNfMZq)
{
	// [8017] set the function logic
	if (location_tcNfMZq == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the CiLvGkz function
function CiLvGkz(type_CiLvGkz)
{
	if (isSet(type_CiLvGkz) && type_CiLvGkz.constructor !== Array)
	{
		var temp_CiLvGkz = type_CiLvGkz;
		var type_CiLvGkz = [];
		type_CiLvGkz.push(temp_CiLvGkz);
	}
	else if (!isSet(type_CiLvGkz))
	{
		var type_CiLvGkz = [];
	}
	var type = type_CiLvGkz.some(type_CiLvGkz_SomeFunc);


	// [7995] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_CiLvGkzvad_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_CiLvGkzvad_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_CiLvGkzvad_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_CiLvGkzvad_required = true;
		}
	}
}

// the CiLvGkz Some function
function type_CiLvGkz_SomeFunc(type_CiLvGkz)
{
	// [7982] set the function logic
	if (type_CiLvGkz == 3)
	{
		return true;
	}
	return false;
}

// the TBqocUA function
function TBqocUA(type_TBqocUA)
{
	if (isSet(type_TBqocUA) && type_TBqocUA.constructor !== Array)
	{
		var temp_TBqocUA = type_TBqocUA;
		var type_TBqocUA = [];
		type_TBqocUA.push(temp_TBqocUA);
	}
	else if (!isSet(type_TBqocUA))
	{
		var type_TBqocUA = [];
	}
	var type = type_TBqocUA.some(type_TBqocUA_SomeFunc);


	// [7995] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_TBqocUAltU_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_TBqocUAltU_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_TBqocUAltU_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_TBqocUAltU_required = true;
		}
	}
}

// the TBqocUA Some function
function type_TBqocUA_SomeFunc(type_TBqocUA)
{
	// [7982] set the function logic
	if (type_TBqocUA == 1)
	{
		return true;
	}
	return false;
}

// the dQlZimN function
function dQlZimN(type_dQlZimN)
{
	if (isSet(type_dQlZimN) && type_dQlZimN.constructor !== Array)
	{
		var temp_dQlZimN = type_dQlZimN;
		var type_dQlZimN = [];
		type_dQlZimN.push(temp_dQlZimN);
	}
	else if (!isSet(type_dQlZimN))
	{
		var type_dQlZimN = [];
	}
	var type = type_dQlZimN.some(type_dQlZimN_SomeFunc);


	// [7995] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_dQlZimNoYJ_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_dQlZimNoYJ_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_dQlZimNoYJ_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_dQlZimNoYJ_required = true;
		}
	}
}

// the dQlZimN Some function
function type_dQlZimN_SomeFunc(type_dQlZimN)
{
	// [7982] set the function logic
	if (type_dQlZimN == 2)
	{
		return true;
	}
	return false;
}

// the BBVrdIC function
function BBVrdIC(target_BBVrdIC)
{
	// [8017] set the function logic
	if (target_BBVrdIC == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_BBVrdICKeD_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_BBVrdICKeD_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_BBVrdICKeD_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_BBVrdICKeD_required = true;
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
