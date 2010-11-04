<?php
/**
 * Piwik - Open source web analytics
 * ABTests Plugin - allow the creation of A/B tests.
 * 
 * @link http://mysociety.org
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 * @version 0.1
 * 
 * @category Piwik_Plugins
 * @package Piwik_ABTests
 */
 
/**
 *
 * @package Piwik_ABTests
 */
class Piwik_ABTests_Controller extends Piwik_Controller
{		

	function __construct()
	{
		parent::__construct();
		$this->idSite = Piwik_Common::getRequestVar('idSite');
		$this->experiments = Piwik_ABTests_API::getInstance()->getExperiments($this->idSite);
		
	}
	
	function index()
	{
    $view = Piwik_View::factory('overview');
		$this->setGeneralVariablesView($view);
		$view->experiments = $this->experiments;
		$view->experimentsJSON = json_encode($this->experiments);
		$view->userCanEditExperiments = Piwik::isUserHasAdminAccess($this->idSite);
    $view->goals = Piwik_Goals_API::getInstance()->getGoals($this->idSite);
		echo $view->render();
	}

	function addNewExperiment()
	{
		$view = Piwik_View::factory('add_new_experiment');
		$this->setGeneralVariablesView($view);
		$view->userCanEditExperiments = Piwik::isUserHasAdminAccess($this->idSite);
		$view->goalsPluginDeactived = ! Piwik_PluginsManager::getInstance()->isPluginActivated('Goals');
		$view->goals = Piwik_Goals_API::getInstance()->getGoals($this->idSite);
		$view->onlyShowAddNewExperiment = true;
		echo $view->render();
	}
	
	function experimentReport()
	{
	  $view = Piwik_View::factory('experiment_report');
	  $this->setGeneralVariablesView($view);
	  echo $view->render();
	}

}
