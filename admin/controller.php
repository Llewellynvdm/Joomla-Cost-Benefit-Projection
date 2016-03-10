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
 * General Controller of Costbenefitprojection component
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
		$view   = $this->input->getCmd('view', 'Costbenefitprojection');
		$data	= $this->getViewRelation($view);
		$layout	= $this->input->get('layout', null, 'WORD');
		$id    	= $this->input->getInt('id');

		// Check for edit form.
                if(CostbenefitprojectionHelper::checkArray($data))
                {
                    if ($data['edit'] && $layout == 'edit' && !$this->checkEditId('com_costbenefitprojection.edit.'.$data['view'], $id))
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
                            // normal redirect back to the list view
                            $this->setRedirect(JRoute::_('index.php?option=com_costbenefitprojection&view='.$data['views'], false));
                        }

                        return false;
                    }
                }

		return parent::display($cachable, $urlparams);
	}

	protected function getViewRelation($view)
	{
                if (CostbenefitprojectionHelper::checkString($view))
                {
                        $views = array(
				'company' => 'companies',
				'service_provider' => 'service_providers',
				'country' => 'countries',
				'causerisk' => 'causesrisks',
				'health_data' => 'health_data_sets',
				'scaling_factor' => 'scaling_factors',
				'intervention' => 'interventions',
				'currency' => 'currencies',
				'help_document' => 'help_documents'
                                );
                        // check if this is a list view
                        if (in_array($view,$views))
                        {
                            return array('edit' => false, 'view' => array_search($view,$views), 'views' => $view);
                        }
                        // check if it is an edit view
                        elseif (array_key_exists($view,$views))
                        {
                                return array('edit' => true, 'view' => $view, 'views' => $views[$view]);
                        }
                }
		return false;
	}
}
