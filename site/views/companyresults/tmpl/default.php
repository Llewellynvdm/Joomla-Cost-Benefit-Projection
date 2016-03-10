<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// set the active tabs based on interventions
if (isset($this->results->interventions) && CostbenefitprojectionHelper::checkArray($this->results->interventions))
{
	$savings = 'uk-active ';
	$details = '';	
}
else
{
	$savings = '';
	$details = 'uk-active ';
}

?>
<div class="uk-clearfix"><div class="uk-float-right"><?php echo $this->toolbar->render(); ?></div></div> 
<div id="loading" style="height:300px; width:100%">
	<h1 style="text-align:center;" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_PLEASE_WAIT'); ?></h1>
    <div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<img width="180" height="24" src="<?php echo JRoute::_('media/com_costbenefitprojection/images/load.gif'); ?>" alt="......." title="........"/>
    </div>
</div>
<div id="main_costbenefitprojection" style="display:none;">
    <!-- SCALE SWITCH -->
    <div class="uk-clearfix"><div class="uk-float-right"><button class="uk-button switch footabletab" type="button" onclick="controlSwitch()"><i class="switch-icon uk-icon-toggle-off"></i> <span class="switch-text"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTORS'); ?></span></button></div></div>
    <div class="main clearfix">
	<ul data-uk-switcher="{connect:'#results'}" class="uk-subnav uk-subnav-pill">
		<li class="<?php echo $savings; ?>uk-text-small"><a href="#"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_SAVINGS'); ?></a></li>
		<li class="<?php echo $details; ?>uk-text-small" data-uk-dropdown="{mode:'hover'}">
			<a href="#"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_MORE_DETAILS'); ?></a>

			<div class="uk-dropdown uk-dropdown-small">
				<ul data-uk-switcher="{connect:'#table-chart'}" class="uk-nav uk-nav-dropdown">
					<li class="<?php echo $savings; ?> footabletab"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES'); ?></a></li>
					<li class="<?php echo $details; ?>"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS'); ?></a></li>
				</ul>
			</div>

		</li>
	</ul>
	<ul class="uk-switcher uk-margin" id="results">
		<li id="results-one" class="uk-active">
			<!-- Intervention Cost Benefit -->
			<?php echo $this->loadTemplate('chart_intervention_cost_benefit_save'); ?>
		</li>
		<li>
			<ul class="uk-switcher uk-margin" id="table-chart">
				<li class="">
					<ul id="table-switch" data-uk-tab="{connect:'#tables'}" class="uk-tab">
						<li class="uk-text-small footabletab"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST_SUMMARY'); ?></a></li>
						<li class="uk-text-small footabletab"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_SUMMARY'); ?></a></li>
						<li class="uk-active uk-text-small footabletab"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CALCULATED_COSTS_IN_DETAIL'); ?></a></li>
						<li class="uk-text-small footabletab"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_NET_BENEFIT'); ?></a></li>
					</ul>
					<ul id="tables" class="uk-switcher uk-margin">
						<li class="">
							<!-- Work Days Lost Summary -->
							<?php echo $this->loadTemplate('table_work_days_lost_summary'); ?>
						</li>
						<li class="">
							<!-- Cost Summary -->
							<?php echo $this->loadTemplate('table_cost_summary'); ?>
						</li>
						<li class="uk-active">
							<!-- Calculated Costs in Detail -->
							<?php echo $this->loadTemplate('table_calculated_cost_in_detail'); ?>
						</li>
						<li class="">
							<!-- Intervention Net Benefit -->
							<?php echo $this->loadTemplate('table_intervention_net_benefit'); ?>
						</li>
					</ul>
				</li>
				<li class="uk-active">
					<ul data-uk-tab="{connect:'#charts'}" class="uk-tab">
						<li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST'); ?></a></li>
						<li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORK_DAYS_LOST_PERCENT'); ?></a></li>
						<li class="uk-active uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST'); ?></a></li>
						<li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_PERCENT'); ?></a></li>
						<li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_BENEFIT'); ?></a></li>
					</ul>
					<ul id="charts" class="uk-switcher uk-margin">
						<li class="">
							<!-- Work days Lost -->
							<?php echo $this->loadTemplate('chart_work_days_lost'); ?>
						</li>
						<li class="">
							<!-- Work days Lost Percent -->
							<?php echo $this->loadTemplate('chart_work_days_lost_percent'); ?>
						</li>
						<li class="uk-active">
							<!--  Costs -->
							<?php echo $this->loadTemplate('chart_cost'); ?>
						</li>
						<li class="">
							<!-- Cost Percent -->
							<?php echo $this->loadTemplate('chart_cost_percent'); ?>
						</li>
						<li class="">
							<!-- Intervention Cost Benefit -->
							<?php echo $this->loadTemplate('chart_intervention_cost_benefit'); ?>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
</div>
<script>
var height 	= 0;
var width 	= 0;
var url 	= 0;

// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#main_costbenefitprojection').fadeIn( 'fast', function() {
			jQuery('li.uk-active .footable').trigger('footable_resize');
		});
	});
});
// foo table trigger on click
jQuery('.footabletab').click(function(e){
	 // use setTimeout() to execute
	setTimeout( resizeFooTable, 200);
});
// funtion to resize the foo table 
function resizeFooTable() {     
	jQuery('.footable').trigger('footable_resize');
}
// resize table on default/include/one click
jQuery('[data-uk-switcher]').on('uk.switcher.show', function(event, area){
	jQuery('li.uk-active .footable').trigger('footable_resize');
});
// resize table on tab click
jQuery('[data-uk-tab]').on('uk.switcher.show', function(event, area){
	jQuery('li.uk-active .footable').trigger('footable_resize');
});
jQuery(function () {
    jQuery('table.data').footable().bind('footable_filtering', function(e){
      var selected = jQuery(this).prev('p').find('.filter-status').find(':selected').text();
      if (selected && selected.length > 0){
        e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
        e.clear = !e.filter;
      }
    });
});

/* The PDF generator funtions */
function getImgData(chartContainer) {
    var chartArea = chartContainer.getElementsByTagName('svg')[0].parentNode;
    var svg = chartArea.innerHTML;
    var doc = chartContainer.ownerDocument;
    var canvas = doc.createElement('canvas');
    canvas.setAttribute('width', chartArea.offsetWidth);
    canvas.setAttribute('height', chartArea.offsetHeight);


    canvas.setAttribute(
        'style',
        'position: absolute; ' +
        'top: ' + (-chartArea.offsetHeight * 2) + 'px;' +
        'left: ' + (-chartArea.offsetWidth * 2) + 'px;');
    doc.body.appendChild(canvas);
    canvg(canvas, svg);
    var imgData = canvas.toDataURL("image/png");
    canvas.parentNode.removeChild(canvas);
    return imgData;
}
jQuery.download = function(url, dataOne, dataTwo, method){
        //url and data options required
        if( url && dataOne ){
                //split params into form inputs
                var input = '<input type="hidden" name="<?php echo 'key'; ?>" value="'+ dataOne +'" />';
				if(dataTwo){
					input += '<input type="hidden" name="<?php echo 'text'; ?>" value="'+ dataTwo +'" />';
				}
                //send request
                jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+input+'</form>')
                .appendTo('body').submit().remove();
        };
};
function getPDF(chartContainer,text) {
	var doc = chartContainer.ownerDocument;
	var img = doc.createElement('img');
	img.src = getImgData(chartContainer);
	var url = 'index.php?option=com_costbenefitprojection&view=cp&format=pdf';
	var data = img.src;
	jQuery.download(url,data,text );
}

/* The Excel generator funtions */
jQuery.exel = function(url, data, title){
	//url and data options required
	if( url && data ){
			//split params into form inputs
			var input = '<input type="hidden" name="csv_text" value="'+ data +'" />';
			if(title){
				input += '<input type="hidden" name="title" value="'+ title +'" />';
			}
			//send request
			jQuery('<form action="'+ url +'" method="post">'+input+'</form>')
			.appendTo('body').submit().remove();
	};
};

function getEXEL(theTable,theTitle){
	var title = theTitle;
	var url = 'index.php?option=com_costbenefitprojection&view=cp&format=csv';
	var data = jQuery(theTable).table2excel();
	jQuery.exel(url,data,title);
}

// Switch for Scaling factors
function controlSwitch(){
	if ( jQuery(".switch").hasClass("on") ) {
		jQuery(".switch").removeClass("on uk-button-primary");
		jQuery(".switch-icon").removeClass("uk-icon-toggle-on");
		jQuery(".switch-icon").addClass("uk-icon-toggle-off");
		jQuery(".unscaled").css( "display", "table" );
		jQuery(".scaled").css( "display", "none" );
	} else {
		jQuery(".switch").addClass("on uk-button-primary");
		jQuery(".switch-icon").removeClass("uk-icon-toggle-off");
		jQuery(".switch-icon").addClass("uk-icon-toggle-on");
		jQuery(".scaled").css( "display", "table" );
		jQuery(".unscaled").css( "display", "none" );
	};
}
</script>  
