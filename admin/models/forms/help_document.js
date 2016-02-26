/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.7
	@build			26th February, 2016
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
jform_hqyqHkSVEX_required = false;
jform_TXCmbHJieM_required = false;
jform_BZIvONJWYe_required = false;
jform_kguPvVXpQH_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_jsYlSTb = jQuery("#jform_location input[type='radio']:checked").val();
	jsYlSTb(location_jsYlSTb);

	var location_Rhrgnts = jQuery("#jform_location input[type='radio']:checked").val();
	Rhrgnts(location_Rhrgnts);

	var type_hqyqHkS = jQuery("#jform_type").val();
	hqyqHkS(type_hqyqHkS);

	var type_TXCmbHJ = jQuery("#jform_type").val();
	TXCmbHJ(type_TXCmbHJ);

	var type_BZIvONJ = jQuery("#jform_type").val();
	BZIvONJ(type_BZIvONJ);

	var target_kguPvVX = jQuery("#jform_target input[type='radio']:checked").val();
	kguPvVX(target_kguPvVX);
});

// the jsYlSTb function
function jsYlSTb(location_jsYlSTb)
{
	// set the function logic
	if (location_jsYlSTb == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the Rhrgnts function
function Rhrgnts(location_Rhrgnts)
{
	// set the function logic
	if (location_Rhrgnts == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the hqyqHkS function
function hqyqHkS(type_hqyqHkS)
{
	if (isSet(type_hqyqHkS) && type_hqyqHkS.constructor !== Array)
	{
		var temp_hqyqHkS = type_hqyqHkS;
		var type_hqyqHkS = [];
		type_hqyqHkS.push(temp_hqyqHkS);
	}
	else if (!isSet(type_hqyqHkS))
	{
		var type_hqyqHkS = [];
	}
	var type = type_hqyqHkS.some(type_hqyqHkS_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_hqyqHkSVEX_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_hqyqHkSVEX_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_hqyqHkSVEX_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_hqyqHkSVEX_required = true;
		}
	}
}

// the hqyqHkS Some function
function type_hqyqHkS_SomeFunc(type_hqyqHkS)
{
	// set the function logic
	if (type_hqyqHkS == 3)
	{
		return true;
	}
	return false;
}

// the TXCmbHJ function
function TXCmbHJ(type_TXCmbHJ)
{
	if (isSet(type_TXCmbHJ) && type_TXCmbHJ.constructor !== Array)
	{
		var temp_TXCmbHJ = type_TXCmbHJ;
		var type_TXCmbHJ = [];
		type_TXCmbHJ.push(temp_TXCmbHJ);
	}
	else if (!isSet(type_TXCmbHJ))
	{
		var type_TXCmbHJ = [];
	}
	var type = type_TXCmbHJ.some(type_TXCmbHJ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_TXCmbHJieM_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_TXCmbHJieM_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_TXCmbHJieM_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_TXCmbHJieM_required = true;
		}
	}
}

// the TXCmbHJ Some function
function type_TXCmbHJ_SomeFunc(type_TXCmbHJ)
{
	// set the function logic
	if (type_TXCmbHJ == 1)
	{
		return true;
	}
	return false;
}

// the BZIvONJ function
function BZIvONJ(type_BZIvONJ)
{
	if (isSet(type_BZIvONJ) && type_BZIvONJ.constructor !== Array)
	{
		var temp_BZIvONJ = type_BZIvONJ;
		var type_BZIvONJ = [];
		type_BZIvONJ.push(temp_BZIvONJ);
	}
	else if (!isSet(type_BZIvONJ))
	{
		var type_BZIvONJ = [];
	}
	var type = type_BZIvONJ.some(type_BZIvONJ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_BZIvONJWYe_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_BZIvONJWYe_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_BZIvONJWYe_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_BZIvONJWYe_required = true;
		}
	}
}

// the BZIvONJ Some function
function type_BZIvONJ_SomeFunc(type_BZIvONJ)
{
	// set the function logic
	if (type_BZIvONJ == 2)
	{
		return true;
	}
	return false;
}

// the kguPvVX function
function kguPvVX(target_kguPvVX)
{
	// set the function logic
	if (target_kguPvVX == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_kguPvVXpQH_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_kguPvVXpQH_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_kguPvVXpQH_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_kguPvVXpQH_required = true;
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
