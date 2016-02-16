/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.2
	@build			16th February, 2016
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
jform_nBdbRfDClv_required = false;
jform_typoIBttBg_required = false;
jform_escOQgLoFt_required = false;
jform_ENSCNMHstX_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_HSNKYXL = jQuery("#jform_location input[type='radio']:checked").val();
	HSNKYXL(location_HSNKYXL);

	var location_qeWtANs = jQuery("#jform_location input[type='radio']:checked").val();
	qeWtANs(location_qeWtANs);

	var type_nBdbRfD = jQuery("#jform_type").val();
	nBdbRfD(type_nBdbRfD);

	var type_typoIBt = jQuery("#jform_type").val();
	typoIBt(type_typoIBt);

	var type_escOQgL = jQuery("#jform_type").val();
	escOQgL(type_escOQgL);

	var target_ENSCNMH = jQuery("#jform_target input[type='radio']:checked").val();
	ENSCNMH(target_ENSCNMH);
});

// the HSNKYXL function
function HSNKYXL(location_HSNKYXL)
{
	// set the function logic
	if (location_HSNKYXL == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the qeWtANs function
function qeWtANs(location_qeWtANs)
{
	// set the function logic
	if (location_qeWtANs == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the nBdbRfD function
function nBdbRfD(type_nBdbRfD)
{
	if (isSet(type_nBdbRfD) && type_nBdbRfD.constructor !== Array)
	{
		var temp_nBdbRfD = type_nBdbRfD;
		var type_nBdbRfD = [];
		type_nBdbRfD.push(temp_nBdbRfD);
	}
	else if (!isSet(type_nBdbRfD))
	{
		var type_nBdbRfD = [];
	}
	var type = type_nBdbRfD.some(type_nBdbRfD_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_nBdbRfDClv_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_nBdbRfDClv_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_nBdbRfDClv_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_nBdbRfDClv_required = true;
		}
	}
}

// the nBdbRfD Some function
function type_nBdbRfD_SomeFunc(type_nBdbRfD)
{
	// set the function logic
	if (type_nBdbRfD == 3)
	{
		return true;
	}
	return false;
}

// the typoIBt function
function typoIBt(type_typoIBt)
{
	if (isSet(type_typoIBt) && type_typoIBt.constructor !== Array)
	{
		var temp_typoIBt = type_typoIBt;
		var type_typoIBt = [];
		type_typoIBt.push(temp_typoIBt);
	}
	else if (!isSet(type_typoIBt))
	{
		var type_typoIBt = [];
	}
	var type = type_typoIBt.some(type_typoIBt_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_typoIBttBg_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_typoIBttBg_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_typoIBttBg_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_typoIBttBg_required = true;
		}
	}
}

// the typoIBt Some function
function type_typoIBt_SomeFunc(type_typoIBt)
{
	// set the function logic
	if (type_typoIBt == 1)
	{
		return true;
	}
	return false;
}

// the escOQgL function
function escOQgL(type_escOQgL)
{
	if (isSet(type_escOQgL) && type_escOQgL.constructor !== Array)
	{
		var temp_escOQgL = type_escOQgL;
		var type_escOQgL = [];
		type_escOQgL.push(temp_escOQgL);
	}
	else if (!isSet(type_escOQgL))
	{
		var type_escOQgL = [];
	}
	var type = type_escOQgL.some(type_escOQgL_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_escOQgLoFt_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_escOQgLoFt_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_escOQgLoFt_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_escOQgLoFt_required = true;
		}
	}
}

// the escOQgL Some function
function type_escOQgL_SomeFunc(type_escOQgL)
{
	// set the function logic
	if (type_escOQgL == 2)
	{
		return true;
	}
	return false;
}

// the ENSCNMH function
function ENSCNMH(target_ENSCNMH)
{
	// set the function logic
	if (target_ENSCNMH == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_ENSCNMHstX_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_ENSCNMHstX_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_ENSCNMHstX_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_ENSCNMHstX_required = true;
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
