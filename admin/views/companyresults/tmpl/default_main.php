<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		default_main.php
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
<div class="hiddenit" style="display:none" id="giz_main">     
	<div id="view_main" style="margin:0 auto; width: 900px; height: 700px;">
		<div id="costbenefitprojection" >
			<div class="span12">
				<h3 class="title">
					<span><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_QUICK_LINKS'); ?></span>
				</h3>
                <?php if(is_array($this->chart_tabs)) :?>
					<?php foreach ($this->chart_tabs as $item): ?>
                        <div class="dashboard-wraper">
                           <div class="dashboard-content"> 
                                <a onclick="loadViews('<?php echo $item['view']; ?>')"  class="icon CTsubmenu" href="javascript:void(0)">
                                    <img alt="<?php echo $item['name']; ?>" src="<?php echo JURI::root().$item['img']; ?>">
                                    <span class="dashboard-title"><?php echo $item['name']; ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <div class="clearfix"></div>
                <?php endif; ?>
				<h3 class="title">
					<span><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_QUICK_LINKS'); ?></span>
				</h3>
                <?php if(is_array($this->table_tabs)) :?>
					<?php foreach ($this->table_tabs as $item): ?>
                        <div class="dashboard-wraper">
                           <div class="dashboard-content"> 
                                <a onclick="loadViews('<?php echo $item['view']; ?>')" class="icon CTsubmenu  footabletab" href="javascript:void(0)">
                                    <img alt="<?php echo $item['name']; ?>" src="<?php echo JURI::root().$item['img']; ?>">
                                    <span class="dashboard-title"><?php echo $item['name']; ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <div class="clearfix"></div>
                <?php endif; ?>
			</div>
		</div>
	</div>
</div>
