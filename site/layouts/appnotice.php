<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		appnotice.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

$manifest = CostbenefitprojectionHelper::manifest();
$contributors = CostbenefitprojectionHelper::getContributors();

?>
<div class="uk-panel">
<img alt="<?php echo JText::_('COM_COSTBENEFITPROJECTION_VAST_DEVELOPMENT_METHOD'); ?>" src="<?php echo JRoute::_('media/com_costbenefitprojection/images/cbp_box.png'); ?>">
<p class="uk-text-bold uk-text-contrast">
<i class="uk-icon-cog uk-icon-spin"></i>
<?php echo JText::_('COM_COSTBENEFITPROJECTION_GIZ_COST_BENEFIT_PROJECTION_TOOL'); ?>
</p>
<ul class="uk-list uk-list-line">
<li><i class="uk-icon-bolt"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CURRENT_INSTALLED_VERSION'); ?> <em><?php echo $manifest->version; ?></em></li>
<li><i class="uk-icon-rocket"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RELEASE_DATE'); ?> <em><?php echo $manifest->creationDate; ?></em></li>
<li><i class="uk-icon-copyright"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COPYRIGHT_GIZ'); ?></li>
<li><i class="uk-icon-legal"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_LICENSE_A_TARGET_BLANK_HREFHTTPSWWWGNUORGLICENSESGPLTWOZEROHTMLGNUGPLA_COMMERCIAL'); ?></li>
<?php if(CostbenefitprojectionHelper::checkArray($contributors)): ?>
<?php foreach($contributors as $contributor): ?>
    <li><i class="uk-icon-stack-overflow"></i> <?php echo $contributor['title']; ?><br /><?php echo $contributor['name']; ?></li>
<?php endforeach; ?>
<?php endif; ?>
<li><i class="uk-icon-code"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_APPLICATION_DEVELOPER'); ?><br /><a href="<?php echo $manifest->authorUrl; ?>" target="_blank"><?php echo $manifest->author; ?></a></li>
</ul>
<div class="clearfix"></div>
</div>
