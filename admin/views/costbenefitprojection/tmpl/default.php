<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.2.0
	@build			12th January, 2016
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

?>
<div id="j-main-container" class="span9">
<?php  echo JHtml::_('bootstrap.startAccordion', 'dashboard_left', array('active' => 'main')); ?>

<?php  echo JHtml::_('bootstrap.addSlide', 'dashboard_left', 'cPanel', 'main'); ?>
<?php echo $this->loadTemplate('main');?>
<?php  echo JHtml::_('bootstrap.endSlide'); ?>

<?php  echo JHtml::_('bootstrap.endAccordion'); ?>
</div>
<div id="j-main-container" class="span3">
<?php  echo JHtml::_('bootstrap.startAccordion', 'dashboard_right', array('active' => 'vdm')); ?>

<?php  echo JHtml::_('bootstrap.addSlide', 'dashboard_right', 'Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb', 'vdm'); ?>
<?php echo $this->loadTemplate('vdm');?>
<?php  echo JHtml::_('bootstrap.endSlide'); ?>

<?php  echo JHtml::_('bootstrap.endAccordion'); ?>
</div>