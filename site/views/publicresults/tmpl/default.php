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
	<?php echo $this->loadTemplate('cbpmenumodule'); ?>
	<div class="uk-grid">
		<div class="uk-width-medium-1-1">
			<ul class="uk-tab uk-tab-grid uk-animation-slide-top" data-uk-tab="{connect:'#tab-public'}" >
			    <li class="uk-active uk-width-1-3"><a href="">Annual Cost</a></li>
			    <li class="uk-width-1-3"><a href="">Annual Costs Saved</a></li>
			    <li class="uk-width-1-3"><a href="">Full Access</a></li>
			</ul>
			<ul class="uk-switcher" id="tab-public" data-uk-grid-margin>
			    <li class="uk-active"><?php echo $this->loadTemplate('chart_cost_public'); ?></li>
			    <li class="" ><?php echo $this->loadTemplate('chart_intervention_cost_benefit_public'); ?></li>
			    <li class="" ><?php echo $this->loadTemplate('contact_form_public'); ?></li>
			</ul>
		</div>
	</div>
	<div class="uk-width-1-1">
		<p class="uk-text-center"><a href="#appnotice" data-uk-offcanvas class="uk-text-primary uk-text-bold"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_BENEFIT_PROJECTION_TOOL'); ?></a></p>
	</div>
</div>

<!-- This is the off-canvas sidebar -->
<div id="appnotice" class="uk-offcanvas">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-flip"><?php echo JLayoutHelper::render('appnotice',''); ?></div>
</div>

<script>
// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#main_costbenefitprojection').fadeIn('fast');
	});
});
</script>  
