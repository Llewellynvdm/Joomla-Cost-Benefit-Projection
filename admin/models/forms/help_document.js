/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.0
	@build			14th February, 2016
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
jform_UJteXUtgky_required = false;
jform_QEIUlDzBxE_required = false;
jform_TReWnxQEpN_required = false;
jform_LjCaoATFEe_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_wbXluyd = jQuery("#jform_location input[type='radio']:checked").val();
	wbXluyd(location_wbXluyd);

	var location_uuUtMWc = jQuery("#jform_location input[type='radio']:checked").val();
	uuUtMWc(location_uuUtMWc);

	var type_UJteXUt = jQuery("#jform_type").val();
	UJteXUt(type_UJteXUt);

	var type_QEIUlDz = jQuery("#jform_type").val();
	QEIUlDz(type_QEIUlDz);

	var type_TReWnxQ = jQuery("#jform_type").val();
	TReWnxQ(type_TReWnxQ);

	var target_LjCaoAT = jQuery("#jform_target input[type='radio']:checked").val();
	LjCaoAT(target_LjCaoAT);
});

// the wbXluyd function
function wbXluyd(location_wbXluyd)
{
	// set the function logic
	if (location_wbXluyd == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the uuUtMWc function
function uuUtMWc(location_uuUtMWc)
{
	// set the function logic
	if (location_uuUtMWc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the UJteXUt function
function UJteXUt(type_UJteXUt)
{
	if (isSet(type_UJteXUt) && type_UJteXUt.constructor !== Array)
	{
		var temp_UJteXUt = type_UJteXUt;
		var type_UJteXUt = [];
		type_UJteXUt.push(temp_UJteXUt);
	}
	else if (!isSet(type_UJteXUt))
	{
		var type_UJteXUt = [];
	}
	var type = type_UJteXUt.some(type_UJteXUt_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_UJteXUtgky_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_UJteXUtgky_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_UJteXUtgky_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_UJteXUtgky_required = true;
		}
	}
}

// the UJteXUt Some function
function type_UJteXUt_SomeFunc(type_UJteXUt)
{
	// set the function logic
	if (type_UJteXUt == 3)
	{
		return true;
	}
	return false;
}

// the QEIUlDz function
function QEIUlDz(type_QEIUlDz)
{
	if (isSet(type_QEIUlDz) && type_QEIUlDz.constructor !== Array)
	{
		var temp_QEIUlDz = type_QEIUlDz;
		var type_QEIUlDz = [];
		type_QEIUlDz.push(temp_QEIUlDz);
	}
	else if (!isSet(type_QEIUlDz))
	{
		var type_QEIUlDz = [];
	}
	var type = type_QEIUlDz.some(type_QEIUlDz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_QEIUlDzBxE_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_QEIUlDzBxE_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_QEIUlDzBxE_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_QEIUlDzBxE_required = true;
		}
	}
}

// the QEIUlDz Some function
function type_QEIUlDz_SomeFunc(type_QEIUlDz)
{
	// set the function logic
	if (type_QEIUlDz == 1)
	{
		return true;
	}
	return false;
}

// the TReWnxQ function
function TReWnxQ(type_TReWnxQ)
{
	if (isSet(type_TReWnxQ) && type_TReWnxQ.constructor !== Array)
	{
		var temp_TReWnxQ = type_TReWnxQ;
		var type_TReWnxQ = [];
		type_TReWnxQ.push(temp_TReWnxQ);
	}
	else if (!isSet(type_TReWnxQ))
	{
		var type_TReWnxQ = [];
	}
	var type = type_TReWnxQ.some(type_TReWnxQ_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_TReWnxQEpN_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_TReWnxQEpN_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_TReWnxQEpN_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_TReWnxQEpN_required = true;
		}
	}
}

// the TReWnxQ Some function
function type_TReWnxQ_SomeFunc(type_TReWnxQ)
{
	// set the function logic
	if (type_TReWnxQ == 2)
	{
		return true;
	}
	return false;
}

// the LjCaoAT function
function LjCaoAT(target_LjCaoAT)
{
	// set the function logic
	if (target_LjCaoAT == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_LjCaoATFEe_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_LjCaoATFEe_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_LjCaoATFEe_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_LjCaoATFEe_required = true;
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
