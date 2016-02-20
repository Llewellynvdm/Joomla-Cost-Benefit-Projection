/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.4
	@build			20th February, 2016
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
jform_FpIPbneirK_required = false;
jform_fgOCQzCsnx_required = false;
jform_XzczrIMrYp_required = false;
jform_BCMduNxxVN_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_zJdFpwB = jQuery("#jform_location input[type='radio']:checked").val();
	zJdFpwB(location_zJdFpwB);

	var location_QLgZpKd = jQuery("#jform_location input[type='radio']:checked").val();
	QLgZpKd(location_QLgZpKd);

	var type_FpIPbne = jQuery("#jform_type").val();
	FpIPbne(type_FpIPbne);

	var type_fgOCQzC = jQuery("#jform_type").val();
	fgOCQzC(type_fgOCQzC);

	var type_XzczrIM = jQuery("#jform_type").val();
	XzczrIM(type_XzczrIM);

	var target_BCMduNx = jQuery("#jform_target input[type='radio']:checked").val();
	BCMduNx(target_BCMduNx);
});

// the zJdFpwB function
function zJdFpwB(location_zJdFpwB)
{
	// set the function logic
	if (location_zJdFpwB == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the QLgZpKd function
function QLgZpKd(location_QLgZpKd)
{
	// set the function logic
	if (location_QLgZpKd == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the FpIPbne function
function FpIPbne(type_FpIPbne)
{
	if (isSet(type_FpIPbne) && type_FpIPbne.constructor !== Array)
	{
		var temp_FpIPbne = type_FpIPbne;
		var type_FpIPbne = [];
		type_FpIPbne.push(temp_FpIPbne);
	}
	else if (!isSet(type_FpIPbne))
	{
		var type_FpIPbne = [];
	}
	var type = type_FpIPbne.some(type_FpIPbne_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_FpIPbneirK_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_FpIPbneirK_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_FpIPbneirK_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_FpIPbneirK_required = true;
		}
	}
}

// the FpIPbne Some function
function type_FpIPbne_SomeFunc(type_FpIPbne)
{
	// set the function logic
	if (type_FpIPbne == 3)
	{
		return true;
	}
	return false;
}

// the fgOCQzC function
function fgOCQzC(type_fgOCQzC)
{
	if (isSet(type_fgOCQzC) && type_fgOCQzC.constructor !== Array)
	{
		var temp_fgOCQzC = type_fgOCQzC;
		var type_fgOCQzC = [];
		type_fgOCQzC.push(temp_fgOCQzC);
	}
	else if (!isSet(type_fgOCQzC))
	{
		var type_fgOCQzC = [];
	}
	var type = type_fgOCQzC.some(type_fgOCQzC_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_fgOCQzCsnx_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_fgOCQzCsnx_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_fgOCQzCsnx_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_fgOCQzCsnx_required = true;
		}
	}
}

// the fgOCQzC Some function
function type_fgOCQzC_SomeFunc(type_fgOCQzC)
{
	// set the function logic
	if (type_fgOCQzC == 1)
	{
		return true;
	}
	return false;
}

// the XzczrIM function
function XzczrIM(type_XzczrIM)
{
	if (isSet(type_XzczrIM) && type_XzczrIM.constructor !== Array)
	{
		var temp_XzczrIM = type_XzczrIM;
		var type_XzczrIM = [];
		type_XzczrIM.push(temp_XzczrIM);
	}
	else if (!isSet(type_XzczrIM))
	{
		var type_XzczrIM = [];
	}
	var type = type_XzczrIM.some(type_XzczrIM_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_XzczrIMrYp_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_XzczrIMrYp_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_XzczrIMrYp_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_XzczrIMrYp_required = true;
		}
	}
}

// the XzczrIM Some function
function type_XzczrIM_SomeFunc(type_XzczrIM)
{
	// set the function logic
	if (type_XzczrIM == 2)
	{
		return true;
	}
	return false;
}

// the BCMduNx function
function BCMduNx(target_BCMduNx)
{
	// set the function logic
	if (target_BCMduNx == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_BCMduNxxVN_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_BCMduNxxVN_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_BCMduNxxVN_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_BCMduNxxVN_required = true;
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
