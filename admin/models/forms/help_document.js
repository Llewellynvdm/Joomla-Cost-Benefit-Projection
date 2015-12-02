/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
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
jform_PAoOXjBorP_required = false;
jform_lBBAnFIlWd_required = false;
jform_bpRduNMQlg_required = false;
jform_mdMKRGQtUd_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_clqPnOc = jQuery("#jform_location input[type='radio']:checked").val();
	clqPnOc(location_clqPnOc);

	var location_jWExvAv = jQuery("#jform_location input[type='radio']:checked").val();
	jWExvAv(location_jWExvAv);

	var type_PAoOXjB = jQuery("#jform_type").val();
	PAoOXjB(type_PAoOXjB);

	var type_lBBAnFI = jQuery("#jform_type").val();
	lBBAnFI(type_lBBAnFI);

	var type_bpRduNM = jQuery("#jform_type").val();
	bpRduNM(type_bpRduNM);

	var target_mdMKRGQ = jQuery("#jform_target input[type='radio']:checked").val();
	mdMKRGQ(target_mdMKRGQ);
});

// the clqPnOc function
function clqPnOc(location_clqPnOc)
{
	// [8016] set the function logic
	if (location_clqPnOc == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the jWExvAv function
function jWExvAv(location_jWExvAv)
{
	// [8016] set the function logic
	if (location_jWExvAv == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the PAoOXjB function
function PAoOXjB(type_PAoOXjB)
{
	if (isSet(type_PAoOXjB) && type_PAoOXjB.constructor !== Array)
	{
		var temp_PAoOXjB = type_PAoOXjB;
		var type_PAoOXjB = [];
		type_PAoOXjB.push(temp_PAoOXjB);
	}
	else if (!isSet(type_PAoOXjB))
	{
		var type_PAoOXjB = [];
	}
	var type = type_PAoOXjB.some(type_PAoOXjB_SomeFunc);


	// [7994] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_PAoOXjBorP_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_PAoOXjBorP_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_PAoOXjBorP_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_PAoOXjBorP_required = true;
		}
	}
}

// the PAoOXjB Some function
function type_PAoOXjB_SomeFunc(type_PAoOXjB)
{
	// [7981] set the function logic
	if (type_PAoOXjB == 3)
	{
		return true;
	}
	return false;
}

// the lBBAnFI function
function lBBAnFI(type_lBBAnFI)
{
	if (isSet(type_lBBAnFI) && type_lBBAnFI.constructor !== Array)
	{
		var temp_lBBAnFI = type_lBBAnFI;
		var type_lBBAnFI = [];
		type_lBBAnFI.push(temp_lBBAnFI);
	}
	else if (!isSet(type_lBBAnFI))
	{
		var type_lBBAnFI = [];
	}
	var type = type_lBBAnFI.some(type_lBBAnFI_SomeFunc);


	// [7994] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_lBBAnFIlWd_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_lBBAnFIlWd_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_lBBAnFIlWd_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_lBBAnFIlWd_required = true;
		}
	}
}

// the lBBAnFI Some function
function type_lBBAnFI_SomeFunc(type_lBBAnFI)
{
	// [7981] set the function logic
	if (type_lBBAnFI == 1)
	{
		return true;
	}
	return false;
}

// the bpRduNM function
function bpRduNM(type_bpRduNM)
{
	if (isSet(type_bpRduNM) && type_bpRduNM.constructor !== Array)
	{
		var temp_bpRduNM = type_bpRduNM;
		var type_bpRduNM = [];
		type_bpRduNM.push(temp_bpRduNM);
	}
	else if (!isSet(type_bpRduNM))
	{
		var type_bpRduNM = [];
	}
	var type = type_bpRduNM.some(type_bpRduNM_SomeFunc);


	// [7994] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_bpRduNMQlg_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_bpRduNMQlg_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_bpRduNMQlg_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_bpRduNMQlg_required = true;
		}
	}
}

// the bpRduNM Some function
function type_bpRduNM_SomeFunc(type_bpRduNM)
{
	// [7981] set the function logic
	if (type_bpRduNM == 2)
	{
		return true;
	}
	return false;
}

// the mdMKRGQ function
function mdMKRGQ(target_mdMKRGQ)
{
	// [8016] set the function logic
	if (target_mdMKRGQ == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_mdMKRGQtUd_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_mdMKRGQtUd_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_mdMKRGQtUd_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_mdMKRGQtUd_required = true;
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
