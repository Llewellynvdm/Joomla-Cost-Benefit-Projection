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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
?>
<?php if ($this->canDo->get('companyresults.access')): ?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'companyresults.back') {
                        parent.history.back();
			return false;
                } else {
			var form = document.getElementById('adminForm');
			form.task.value = task;
			form.submit();
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=companyresults&id='.$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
</form>
<div id="loading" style="height:300px; width:100%">
	<h1 style="text-align:center;" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_PLEASE_WAIT'); ?></h1>
    <div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<img width="180" height="24" src="../media/com_costbenefitprojection/images/load.gif" alt="......." title="........"/>
    </div>
</div>
<div id="st-container" class="st-container" style="display:none;">
    <!-- Top Navigation -->
    <div class="navButton">
        <button class="notice_off uk-button uk-icon-bars uk-button-primary" data-uk-offcanvas="{target:'#navCompany'}"></button>
    </div>
	<?php if($this->menuNotice < 5): ?>
        <div class="notice note_menu"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NBSPNBSPLARRNBSPEASYBRNBSPNBSPNBSPNBSPNAVIGATIONBRNBSPNBSPNBSPNBSPMENU'); ?></div>
    <?php endif; ?>
    <!-- SCALE SWITCH -->
    <div class="switchbox" >
        <div class="control_switch_sf" >
            <div class="switch footabletab" onclick="controlSwitch()" >
                <span class="thumb"></span>
                <input type="checkbox" />
            </div>
        </div>
    </div>
    <div class="main clearfix">
    	<br />
    	<br class="uk-visible-small" />
        <!-- MAIN PAGE -->
        <?php echo $this->loadTemplate('main'); ?>
        
        <!-- CHARTS -->
        <!-- Work days Lost -->
	<div class="hiddenit" style="display:none" id="giz_wdl">
		<?php echo $this->loadTemplate('chart_work_days_lost'); ?>
	</div>
        
        <!-- Work days Lost Percent -->
	<div class="hiddenit" style="display:none" id="giz_wdlp">
		<?php echo $this->loadTemplate('chart_work_days_lost_percent'); ?>
	</div>
        
        <!--  Costs -->
	<div class="hiddenit" style="display:none" id="giz_c">
		<?php echo $this->loadTemplate('chart_cost'); ?>
	</div>
        
        <!-- Cost Percent -->
	<div class="hiddenit" style="display:none" id="giz_cp">
		<?php echo $this->loadTemplate('chart_cost_percent'); ?>
	</div>
        
        <!-- Intervention Cost Benefit -->
	<div class="hiddenit" style="display:none" id="giz_icb">
		<?php echo $this->loadTemplate('chart_intervention_cost_benefit'); ?>
	</div>
        
                            
        <!-- TABLES -->
        <!-- Work Days Lost Summary -->
	<div class="hiddenit" style="display:none" id="giz_wdls">
		<?php echo $this->loadTemplate('table_work_days_lost_summary'); ?>
	</div>
        
        <!-- Cost Summary -->
	<div class="hiddenit" style="display:none" id="giz_cs">
		<?php echo $this->loadTemplate('table_cost_summary'); ?>
	</div>
        
        <!-- Calculated Costs in Detail -->
	<div class="hiddenit" style="display:none" id="giz_ccid">
		<?php echo $this->loadTemplate('table_calculated_cost_in_detail'); ?>
	</div>
        
        <!-- Intervention Net Benefit -->
	<div class="hiddenit" style="display:none" id="giz_inb">
		<?php echo $this->loadTemplate('table_intervention_net_benefit'); ?>
	</div>
        
        <!--  Debug -->
        <!-- Only for Developers -->
	<div class="hiddenit" style="display:none" id="giz_variables">
		<?php if ($this->canDo->get('core.admin') && $this->item->per == 1){ echo $this->loadTemplate('variables'); } ?>
	</div>
    </div><!-- /main -->
</div><!-- /st-container -->

<!-- This is the off-canvas sidebar -->
<div id="navCompany" class="uk-offcanvas">
    <div class="uk-offcanvas-bar">
        <ul data-uk-nav="" class="uk-nav uk-nav-offcanvas uk-nav-parent-icon">
        	<li class="uk-nav-header"><?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS_MENU'); ?></li>
            <li><a onclick="loadViews('main')" class="icon CTsubmenu" href="javascript:void(0)"><?php echo $this->item->name; ?></a></li>
            <li class="uk-parent">
                <a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_MENU'); ?></a>
               	<ul class="uk-nav-sub">
                     <?php foreach ($this->chart_tabs as &$item): ?>
                    <li>
                        <a onclick="loadViews('<?php echo $item['view']; ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                            <?php echo $item['name']; ?>
                        </a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_MENU'); ?></a>
                <ul class="uk-nav-sub">
                     <?php foreach ($this->table_tabs as &$item): ?>
                    <li>
                        <a onclick="loadViews('<?php echo $item['view']; ?>')" class="icon CTsubmenu footabletab" href="javascript:void(0)">
                            <?php echo $item['name']; ?>
                        </a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </li>
            <!--  Debug -->
            <?php  if ($this->canDo->get('core.admin')): ?>
            <li class="uk-nav-header"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DEBUG_MENU'); ?></li>
            <li><a onclick="loadViews('variables')" class="icon CTsubmenu" href="javascript:void(0)">Variables</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<script>
// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#st-container').fadeIn( 'fast', function() {
			loadViews('main');
			jQuery('#main .footable').trigger('footable_resize');
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
jQuery(function () {
    jQuery('table.data').footable().bind('footable_filtering', function(e){
      var selected = jQuery(this).prev('p').find('.filter-status').find(':selected').text();
      if (selected && selected.length > 0){
        e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
        e.clear = !e.filter;
      }
    });
});
// notice for menu controller
jQuery( ".notice_off" ).hover(function() {
	jQuery(".notice").fadeOut("slow");
});

function loadViews(e){
	jQuery( ".hiddenit" ).hide();
	jQuery( "#giz_"+e ).show();
	jQuery( "#giz_"+e+" .footable" ).trigger('footable_resize');
	// set the view height to always be above 700px
	var h = jQuery("#giz_"+e).height();
	
	if (h < 700){
		var scale = 700 - h;
		var add = '<div style="height: '+scale+'px;"></div>'
		jQuery("#view_"+e).append(add);
	}
	UIkit.offcanvas.hide(false);
}

// Switch for Scaling factors & One Episode
function controlSwitch(){
	if ( jQuery(".switch").hasClass("on") ) {
		jQuery(".switch").removeClass("on");
		jQuery(".unscaled").css( "display", "table" );
		jQuery(".scaled").css( "display", "none" );
	} else {
		jQuery(".switch").addClass("on");
		jQuery(".scaled").css( "display", "table" );
		jQuery(".unscaled").css( "display", "none" );
	};
}
</script>
<?php else: ?>
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_ACCESS_GRANTED'); ?></h1>
<?php endif; ?>

