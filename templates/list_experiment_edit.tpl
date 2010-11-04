<span id='EditExperiments' style="display:none;">
	<table class="dataTable tableFormExperiments">
	<thead style="font-weight:bold">
		<th>Id</th>
		<th>Name</th>
        <th>{'General_Edit'|translate}</th>
        <th>{'General_Delete'|translate}</th>
	</thead>
	{foreach from=$experiments item=experiment}
	<tr>
		<td>{$experiment.idexperiment}</td>
		<td>{$experiment.name}</td>
		
		<td><a href='#' name="linkEditExperiment" id="{$experiment.idexperiment}" class="link_but"><img src='themes/default/images/ico_edit.png' border="0" /> {'General_Edit'|translate}</a></td>
		<td><a href='#' name="linkDeleteExperiment" id="{$experiment.idexperiment}" class="link_but"><img src='themes/default/images/ico_delete.png' border="0" /> {'General_Delete'|translate}</a></td>
	</tr>
	{/foreach}
	</table>
</span>