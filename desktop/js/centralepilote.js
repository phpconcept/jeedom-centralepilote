
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


$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
        tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}


/*
 * Display  list of the equipements in the plugin dashboard list
 */
var refresh_timeout;

function refreshDeviceList() {
  //console.log('refresh device list');
  $('#device_list').load('index.php?v=d&plugin=centralepilote&modal=modal.device_list');
}

/*
 * Display programmation modal
 */
function modal_programmation_display() {
  $('#md_modal').dialog({title: "{{Gestion des modèles de programmation}}"});
  $('#md_modal').load('index.php?v=d&plugin=centralepilote&modal=modal.programmation&prog_id=0').dialog('open');
}


// TBC : should not be used for now. May be for future use, if adding multiple centrale becomes an option
$('.eqLogicAction[data-action=cp_add_centrale]').off('click').on('click', function () {
  bootbox.prompt("{{Nom de la centrale ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"centrale"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

$('.eqLogicAction[data-action=cp_add_radiateur]').off('click').on('click', function () {
  bootbox.prompt("{{Nom du radiateur ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"radiateur"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});

$('.eqLogicAction[data-action=cp_add_zone]').off('click').on('click', function () {
  bootbox.prompt("{{Nom de la zone ?}}", function (result) {
    if (result !== null) {
      jeedom.eqLogic.save({
        type: eqType,
        eqLogics: [{name: result,configuration: '{"type":"zone"}'}],
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (_data) {
          var vars = getUrlVars();
          var url = 'index.php?';
          for (var i in vars) {
            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
              url += i + '=' + vars[i].replace('#', '') + '&';
            }
          }
          modifyWithoutSave = false;
          url += 'id=' + _data.id + '&saveSuccessFull=1';
          loadPage(url);
        }
      });
    }
  });
});




/*
 * Fonction qui permet d'intercepter l'ouverture des propriétés d'un équipement
 * On va alors lancer l'appel d'une fonction d'initialisation qui va attendre
 * l'affichage de l'objet et en fonction du type de l'objet va pouvoir initialiser 
 * le display.
 * Upadte :
 *   using change() fonction is better. Each time the 'type' is changed the display is updated.
 *   Notice that 'type' is hidden so only the jeedom core is chnaging it when loading an object
 */

$("#cp_type").change( function() {
  //console.log('change type :'+$('#cp_type').value());
  if ($('#cp_type').value() != '') {
    //cp_equipement_display_init();
    // Need some delay for all the attributes of the objects to be loaded
    setTimeout(cp_equipement_display_init, 100); // 100msec
  }
});

var g_cp_count_selector = 0;
$(".li_eqLogic,.eqLogicDisplayCard").off('click').on('click', function () {
  //placeholder pour faire une action lors de l'ouverture, mais pb de délai pour mise à jour des valeurs de l'équipement
  
  //console.log('click display card');
  

  
  /*
  console.log('val : '+$('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').value());
  
  // ----- Reset de la propriété de référence qui permet de s'assurer que le 
  // load de l'équipement est terminé par le core jeedom
  $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').val('');
  
  // ----- Lancement de l'application d'init décalée
  setTimeout(cp_equipement_display_init, 100); // 100msec
  */
});

function cp_equipement_display_init() {
  var v_type = $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').value();
  
  // ----- Si la valeur de référence n'est toujours pas chargée, attendre encore un peu ...
  if (v_type == '') {
    //console.log('type is empty increment count');

    g_cp_count_selector++;
    if (g_cp_count_selector > 100) {
      // TBC : error impossible d'afficher ...
      return;
    }
    setTimeout(cp_equipement_display_init, 100);
    // TBC : faut-il mettre un stop à une boucle potentiellement infinie ....
    return;
  }
  
  //console.log('type is '+v_type);
    
  // ----- Initialisation du display
  if (v_type == 'centrale') {
    cp_centrale_display_init();
  }
  else if (v_type == 'radiateur') {
    cp_radiateur_display_init();
  }
  else if (v_type == 'zone') {
    cp_zone_display_init();
  }
  else {
    // TBC : valeur non supportée
    //console.log('Unexpected type is "'+v_type+'"');
  }

  // ----- Cacher l'icone d'attente
  $('.cp_panel_waiting').hide();

  //console.log('id:'+$('#cp_id').val()+'(count:'+g_cp_count_selector+')');
}
 

/*
 * Fonction d'initialisation du display d'un centrale
 */
function cp_centrale_display_init() {
  
  // ----- Affichage du panel pour les radiateurs
  $('.cp_panel_radiateur').hide();
  $('.cp_panel_zone').hide();
  $('.cp_panel_radiateur_zone').hide();
  $('.cp_panel_centrale').show();
  
  /*
  // ----- Remove all other Attr
  $('.cp_attr_radiateur').each(function () {
    if ($(this).hasClass('eqLogicAttr')) {
      $(this).removeClass('eqLogicAttr').addClass('eqLogicAttrOff');
    }
  });
  $('.cp_attr_zone').each(function () {
    if ($(this).hasClass('eqLogicAttr')) {
      $(this).removeClass('eqLogicAttr').addClass('eqLogicAttrOff');
    }
  });
  */
  

}

/*
 * Fonction d'initialisation du display d'un radiateur
 */
function cp_radiateur_display_init() {

  // ----- Affichage du panel pour les radiateurs
  $('.cp_panel_radiateur').show();
  $('.cp_panel_zone').hide();
  $('.cp_panel_radiateur_zone').show();
  $('.cp_panel_centrale').hide();
  
  // ----- Rafraichi l'affichage initial des commandes
  v_mode = 'support_confort';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  v_mode = 'support_eco';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  v_mode = 'support_horsgel';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  v_mode = 'support_off';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  v_mode = 'support_confort_1';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  v_mode = 'support_confort_2';
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_'+v_mode+'').show() : $('#cp_disp_'+v_mode+'').hide()); 
  ($('.eqLogicAttr[data-l1key=configuration][data-l2key='+v_mode+']').value()==1 ? $('#cp_disp_no'+v_mode+'').hide() : $('#cp_disp_no'+v_mode+'').show()); 
  
    
}

/*
 * Fonction d'initialisation du display d'un zone
 */
function cp_zone_display_init() {
  
  // ----- Affichage du panel pour les radiateurs
  $('.cp_panel_radiateur').hide();
  $('.cp_panel_zone').show();
  $('.cp_panel_radiateur_zone').show();
  $('.cp_panel_centrale').hide();
  
  /*
  $('.cp_attr_radiateur[data-l1key=configuration]').each(function () {
    var v_elt = $(this);
    v_elt.data('data-l2key', '');
  });
  
  $('.cp_attr_centrale[data-l1key=configuration]').each(function () {
    var v_elt = $(this);
    v_elt.data('data-l2key', '');
  });
  */
  
  
}


/*
 * Fonction qui permet d'intercepter tout changement d'état des checkbox des modes de chauffage supportés
 */
$('.cp_support_mode').off('click').on('click', function () {
  var v_elt = $(this);
  var v_mode = v_elt.data('l2key');
  
  if (this.checked) {
    //alert('mode '+v_mode+' selectionné');
    $('#cp_disp_'+v_mode+'').show();
    $('#cp_disp_no'+v_mode+'').hide();
  }
  else {
    //alert('mode '+v_mode+' déselectionné');
    // ----- Check at list 2 checked
    var count = 0;
    $('.cp_support_mode').each(function() { if (this.checked) count++; });
    if (count < 2) {
      //alert('Au moins 2 modes selectionnés !!');
      $('#div_alert').showAlert({message: '{{Au minimum 2 modes doivent être selectionnés}}', level: 'warning'});
      this.checked = true;
    }
    else {
      $('#cp_disp_'+v_mode+'').hide();
      $('#cp_disp_no'+v_mode+'').show();
    }
    
  }  
});




/*
 * Fonction qui permet de lancer le modal de selection d'une commande
 * et mettre le résultat dans un textarea
 */
$(".cp_modal_select_command").off('click').on('click', function () {
  // ----- Stockage de l'element appelant
  var v_elt = $(this);

  // ----- Ouverture du modal et récupération du resultat
  jeedom.cmd.getSelectModal({cmd: {type: 'action'}}, function (result) {
    // ----- Récupération du textarea à remplir
    // Dans mon element, le champ "data-input" contient le nom
    var v_textarea = $('.eqLogicAttr[data-l1key=configuration][data-l2key=' + v_elt.data('input') + ']');
    
    // ----- Insertion de la valeur au pointeur courant
    v_textarea.atCaret('insert', result.human);
  });
});

/*
 * Fonction qui permet de lancer le modal de selection d'une commande
 * et mettre le résultat dans un textarea
 */
$(".cp_modal_select_cmd_info").off('click').on('click', function () {
  // ----- Stockage de l'element appelant
  var v_elt = $(this);

  // ----- Ouverture du modal et récupération du resultat
  jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
    // ----- Récupération du textarea à remplir
    // Dans mon element, le champ "data-input" contient le nom
    var v_textarea = $('.eqLogicAttr[data-l1key=configuration][data-l2key=' + v_elt.data('input') + ']');
    
    // ----- Insertion de la valeur au pointeur courant
    v_textarea.atCaret('insert', result.human);
  });
});

/*
 * Fonction qui permet de lancer le modal de selection d'un équipement
 * et mettre le résultat dans un textarea
 */
$(".cp_modal_select_equipement").off('click').on('click', function () {
  // ----- Stockage de l'element appelant
  var v_elt = $(this);

  // ----- Ouverture du modal et récupération du resultat
  jeedom.eqLogic.getSelectModal({}, function (result) {
    // ----- Récupération du textarea à remplir
    // Dans mon element, le champ "data-input" contient le nom
    var v_textarea = $('.eqLogicAttr[data-l1key=configuration][data-l2key=' + v_elt.data('input') + ']');
    
    // ----- Insertion de la valeur au pointeur courant
    v_textarea.value(result.human);
  });
});

/*
 * Fonction qui permet de lancer le modal de selection d'un équipement
 * et mettre le résultat dans un textarea
 */
$(".cp_modal_select_equipement_append").off('click').on('click', function () {
  // ----- Stockage de l'element appelant
  var v_elt = $(this);

  // ----- Ouverture du modal et récupération du resultat
  jeedom.eqLogic.getSelectModal({}, function (result) {
    // ----- Récupération du textarea à remplir
    // Dans mon element, le champ "data-input" contient le nom
    var v_textarea = $('.eqLogicAttr[data-l1key=configuration][data-l2key=' + v_elt.data('input') + ']');
    
    // ----- Insertion de la valeur au pointeur courant
    v_textarea.atCaret('insert', result.human);
  });
});


/*
 * Fonction qui affiche/cache les objets en fonction des options choisies 
 * pour la nature du fil pilote
 */
function cp_nature_change(event) {
  //alert('Hello :'+event.target.value);

  // ----- Change display depending on selection
  /*
  (event.target.value == '1_commutateur_c_o') ? $('#cp_disp_1_commutateur').show() : $('#cp_disp_1_commutateur').hide();
  (event.target.value == '1_commutateur_h_g') ? $('#cp_disp_1_commutateur').show() : $('#cp_disp_1_commutateur').hide();
  (event.target.value == '2_commutateur') ? $('#cp_disp_2_commutateur').show() : $('#cp_disp_2_commutateur').hide();
  (event.target.value == 'virtuel') ? $('#cp_disp_virtuel').show() : $('#cp_disp_virtuel').hide();
  */

  $('#cp_disp_1_commutateur').hide();
  $('#cp_disp_2_commutateur').hide();
  $('#cp_disp_virtuel').hide();
  $('#cp_disp_fp_device').hide();
  
  
  if (event.target.value == '1_commutateur_c_o') {
    
    // ----- Select only ther right ones
    $('.cp_support_mode').each(function() { 
      if (($(this).data('l2key') == 'support_confort') || (($(this).data('l2key') == 'support_off'))) {
        this.checked = true;
      }
      else {
        this.checked = false;
      }
    });
    
    $('#cp_disp_1_commutateur').show();
    $('#img_1_commutateur_c_o').show();
    $('#img_1_commutateur_c_h').hide();
    
  }
  
  else if (event.target.value == '1_commutateur_c_h') {
    // ----- Select only ther right ones
    $('.cp_support_mode').each(function() { 
      if (($(this).data('l2key') == 'support_confort') || (($(this).data('l2key') == 'support_horsgel'))) {
        this.checked = true;
      }
      else {
        this.checked = false;
      }
    });

    $('#cp_disp_1_commutateur').show();
    $('#img_1_commutateur_c_h').show();
    $('#img_1_commutateur_c_o').hide();
  }
  
  else if (event.target.value == '2_commutateur') {
    // ----- Select only ther right ones
    $('.cp_support_mode').each(function() { 
      if (    ($(this).data('l2key') == 'support_confort') || (($(this).data('l2key') == 'support_eco'))
           || (($(this).data('l2key') == 'support_horsgel')) || (($(this).data('l2key') == 'support_off'))) {
        this.checked = true;
      }
      else {
        this.checked = false;
      }
    });

    $('#cp_disp_2_commutateur').show();
  }
  
  else if (event.target.value == 'virtuel') {
    $('#cp_disp_virtuel').show();
  }
  
  else if (event.target.value == 'fp_device') {
    // ----- Afficher le div
    $('#cp_disp_fp_device').show();
    
    /*
    if ($('#cp_fp_device_selected').val() == '') {
      cp_fp_device_list_open();
    }
    */
  }
  
}

function cp_fp_update_list_DEPRECATED(p_id='') {

  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpFpSupportedList"
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
      
      var v_html = '';
      for (var i in v_data) {
        var v_item = "#"+v_data[i]['human_name']+"#";
        if (v_item == p_id) {
          v_html += '<option value="'+v_item+'" selected>'+v_item+'</option>';
        }
        else {
          v_html += '<option value="'+v_item+'">'+v_item+'</option>';
        }
      }
    
      $('#cp_fp_device_list').html(v_html);

    }
  });
  
}

function cp_fp_device_list_open(p_id='') {

  $('#cp_fp_device_selected').hide();
  $('#cp_fp_device_list_open_span').hide();

  $('#cp_fp_device_list').show();
  $('#cp_fp_device_select_span').show();
  
  $.ajax({
    type: "POST",
    url: "plugins/centralepilote/core/ajax/centralepilote.ajax.php",
    data: {
      action: "cpFpSupportedList"
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
      
      var v_html = '';
      for (var i in v_data) {
        var v_item = "#"+v_data[i]['human_name']+"#";
        if (v_item == p_id) {
          v_html += '<option value="'+v_item+'" selected>'+v_item+'</option>';
        }
        else {
          v_html += '<option value="'+v_item+'">'+v_item+'</option>';
        }
      }
    
      $('#cp_fp_device_list').html(v_html);
      
      // ----- Copie de suite celui affiché dans la selection
      $('#cp_fp_device_selected').val($('#cp_fp_device_list').val());

    }
  });
  
}


function cp_fp_device_change(event) {
  //alert('target :'+event.target.value);
  //$('#cp_fp_device_selected').val(event.target.value);
  
  cp_fp_device_select(event.target.value);
  
  
}

function cp_fp_device_select(p_value) {
  //alert('target :'+event.target.value);
  $('#cp_fp_device_selected').val(p_value);
  
  $('#cp_fp_device_selected').show();
  $('#cp_fp_device_list_open_span').show();

  $('#cp_fp_device_list').hide();
  $('#cp_fp_device_select_span').hide();
 
}

function cp_fp_device_cancel_select() {
  
  $('#cp_fp_device_selected').show();
  $('#cp_fp_device_list_open_span').show();

  $('#cp_fp_device_list').hide();
  $('#cp_fp_device_select_span').hide();
 
}



/*
 * saveEqLogic callback called by plugin.template before saving an eqLogic
 * 
 */
function saveEqLogic(_eqLogic) {
  // ----- Temporary table to store the new list of configuration attributes/values
  var v_new_conf = {};

  var v_att_to_save = { "radiateur" : 
                          {"support_confort":1,
                           "support_confort_1":1,
                           "support_confort_2":1,
                           "support_eco":1,
                           "support_horsgel":1,
                           "support_off":1,
                           "temperature":1,
                           "puissance":1,
                           "zone":1,
                           "delestage_sortie_delai":1,
                           "notes":1,
                           "nature_fil_pilote":1,
                           "lien_commutateur":1,
                           "lien_commutateur_a":1,
                           "lien_commutateur_b":1,
                           "command_confort":1,
                           "statut_confort":1,
                           "fallback_confort":1,
                           "command_eco":1,
                           "statut_eco":1,
                           "fallback_eco":1,
                           "command_horsgel":1,
                           "statut_horsgel":1,
                           "fallback_horsgel":1,
                           "command_off":1,
                           "statut_off":1,
                           "fallback_off":1,
                           "command_confort_1":1,
                           "statut_confort_1":1,
                           "fallback_confort_1":1,
                           "command_confort_2":1,
                           "statut_confort_2":1,
                           "fallback_confort_2":1,
                           "radiateur_temperature_confort":1,
                           "radiateur_temperature_confort_1":1,
                           "radiateur_temperature_confort_2":1,
                           "radiateur_temperature_eco":1,
                           "radiateur_temperature_horsgel":1,
                           "fp_device_id":1
                           }
                        ,"zone" : 
                          {"support_confort":1,
                           "support_confort_1":1,
                           "support_confort_2":1,
                           "support_eco":1,
                           "support_horsgel":1,
                           "support_off":1,
                           "temperature":1,
                           "delestage_sortie_delai":1,
                           "notes":1,
                           "zone_temperature_confort":1,
                           "zone_temperature_confort_1":1,
                           "zone_temperature_confort_2":1,
                           "zone_temperature_eco":1,
                           "zone_temperature_horsgel":1}
                        ,"centrale" : 
                          {"temperature_externe":1,
                           "temperature_confort":1,
                           "temperature_confort_1":1,
                           "temperature_confort_2":1,
                           "temperature_eco":1,
                           "temperature_horsgel":1}
                        };


  for (v_item in _eqLogic.configuration ) {
    
    // ----- eqLogic type, should be 'radiateur', 'zone' or 'centrale'
    var v_type = _eqLogic.configuration.type;
    if ((v_type != 'radiateur') && (v_type != 'zone') && (v_type != 'centrale')) {
      $('#div_alert').showAlert({message: 'saveEqLogic() : Type inconnu : '+v_type, level: 'warning'});
      return _eqLogic;
    }
    
    // ----- Look if this is an attribute to save for this object type
    if ((v_att_to_save[v_type][v_item]) || (v_item == 'type')) {
      //console.log('item to save : '+v_item);
      v_new_conf[v_item] = _eqLogic.configuration[v_item];
    }
    
    else {
      //console.log('item not to save : '+v_item);
    }

  }

  // ----- Change for filtered list
  _eqLogic.configuration = v_new_conf;
  
  return _eqLogic;
}


