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
jform_nwQaKJScpA_required = false;
jform_rViFrVkZqx_required = false;
jform_BUFBIxSSWh_required = false;
jform_bgwRgitajP_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_BphvImg = jQuery("#jform_location input[type='radio']:checked").val();
	BphvImg(location_BphvImg);

	var location_JMkoFuj = jQuery("#jform_location input[type='radio']:checked").val();
	JMkoFuj(location_JMkoFuj);

	var type_nwQaKJS = jQuery("#jform_type").val();
	nwQaKJS(type_nwQaKJS);

	var type_rViFrVk = jQuery("#jform_type").val();
	rViFrVk(type_rViFrVk);

	var type_BUFBIxS = jQuery("#jform_type").val();
	BUFBIxS(type_BUFBIxS);

	var target_bgwRgit = jQuery("#jform_target input[type='radio']:checked").val();
	bgwRgit(target_bgwRgit);
});

// the BphvImg function
function BphvImg(location_BphvImg)
{
	// set the function logic
	if (location_BphvImg == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the JMkoFuj function
function JMkoFuj(location_JMkoFuj)
{
	// set the function logic
	if (location_JMkoFuj == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the nwQaKJS function
function nwQaKJS(type_nwQaKJS)
{
	if (isSet(type_nwQaKJS) && type_nwQaKJS.constructor !== Array)
	{
		var temp_nwQaKJS = type_nwQaKJS;
		var type_nwQaKJS = [];
		type_nwQaKJS.push(temp_nwQaKJS);
	}
	else if (!isSet(type_nwQaKJS))
	{
		var type_nwQaKJS = [];
	}
	var type = type_nwQaKJS.some(type_nwQaKJS_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_nwQaKJScpA_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_nwQaKJScpA_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_nwQaKJScpA_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_nwQaKJScpA_required = true;
		}
	}
}

// the nwQaKJS Some function
function type_nwQaKJS_SomeFunc(type_nwQaKJS)
{
	// set the function logic
	if (type_nwQaKJS == 3)
	{
		return true;
	}
	return false;
}

// the rViFrVk function
function rViFrVk(type_rViFrVk)
{
	if (isSet(type_rViFrVk) && type_rViFrVk.constructor !== Array)
	{
		var temp_rViFrVk = type_rViFrVk;
		var type_rViFrVk = [];
		type_rViFrVk.push(temp_rViFrVk);
	}
	else if (!isSet(type_rViFrVk))
	{
		var type_rViFrVk = [];
	}
	var type = type_rViFrVk.some(type_rViFrVk_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_rViFrVkZqx_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_rViFrVkZqx_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_rViFrVkZqx_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_rViFrVkZqx_required = true;
		}
	}
}

// the rViFrVk Some function
function type_rViFrVk_SomeFunc(type_rViFrVk)
{
	// set the function logic
	if (type_rViFrVk == 1)
	{
		return true;
	}
	return false;
}

// the BUFBIxS function
function BUFBIxS(type_BUFBIxS)
{
	if (isSet(type_BUFBIxS) && type_BUFBIxS.constructor !== Array)
	{
		var temp_BUFBIxS = type_BUFBIxS;
		var type_BUFBIxS = [];
		type_BUFBIxS.push(temp_BUFBIxS);
	}
	else if (!isSet(type_BUFBIxS))
	{
		var type_BUFBIxS = [];
	}
	var type = type_BUFBIxS.some(type_BUFBIxS_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_BUFBIxSSWh_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_BUFBIxSSWh_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_BUFBIxSSWh_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_BUFBIxSSWh_required = true;
		}
	}
}

// the BUFBIxS Some function
function type_BUFBIxS_SomeFunc(type_BUFBIxS)
{
	// set the function logic
	if (type_BUFBIxS == 2)
	{
		return true;
	}
	return false;
}

// the bgwRgit function
function bgwRgit(target_bgwRgit)
{
	// set the function logic
	if (target_bgwRgit == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_bgwRgitajP_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_bgwRgitajP_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_bgwRgitajP_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_bgwRgitajP_required = true;
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
