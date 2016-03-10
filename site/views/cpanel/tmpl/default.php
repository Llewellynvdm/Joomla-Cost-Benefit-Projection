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

// get user type 
$useris =  CostbenefitprojectionHelper::userIs($this->user->id);
$usergroup =  CostbenefitprojectionHelper::setGroupNames($this->user->get('groups'));
// load modules if public
$login_cp = false;
$public_cp = false;
$top_cp = array();
if(!$useris)
{
	$login_cp = $this->getModules('login-cp','div','uk-panel');
	$public_cp = $this->getModules('public-cp','div','uk-panel');
	$top_cp = $this->getModules('top_cp','array');
}
// quick header fix function
function setHeaderString($n)
{
	return CostbenefitprojectionHelper::safeString($n,'Ww');
}
// setting the published state
function setPublised($item)
{
	$state = '<td>'.$item->published.'</td>';
	switch($item->published)
	{
		case 1:
			// Published
			$state = '<td data-value="published"><i class="uk-badge uk-badge-notification uk-icon-check uk-badge-success" data-uk-tooltip title="'.JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED').'"></i></td>';
			break;
		case 0:
			// UnPublished
			$state = '<td data-value="unpublished"><i class="uk-badge uk-badge-notification uk-icon-ban uk-badge-warning" data-uk-tooltip title="'.JText::_('COM_COSTBENEFITPROJECTION_UNPUBLISHED').'"></i></td>';
			break;
		case 2:
			// Archived
			$state = '<td data-value="archived"><i class="uk-badge uk-badge-notification uk-icon-archive" data-uk-tooltip title="'.JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED').'"></i></td>';
			break;
		case -2:
			// Trashed
			$state = '<td data-value="trashed"><i class="uk-badge uk-badge-notification uk-icon-trash-o uk-badge-danger" data-uk-tooltip title="'.JText::_('COM_COSTBENEFITPROJECTION_TRASHED').'"></i></td>';
			break;	
	}
	return $state;
}
// set scaling factor link
function setScalingFactorLink($item)
{
	$user	= JFactory::getUser();
	$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
	$userChkOut = JFactory::getUser($item->checked_out);
	$canDo = CostbenefitprojectionHelper::getActions('scaling_factor',$item,'scaling_factors');
	if ($canDo->get('scaling_factor.edit'))
	{
		if ($item->checked_out && $canCheckin)
		{
			$link =	'<td><a href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=scaling_factor&task=scaling_factor.edit&id='.$item->id) . '" >' . $item->causerisk_name. '</a>';
			$link .= JHtml::_('jgrid.checkedout', $item->id, $userChkOut->name, $item->checked_out_time, 'scaling_factors.', $canCheckin).'</td>';
		}
		elseif ($item->checked_out && !$canCheckin)
		{
			$link =	'<td>' . $item->causerisk_name;
			$link .= JHtml::_('jgrid.checkedout', $item->id, $userChkOut->name, $item->checked_out_time, 'scaling_factors.', $canCheckin).'</td>';
		}
		else
		{
			$link =	'<td><a href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=scaling_factor&task=scaling_factor.edit&id='.$item->id) . '" >' . $item->causerisk_name. '</a></td>';
		}
	}
	else
	{
		$link =	'<td>' . $item->causerisk_name . '</td>';
	}
	return $link;
}
// set intervention link
function setInterventionLink($item)
{
	$user	= JFactory::getUser();
	$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
	$userChkOut = JFactory::getUser($item->checked_out);
	$canDo = CostbenefitprojectionHelper::getActions('intervention',$item,'interventions');
	if ($canDo->get('intervention.edit'))
	{
		if ($item->checked_out && $canCheckin)
		{
			$link =	'<td><a href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=intervention&task=intervention.edit&id='.$item->id) . '" >' . $item->name. '</a>';
			$link .= JHtml::_('jgrid.checkedout', $item->id, $userChkOut->name, $item->checked_out_time, 'interventions.', $canCheckin).'</td>';
		}
		elseif ($item->checked_out && !$canCheckin)
		{
			$link =	'<td>' . $item->name;
			$link .= JHtml::_('jgrid.checkedout', $item->id, $userChkOut->name, $item->checked_out_time, 'interventions.', $canCheckin).'</td>';
		}
		else
		{
			$link =	'<td><a href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=intervention&task=intervention.edit&id='.$item->id) . '" >' . $item->name. '</a></td>';
		}
	}
	else
	{
		$link =	'<td>' . $item->name . '</td>';
	}
	return $link;
}
// setting the intervetnion type
function setInterventionType($item)
{
	$state = '<td>'.$item->type.'</td>';
	switch($item->type)
	{
		case 1:
			// Single
			$state = '<td>'.JText::_('COM_COSTBENEFITPROJECTION_SINGLE').'</td>';
			break;
		case 2:
			// Cluster
			$state = '<td>'.JText::_('COM_COSTBENEFITPROJECTION_CLUSTER').'</td>';
			break;
	}
	return $state;
}
// setting the intervetnion share
function setInterventionShare($item)
{
	$state = '<td>'.$item->share.'</td>';
	switch($item->share)
	{
		case 1:
			// Only Me
			$state = '<td>'.JText::_('COM_COSTBENEFITPROJECTION_ONLY_ME').'</td>';
			break;
		case 2:
			// My Service Provider
			$state = '<td>'.JText::_('COM_COSTBENEFITPROJECTION_MY_SERVICE_PROVIDER').'</td>';
			break;
			break;
		case 3:
			// All Service Providers
			$state = '<td>'.JText::_('COM_COSTBENEFITPROJECTION_ALL_SERVICE_PROVIDERS').'</td>';
			break;
	}
	return $state;
}

// set the intervention details
function setIntervention($item)
{
	if (CostbenefitprojectionHelper::isJson($item->intervention))
	{
		$bucket = array();
		$bucketsmall = array();
		$interventions = json_decode($item->intervention);
		foreach ($interventions as $name => $values)
		{
			if (CostbenefitprojectionHelper::checkArray($values))
			{
				foreach ($values as $pointer => $value)
				{
					if (!isset($bucket[$pointer]))
					{
						$bucket[$pointer] = '';
						$bucketsmall[$pointer] = '';
					}
					switch($name)
					{
						case 'causerisk':
							$causeName = CostbenefitprojectionHelper::getVar('causerisk', $value, 'id', 'name');
							$bucket[$pointer] .= '<td>'.$causeName."</td>";
							$bucketsmall[$pointer] .= $causeName.': ';
							break;
						case 'cpe':
							$bucket[$pointer] .= '<td class="uk-text-center"><span class="uk-badge uk-badge-notification uk-badge-success">'.$value.'</span></td>';
							$bucketsmall[$pointer] .= '<span class="uk-badge uk-badge-success">'.$name.' '.$value.'</span> ';
							break;
						case 'mbr':
							$bucket[$pointer] .= '<td class="uk-text-center"><span class="uk-badge uk-badge-notification uk-badge-success">'.$value.'</span></td>';
							$bucketsmall[$pointer] .= '<span class="uk-badge uk-badge-success">'.$name.' '.$value.'</span> ';
							break;
						case 'mtr':
							$bucket[$pointer] .= '<td class="uk-text-center"><span class="uk-badge uk-badge-notification uk-badge-success">'.$value.'</span></td>';
							$bucketsmall[$pointer] .= '<span class="uk-badge uk-badge-success">'.$name.' '.$value.'</span>';
							break;
					}
				}
			}
		}
	}
	return '<td><table class="uk-table  uk-table-hover uk-table-striped uk-table-condensed uk-hidden-small"><thead><tr><th>'.JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK').'</th><th>'.JText::_('COM_COSTBENEFITPROJECTION_COST_PER_EMPLOYEE').'</th><th>'.JText::_('COM_COSTBENEFITPROJECTION_MORBIDITY_REDUCTION').'</th><th>'.JText::_('COM_COSTBENEFITPROJECTION_MORTALITY_REDUCTION').'</th></tr></thead><tbody><tr>'.implode('</tr><tr>',$bucket).'</tr></tbody></table><div class="uk-visible-small">'.implode('<br />',$bucketsmall).'</div></td>';
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
	<?php if(1 == $useris): ?>
		<div class="uk-grid uk-margin" >
			<div class="uk-panel uk-width-1-1 uk-container-center uk-text-center">
				<h3>
					<i class="uk-icon-user"></i> <?php echo $this->user->name; ?>: <?php echo (count($this->items) > 1) ? JText::_('COM_COSTBENEFITPROJECTION_COMPANIES'):JText::_('COM_COSTBENEFITPROJECTION_COMPANY'); ?>
				</h3>
			</div>
			<?php if (isset($this->items) && costbenefitprojectionHelper::checkArray($this->items) && count($this->items) > 1): ?>
				<div class="uk-width-1-1">
					<button class="uk-button uk-button-large uk-width-1-1 uk-margin-small-bottom uk-icon-hover uk-icon-object-group" data-uk-modal="{target:'#combine'}" data-uk-tooltip title="Click to Combine Company Data" > <?php echo JText::_('COM_COSTBENEFITPROJECTION_VIEW_COMBINED_RESULTS'); ?></span</button>
				</div>
			<?php endif; ?>
			<?php if (isset($this->items) && costbenefitprojectionHelper::checkArray($this->items)): ?>
				<!-- This is the modal -->
				<div id="combine" class="uk-modal">
				    <div class="uk-modal-dialog">
				        <a class="uk-modal-close uk-close"></a>
				        <h3><?php echo JText::_('COM_COSTBENEFITPROJECTION_SELECT_COMPANIES_TO_COMBINE'); ?><h3>
				        <p data-uk-margin>
				        <?php foreach ($this->items as $item): ?>
                       	        	         <button id="combined_<?php echo $item->id; ?>" class="uk-button uk-margin-small-top" onclick="setCombined(<?php echo $item->id; ?>)" status="0"><i id="icon_<?php echo $item->id; ?>" class='uk-icon-toggle-off uk-hidden-small'></i> <?php echo $item->name; ?></button>
				       <?php endforeach;?>
				        </p><input id="combined" value="" type="hidden"><div class="uk-modal-footer"><button class="uk-button uk-button-primary" type="button" onclick="combine()"><?php echo JText::_('COM_COSTBENEFITPROJECTION_VIEW_COMBINED_RESULTS'); ?></button></div>
				    </div>
				</div>
				<div class="uk-accordion uk-width-1-1" data-uk-accordion="{showfirst:false}">
					<?php foreach ($this->items as $item): ?>
						<h3 class="uk-accordion-title"><i class='uk-icon-building-o uk-hidden-small'></i> <?php echo $item->name; ?><span class="uk-hidden-small"> <em>(<?php echo ($item->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_ADVANCED_PROFILE'); ?>)</em></span><span class="uk-visible-small"> <em>(<?php echo ($item->department ==1) ? 'B' : 'A'; ?>)</em></span></h3>
						<div class="uk-accordion-content">
							<?php echo JLayoutHelper::render('companydetails', $item); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ($this->user->authorise('company.create', 'com_costbenefitprojection')): ?>
			<div class="uk-width-1-1">
				<a class="uk-button uk-button-large uk-button-success uk-width-1-1" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company&layout=edit'); ?>"><i class="uk-icon-wrench"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CREATE_COMPANY'); ?></a>
			</div>
			<?php endif; ?>
		</div>
	<?php elseif($useris > 1): ?>
		<div class="uk-grid uk-margin" >
			<div class="uk-width-1-1">
				<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_WELCOME') . '  '  . $this->user->name; $userToken = JSession::getFormToken(); $adminArea = JRoute::_('administrator');?></h1>
				<div class="uk-alert"><?php echo JText::_('COM_COSTBENEFITPROJECTION_YOU_SHOULD_LOGOUT_OF_THIS_AREA_OF_THE_TOOL_AND_LOGIN_TO_THE_BACKEND_TO_MAKE_FULL_USE_OF_THIS_TOOL'); ?></div>
				<a class="uk-button uk-button-large uk-button-success uk-width-1-1" href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . $userToken . '=1&return='.  base64_encode($adminArea)); ?>"><i class="uk-icon-shield"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_ACCESS_BACKEND_LOGOUT_FROM_THIS_AREA'); ?></a>
			</div>
		</div>
	<?php else: ?>
		<div class="uk-grid uk-margin" >
			<div class="uk-width-1-1">
				<br/>
				<?php if (CostbenefitprojectionHelper::checkArray($top_cp)): ?>
					<?php $width = count($top_cp); // data-uk-lightbox just in case ?>
					<div class="uk-width-medium-1-<?php echo $width; ?>"><div class="uk-panel"><?php echo implode('</div></div><div class="uk-width-medium-1-<?php echo $width; ?>"><div class="uk-panel">',$top_cp); ?></div></div>
				<?php endif; ?>
				<div class="uk-animation-fade uk-animation-scale-down">
					<img style="display: block; margin-left: auto; margin-right: auto; vertical-align: middle;" src="<?php echo JRoute::_('media/com_costbenefitprojection/images/cbp_box.png'); ?>" alt="<?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_BENEFIT_PROJECTION'); ?>"/>
				</div>
				<div class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin="">
					<div class="uk-width-medium-1-2 uk-animation-scale-up">
						<div class="uk-panel uk-panel-box uk-text-center">
						    <p><?php echo JText::_('COM_COSTBENEFITPROJECTION_TO_TRY_OUT_THE_TOOL_AND_DISCOVER_MORE'); ?></p>
							<!-- This is a button toggling the modal -->
								<button class="uk-button" data-uk-modal="{target:'#public'}"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLICK_HERE'); ?></button>
						</div>
					</div>
					<div class="uk-width-medium-1-2 uk-animation-scale-up">
						<div class="uk-panel uk-panel-box uk-text-center">
							<p><?php echo JText::_('COM_COSTBENEFITPROJECTION_MEMBER_LOGIN'); ?></p>
							<!-- This is a button toggling the modal -->
							<button class="uk-button" data-uk-modal="{target:'#member'}"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLICK_HERE'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="uk-width-1-1">
		<p class="uk-text-center"><a href="#appnotice" data-uk-offcanvas class="uk-text-primary uk-text-bold"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COST_BENEFIT_PROJECTION_TOOL'); ?></a></p>
	</div>
</div>

<?php if (!$useris): ?>
<!-- This is the modal -->
<div id="member" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
	<div class="uk-panel">
		<a class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=createaccount'); ?>"><i class="uk-icon-check"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_OPEN_FREE_ACCOUNT_NOW'); ?></a>
	</div>
	<?php if ($login_cp): ?>
		<?php echo $login_cp; ?>
	<?php else: ?>
		<div class="uk-alert"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SOON_THE_MEMBER_ACCESS_WILL_BE_READY'); ?></div>
	<?php endif; ?>
    </div>
</div>

<!-- This is the modal -->
<div id="public" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
	<div class="uk-panel">
		<a class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=createaccount'); ?>"><i class="uk-icon-check"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_OPEN_FREE_ACCOUNT_NOW'); ?></a>
	</div>
	<?php if ($public_cp): ?>
		<?php echo $public_cp; ?>
	<?php else: ?>
		<div class="uk-alert"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SOON_THE_PUBLIC_ACCESS_WILL_BE_READY'); ?></div>
	<?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- This is the off-canvas sidebar -->
<div id="appnotice" class="uk-offcanvas">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-flip"><?php echo JLayoutHelper::render('appnotice',''); ?></div>
</div>
<script>
// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#main_costbenefitprojection').fadeIn( 'fast', function() {
			<?php if (isset($this->items) && costbenefitprojectionHelper::checkArray($this->items) && $useris): ?>
				jQuery('.footable').footable();
			<?php endif; ?>
		});
	});
});
<?php if (isset($this->items) && costbenefitprojectionHelper::checkArray($this->items) && $useris): ?>
// foo table trigger on click
jQuery('.footabletab').click(function(e){
	 // use setTimeout() to execute
	setTimeout( resizeFooTable, 300);
});
// funtion to resize the foo table 
function resizeFooTable() {     
	jQuery('.footable').trigger('footable_resize');
}
// update the value of combined
function setCombined(id)
{
	jQuery('#combined_'+id).toggleClass('uk-button-success');
	jQuery('#icon_'+id).toggleClass('uk-icon-toggle-off uk-icon-toggle-on');
	// now update the input value
	var has = jQuery('#combined_'+id).attr('status');
	// change the status
	if (has == 1)
	{
		jQuery('#combined_'+id).attr('status',0);
		has = false;
	}
	else
	{
		jQuery('#combined_'+id).attr('status',1);
		has = true;
	}
	// update the input values
	updateCombined(id,has);
}
// update required fields
function updateCombined(value,status)
{
	var input = jQuery('#combined').val();
	if(status)
	{
		if (isThisSet(input) && input != 0)
		{
			input = input+'_'+value;
		}
		else
		{
			input = '_'+value;
		}
	}
	else
	{
		if (isThisSet(input) && input != 0)
		{
			input = input.replace('_'+value,'');
		}
	}
	// now update the values
	jQuery('#combined').val(input);
}
// the isThisSet function
function isThisSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
}
// load combined
function combine()
{
	var combine = jQuery('#combined').val();
	window.location.href = "<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=combinedresults'); ?>&cid="+combine;
	return false;
}
<?php endif; ?>
</script> 
