
function bindExperimentForm()
{
  $('#experiment_submit').click( function() {
		// prepare ajax query to API to add/update experiment
		ajaxRequestAddEditExperiment = getAjaxAddEditExperiment();
    $.ajax( ajaxRequestAddEditExperiment );
		return false;
	});
	
	$('a[name=linkAddNewExperiment]').click( function(){ 
		initAndShowAddExperimentForm();
	} );
}

function bindListExperimentEdit()
{
	$('a[name=linkEditExperiment]').click( function() {
		var experimentId = $(this).attr('id');
		var experiment = piwik.experiments[experimentId];
		var goalId = experiment.idgoal;
		var goalName = experiment.goal_name;
		var name = experiment.name;
		var originalPage = experiment.original_page;
		var variationPages = experiment.variation_pages;
		var urls = experiment.urls;
		initExperimentForm("ABTests.updateExperiment", _pk_translate('ABTests_UpdateExperiment_js'), goalName, goalId, name, originalPage, variationPages, experiment.idexperiment);
		showAddNewExperiment();
		return false;
	});
	
	$('a[name=linkDeleteExperiment]').click( function() {
		var experimentId = $(this).attr('id');
		var experiment = piwik.experiments[experimentId];
    if(confirm(sprintf(_pk_translate('ABTests_DeleteExperimentConfirm_js'), '"'+experiment.name+'"')))
		{
			$.ajax( getAjaxDeleteExperiment( experimentId, experiment.idgoal ) );
		}
		return false;
	});

	$('a[name=linkEditExperiments]').click( function(){ 
		return showEditExperiments(); 
	} );  
}

function showEditExperiments()
{
  $("#EditExperiments").show();
	$("#ExperimentForm").hide();
	piwikHelper.lazyScrollTo("#AddEditExperiments", 400);
	return false;
}

function initExperimentForm(methodABTestsAPI, submitText, goalName, goalId, name, originalPage, variationPages, experimentId)
{
  if (goalId != undefined){
    $('input[name=goalIdUpdate]').val(goalId);
    $('[name=goal_id]').hide();
    $('#goal_name').text(goalName);
    $('#goal_name').show();
  }else{
    $('input[name=goalIdUpdate]').val('');
    $('[name=goal_id]').show();
    $('#goal_name').hide();
  }
  if (name != undefined) {
    $('input[name=name]').val(name);
  }
	if (experimentId != undefined) {
		$('input[name=experimentIdUpdate]').val(experimentId);
	}
	if (originalPage != undefined) {
	  $('#original_page_url').val(originalPage.url);
	  $('#original_page_name').val(originalPage.name);
	}
	if (variationPages != undefined) {
  	$.each(variationPages, function(index, value) { 
  	  $('#variation_page_url_' + parseInt(value.idpage-1)).val(value.url);
  	  $('#variation_page_name_' + parseInt(value.idpage-1)).val(value.name);
  	});
  }
	$('input[name=methodABTestsAPI]').val(methodABTestsAPI);
	$('#experiment_submit').val(submitText);
}

function initAndShowAddExperimentForm()
{
	initExperimentForm('ABTests.addExperiment', _pk_translate('ABTests_CreateExperiment_js'));
	return showAddNewExperiment(); 
}

function showAddNewExperiment()
{
	$("#ExperimentForm").show();
	$("#EditExperiments").hide();
	piwikHelper.lazyScrollTo("#AddEditExperiments", 400);
	return false;
}

function getAjaxAddEditExperiment()
{
  var ajaxRequest = piwikHelper.getStandardAjaxConf('abtestAjaxLoading', 'abtestAjaxError');
	piwikHelper.lazyScrollTo("#AddEditExperiments", 400);
	var parameters = {};
	
  parameters.idSite = piwik.idSite;
    // Updating an existing experiment for a goal
    parameters.idGoal = $('input[name=goalIdUpdate]').val();
    // New experiment 
    if (parameters.idGoal == ''){
    parameters.idGoal = $('[name=goal_id]').val();
    }
    parameters.idExperiment = $('input[name=experimentIdUpdate]').val();
    
    parameters.name = $('input[name=name]').val();
    parameters.originalPage = {};
    parameters.originalPage['url'] = $('input[name=original_page_url]').val();
    parameters.originalPage['name'] = $('input[name=original_page_name]').val();  
    parameters.originalPage['id'] = 1;  
    parameters.variationPages = {};
    // Experiment pages
    $('input[name=variation_page_name]').each(function(index){
       
      var id_parts = this.id.split('_');
      var id = parseInt(id_parts[id_parts.length - 1]);
      var name = $(this).val();
      var url = $("#variation_page_url_" + id).val();
      id = id + 1;
      parameters.variationPages[id] = {};
      parameters.variationPages[id]['url'] = encodeURIComponent(url);
      parameters.variationPages[id]['name'] = encodeURIComponent(name);
      parameters.variationPages[id]['id'] = id;
    });
    
	parameters.method =  $('input[name=methodABTestsAPI]').val();
	parameters.module = 'API';
	parameters.format = 'json';
	parameters.token_auth = piwik.token_auth;
	
	ajaxRequest.data = parameters;
	return ajaxRequest;
}

function getAjaxDeleteExperiment(idExperiment, idGoal)
{
	var ajaxRequest = piwikHelper.getStandardAjaxConf('abtestAjaxLoading', 'abtestAjaxError');
	piwikHelper.lazyScrollTo("#AddEditExperiments", 400);
	
	var parameters = {};
	parameters.idSite = piwik.idSite;
	parameters.idExperiment =  idExperiment;
	parameters.idGoal =  idGoal;
	parameters.method =  'ABTests.deleteExperiment';
	parameters.module = 'API';
	parameters.format = 'json';
	parameters.token_auth = piwik.token_auth;
	ajaxRequest.data = parameters;
	return ajaxRequest;
}