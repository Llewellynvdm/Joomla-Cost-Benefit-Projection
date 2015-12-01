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
jform_GNSempCdHk_required = false;
jform_btpFKYzVNQ_required = false;
jform_kYXZiqcCXv_required = false;
jform_fKlWJFrnET_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_qNlskgN = jQuery("#jform_location input[type='radio']:checked").val();
	qNlskgN(location_qNlskgN);

	var location_ksdmMsB = jQuery("#jform_location input[type='radio']:checked").val();
	ksdmMsB(location_ksdmMsB);

	var type_GNSempC = jQuery("#jform_type").val();
	GNSempC(type_GNSempC);

	var type_btpFKYz = jQuery("#jform_type").val();
	btpFKYz(type_btpFKYz);

	var type_kYXZiqc = jQuery("#jform_type").val();
	kYXZiqc(type_kYXZiqc);

	var target_fKlWJFr = jQuery("#jform_target input[type='radio']:checked").val();
	fKlWJFr(target_fKlWJFr);
});

// the qNlskgN function
function qNlskgN(location_qNlskgN)
{
	// [8008] set the function logic
	if (location_qNlskgN == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the ksdmMsB function
function ksdmMsB(location_ksdmMsB)
{
	// [8008] set the function logic
	if (location_ksdmMsB == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the GNSempC function
function GNSempC(type_GNSempC)
{
	if (isSet(type_GNSempC) && type_GNSempC.constructor !== Array)
	{
		var temp_GNSempC = type_GNSempC;
		var type_GNSempC = [];
		type_GNSempC.push(temp_GNSempC);
	}
	else if (!isSet(type_GNSempC))
	{
		var type_GNSempC = [];
	}
	var type = type_GNSempC.some(type_GNSempC_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_GNSempCdHk_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_GNSempCdHk_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_GNSempCdHk_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_GNSempCdHk_required = true;
		}
	}
}

// the GNSempC Some function
function type_GNSempC_SomeFunc(type_GNSempC)
{
	// [7973] set the function logic
	if (type_GNSempC == 3)
	{
		return true;
	}
	return false;
}

// the btpFKYz function
function btpFKYz(type_btpFKYz)
{
	if (isSet(type_btpFKYz) && type_btpFKYz.constructor !== Array)
	{
		var temp_btpFKYz = type_btpFKYz;
		var type_btpFKYz = [];
		type_btpFKYz.push(temp_btpFKYz);
	}
	else if (!isSet(type_btpFKYz))
	{
		var type_btpFKYz = [];
	}
	var type = type_btpFKYz.some(type_btpFKYz_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_btpFKYzVNQ_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_btpFKYzVNQ_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_btpFKYzVNQ_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_btpFKYzVNQ_required = true;
		}
	}
}

// the btpFKYz Some function
function type_btpFKYz_SomeFunc(type_btpFKYz)
{
	// [7973] set the function logic
	if (type_btpFKYz == 1)
	{
		return true;
	}
	return false;
}

// the kYXZiqc function
function kYXZiqc(type_kYXZiqc)
{
	if (isSet(type_kYXZiqc) && type_kYXZiqc.constructor !== Array)
	{
		var temp_kYXZiqc = type_kYXZiqc;
		var type_kYXZiqc = [];
		type_kYXZiqc.push(temp_kYXZiqc);
	}
	else if (!isSet(type_kYXZiqc))
	{
		var type_kYXZiqc = [];
	}
	var type = type_kYXZiqc.some(type_kYXZiqc_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_kYXZiqcCXv_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_kYXZiqcCXv_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_kYXZiqcCXv_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_kYXZiqcCXv_required = true;
		}
	}
}

// the kYXZiqc Some function
function type_kYXZiqc_SomeFunc(type_kYXZiqc)
{
	// [7973] set the function logic
	if (type_kYXZiqc == 2)
	{
		return true;
	}
	return false;
}

// the fKlWJFr function
function fKlWJFr(target_fKlWJFr)
{
	// [8008] set the function logic
	if (target_fKlWJFr == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_fKlWJFrnET_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_fKlWJFrnET_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_fKlWJFrnET_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_fKlWJFrnET_required = true;
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
