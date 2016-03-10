<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		databreakdownmalefemale.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

$builder =  array(
	'cost' => array('yld' => 'YLD','death' => 'Death','costmoney_morbidity_'.$displayData->_tmpScale => 'Cost Morbidity','costmoney_presenteeism_'.$displayData->_tmpScale => 'Cost Presenteeism (due to morbidity)','costmoney_mortality_'.$displayData->_tmpScale => 'Cost Mortality'),
	'day' =>  array('yld' => 'YLD','death' => 'Death','morbidity_'.$displayData->_tmpScale => 'Days Lost Morbidity','presenteeism_'.$displayData->_tmpScale => 'Days Lost Presenteeism (due to morbidity)','days_lost_mortality_'.$displayData->_tmpScale => 'Days Lost Mortality')
	);
$rounder =  array(
	'cost' => array('yld','death'),
	'day' =>  array('yld','death','morbidity_'.$displayData->_tmpScale, 'presenteeism_'.$displayData->_tmpScale, 'days_lost_mortality_'.$displayData->_tmpScale)
	);

?>
<?php if (isset($displayData->male) && isset($displayData->female) ): ?>
<div class="uk-grid">
	<div style="float:left;">
		<ul class="uk-list uk-list-striped">
			<?php foreach ($displayData->male as $age => $values): ?>
			<li>
				<?php echo JText::_('COM_COSTBENEFITPROJECTION_MALES_AGE_GROUP'); ?> <?php echo $age; ?>
				<?php foreach ($values as $key => $value): ?>
					<?php if (in_array($key, $rounder[$displayData->_tmpType])) { $value = round($value,3); } ?>
					<?php if (isset($builder[$displayData->_tmpType][$key])): ?>
						<br />&#8627;&nbsp;<?php echo $builder[$displayData->_tmpType][$key] ?>: <b><?php echo $value;?></b>
					<?php endif; ?>
				<?php endforeach; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div style="float:left;">
		<ul class="uk-list uk-list-striped">
			<?php foreach ($displayData->female as $age => $values): ?>
			<li>
				<?php echo JText::_('COM_COSTBENEFITPROJECTION_FEMALES_AGE_GROUP'); ?> <?php echo $age; ?>
				<?php foreach ($values as $key => $value): ?>
					<?php if (in_array($key, $rounder[$displayData->_tmpType])) { $value = round($value,3); } ?>
					<?php if (isset($builder[$displayData->_tmpType][$key])): ?>
						<br />&#8627;&nbsp;<?php echo $builder[$displayData->_tmpType][$key] ?>: <b><?php echo $value;?></b>
					<?php endif; ?>
				<?php endforeach; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php else: ?>
	<?php echo JText::_('COM_COSTBENEFITPROJECTION_UNAVAILABLE_AT_THIS_TIME'); ?>
<?php endif; ?>
