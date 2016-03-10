<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_vdm.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$manifest = CostbenefitprojectionHelper::manifest();
JHtml::_('bootstrap.loadCss');

?>
<img alt="<?php echo JText::_('COM_COSTBENEFITPROJECTION'); ?>" src="components/com_costbenefitprojection/assets/images/component-300.png">
<ul class="list-striped">
<li><b><?php echo JText::_('COM_COSTBENEFITPROJECTION_VERSION'); ?>:</b> <?php echo $manifest->version; ?></li>
<li><b><?php echo JText::_('COM_COSTBENEFITPROJECTION_DATE'); ?>:</b> <?php echo $manifest->creationDate; ?></li>
<li><b><?php echo JText::_('COM_COSTBENEFITPROJECTION_AUTHOR'); ?>:</b> <a href="mailto:<?php echo $manifest->authorEmail; ?>"><?php echo $manifest->author; ?></a></li>
<li><b><?php echo JText::_('COM_COSTBENEFITPROJECTION_WEBSITE'); ?>:</b> <a href="<?php echo $manifest->authorUrl; ?>" target="_blank"><?php echo $manifest->authorUrl; ?></a></li>
<li><b><?php echo JText::_('COM_COSTBENEFITPROJECTION_LICENSE'); ?>:</b> <?php echo $manifest->license; ?></li>
<li><b><?php echo $manifest->copyright; ?></b></li>
</ul>
<div class="clearfix"></div>
<?php if(CostbenefitprojectionHelper::checkArray($this->contributors)): ?>
<?php if(count($this->contributors) > 1): ?>
<h3><?php echo JText::_('COM_COSTBENEFITPROJECTION_CONTRIBUTORS'); ?></h3>
<?php else: ?>
<h3><?php echo JText::_('COM_COSTBENEFITPROJECTION_CONTRIBUTOR'); ?></h3>
<?php endif; ?>
<ul class="list-striped">
	<?php foreach($this->contributors as $contributor): ?>
    <li><b><?php echo $contributor['title']; ?>:</b> <?php echo $contributor['name']; ?></li>
    <?php endforeach; ?>
</ul>
<div class="clearfix"></div>
<?php endif; ?>