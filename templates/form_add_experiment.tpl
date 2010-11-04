<span id='ExperimentForm' style="display:none;">
<form>
<table class="dataTable tableFormExperiments">
	<tr class="first">
		<th colspan="3">{'ABTests_Experiment'|translate} </th>
	<tr>
	<tbody>
		<tr>
      <td>{'ABTests_ExperimentGoal'|translate} </td>
			<td>
				<select name="goal_id" class="inp">
					{foreach from=$goals item=goal}
					<option value="{$goal.idgoal}">{$goal.name}</option>
					{/foreach}
				</select>
				<span id="goal_name">	
				</span>
			</td>
		</tr>
		<tr>
      <td>{'ABTests_ExperimentName'|translate} </td>
			<td>
				<input type="text" class="inp" name="name" size="40" id="name" value="" />
			</td>
		</tr>
		<tr>
			<td>{'ABTests_OriginalName'|translate}
			  <input type="text" class="inp" name="original_page_name" size="40" id="original_page_name" value="{'ABTests_Original'|translate}" />
			</td>
       <td>{'ABTests_OriginalUrl'|translate}
				<input type="text" class="inp" name="original_page_url" size="40" id="original_page_url" value="" />
				<div class="experimentInlineHelp">{'ABTests_OriginalUrlExample'|translate}</div>
			</td>

		</tr>
		{section name=variation_page start=1 loop=2 step=1}
			<tr>
			  <td>{'ABTests_VariationName'|translate}
  			  <input type="text" class="inp" name="variation_page_name" size="40" id="variation_page_name_{$smarty.section.variation_page.index}" value="{'ABTests_Variation'|translate}" />
  			</td>
	      <td>{'ABTests_VariationUrl'|translate} 
					<input type="text" class="inp" name="variation_page_url" size="40" id="variation_page_url_{$smarty.section.variation_page.index}" value="" />
				  <div class="experimentInlineHelp">{'ABTests_VariationUrlExample'|translate}</div>
				</td>
			</tr>
		{/section}

	</tbody>
</table>
	<input type="hidden" name="methodABTestsAPI" value="" />	
	<input type="hidden" name="experimentIdUpdate" value="" />
	<input type="hidden" name="goalIdUpdate" value="" />
    <input type="submit" value="" name="submit" id="experiment_submit" class="but_submit" />
</form>
</span>