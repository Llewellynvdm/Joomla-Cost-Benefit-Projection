<?php
/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.3.8
	@build			10th March, 2016
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		controller.php
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Costbenefitprojection Component Controller
 */
class CostbenefitprojectionController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
        function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		$view		= $this->input->getCmd('view', 'cpanel');
		$isEdit		= $this->checkEditView($view);
		$layout		= $this->input->get('layout', null, 'WORD');
		$id		= $this->input->getInt('id');
		$cachable	= true;
		
		// Check for edit form.
                if($isEdit)
                {
			if ($layout == 'edit' && !$this->checkEditId('com_costbenefitprojection.edit.'.$view, $id))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				// check if item was opend from other then its own list view
				$ref 	= $this->input->getCmd('ref', 0);
				$refid 	= $this->input->getInt('refid', 0);
				// set redirect
				if ($refid > 0 && CostbenefitprojectionHelper::checkString($ref))
				{
					// redirect to item of ref
					$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view='.(string)$ref.'&layout=edit&id='.(int)$refid, false));
				}
				elseif (CostbenefitprojectionHelper::checkString($ref))
				{

					// redirect to ref
					 $this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view='.(string)$ref, false));
				}
				else
				{
					// normal redirect back to the list default site view
					$this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view=cpanel', false));
				}
				return false;
			}
                }

		return parent::display($cachable, $urlparams);
	}

	protected function checkEditView($view)
	{
                if (CostbenefitprojectionHelper::checkString($view))
                {
                        $views = array(
				'company',
				'scaling_factor',
				'intervention'
                                );
                        // check if this is a edit view
                        if (in_array($view,$views))
                        {
                                return true;
                        }
                }
		return false;
	}
}
