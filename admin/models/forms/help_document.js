/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.1.0
	@build			6th January, 2016
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
jform_GScdMmesxu_required = false;
jform_qxrBrKuaVU_required = false;
jform_hLJJtZdZWj_required = false;
jform_fBbhMNsOcX_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_IrzgNQC = jQuery("#jform_location input[type='radio']:checked").val();
	IrzgNQC(location_IrzgNQC);

	var location_NqpFWgf = jQuery("#jform_location input[type='radio']:checked").val();
	NqpFWgf(location_NqpFWgf);

	var type_GScdMme = jQuery("#jform_type").val();
	GScdMme(type_GScdMme);

	var type_qxrBrKu = jQuery("#jform_type").val();
	qxrBrKu(type_qxrBrKu);

	var type_hLJJtZd = jQuery("#jform_type").val();
	hLJJtZd(type_hLJJtZd);

	var target_fBbhMNs = jQuery("#jform_target input[type='radio']:checked").val();
	fBbhMNs(target_fBbhMNs);
});

// the IrzgNQC function
function IrzgNQC(location_IrzgNQC)
{
	// [8269] set the function logic
	if (location_IrzgNQC == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the NqpFWgf function
function NqpFWgf(location_NqpFWgf)
{
	// [8269] set the function logic
	if (location_NqpFWgf == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the GScdMme function
function GScdMme(type_GScdMme)
{
	if (isSet(type_GScdMme) && type_GScdMme.constructor !== Array)
	{
		var temp_GScdMme = type_GScdMme;
		var type_GScdMme = [];
		type_GScdMme.push(temp_GScdMme);
	}
	else if (!isSet(type_GScdMme))
	{
		var type_GScdMme = [];
	}
	var type = type_GScdMme.some(type_GScdMme_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_GScdMmesxu_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_GScdMmesxu_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_GScdMmesxu_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_GScdMmesxu_required = true;
		}
	}
}

// the GScdMme Some function
function type_GScdMme_SomeFunc(type_GScdMme)
{
	// [8234] set the function logic
	if (type_GScdMme == 3)
	{
		return true;
	}
	return false;
}

// the qxrBrKu function
function qxrBrKu(type_qxrBrKu)
{
	if (isSet(type_qxrBrKu) && type_qxrBrKu.constructor !== Array)
	{
		var temp_qxrBrKu = type_qxrBrKu;
		var type_qxrBrKu = [];
		type_qxrBrKu.push(temp_qxrBrKu);
	}
	else if (!isSet(type_qxrBrKu))
	{
		var type_qxrBrKu = [];
	}
	var type = type_qxrBrKu.some(type_qxrBrKu_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_qxrBrKuaVU_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_qxrBrKuaVU_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_qxrBrKuaVU_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_qxrBrKuaVU_required = true;
		}
	}
}

// the qxrBrKu Some function
function type_qxrBrKu_SomeFunc(type_qxrBrKu)
{
	// [8234] set the function logic
	if (type_qxrBrKu == 1)
	{
		return true;
	}
	return false;
}

// the hLJJtZd function
function hLJJtZd(type_hLJJtZd)
{
	if (isSet(type_hLJJtZd) && type_hLJJtZd.constructor !== Array)
	{
		var temp_hLJJtZd = type_hLJJtZd;
		var type_hLJJtZd = [];
		type_hLJJtZd.push(temp_hLJJtZd);
	}
	else if (!isSet(type_hLJJtZd))
	{
		var type_hLJJtZd = [];
	}
	var type = type_hLJJtZd.some(type_hLJJtZd_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_hLJJtZdZWj_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_hLJJtZdZWj_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_hLJJtZdZWj_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_hLJJtZdZWj_required = true;
		}
	}
}

// the hLJJtZd Some function
function type_hLJJtZd_SomeFunc(type_hLJJtZd)
{
	// [8234] set the function logic
	if (type_hLJJtZd == 2)
	{
		return true;
	}
	return false;
}

// the fBbhMNs function
function fBbhMNs(target_fBbhMNs)
{
	// [8269] set the function logic
	if (target_fBbhMNs == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_fBbhMNsOcX_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_fBbhMNsOcX_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_fBbhMNsOcX_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_fBbhMNsOcX_required = true;
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
