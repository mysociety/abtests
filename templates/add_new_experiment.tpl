{if $userCanEditExperiments}
	{if $goalsPluginDeactived}
		{'ABTests_GoalsPluginDeactivated'|translate}
	{else}
		{include file=ABTests/templates/add_edit_experiment.tpl}
	{/if}
{else}
	{'ABTests_NoExperimentsNeedAccess'|translate}
{/if}
