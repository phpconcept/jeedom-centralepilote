
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * 
 */
$(".cp_mode_select").off('click').on('click', function () {

  var v_new_mode = g_cache_selected_mode;
  
  var v_elt = $(this);
  var v_jour = v_elt.data('jour');
  var v_heure = v_elt.data('heure');
  var v_mode = v_elt.data('mode');
  
  var v_new_cache = v_jour+'_'+v_heure+'_'+v_mode;
  
  // ----- Look for click on same
  // Select the next mode from the current
  if ((g_cache_last_click == v_new_cache) || ((g_cache_last_click == '') && (g_cache_selected_mode == ''))) {
    // TBC : automatiser
    /*
    if (v_mode == 'eco') v_new_mode = 'confort';
    if (v_mode == 'confort') v_new_mode = 'horsgel';
    if (v_mode == 'horsgel') v_new_mode = 'off';
    if (v_mode == 'off') v_new_mode = 'eco';
    */
    
    const modes = ['confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off'];
    const currentIndex = modes.indexOf(v_mode);
    v_new_mode = modes[(currentIndex + 1) % modes.length];    
  }
  else {
    v_new_mode = g_cache_selected_mode;
  }
  
  $('#cp_debug_value').html('Courant : '+v_jour+' '+v_heure+': '+v_mode+'('+g_cache_last_click+','+v_new_cache+') --> '+v_new_mode+': '+g_mode_attr[v_new_mode]['icon']);
    
  $(this).data('mode', v_new_mode);
  $(this).removeClass( g_mode_attr[v_mode]['icon'] ).addClass( g_mode_attr[v_new_mode]['icon'] );
  $(this).css('color', g_mode_attr[v_new_mode]['color']);

  // ----- Change cached mode (update dependencies)
  cp_mode_change_selected(v_new_mode);
  
  // ----- Update cache
  g_cache_last_click = v_jour+'_'+v_heure+'_'+v_new_mode;
  //g_cache_selected_mode = v_new_mode;  
});

function cp_mode_set_slot(v_jour, v_heure, v_new_mode) {

  var v_elt = $("#cp_"+v_jour+"_"+v_heure);
  var v_mode = v_elt.data('mode');
  
  v_elt.data('mode', v_new_mode);
  v_elt.removeClass( g_mode_attr[v_mode]['icon'] ).addClass( g_mode_attr[v_new_mode]['icon'] );
  v_elt.css('color', g_mode_attr[v_new_mode]['color']);

}

$(".cp_mode_select_button").off('click').on('click', function () {
  cp_mode_change_selected($(this).data('mode'));
  g_cache_last_click = '';
});


function cp_mode_change_selected(p_mode) {
  // TBC : check valid mode
  
  // ----- Change activ button
  $(".cp_mode_select_button").removeClass('btn-success');
  $(".cp_mode_select_button[data-mode="+p_mode+"]").addClass('btn-success');
  
  // ----- Change cache value
  g_cache_selected_mode = p_mode;
}

$(".cp_mode_save").off('click').on('click', function () {

  v_result = cp_prog_update_agenda();
  
  //alert('Save');
  $('#cp_debug_value').html('Value : '+v_result);
  
  v_id = $("#cp_prog_id").val();
  
  cp_prog_save(v_id, v_result);

});

$(".cp_mode_load").off('click').on('click', function () {

  v_id = $("#cp_prog_id").val();
  if (v_id == '') v_id = 0;
  cp_prog_load(v_id);

});


$(".cp_mode_clean").off('click').on('click', function () {

  cp_prog_clean();

});

$(".cp_mode_reset").off('click').on('click', function () {

  cp_mode_reset();

});

$(".cp_mode_duplicate").off('click').on('click', function () {

  $('#cp_prog_id').val('');

  v_name = $("#cp_prog_name").val();
  $('#cp_prog_name').val(v_name+'-copy');
  
  v_result = cp_prog_update_agenda();
  
  //alert('Save');
  $('#cp_debug_value').html('Value : '+v_result);
  
  v_id = $("#cp_prog_id").val();
  
  cp_prog_save(v_id, v_result);
  
});

function cp_prog_clean() {

  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpProgClean"
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      
      cp_prog_list_load();

    }
  });

}

function cp_prog_save(p_id, p_prog_json) {
  
  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpProgSave",
      id: p_id,
      prog: p_prog_json
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      v_val = data.result;
      v_data = JSON.parse(v_val);
      $('#cp_debug_value').html('Result : '+v_val+'');
      
      // ----- redispaly the prog 
      cp_prog_list_load(v_data.id);
      
      // ----- Redisplay the list (if name was changed)
      cp_prog_load(v_data.id);
    }
  });

}


$(".cp_prog_delete").off('click').on('click', function () {
  v_id = $("#cp_prog_id").val();
  v_name = $("#cp_prog_name").val();
  bootbox.confirm("{{Supprimer}} '"+v_name+"' ?", function(result){
    /* your callback code */ 
    if (result) {
      cp_prog_delete(v_id);
    }
  });

});


function cp_prog_delete(p_id) {

  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpProgDelete",
      id: p_id
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: '{{Impossible de supprimer la programmation}}', level: 'danger'});
        return;
      }
      
      $('#cp_debug_value').html('Loaded : '+data.result+'');
      
      cp_prog_list_load();
      cp_prog_load(0);
    }
  });
  
}

$("#cp_prog_delete_all").off('click').on('click', function () {
  bootbox.confirm("{{Supprimer tous les programmes ?}}", function(result){
    if (result) {
      /* your callback code */ 
      cp_prog_clean();
    }
  });

});

function cp_prog_load(p_id) {
  
  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpProgLoad",
      id: p_id
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      
      $('#cp_debug_value').html('Loaded : '+data.result+'');
      
      cp_prog_display(data.result);
    }
  });

}

function cp_prog_list_load(p_selected_id='') {
  
  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpProgList",
      data: "tbd"
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      
      $('#cp_debug_value').html('Loaded : '+data.result+'');
      
      cp_prog_update_list(data.result, p_selected_id);
    }
  });

}

function cp_prog_update_list(p_json_list, p_selected_id='') {

  try {
    var v_list = JSON.parse(p_json_list);
  }
  catch (err) { 
    $('#div_alert').showAlert({message: 'Error while parsing JSON result : '+p_json_list, level: 'danger'});
    return;
  }
  var v_txt = '';
  for (v_prog in v_list) {
    v_txt += '<option value="'+v_list[v_prog]["id"]+'">'+v_list[v_prog]["id"]+' - '+v_list[v_prog]["name"]+'</option>';
  }
  $("#cp_prog_select").html(v_txt);
  
  if (p_selected_id != '') {
    $('#cp_prog_select option[value="'+p_selected_id+'"]').prop('selected', true);
  }
  
}

function cp_prog_display(p_prog) {
  if (p_prog == '') {
    p_prog = g_cache_agenda;
  }
  //$('#cp_debug_value').html('Redisplay : '+p_prog+'');

  try {
    var v_obj = JSON.parse(p_prog);
  }
  catch (err) { 
    $('#div_alert').showAlert({message: 'Error while parsing JSON result : '+p_prog, level: 'danger'});
    return;
  }
    
  $('#cp_prog_id').val(v_obj.id);
  $('#cp_prog_name').val(v_obj.name);
  if (v_obj.short_name) {
    $('#cp_prog_short_name').val(v_obj.short_name);
  }
  else {
    $('#cp_prog_short_name').val('');
  }
  
  // ----- Choisir le bon mode horaire dans la liste deroulante
  if ((v_obj.mode_horaire) && (v_obj.mode_horaire != '')) {
    v_val = v_obj.mode_horaire;
  }
  else {
    v_val = 'horaire';
  }

  $('#cp_prog_mode_horaire_select option[value="'+v_val+'"]').prop('selected', true);
  $('#cp_prog_mode_horaire').val(v_val);
  
  // ----- Change le display des tableaux
  cp_prog_mode_horaire_hide(v_val);

  // ----- Afficher l'agenda des plages horaires
  v_agenda = v_obj.agenda;
  
  //v_val = '';
  for (v_jour in v_agenda) {
    for (v_heure in v_agenda[v_jour]) {
      v_mode = v_agenda[v_jour][v_heure];
      
      cp_mode_set_slot(v_jour, v_heure, v_mode);
      
      //v_val += v_jour+':'+v_heure+':'+v_agenda.agenda[v_jour][v_heure];
    }
  }
  //$('#cp_debug_value').html('Parse/disp : '+v_val+'');
  
  // ----- Update de la liste ?
  
  // ----- selected dans le menu déroulant la bonne ligne
  $('#cp_prog_select option[value="'+v_obj.id+'"]').prop('selected', true);
}

function cp_prog_reset() {
    
  $('#cp_prog_id').val('');
  $('#cp_prog_name').val('Nouvelle Programmation');
  $('#cp_prog_short_name').val('');
    
  cp_mode_reset();
  /*
  v_mode = 'eco';
  
  for (i=0; i<24; i++) {
    v_heure = i;
    cp_mode_set_slot('lundi', v_heure, v_mode);
    cp_mode_set_slot('mardi', v_heure, v_mode);
    cp_mode_set_slot('mercredi', v_heure, v_mode);
    cp_mode_set_slot('jeudi', v_heure, v_mode);
    cp_mode_set_slot('vendredi', v_heure, v_mode);
    cp_mode_set_slot('samedi', v_heure, v_mode);
    cp_mode_set_slot('dimanche', v_heure, v_mode);
  }
  */
  

}


$("#cp_prog_add").off('click').on('click', function () {

  cp_prog_reset();

});



function cp_prog_update_agenda() {

  var v_obj = {};
  var v_agenda = {};
  var v_val = {};
  var v_current_day = '';
  var v_result = '';

  v_id = $("#cp_prog_id").val();
  v_name = $("#cp_prog_name").val();
  v_short_name = $("#cp_prog_short_name").val();
  //v_mode_horaire = $('#cp_prog_mode_horaire_select').val();
  v_mode_horaire = $('#cp_prog_mode_horaire').val();
  
  
  $('.cp_mode_select[data-mode_horaire='+v_mode_horaire+']').each(function (p_value) {
    var v_elt = $(this);
    var v_jour = v_elt.data('jour');
    var v_heure = v_elt.data('heure');
    var v_mode = v_elt.data('mode');        
        
    if (v_jour != v_current_day) {
      if (v_current_day != '') {
        v_agenda[v_current_day] = v_obj;
      }
      v_obj = {};      
      v_current_day = v_jour;
    }

    v_obj[v_heure] = v_mode;    
    
  });
  // ----- Store last day
  v_agenda[v_current_day] = v_obj;
  
  v_prog = {'id':v_id, 'name':v_name, 'short_name':v_short_name, 'mode_horaire': v_mode_horaire, 'agenda':v_agenda};
  v_result = JSON.stringify(v_prog);
  
  //v_result = JSON.stringify(v_agenda);
  
  g_cache_agenda = v_result;
  
  return(v_result);  
}

/*
* Fonction appelée lors de la selection dans la liste des programmations
*/
$('#cp_prog_select').on('change', function (e) {
  cp_prog_load($('#cp_prog_select').val());
});


/*
* Fonction appelée lors de la selection dans la liste des modes horaires
*/
$('#cp_prog_mode_horaire_select').on('change', function (e) {
  cp_prog_mode_swap_horaire($('#cp_prog_mode_horaire_select').val());
});

/*
* Modification du mode horaire
*/
function cp_prog_mode_swap_horaire(p_mode_horaire) {

  v_current_mode_horaire = $('#cp_prog_mode_horaire').val();
  
  // Look for no swap to do
  if (v_current_mode_horaire == p_mode_horaire) {
    return;
  }
  
  // ----- Je parse toutes les valeurs et je les passe dans l'autre tableau
  // en adaptant les demi-heures
  $('.cp_mode_select[data-mode_horaire='+v_current_mode_horaire+']').each(function (p_value) {
    var v_elt = $(this);
    var v_jour = v_elt.data('jour');
    var v_heure = v_elt.data('heure');
    var v_mode = v_elt.data('mode');        
    
    if (v_current_mode_horaire == 'horaire') {
      cp_mode_set_slot(v_jour, v_heure+'_00', v_mode);
      cp_mode_set_slot(v_jour, v_heure+'_30', v_mode);
    }
    else {
      if (v_position = v_heure.search("_00") != -1) {
        v_new_heure = v_heure.replace("_00", ""); 
        cp_mode_set_slot(v_jour, v_new_heure, v_mode);
      }
    }
        
  });  
  
  // ----- Update hidden value
  $('#cp_prog_mode_horaire').val(p_mode_horaire);
  
  cp_prog_mode_horaire_hide(p_mode_horaire)
}

function cp_prog_mode_horaire_hide(p_mode_horaire) {

  if (p_mode_horaire == 'horaire') {
    $('#cp_prog_table_horaire').show();
    $('#cp_prog_table_demiheure').hide();
  }
  else {
    $('#cp_prog_table_horaire').hide();
    $('#cp_prog_table_demiheure').show();
  }
  
}

/*
* 
*/
$(".cp_prog_copy_line").off('click').on('click', function () {
  var v_elt = $(this);
  var v_jour = v_elt.data('jour');
  var v_jour_cible = v_elt.data('jour_cible');

  //alert('copy jour : '+v_jour_cible+' dans jour '+v_jour);
  
  for (i=0; i<24; i++) {
    // ----- Modification des créneaux en mode horaire
    v_heure = i;
    var v_elt = $("#cp_"+v_jour_cible+"_"+v_heure);
    cp_mode_set_slot(v_jour, v_heure, v_elt.data('mode'));
    
    // ----- Modification des créneaux en mode demiheure
    v_heure = i+'_00';
    var v_elt = $("#cp_"+v_jour_cible+"_"+v_heure);
    cp_mode_set_slot(v_jour, v_heure, v_elt.data('mode'));
    v_heure = i+'_30';
    var v_elt = $("#cp_"+v_jour_cible+"_"+v_heure);
    cp_mode_set_slot(v_jour, v_heure, v_elt.data('mode'));
  }
});


/*
* 
*/
$(".cp_prog_reset_line").off('click').on('click', function () {
  var v_elt = $(this);
  var v_jour = v_elt.data('jour');

  //alert('reset jour : '+v_jour);
  
  for (i=0; i<24; i++) {
    // ----- Modification des créneaux en mode horaire
    v_heure = i;
    cp_mode_set_slot(v_jour, v_heure, 'eco');
    
    // ----- Modification des créneaux en mode demiheure
    v_heure = i+'_00';
    cp_mode_set_slot(v_jour, v_heure, 'eco');
    v_heure = i+'_30';
    cp_mode_set_slot(v_jour, v_heure, 'eco');
  }
  
});

function cp_mode_reset() {
    
  const v_jour_list = ["lundi","mardi","mercredi", "jeudi","vendredi","samedi","dimanche"];
  var v_mode = 'eco';
  
  for (i=0; i<24; i++) {
    for (j=0; j<7; j++) {
      v_heure = i;
      cp_mode_set_slot(v_jour_list[j], v_heure, v_mode);
      v_heure = i+'_00';
      cp_mode_set_slot(v_jour_list[j], v_heure, v_mode);
      v_heure = i+'_30';
      cp_mode_set_slot(v_jour_list[j], v_heure, v_mode);
    }
  }
  

}


function cp_mode_list_load() {
  
  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpModeGetList",
      data: "tbd"
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      
      $('#cp_debug_value').html('Loaded : '+data.result+'');
      
      cp_mode_update_list(data.result);
    }
  });

}


function cp_mode_update_list(p_json_list) {

  try {
    var v_list = JSON.parse(p_json_list);
  }
  catch (err) { 
    $('#div_alert').showAlert({message: 'Error while parsing JSON result : '+p_json_list, level: 'danger'});
    return;
  }
  
  var v_txt = '';
  for (v_item in v_list) {
    v_txt += '<button type="button" ';
    v_txt += 'class="btn btn-xs cp_mode_select_button" ';
    v_txt += 'style="top: -1px !important; right: -6px !important;" ';
    v_txt += 'data-mode="'+v_item+'">';
    v_txt += '<i class="'+v_list[v_item]["icon"]+'"';
    v_txt += ' style="color: '+v_list[v_item]["color"]+';"';
    v_txt += '></i> '+v_list[v_item]["name"]+'</button>';
  }
  $("#cp_prog_bt_horaire").html(v_txt);
  $("#cp_prog_bt_demiheure").html(v_txt);
  
  
  $(".cp_mode_select_button").off('click').on('click', function () {
    cp_mode_change_selected($(this).data('mode'));
    g_cache_last_click = '';
  });

}



