<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		@update number 10 of this MVC
	@build			18th August, 2017
	@created		16th December, 2015
	@package		Cost Benefit Projection
	@subpackage		default_cbpmenumodule.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// get the login module
$modules = $this->getModules('CBP-MENU', 'array');

?>
<?php if (CostbenefitprojectionHelper::checkArray($modules)): ?>
	<div class="uk-panel"><?php echo implode('</div><br /><div class="uk-panel">', $modules); ?></div>
<?php endif; ?>
