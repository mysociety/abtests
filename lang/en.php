<?php 
/**
 * Piwik - Open source web analytics
 * ABTests Plugin - conduct A/B tests
 * 
 * @link http://mysociety.org
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 * @version 0.1
 * 
 * @category Piwik_Plugins
 * @package Piwik_ABTests
 */

$translations = array(
	'ABTests_PluginDescription' => 'ABTests Plugin: This plugin allows you to conduct A/B tests of different versions of pages on your site.',
  'ABTests_ABTests' => 'A/B Tests',
  'ABTests_CreateExperiment' => 'Create an experiment', 
  'ABTests_NoExperimentsNeedAccess' => 'Only an Administrator or the Super User can add experiments for a given website. Please ask your Piwik administrator to set up an A/B test for a Goal for your website.',
	'ABTests_GoalsPluginDeactivated' => 'In order to add experiments, you need to activate the Goals plugin.',
	'ABTests_CreateExperiment_js' => 'Create experiment', 
	'ABTests_UpdateExperiment_js' => 'Update experiment', 
	'ABTests_DeleteExperimentConfirm_js' => 'Are you sure you want to delete the experiment %s?', 
	'ABTests_Experiment' => 'Experiment',
	'ABTests_ExperimentGoal' => 'Goal',
	'ABTests_OriginalUrl' => 'Original page URL', 
	'ABTests_OriginalUrlExample' => 'e.g. http://www.example.com/index.html',
	'ABTests_VariationUrl' => 'Page variation URL', 
	'ABTests_VariationUrlExample' => 'e.g. http://www.example.com/index1.html',
	'ABTests_ExperimentName' => 'Experiment Name',
	'ABTests_OriginalName' => 'Original page name', 
	'ABTests_Original' => 'Original', 
	'ABTests_VariationName' => 'Variation page name',
	'ABTests_Variation' => 'Variation', 
	'ABTests_ExceptionNoPageId' => 'Missing page ID in experiment "%s"', 
	'ABTests_ExceptionOriginalUrlBlank' => 'Please give a URL for the original page.',
	'ABTests_ExceptionOriginalNameBlank' => 'Please give a name for the original page.',
	'ABTests_ExceptionVariationUrlBlank' => 'Please give a URL for the variation page.',
	'ABTests_ExceptionVariationNameBlank' => 'Please give a name for the variation page.',
	'ABTests_ExceptionInvalidUrl' => 'The URL for a page must start with %s. For example, \'%s\'.',
  'ABTests_ExceptionNoVariationPage' => 'Please give a name and URL for the variation page.',
  'ABTests_Overview' => 'Overview', 
  'ABTests_AddNewExperimentLink' => 'Create experiment',
  'ABTests_EditExistingExperiment' => 'Edit existing experiment',
  'ABTests_ExperimentManagement' => 'Experiment Management'
);
