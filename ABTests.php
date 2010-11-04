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
class Piwik_ABTests extends Piwik_Plugin
{
	/**
	 * Return information about this plugin.
	 *
	 * @see Piwik_Plugin
	 *
	 * @return array
	 */
	public function getInformation()
	{
		return array(
			'name' => 'ABTests',
			'description' => Piwik_Translate('ABTests_PluginDescription'),
			'author' => 'mySociety',
			'author_homepage' => 'http://mysociety.org/',
			'version' => '0.1',
			'homepage' => 'http://github.com/mysociety/abtests',
			'translationAvailable' => true,
			'TrackerPlugin' => true, // this plugin must be loaded during the stats logging
		);
	}
	
	public function getListHooksRegistered()
	{
		return array(
			'AssetManager.getCssFiles' => 'getCssFiles',
			'AssetManager.getJsFiles' => 'getJsFiles',
			'Menu.add' => 'addMenus',
		);
	}
	
	function getJsFiles( $notification )
	{
		$jsFiles = &$notification->getNotificationObject();
		$jsFiles[] = "plugins/ABTests/templates/ABTestForm.js";
	}

	function getCssFiles( $notification )
	{
		$cssFiles = &$notification->getNotificationObject();
		$cssFiles[] = "plugins/ABTests/templates/abtests.css";
	}
	
	function addMenus()
	{
		$idSite = Piwik_Common::getRequestVar('idSite');
	 	$experiments = Piwik_ABTests_API::getInstance()->getExperiments($idSite);
		$goals = Piwik_Goals_API::getInstance()->getGoals($idSite);
		
		if(count($experiments) == 0 && count($goals) > 0)
		{	
      Piwik_AddMenu(Piwik_Translate('ABTests_ABTests'), Piwik_Translate('ABTests_CreateExperiment') , array('module' => 'ABTests', 'action' => 'addNewExperiment'));
		}
		elseif (count($experiments) == 0)
		{
		   # messge saying you need goals to create experiments
		}
		else
		{
			Piwik_AddMenu('ABTests_ABTests', 'ABTests_Overview', array('module' => 'ABTests'));	
			foreach($experiments as $experiment) 
			{
				Piwik_AddMenu('ABTests_ABTests', str_replace('%', '%%', $experiment['name']), array('module' => 'ABTests', 'action' => 'experimentReport', 'idExperiment' => $experiment['idexperiment']));
			}

		}
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	function install()
	{
	  $experiments_table_spec	 = "`idsite` int(11) NOT NULL,
		                            `idgoal` int(11) NOT NULL,
		            		            `idexperiment` int(11) NOT NULL, 
		            		            `name` varchar(255) NOT NULL,
		 					                  `deleted` tinyint(4) NOT NULL default '0',
		                      	    PRIMARY KEY  (`idsite`,`idgoal`, `idexperiment`) ";
		
    self::createTable('experiment', $experiments_table_spec);
		
		$experiment_pages_table_spec = "`idsite` int(11) NOT NULL,
									                 `idexperiment` int(11) NOT NULL, 
                              		 `idpage` int(11) NOT NULL, 
                         			     `url` text NOT NULL,
                         			     `name` varchar(255) NOT NULL,
                         			     `original` tinyint(4) NOT NULL default '0',
                          			   `deleted` tinyint(4) NOT NULL default '0',
                         			PRIMARY KEY  (`idexperiment`, `idsite`, `idpage`) ";
		self::createTable('experiment_page', $experiment_pages_table_spec);
	  
	}
	
	/**
	 * @throws Exception if non-recoverable error
	 */
	function uninstall()
	{
	 	$sql = "DROP TABLE ". Piwik_Common::prefixTable('experiment') ;
		Piwik_Exec($sql);    
	 
	}
	
	function createTable( $tablename, $spec ) 
	{
		$sql = "CREATE TABLE IF NOT EXISTS ". Piwik_Common::prefixTable($tablename)." ( $spec )  DEFAULT CHARSET=utf8 " ;
		Piwik_Exec($sql);
	}
}
