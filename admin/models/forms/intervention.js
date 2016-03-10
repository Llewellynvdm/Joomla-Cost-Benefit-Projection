/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		intervention.js
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvvyvwj_required = false;
jform_vvvvvwavwk_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var type_vvvvvvy = jQuery("#jform_type input[type='radio']:checked").val();
	vvvvvvy(type_vvvvvvy);

	var type_vvvvvvz = jQuery("#jform_type input[type='radio']:checked").val();
	vvvvvvz(type_vvvvvvz);

	var company_vvvvvwa = jQuery("#jform_company").val();
	vvvvvwa(company_vvvvvwa);
});

// the vvvvvvy function
function vvvvvvy(type_vvvvvvy)
{
	// set the function logic
	if (type_vvvvvvy == 2)
	{
		jQuery('#jform_interventions').closest('.control-group').show();
		if (jform_vvvvvvyvwj_required)
		{
			updateFieldRequired('interventions',0);
			jQuery('#jform_interventions').prop('required','required');
			jQuery('#jform_interventions').attr('aria-required',true);
			jQuery('#jform_interventions').addClass('required');
			jform_vvvvvvyvwj_required = false;
		}

	}
	else
	{
		jQuery('#jform_interventions').closest('.control-group').hide();
		if (!jform_vvvvvvyvwj_required)
		{
			updateFieldRequired('interventions',1);
			jQuery('#jform_interventions').removeAttr('required');
			jQuery('#jform_interventions').removeAttr('aria-required');
			jQuery('#jform_interventions').removeClass('required');
			jform_vvvvvvyvwj_required = true;
		}
	}
}

// the vvvvvvz function
function vvvvvvz(type_vvvvvvz)
{
	// set the function logic
	if (type_vvvvvvz == 1)
	{
		jQuery('#jform_intervention').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_intervention').closest('.control-group').hide();
	}
}

// the vvvvvwa function
function vvvvvwa(company_vvvvvwa)
{
	if (isSet(company_vvvvvwa) && company_vvvvvwa.constructor !== Array)
	{
		var temp_vvvvvwa = company_vvvvvwa;
		var company_vvvvvwa = [];
		company_vvvvvwa.push(temp_vvvvvwa);
	}
	else if (!isSet(company_vvvvvwa))
	{
		var company_vvvvvwa = [];
	}
	var company = company_vvvvvwa.some(company_vvvvvwa_SomeFunc);


	// set this function logic
	if (company)
	{
		jQuery('#jform_country').closest('.control-group').show();
		if (jform_vvvvvwavwk_required)
		{
			updateFieldRequired('country',0);
			jQuery('#jform_country').prop('required','required');
			jQuery('#jform_country').attr('aria-required',true);
			jQuery('#jform_country').addClass('required');
			jform_vvvvvwavwk_required = false;
		}

	}
	else
	{
		jQuery('#jform_country').closest('.control-group').hide();
		if (!jform_vvvvvwavwk_required)
		{
			updateFieldRequired('country',1);
			jQuery('#jform_country').removeAttr('required');
			jQuery('#jform_country').removeAttr('aria-required');
			jQuery('#jform_country').removeClass('required');
			jform_vvvvvwavwk_required = true;
		}
	}
}

// the vvvvvwa Some function
function company_vvvvvwa_SomeFunc(company_vvvvvwa)
{
	// set the function logic
	if (company_vvvvvwa == 0)
	{
		return true;
	}
	return false;
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

jQuery(document).ready(function()
{
	var inter_type = jQuery("#jform_type input[type='radio']:checked").val();
	var interventions = jQuery('#jform_intervention').val();
	if (1 == inter_type && interventions) {
		getBuildTable(interventions,'jform_intervention','nee');
	} else if (2 == inter_type && interventions) {
		getBuildTable(interventions,'jform_interventions','ja');
	}
	jQuery('.save-modal-data').text('Done');
});

function getClusterData(array,idName){
	var cluster = JSON.stringify(array);
	getClusterData_server(cluster,idName).done(function(result) {
		if(result.table){
			buildTable(result.table,idName);
			// ubdate the main set of values
			jQuery('#jform_intervention').val(result.values);
		} else {
			jQuery('#table_'+idName).remove();
			jQuery('#jform_intervention').val('');
			jQuery('.btn-wrapper').show();
			jQuery('#inputYYYNote').remove();
		}
	})
}

function getClusterData_server(cluster,idName){
	var getUrl = "index.php?option=com_costbenefitprojection&task=ajax.getClusterData&format=json";
	if(token.length > 0 && cluster.length > 0 && idName.length > 0){
		var request = 'token='+token+'&idName='+idName+'&cluster='+cluster;
	}
	return jQuery.ajax({
		type: 'GET',
		url: getUrl,
		dataType: 'jsonp',
		data: request,
		jsonp: 'callback'
	});
}

function getBuildTable_server(string,idName,cluster){
	var getUrl = "index.php?option=com_costbenefitprojection&task=ajax.interventionBuildTable&format=json";
	if(token.length > 0 && string.length > 0 && idName.length > 0){
		var request = 'token='+token+'&idName='+idName+'&oject='+string+'&cluster='+cluster;
	}
	return jQuery.ajax({
		type: 'GET',
		url: getUrl,
		dataType: 'jsonp',
		data: request,
		jsonp: 'callback'
	});
}

function getBuildTable(intervention,idName,cluster){
	if ('ne' == cluster)
	{
		var intervention = JSON.stringify(intervention);
		cluster = 'nee';
	}
	getBuildTable_server(intervention,idName,cluster).done(function(result) {
		if(result){
			buildTable(result,idName);
		} else {
			jQuery('#table_'+idName).remove();			
		}
	})
}

function buildTable(result,idName){
	jQuery('#table_'+idName).remove();
	jQuery('#'+idName).closest('.control-group').append(result);
	// check if we have cross match values
	if (jQuery(".eRrOr").length > 0){
		jQuery('.btn-wrapper').hide();
		if (jQuery('#inputYYYNote').length  <= 0){
			jQuery('#system-message-container').append('<div id="inputYYYNote" class="alert alert-error"><p>Values cross match between selected interventions please update all in red!</p></div>');
		}
	} else {
		jQuery('.btn-wrapper').show();
		jQuery('#inputYYYNote').remove();
	}
}

function updateSelection(row)
{
	var groupId = jQuery(row).find("select:first").attr("id");
	var percentValue = jQuery(row).find(".text_area:first").val();
	var arr = groupId.split('-');
	if (arr[1] != 1)
	{
		var selection = {};
		jQuery(row).find("select:first option").each(function()
		{
			// first get the values and text
			selection[jQuery(this).text()] = jQuery(this).val();
		});
		jQuery.each(causerisk, function(i, group){
			jQuery(row).find("select:first option[value='"+group+"']").remove();
		});
		if (percentValue)
		{
			var text = jQuery(row).find(".chzn-single:first span").text();
			jQuery(row).find("select:first").append(jQuery('<option>', {
				value: selection[text],
				text: text
			}));
		}
		jQuery(row).find("select:first").trigger("liszt:updated");	
		
		if (percentValue)
		{
			jQuery(row).find("select:first option:selected").val(selection[text]);	
			jQuery(row).find(".chzn-single:first span").text(text);
		}
	}
} 

function changeFieldValue(id,value) {
	if(id.length > 0) {
		value = jQuery.trim(value);
		if (value.match(/[^0-9\.]/g) || !value){
			jQuery('#'+id).css({'color':'red'});
			jQuery('#'+id).removeClass('eRrOr');
			jQuery('#'+id).addClass('eRrOr');
			alert('Only numbers should be used, not ('+value+')');
		} else {
			// update value
			jQuery('#'+id).val(value);
			// we must also update the main set of values  
			var mainset = jQuery('#jform_intervention').val();
			mainset = jQuery.parseJSON(mainset)
			var key = id.split('_');
			mainset[key[0]][key[1]] = value;
			mainset = JSON.stringify(mainset);
			jQuery('#jform_intervention').val(mainset);
			jQuery('#'+id).removeClass('eRrOr');
			jQuery('#'+id).css({'color':''});
		}
		// okay update the value
		if (jQuery(".eRrOr").length > 0){
			jQuery('.btn-wrapper').hide();
			if (jQuery('#inputYYYNote').length  <= 0){
				jQuery('#system-message-container').append('<div id="inputYYYNote" class="alert alert-error"><p>Values cross match between selected interventions please update all in red!</p></div>');
			}
		} else {
			jQuery('.btn-wrapper').show();
			jQuery('#inputYYYNote').remove();
		}
	}
} 
 
 
