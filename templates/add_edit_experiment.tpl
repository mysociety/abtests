<div id="AddEditExperiments">
{if isset($onlyShowAddNewExperiment)}
    <h2>{'ABTests_CreateExperiment'|translate}</h2>
{else}
<h2>{'ABTests_ExperimentManagement'|translate}</h2>
		<ul class='listCircle'>
			<li><a onclick='' name='linkAddNewExperiment'><u>{'ABTests_AddNewExperimentLink'|translate}</u></a></li>
			{if count($experiments) > 0}
			<li><a onclick='' name='linkEditExperiments'><u>{'ABTests_EditExistingExperiment'|translate}</u></a></li>
			{/if}
		</ul>
		<br>
{/if}
{ajaxErrorDiv id=abtestAjaxError}
{ajaxLoadingDiv id=abtestAjaxLoading}

{if !isset($onlyShowAddNewExperiment)}
	{include file="ABTests/templates/list_experiment_edit.tpl"}
{/if}
	{include file="ABTests/templates/form_add_experiment.tpl"}

	<a id='bottom'></a>
</div>

{loadJavascriptTranslations plugins='ABTests'}
<script type="text/javascript" src="plugins/ABTests/templates/ABTestForm.js"></script>
<script type="text/javascript">
bindExperimentForm();
{if !isset($onlyShowAddNewExperiment)}
piwik.experiments = {$experimentsJSON};
bindListExperimentEdit();
{else}
initAndShowAddExperimentForm();
{/if}
</script>