<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		companydetails.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

// set som user permissions
$user = JFactory::getUser();
$canCheckin = $user->authorise('core.manage', 'com_checkin') || $displayData->checked_out == $user->id || $displayData->checked_out == 0;
$userChkOut = JFactory::getUser($displayData->checked_out);
$canDo = CostbenefitprojectionHelper::getActions('company',$displayData,'companies');
// setup the cause risk list
$causesrisks = '<div class="uk-alert">'.JText::_('COM_COSTBENEFITPROJECTION_NO_CAUSERISK_SELECTED').'</div>';
if (isset($displayData->causesrisks) && CostbenefitprojectionHelper::checkArray($displayData->causesrisks))
{
	$causesrisks = '';
	$body = '';
	$head = array();
	foreach ($displayData->causesrisks as $id)
	{
		// get cause risk details
		$getValues = array('name','description','ref');
		$details = CostbenefitprojectionHelper::getCauseRiskDetails($id,$getValues);
		if (CostbenefitprojectionHelper::checkObject($details))
		{
			// build the dl list
			$row = '<tr>';
			foreach ($details as $title => $value)
			{
				// fix the ref display
				if ('ref' == $title)
				{
					$key = explode('.0',$value);
					$sort = explode('.',$value);
					$value = implode('.',$key);
					$sort = implode('',$key);
					// now set
					$row .= '<td data-value="'.$sort.'">' . $value . '</td>';
				}
				else
				{
					$row .= '<td>' . $value . '</td>';
				}
			}
			$row .= '</tr>';
			$body .= $row;
		}
	}
	$head = '<th data-toggle="true">'.implode('</th><th data-hide="phone,tablet">', array_map('setHeaderString',array_keys((array)$details))).'</th>';
	$causesrisks .= '<table class="footable metro-blue" data-page-size="10"><thead><tr>'.$head.'</li></tr></thead><tbody>'.$body.'</tbody><tfoot class="hide-if-no-paging"><tr><td colspan="3"><div class="pagination pagination-centered"></div></td></tr></tfoot></table>';
}
// setup the age groups display
$agepercents = '<div class="uk-panel uk-width-1-1"><div class="uk-alert">'.JText::_('COM_COSTBENEFITPROJECTION_NO_AGE_GROUPS_HAS_BEEN_SET').'</div></div>';
$agepercents_numbers = '<div class="uk-panel uk-width-1-1"><div class="uk-alert">'.JText::_('COM_COSTBENEFITPROJECTION_NO_AGE_GROUPS_HAS_BEEN_SET').'</div></div>';
$genderArray = array('male','female');
// loading option var
$both = 0;
foreach ($genderArray as $gender)
{
	// setup the related gender age groups
	if (isset($displayData->{'percent'.$gender}) && CostbenefitprojectionHelper::checkString($displayData->{'percent'.$gender}))
	{	
		// load chart builder
		$chart = new Chartbuilder('PieChart');
		$i = 0;
		$data = array();
		$rowArray = array();
		$rowArray_numbers = array();
		$dataset = json_decode($displayData->{'percent'.$gender});
		foreach ($dataset as $key => &$set)
		{
			if ('age' == $key)
			{
				$type = 'string';
				$percent = false;
			}
			else
			{
				$type = 'number';
				$percent = true;
			}
			// set header
			$data['cols'][$i] = array('id' => '', 'lable' => CostbenefitprojectionHelper::safeString($key,'Ww'), 'type' => $type);
			foreach ($set as $nr => $val)
			{
				if (!isset($rowArray_numbers[$nr]))
				{
					$rowArray_numbers[$nr] = '';
				}
				if ($percent)
				{
					$rowArray[$nr]['c'][] = array('v' => (int) $val);
					// set the table values
					$rowArray_numbers[$nr] .= '<td data-value="'.$val.'">' . $val . '%</td>';
				}
				else
				{
					$rowArray[$nr]['c'][] = array('v' => $val, 'f' => JText::_('COM_COSTBENEFITPROJECTION_AGE').' '.$val);
					// set the table values
					$rowArray_numbers[$nr] .= '<td>'.JText::_('COM_COSTBENEFITPROJECTION_AGE').' '. $val . '</td>';
				}
			}
			$i++;
		}
		$data['rows'] = $rowArray;
		$string = json_encode($data);
		$chart->load($string);
		$title = ($gender == 'male') ? JText::_('COM_COSTBENEFITPROJECTION_MALES'):JText::_('COM_COSTBENEFITPROJECTION_FEMALES');
		$options = array('backgroundColor' => '#fafafa', 'title' => $title, 'height' => '500', 'width' => '500','is3D' => 'true', 'tabclickdraw' => 'charttab');
		echo $chart->draw('age_'.$gender.'_'.$displayData->id,$options);
		// set chart array
		$age[] = '<div id="age_'.$gender.'_'.$displayData->id.'" style="width: 100%;"></div>';
		$gen[$title] = '<tr>'.implode('</tr><tr>',$rowArray_numbers).'</tr>';
		$both++;
	}
}

// now set the group percentages to the view
if ($both)
{
	// setup the chart
	$agepercents = '<div data-uk-grid-margin="" class="uk-grid" data-uk-grid-match="{target:\'.uk-panel\'}">';
	foreach ($age as $gender)
	{
			$agepercents .= '<div class="uk-width-medium-1-'.$both.'">';
			$agepercents .= '<div class="uk-panel uk-panel-box">';
			$agepercents .= $gender;
			$agepercents .= '</div>';
			$agepercents .= '</div>';
	}
	$agepercents .= '</div>';
	
	// setup the table
	$agepercents_numbers = '<div data-uk-grid-margin="" class="uk-grid" data-uk-grid-match="{target:\'.uk-panel\'}">';
	foreach ($gen as $title => $body)
	{
		$agepercents_numbers .= '<div class="uk-width-medium-1-'.$both.'">';
		$agepercents_numbers .= '<div class="uk-panel">';
		$agepercents_numbers .= '<h3>'.$title.'</h3>';
		$agepercents_numbers .= '<table class="footable metro-blue toggle-circle" data-page-size="10">';
		$agepercents_numbers .= '<thead><tr><th>'.JText::_('COM_COSTBENEFITPROJECTION_AGE').'</th><th data-type="numeric">'.JText::_('COM_COSTBENEFITPROJECTION_PERCENT').'</th></tr></thead>';
		$agepercents_numbers .= '<tbody>'.$body.'</tbody>';
		$agepercents_numbers .= '<tfoot class="hide-if-no-paging"><tr><td colspan="10"><div class="pagination pagination-centered"></div></td></tr></tfoot>';
		$agepercents_numbers .= '</table>';
		$agepercents_numbers .= '</div>';
		$agepercents_numbers .= '</div>';
	}
	$agepercents_numbers .= '</div>';
}
// set scaling factors
$scalingfactors = '<div class="uk-alert">'.JText::_('COM_COSTBENEFITPROJECTION_NO_SCALING_FACTORS_AVAILABLE_PLEASE_SELECT_A_CAUSERISK_TO_ACTIVATE').'</div>';
if (isset($displayData->idCompanyScaling_factorD) && CostbenefitprojectionHelper::checkArray($displayData->idCompanyScaling_factorD))
{
	// the values to display
	$keys = array(
		'causerisk_name' => JText::_('COM_COSTBENEFITPROJECTION_CAUSERISK'), 'reference' => JText::_('COM_COSTBENEFITPROJECTION_REFERENCE'),
		'yld_scaling_factor_males' => JText::_('COM_COSTBENEFITPROJECTION_YLD_MALES'),'yld_scaling_factor_females' => JText::_('COM_COSTBENEFITPROJECTION_YLD_FEMALES'),
		'mortality_scaling_factor_males' => JText::_('COM_COSTBENEFITPROJECTION_MORTALITY_MALES'),'mortality_scaling_factor_females' => JText::_('COM_COSTBENEFITPROJECTION_MORTALITY_FEMALES'),
		'presenteeism_scaling_factor_males' => JText::_('COM_COSTBENEFITPROJECTION_PRESENTEEISM_MALES'),'presenteeism_scaling_factor_females' => JText::_('COM_COSTBENEFITPROJECTION_PRESENTEEISM_FEMALES'),
		'published' => JText::_('COM_COSTBENEFITPROJECTION_STATUS'),'id' => JText::_('ID'));
	$rows = array('published' => 'setPublised', 'causerisk_name' => 'setScalingFactorLink');
	// header switces
	$datahide = array(
		'reference' => ' data-hide="all"', 'id' => ' data-ignore="true" data-hide="all"',
		'yld_scaling_factor_males' => ' data-hide="phone,tablet"','yld_scaling_factor_females' => ' data-hide="phone,tablet"',
		'mortality_scaling_factor_males' => ' data-hide="phone,tablet"','mortality_scaling_factor_females' => ' data-hide="phone,tablet"',
		'presenteeism_scaling_factor_males' => ' data-hide="phone,tablet"','presenteeism_scaling_factor_females' => ' data-hide="phone,tablet"');
	$datatype = array(
		'yld_scaling_factor_males' => ' data-type="numeric"','yld_scaling_factor_females' => ' data-type="numeric"',
		'mortality_scaling_factor_males' => ' data-type="numeric"','mortality_scaling_factor_females' => ' data-type="numeric"',
		'presenteeism_scaling_factor_males' => ' data-type="numeric"','presenteeism_scaling_factor_females' => ' data-type="numeric"',
		'id' => ' data-type="numeric"');
	$datatoggle = array('causerisk_name' => ' data-toggle="true"');
	// set the body
	$body = '';
	foreach ($displayData->idCompanyScaling_factorD as $details)
	{
		if (CostbenefitprojectionHelper::checkObject($details))
		{
			// build the dl list
			$body .= '<tr>';
			foreach ($keys as $key => $header)
			{
				if (array_key_exists($key, $rows))
				{
					// this should be save since all data passed is internal
					$body .= "{$rows[$key]($details)}";
				}
				else
				{
					$body .= '<td>'.  $details->$key.'</td>';
				}
			}
			$body .= '</tr>';
		}
	}
	// set the header dynamic
	$head = '';
	foreach ($keys as $key => $header)
	{
		$head .= '<th';
		if (array_key_exists($key, $datatoggle))
		{
			// this should be save since all data passed is internal
			$head .= $datatoggle[$key];
		}
		if (array_key_exists($key, $datatype))
		{
			// this should be save since all data passed is internal
			$head .= $datatype[$key];
		}
		if (array_key_exists($key, $datahide))
		{
			// this should be save since all data passed is internal
			$head .= $datahide[$key];
		}
		
		$head .= '>'.  $header.'</th>';
	}
	$scalingfactors = '<table class="footable metro-blue toggle-circle" data-page-size="10"><thead><tr>'.$head.'</tr></thead><tbody>'.$body.'</tbody><tfoot class="hide-if-no-paging"><tr><td colspan="10"><div class="pagination pagination-centered"></div></td></tr></tfoot></table>';
}
// set interventions
$interventions = '<div class="uk-alert">'.JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTIONS_SET').'</div>';
if (isset($displayData->idCompanyInterventionE) && CostbenefitprojectionHelper::checkArray($displayData->idCompanyInterventionE))
{
	// the values to display
	$keys = array(
		'id' => JText::_('ID'),'name' => JText::_('COM_COSTBENEFITPROJECTION_NAME'),
		'type' => JText::_('COM_COSTBENEFITPROJECTION_TYPE'),'coverage' => JText::_('COM_COSTBENEFITPROJECTION_COVERAGE'),
		'share' => JText::_('COM_COSTBENEFITPROJECTION_SHARE'),'description'  => JText::_('COM_COSTBENEFITPROJECTION_DESCRIPTION'),
		'reference' => JText::_('COM_COSTBENEFITPROJECTION_REFERENCE'),
		'intervention' => JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION'),'published' => JText::_('COM_COSTBENEFITPROJECTION_STATUS'));
	$rows = array('published' => 'setPublised', 'intervention' => 'setIntervention', 'name' => 'setInterventionLink', 'type' => 'setInterventionType', 'share' => 'setInterventionShare');
	// header switces
	$datahide = array(
		'id' => ' data-ignore="true" data-hide="all"',
		'share' => ' data-hide="phone,tablet"','description'  => ' data-hide="all"',
		'reference' => ' data-hide="all"',
		'intervention' => ' data-hide="all"');
	$datatype = array(
		'id' => ' data-type="numeric"', 'coverage' => ' data-type="numeric"');
	$datatoggle = array('name' => ' data-toggle="true"');
	// set the body
	$body = '';
	foreach ($displayData->idCompanyInterventionE as $details)
	{
		if (CostbenefitprojectionHelper::checkObject($details))
		{
			// build the dl list
			$body .= '<tr>';
			foreach ($keys as $key => $header)
			{
				if (array_key_exists($key, $rows))
				{
					// this should be save since all data passed is internal
					$body .= "{$rows[$key]($details)}";
				}
				else
				{
					$body .= '<td>'.  $details->$key.'</td>';
				}
			}
			$body .= '</tr>';
		}
	}
	// set the header dynamic
	$head = '';
	foreach ($keys as $key => $header)
	{
		$head .= '<th';
		if (array_key_exists($key, $datatoggle))
		{
			// this should be save since all data passed is internal
			$head .= $datatoggle[$key];
		}
		if (array_key_exists($key, $datatype))
		{
			// this should be save since all data passed is internal
			$head .= $datatype[$key];
		}
		if (array_key_exists($key, $datahide))
		{
			// this should be save since all data passed is internal
			$head .= $datahide[$key];
		}
		
		$head .= '>'.  $header.'</th>';
	}
	$interventions = '<table class="footable metro-blue toggle-circle" data-page-size="10"><thead><tr>'.$head.'</tr></thead><tbody>'.$body.'</tbody><tfoot class="hide-if-no-paging"><tr><td colspan="9"><div class="pagination pagination-centered"></div></td></tr></tfoot></table>';
}

?>
<?php if ($canDo->get('company.edit')): ?>
	<?php if ($displayData->checked_out): ?>
		<?php echo JHtml::_('jgrid.checkedout', $displayData->id, $userChkOut->name, $displayData->checked_out_time, 'company.', $canCheckin); ?>
	<?php endif; ?>
<?php endif; ?>
<?php if ($canDo->get('company.edit') && $canDo->get('company.delete')): ?>
	<div class="uk-button-group uk-width-1-1">
		<?php $canDeleteNow = ''; ?>
		<?php if ($displayData->checked_out && $canCheckin): ?>
			<a class="uk-button uk-button-primary uk-button-large uk-width-2-5" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company&task=company.edit&id=' . $displayData->id); ?>"><i class="uk-icon-pencil"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></a>
		<?php elseif ($displayData->checked_out && !$canCheckin): ?>
			<?php $canDeleteNow = ' disabled'; ?>
			<button class="uk-button uk-button-large uk-width-2-5" type="button" disabled><i class="uk-icon-lock"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></button>
		<?php else: ?>
			<a class="uk-button uk-button-primary uk-button-large uk-width-2-5" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company&task=company.edit&id=' . $displayData->id); ?>"><i class="uk-icon-pencil"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></a>
		<?php endif; ?>
		<a class="uk-button uk-button-success uk-button-large uk-width-2-5" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company-results&id=' . $displayData->id); ?>"><i class="uk-icon-bar-chart"></i><span class="uk-hidden-small"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></span></a>
		<button class="uk-button uk-button-danger uk-button-large uk-width-1-5" type="button"<?php echo $canDeleteNow; ?>><i class="uk-icon-trash"></i><span class="uk-hidden-small"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASH'); ?></span></a>
	</div>
<?php elseif ($canDo->get('company.edit')): ?>
	<div class="uk-button-group uk-width-1-1">
		<?php if ($displayData->checked_out && $canCheckin): ?>
			<a class="uk-button uk-button-primary uk-button-large uk-width-1-2" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company&task=company.edit&id=' . $displayData->id); ?>"><i class="uk-icon-pencil"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></a>
		<?php elseif ($displayData->checked_out && !$canCheckin): ?>
			<button class="uk-button uk-button-large uk-width-1-2" type="button" disabled><i class="uk-icon-lock"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></button>
		<?php else: ?>
			<a class="uk-button uk-button-primary uk-button-large uk-width-1-2" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company&task=company.edit&id=' . $displayData->id); ?>"><i class="uk-icon-pencil"></i><span class="uk-hidden-small"> <?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_EDIT_BASIC_PROFILE') : JText::_('COM_COSTBENEFITPROJECTION_EDIT_ADVANCED_PROFILE'); ?></span></a>
		<?php endif; ?>
		<a class="uk-button uk-button-success uk-button-large uk-width-1-2" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company-results&id=' . $displayData->id); ?>"><i class="uk-icon-bar-chart"></i><span class="uk-hidden-small"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></span></a>
	</div>
<?php else: ?>
	<a class="uk-button uk-button-success uk-button-large uk-width-1-1" href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=company-results&id=' . $displayData->id); ?>"><i class="uk-icon-bar-chart"></i><span class="uk-hidden-small"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></span></a>
<?php endif; ?>
<div class="uk-panel">
<ul class="uk-tab uk-tab-grid uk-width-medium-1-1 uk-tab-bottom" data-uk-tab="{connect:'#company_tab_<?php echo $displayData->id; ?>', animation: 'scale'}">
    <li class="uk-width-medium-1-5 uk-active"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_INFO'); ?></a></li>
    <li class="charttab uk-width-medium-1-5 <?php echo (2 == $displayData->department) ? '':'uk-disabled'; ?>"><a class="charttab" href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_AGE_GROUPS'); ?></a></li>
    <li class="footabletab uk-width-medium-1-5 <?php echo (2 == $displayData->department) ? '':'uk-disabled'; ?>"><a class="footabletab" href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CAUSESRISKS'); ?></a></li>
    <li class="footabletab uk-width-medium-1-5 <?php echo (2 == $displayData->department) ? '':'uk-disabled'; ?>"><a class="footabletab" href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_SCALING_FACTORS'); ?></a></li>
    <li class="footabletab uk-width-medium-1-5 <?php echo (2 == $displayData->department) ? '':'uk-disabled'; ?>"><a class="footabletab" href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTIONS'); ?></a></li>
</ul>

<ul id="company_tab_<?php echo $displayData->id; ?>" class="uk-switcher uk-margin">
	<li>
		<div data-uk-grid-margin="" class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}">
			<div class="uk-width-medium-1-2">
				<div class="uk-panel uk-panel-box">
					<h3 class="uk-panel-title"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DETAILS'); ?></h3>
					<dl class="uk-description-list-line">
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_USER'); ?></dt><dd><?php echo JFactory::getUser($displayData->user)->name; ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_EMAIL'); ?></dt><dd><?php echo $displayData->email; ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_DEPARTMENT'); ?></dt><dd><?php echo ($displayData->department ==1) ? JText::_('COM_COSTBENEFITPROJECTION_BASIC') : JText::_('COM_COSTBENEFITPROJECTION_ADVANCED'); ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY'); ?></dt><dd><?php echo $displayData->country_name; ?></dd>
						<?php if ($displayData->service_provider_publicname): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER'); ?></dt><dd><?php echo $displayData->service_provider_publicname; ?></dd>
						<?php else: ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER'); ?></dt><dd><?php echo $displayData->service_provider_name; ?></dd>
						<?php endif; if ($displayData->service_provider_publicnumber): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_NUMBER'); ?></dt><dd><?php echo $displayData->service_provider_publicnumber; ?></dd>
						<?php endif; if ($displayData->service_provider_publicemail): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDER_EMAIL'); ?></dt><dd><?php echo $displayData->service_provider_publicemail; ?></dd>
						<?php endif; if ($displayData->country_publicname): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_CONTACT'); ?></dt><dd><?php echo $displayData->country_publicname; ?></dd>
						<?php endif; if ($displayData->country_publicname): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_CONTACT_NUMBER'); ?></dt><dd><?php echo $displayData->country_publicnumber; ?></dd>
						<?php endif; if ($displayData->country_publicemail): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_CONTACT_EMAIL'); ?></dt><dd><?php echo $displayData->country_publicemail; ?></dd>
						<?php endif; ?>
					</dl>
				</div>
			</div>
			<div class="uk-width-medium-1-2">
				<div class="uk-panel uk-panel-box">
					<h3 class="uk-panel-title"><?php echo JText::_('COM_COSTBENEFITPROJECTION_NUMBERS'); ?></h3>
					<dl class="uk-description-list-line">
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA_YEAR'); ?></dt><dd><?php echo $displayData->datayear; ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_WORKING_DAYS'); ?></dt><dd><?php echo $displayData->working_days; ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_SALARY'); ?></dt><dd><?php echo costbenefitprojectionHelper::makeMoney($displayData->total_salary); ?></dd>
						<?php if ($displayData->total_healthcare && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_HEALTHCARE_COSTS'); ?></dt><dd><?php echo costbenefitprojectionHelper::makeMoney($displayData->total_healthcare); ?></dd>
						<?php endif; if ($displayData->productivity_losses && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_MEDICAL_TURNOVER_COST_FACTOR'); ?></dt><dd><?php echo $displayData->productivity_losses; ?></dd>
						<?php endif; ?>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_MALE_EMPLOYEES'); ?></dt><dd><?php echo $displayData->males; ?></dd>
						<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_FEMALE_EMPLOYEES'); ?></dt><dd><?php echo $displayData->females; ?></dd>
						<?php if ($displayData->medical_turnovers_males && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_NUMBER_MEDICAL_TURNOVERS_MALE'); ?></dt><dd><?php echo $displayData->medical_turnovers_males; ?></dd>
						<?php endif; if ($displayData->medical_turnovers_females && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_NUMBER_MEDICAL_TURNOVERS_FEMALE'); ?></dt><dd><?php echo $displayData->medical_turnovers_females; ?></dd>
						<?php endif; if ($displayData->sick_leave_males && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_NUMBER_SICK_LEAVE_DAYS_MALES'); ?></dt><dd><?php echo $displayData->sick_leave_males; ?></dd>
						<?php endif; if ($displayData->sick_leave_females && (2 == $displayData->department)): ?>
							<dt><?php echo JText::_('COM_COSTBENEFITPROJECTION_TOTAL_NUMBER_SICK_LEAVE_DAYS_FEMALES'); ?></dt><dd><?php echo $displayData->sick_leave_females; ?></dd>
						<?php endif; ?>
					</dl>
				</div>
			</div>
		</div>
	</li>
	<li>
		<div data-uk-margin>
			<?php echo $agepercents_numbers; ?>
			<div class="uk-visible-large">
				<?php $chartsId = CostbenefitprojectionHelper::randomkey(3); ?>
				<button class="uk-button uk-width-1-1 charttab" data-uk-toggle="{target:'#<?php echo $chartsId; ?>'}"><?php echo JText::_('COM_COSTBENEFITPROJECTION_AGE_GROUP_CHARTS'); ?></button>
				<div id="<?php echo $chartsId; ?>" class="uk-hidden"><?php echo $agepercents; ?></div>
			</div>
		</div>
	</li>
	<li>
		<div class="uk-panel uk-width-1-1">
			<?php echo $causesrisks; ?>
		</div>
	</li>
	<li>
		<div class="uk-panel uk-width-1-1">
			<?php echo $scalingfactors; ?>
		</div>
	</li>
	<li>
		<div class="uk-panel uk-width-1-1">
			<?php if (1): ?>
				<a class="uk-button uk-button-small uk-button-success" href="index.php?option=com_costbenefitprojection&view=intervention&layout=edit&ref=cpanel"><i class="uk-icon-plus"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_NEW'); ?></a><br /><br />
			<?php endif; ?>
			<?php echo $interventions; ?>
		</div>
	</li>
</ul>
</div>
