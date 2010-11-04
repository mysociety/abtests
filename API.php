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
class Piwik_ABTests_API 
{
	static private $instance = null;
	static public function getInstance()
	{
		if (self::$instance == null)
		{            
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	public function getExperiments( $idSite )
	{
	  Piwik::checkUserHasViewAccess($idSite);
		$experiment_table = Piwik_Common::prefixTable('experiment');
		$experiment_page_table = Piwik_Common::prefixTable('experiment_page');
		$goal_table = Piwik_Common::prefixTable('goal');
		$experiments = Piwik_FetchAll("SELECT ".$experiment_table.".*, ".$goal_table.".name as goal_name, ".$goal_table.".idgoal
								   FROM   ".$experiment_table.", ".$goal_table." 
								   WHERE  ".$experiment_table.".idsite = ?
								   AND    ".$experiment_table.".idgoal = ".$goal_table.".idgoal
								   AND    ".$experiment_table.".deleted = 0", array($idSite));
		$experimentsById = array();
		foreach($experiments as &$experiment)
		{
			$experiment_pages = Piwik_FetchAll("SELECT *
  								                    	 FROM   ".$experiment_page_table."
  											                 WHERE  idsite = ?
  											                 AND idexperiment = ?
  											                 AND deleted = 0", array($idSite, $experiment['idexperiment']));
  		$experiment['variation_pages'] = array();
  		foreach($experiment_pages as $experiment_page) 
  		{
  		  if ($experiment_page['original'] == 1)
  		  {
  		    $experiment['original_page'] = $experiment_page;
  		  } else {
  		    $experiment['variation_pages'][] = $experiment_page;
  		  }
  		}
			$experimentsById[$experiment['idexperiment']] = $experiment;
		}
		return $experimentsById;
	}
	
	public function addExperiment($idSite, $idGoal, $name, $originalPage, $variationPages)
	{
	  Piwik::checkUserHasAdminAccess($idSite);

    $this->validateExperiment($originalPage, $variationPages);

		// save in db
		$idExperiment = Piwik_FetchOne("SELECT max(idexperiment) + 1 
						     		FROM ".Piwik_Common::prefixTable('experiment')." 
							     	WHERE idsite = ?", $idSite);
		if($idExperiment == false)
		{
			$idExperiment = 1;
		}
		Piwik_Query("INSERT INTO " . Piwik_Common::prefixTable('experiment')."
					(idsite, idgoal, idexperiment, name)
					VALUES (?, ?, ?, ?)", array($idSite, $idGoal, $idExperiment, $name));
		$this->updateExperiment($idSite, $idGoal, $idExperiment, $name, $originalPage, $variationPages, 1);
		return $idExperiment;
	}
	
	public function updateExperiment($idSite, $idGoal, $idExperiment, $name, $originalPage, $variationPages, $validated=0)
	{
	  Piwik::checkUserHasAdminAccess($idSite);

    if ($validated == 0)
    {
      $this->validateExperiment($originalPage, $variationPages);
    }

	  # update the experiment attributes
	  Piwik_Query("UPDATE ".Piwik_Common::prefixTable('experiment')."
	    			 SET name = ?
						 WHERE idsite = ? AND idgoal = ? AND idexperiment= ?", 
						 array($name, $idSite, $idGoal, $idExperiment));
			
		# update the URLs	
		$currentPageIds = array();
	  
	  $originalPageId = $this->insertOrUpdatePage($idSite, $idExperiment, $originalPage, $name, 1);
	  $currentPageIds[] = $originalPageId;
	  
		foreach($variationPages as &$variationPage){
			$variationPageId = $this->insertOrUpdatePage($idSite, $idExperiment, $variationPage, $name, 0);
			$currentPageIds[] = $variationPageId;
		}
		
		// Any urls not currently defined should be set to deleted
		$whereClause = " WHERE idsite = ? AND idexperiment = ? ";
		$params = array($idSite, $idExperiment);
		if (count($currentPageIds) > 0) 
		{
			$currentIds = join(', ', $currentPageIds);
			$whereClause .= "AND idpage not in ($currentIds)";
		}
		Piwik_Query("UPDATE ". Piwik_Common::prefixTable('experiment_page')."
					 SET deleted = 1
					 $whereClause", $params);
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);	  
		
	}
	
	public function deleteExperiment( $idSite, $idGoal, $idExperiment )
	{
	  Piwik::checkUserHasAdminAccess($idSite);
		Piwik_Query("UPDATE ".Piwik_Common::prefixTable('experiment')."
										SET deleted = 1
										WHERE idsite = ? 
										AND idgoal = ?
										AND idexperiment = ?",
									array($idSite, $idGoal, $idExperiment));
		Piwik_Common::regenerateCacheWebsiteAttributes($idSite);
	}
	
	private function insertOrUpdatePage($idSite, $idExperiment, $pageInfo, $experimentName, $isOriginal)
	{
	  $idPage = $pageInfo['id'];
		if (! is_numeric($idPage))
		{
  	  throw new Exception(Piwik_TranslateException('ABTests_ExceptionNoPageId', $experimentName));
		}
		
		$name = $this->checkName($pageInfo['name']);
		$url = $this->checkUrl($pageInfo['url']);
		$exists = Piwik_FetchOne("SELECT idexperiment
								FROM ".Piwik_Common::prefixTable('experiment_page')." 
								WHERE idsite = ? 
								AND idexperiment = ?
								AND idpage = ?", array($idSite, $idExperiment, $idPage));
		if ($exists){
			Piwik_Query("UPDATE ".Piwik_Common::prefixTable('experiment_page')."
						 SET name = ?, url = ?, deleted = 0, original = ?
						 WHERE idsite = ? AND idpage = ? AND idexperiment = ?", 
						 array($name, $url, $isOriginal, $idSite, $idPage, $idExperiment));	
		} else {
			Piwik_Query("INSERT INTO ". Piwik_Common::prefixTable('experiment_page')."
						 (idsite, idexperiment, idpage, name, url, original) 
						 VALUES (?, ?, ?, ?, ?, ?)", 
 							 array($idSite, $idExperiment, $idPage, $name, $url, $isOriginal));
		}
	  return $idPage;
	}
	
	private function validateExperiment($originalPage, $variationPages)
	{
	  $this->validatePage($originalPage, 1);
    
    if (count($variationPages) == 0)
    {
      throw new Exception(Piwik_TranslateException("ABTests_ExceptionNoVariationPage")); 
    }
    
    foreach($variationPages as &$variationPage){
      $this->validatePage($variationPage, 0);
    }
	}
	
	private function validatePage($page, $original)
	{
	  if (trim($page['name']) == '')
	  {
	    if ($original == 1)
	    {
	      $message = "ABTests_ExceptionOriginalNameBlank";
	    } else {
	      $message = "ABTests_ExceptionVariationNameBlank";
	    }
	    throw new Exception(Piwik_TranslateException($message));
    }
	  
	  if (trim($page['url']) == '')
	  {
	    if ($original == 1)
	    {
	      $message = "ABTests_ExceptionOriginalUrlBlank";
	    } else {
	      $message = "ABTests_ExceptionVariationUrlBlank";
	    }
	    
	    throw new Exception(Piwik_TranslateException($message));
    }
    
    $this->checkUrl($page['url']);
    
	}
	
	private function checkUrl($url)
	{
	  $url = urldecode($url);
		if(substr($url, 0, 4) != 'http')
		{
      throw new Exception(Piwik_TranslateException('ABTests_ExceptionInvalidUrl', array("http:// or https://", "http://www.example.com/index.html")));
		}
		return $url;
	}
	
	private function checkName($name)
	{
		return urldecode($name);
	}
	
}
	