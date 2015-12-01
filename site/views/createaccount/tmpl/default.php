<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.0.8
	@build			1st December, 2015
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
<?php echo $this->toolbar->render(); ?> 
<div class="uk-alert uk-alert-success" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <p><?php echo JText::_('COM_COSTBENEFITPROJECTION_THIS_IS_A_WORK_IN_PROGRESS'); ?></p>
</div>
<form class="uk-form">
    <fieldset>
        <legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_LEGEND'); ?></legend>
        <div class="uk-form-row">
		<label class="uk-form-label" >
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_LABEL'); ?>
		</label>
		<input type="text" name="key" placeholder="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ADD_SOME_TEXT_HERE'); ?>"> <span class="uk-form-help-inline"><?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP_NEEDED'); ?></span>
	</div>
        <div class="uk-form-row">
		<label class="uk-form-label" >
			<?php echo JText::_('COM_COSTBENEFITPROJECTION_LABEL'); ?>
		</label>
		<textarea cols="" rows=""  style="width: 100%; height: 216px;" placeholder="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ADD_SOME_TEXT_HERE'); ?>"></textarea>
		<p class="uk-form-help-block"><?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP_NEEDED'); ?></p>
	</div>
    </fieldset>
</form> 
