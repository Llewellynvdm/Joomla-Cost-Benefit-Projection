/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.0
	@build			14th January, 2016
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
jform_phjHyITDXx_required = false;
jform_YgYLXJabxF_required = false;
jform_oBtjKMVxbQ_required = false;
jform_OrVYZCGSZE_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_dJEmkKS = jQuery("#jform_location input[type='radio']:checked").val();
	dJEmkKS(location_dJEmkKS);

	var location_imHJnTs = jQuery("#jform_location input[type='radio']:checked").val();
	imHJnTs(location_imHJnTs);

	var type_phjHyIT = jQuery("#jform_type").val();
	phjHyIT(type_phjHyIT);

	var type_YgYLXJa = jQuery("#jform_type").val();
	YgYLXJa(type_YgYLXJa);

	var type_oBtjKMV = jQuery("#jform_type").val();
	oBtjKMV(type_oBtjKMV);

	var target_OrVYZCG = jQuery("#jform_target input[type='radio']:checked").val();
	OrVYZCG(target_OrVYZCG);
});

// the dJEmkKS function
function dJEmkKS(location_dJEmkKS)
{
	// set the function logic
	if (location_dJEmkKS == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the imHJnTs function
function imHJnTs(location_imHJnTs)
{
	// set the function logic
	if (location_imHJnTs == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the phjHyIT function
function phjHyIT(type_phjHyIT)
{
	if (isSet(type_phjHyIT) && type_phjHyIT.constructor !== Array)
	{
		var temp_phjHyIT = type_phjHyIT;
		var type_phjHyIT = [];
		type_phjHyIT.push(temp_phjHyIT);
	}
	else if (!isSet(type_phjHyIT))
	{
		var type_phjHyIT = [];
	}
	var type = type_phjHyIT.some(type_phjHyIT_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_phjHyITDXx_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_phjHyITDXx_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_phjHyITDXx_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_phjHyITDXx_required = true;
		}
	}
}

// the phjHyIT Some function
function type_phjHyIT_SomeFunc(type_phjHyIT)
{
	// set the function logic
	if (type_phjHyIT == 3)
	{
		return true;
	}
	return false;
}

// the YgYLXJa function
function YgYLXJa(type_YgYLXJa)
{
	if (isSet(type_YgYLXJa) && type_YgYLXJa.constructor !== Array)
	{
		var temp_YgYLXJa = type_YgYLXJa;
		var type_YgYLXJa = [];
		type_YgYLXJa.push(temp_YgYLXJa);
	}
	else if (!isSet(type_YgYLXJa))
	{
		var type_YgYLXJa = [];
	}
	var type = type_YgYLXJa.some(type_YgYLXJa_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_YgYLXJabxF_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_YgYLXJabxF_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_YgYLXJabxF_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_YgYLXJabxF_required = true;
		}
	}
}

// the YgYLXJa Some function
function type_YgYLXJa_SomeFunc(type_YgYLXJa)
{
	// set the function logic
	if (type_YgYLXJa == 1)
	{
		return true;
	}
	return false;
}

// the oBtjKMV function
function oBtjKMV(type_oBtjKMV)
{
	if (isSet(type_oBtjKMV) && type_oBtjKMV.constructor !== Array)
	{
		var temp_oBtjKMV = type_oBtjKMV;
		var type_oBtjKMV = [];
		type_oBtjKMV.push(temp_oBtjKMV);
	}
	else if (!isSet(type_oBtjKMV))
	{
		var type_oBtjKMV = [];
	}
	var type = type_oBtjKMV.some(type_oBtjKMV_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_oBtjKMVxbQ_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_oBtjKMVxbQ_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_oBtjKMVxbQ_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_oBtjKMVxbQ_required = true;
		}
	}
}

// the oBtjKMV Some function
function type_oBtjKMV_SomeFunc(type_oBtjKMV)
{
	// set the function logic
	if (type_oBtjKMV == 2)
	{
		return true;
	}
	return false;
}

// the OrVYZCG function
function OrVYZCG(target_OrVYZCG)
{
	// set the function logic
	if (target_OrVYZCG == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_OrVYZCGSZE_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_OrVYZCGSZE_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_OrVYZCGSZE_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_OrVYZCGSZE_required = true;
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
