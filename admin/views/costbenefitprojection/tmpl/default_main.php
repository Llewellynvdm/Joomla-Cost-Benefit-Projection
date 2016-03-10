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
<?php if(is_array($this->icons['main'])) :?>
	<?php foreach($this->icons['main'] as $icon): ?>
        <div class="dashboard-wraper">
           <div class="dashboard-content"> 
                <a class="icon" href="<?php echo $icon->url; ?>">
                    <img alt="<?php echo $icon->alt; ?>" src="components/com_costbenefitprojection/assets/images/icons/<?php  echo $icon->image; ?>">
                    <span class="dashboard-title"><?php echo JText::_($icon->name); ?></span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<div class="clearfix"></div>
<?php endif; ?>