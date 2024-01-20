
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
    if (v_mode == 'eco') v_new_mode = 'confort';
    if (v_mode == 'confort') v_new_mode = 'horsgel';
    if (v_mode == 'horsgel') v_new_mode = 'off';
    if (v_mode == 'off') v_new_mode = 'eco';
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
  
  $('.cp_mode_select').each(function (p_value) {
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
  
  v_id = $("#cp_prog_id").val();
  v_name = $("#cp_prog_name").val();
  v_short_name = $("#cp_prog_short_name").val();
  
  v_prog = {'id':v_id, 'name':v_name, 'short_name':v_short_name, 'agenda':v_agenda};
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
