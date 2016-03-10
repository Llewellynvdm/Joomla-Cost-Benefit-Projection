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

// reset all default arrays
$countryList = array();
$countryDetails = array();
$serviceList = array();
// build list layout for countries and service providers
if ($this->items)
{
	foreach($this->items as $item)
	{
		$temp = $this->buildDetails($item);
		$countryList[] = '<li aria-expanded="false" class=""><a href="#">'.$temp['list'].'</a></li>';
		$countryDetails[] = '<li aria-hidden="true" class="">'.$temp['details'].'</li>';
		// check if this country has service providers set
		if (CostbenefitprojectionHelper::checkArray($item->idCountryService_providerB))
		{
			$bucket = array();
			$displayChecker = count($item->idCountryService_providerB);
			foreach ($item->idCountryService_providerB as $service)
			{
				$temp = $this->buildDetails($service);
				if (1 == $displayChecker)
				{
					$bucket[] = '<h3>'.$temp['list'].'</h3><div>'.$temp['details'].'</div>';
				}
				else
				{
					$bucket[] = '<h3 class="uk-accordion-title">'.$temp['list'].'</h3><div class="uk-accordion-content">'.$temp['details'].'</div>';
				}
			}
			if (1 == $displayChecker)
			{			
				$serviceList[] = '<li aria-hidden="true" class="">'.  implode('', $bucket).'</li>';
			}
			else
			{
				$serviceList[] = '<li aria-hidden="true" class="" data-uk-check-display><div class="uk-accordion" data-uk-accordion="{showfirst:false}">'.  implode('', $bucket).'</div></li>';
			}
		}
		else
		{
			$serviceList[] = '<li aria-hidden="true" class="">'.JText::_('COM_COSTBENEFITPROJECTION_NONE_SET').'</li>';
		}
	}
}

?>
<?php echo $this->toolbar->render(); ?> 
<?php echo $this->loadTemplate('cbpmenumodule'); ?>
<?php if ($this->items): ?>
<div class="uk-alert uk-alert-success" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <p><?php echo JText::_('COM_COSTBENEFITPROJECTION_PA_SERVICE_PROVIDER_OR_COUNTRY_ADMINISTRATOR_MUST_SETUP_AN_ACCOUNT_FOR_YOUPPTHEREFORE_PLEASE_SELECT_YOUR_COUNTRY_AND_PREFERRED_SERVICE_PROVIDER_SHOULD_YOU_BE_UNSURE_WHICH_SERVICE_PROVIDER_TO_CONTACT_YOU_CAN_SIMPLY_CONTACT_THE_COUNTRY_ADMINISTRATORP'); ?></p>
</div>
<div data-uk-grid-margin="" class="uk-grid">
	<div class="uk-width-medium-1-4">
		<ul data-uk-switcher="{connect:'#countries, #service-providers'}" class="uk-nav uk-nav-side">
			<li class="uk-active" aria-expanded="true"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_LIST'); ?></a></li>
			<?php echo implode('',$countryList); ?>
		</ul>
	</div>
	<div class="uk-width-medium-3-4">
		<div data-uk-margin="" class="uk-grid">
			<div class="uk-width-medium-1-2">
				<h4><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_ADMINISTRATOR'); ?></h4>
				<ul class="uk-switcher" id="countries">
					<li class="uk-active" aria-hidden="false"><?php echo JText::_('COM_COSTBENEFITPROJECTION_HTWOYOUR_COUNTRY_CONTACT_DETAILS_WILL_SHOW_HEREHTWO'); ?></li>
					<?php echo  implode('',$countryDetails); ?>
				</ul>
			</div>
			<div class="uk-width-medium-1-2">
				<h4><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDERS'); ?></h4>
				<ul class="uk-switcher" id="service-providers">
					<li class="uk-active" aria-hidden="false"><?php echo JText::_('COM_COSTBENEFITPROJECTION_HTWOTHE_SERVICE_PROVIDERS_IN_YOUR_COUNTRY_WILL_SHOW_HEREHTWO'); ?></li>
					<?php echo  implode('',$serviceList); ?>				
				</ul>
			</div>
		</div>

	</div>
</div>
<?php endif; ?> 
