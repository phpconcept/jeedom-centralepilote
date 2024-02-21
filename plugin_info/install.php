<?php

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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';


// Fonction exécutée automatiquement après l'installation ou activation du plugin 
function centralepilote_install() {

  log::add('centralepilote', 'info', "Start installation/activation of plugin 'centralepilote' version ".CP_VERSION);

  // ----- Create a default centrale object (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished installation/activation of plugin 'centralepilote'");
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function centralepilote_update() {
    
  $v_version = config::byKey('version', 'centralepilote', '');
  log::add('centralepilote', 'info', "Update plugin 'centralepilote' from version ".$v_version." to ".CP_VERSION);
  
  // ----- Create a default centrale object (if not exists)
  centralepilote::cpCentraleCreateDefault();

  // ----- Look for specific upgrade from versions
  if (CP_VERSION == '0.2') {
    centralepilote_update_v_0_2($v_version);
  }
  else if (CP_VERSION == '0.3') {
    if ($v_version != '0.3') centralepilote_update_v_0_3($v_version);
  }
  else if (CP_VERSION == '0.4') {
    if ($v_version != '0.4') centralepilote_update_v_0_4($v_version);
  }
  else if (CP_VERSION == '0.5') {
    // Nothing to do
  }
  else if (CP_VERSION == '0.8') {
    if ($v_version < '0.8') centralepilote_update_v_0_8($v_version);
  }
  else if (CP_VERSION == '1.0') {
    if ($v_version < '0.8') centralepilote_update_v_0_8($v_version);
  }
  else if (CP_VERSION == '1.1') {
    if ($v_version < '0.8') centralepilote_update_v_0_8($v_version);
    if ($v_version != '1.1') centralepilote_update_v_1_1($v_version);
  }

/*  else if (CP_VERSION == '1.2') {
    if ($v_version < '0.8') centralepilote_update_v_0_8($v_version);
    if ($v_version < '1.1') centralepilote_update_v_1_1($v_version);
    if ($v_version != '1.2') centralepilote_update_v_1_2($v_version);
  }
  */
/*
  if ($v_version < '0.2') centralepilote_update_v_0_2($v_version);
  if ($v_version < '0.3') centralepilote_update_v_0_3($v_version);
  if ($v_version < '0.4') centralepilote_update_v_0_4($v_version);
  if ($v_version < '0.8') centralepilote_update_v_0_8($v_version);
  if ($v_version < '1.1') centralepilote_update_v_1.1($v_version);
  */

  if ($v_version < '1.2') centralepilote_update_v_1_2($v_version);
  if ($v_version < '1.3') centralepilote_update_v_1_3($v_version);
  if ($v_version < '1.4') centralepilote_update_v_1_4($v_version);
  if ($v_version < '1.5') centralepilote_update_v_1_5($v_version);
    
  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished update of plugin 'centralepilote' to ".CP_VERSION);  
}

function centralepilote_update_v_1_5($v_from_version='') {

  log::add('centralepilote', 'info', "Update devices to version 1.5 of plugin 'centralepilote'");
  log::add('centralepilote', 'info', "  Nothing to do.");
  
}

function centralepilote_update_v_1_4($v_from_version='') {

  log::add('centralepilote', 'info', "Update devices to version 1.4 of plugin 'centralepilote'");
  log::add('centralepilote', 'info', "  Nothing to do.");
  
}

function centralepilote_update_v_1_3($v_from_version='') {

  log::add('centralepilote', 'info', "Update devices to version 1.3 of plugin 'centralepilote'");
  
  // ----- Look for each equip
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
    $v_flag_save = false;
    
    if (!$v_eq->cpIsType(array('radiateur','zone'))) {
      continue;
    }
    
    $v_type = $v_eq->cpGetType();
    
    // ----- Ajout des configurations de temperature cible par radiateur
    if ($v_eq->getConfiguration($v_type.'_temperature_confort', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_confort_1', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort_1', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_confort_2', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort_2', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_eco', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_eco', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_horsgel', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_horsgel', '');
      $v_flag_save = true;
    }

    if ($v_flag_save) {
      $v_eq->save();
    }
    
  }
  
}

function centralepilote_update_v_1_2($v_from_version='') {

  log::add('centralepilote', 'info', "Update devices to version 1.2 of plugin 'centralepilote'");

  // ----- Look for each equip
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
    $v_flag_save = false;
    
    if (!$v_eq->cpIsType(array('radiateur','zone'))) {
      continue;
    }
    
    // TBC

    if ($v_flag_save) {
      $v_eq->save();
    }
    
  }
  
}


function centralepilote_update_v_1_1($v_from_version='') {
    
  // ----- Look for each equip
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
    $v_flag_save = false;
    
    if (!$v_eq->cpIsType(array('radiateur','zone'))) {
      continue;
    }
    
    // ----- Ajout de la configuration de sortie progressive du delestage
    if ($v_eq->getConfiguration('delestage_sortie_delai', '') == '') {
      $v_eq->setConfiguration('delestage_sortie_delai', 0);
      $v_flag_save = true;
    }
    
    // ----- Look to add cmd
    $v_cmd = $v_eq->getCmd(null, 'window_open');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'window_open'");
      $v_eq->cpCmdCreate('window_open', ['name'=>'Window Open', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'icon'=>'icon jeedom-fenetre-ouverte']);
    }

    $v_cmd = $v_eq->getCmd(null, 'window_close');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'window_close'");
      $v_eq->cpCmdCreate('window_close', ['name'=>'Window Close', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++, 'icon'=>'icon jeedom-fenetre-ferme']);
    }

    $v_cmd = $v_eq->getCmd(null, 'window_swap');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'window_swap'");
      $v_eq->cpCmdCreate('window_swap', ['name'=>'Window Swap', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++, 'icon'=>'icon jeedom-fenetre-ouverte']);
    }

    $v_cmd = $v_eq->getCmd(null, 'window_status');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'window_status'");
      $v_eq->cpCmdCreate('window_status', ['name'=>'Window Status', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++]);
    }

    if ($v_flag_save) {
      $v_eq->save();
    }
    
  }
  
    
}

function centralepilote_update_v_0_8($v_from_version='') {
  if ($v_from_version == '0.3') {
    // ----- First upgrade to 0.4
    centralepilote_update_v_0_4($v_version);
  }
    
  // ----- Get programmation list in old format, and change to array format
  $v_prog_list_str = centralepilote::cpCentraleGetConfig('prog_list');
  if (is_string($v_prog_list_str) && ($v_prog_list_str != '')) {
  
    // ----- Parse string value
    try {
      $v_prog_list = json_decode($v_prog_list_str, true);
      centralepilote::cpProgSaveList($v_prog_list);
    }
    catch (Exception $exc) {
      centralepilotelog::log('debug', "Update : fail to reformat program list");
    }
  }
  
}

function centralepilote_update_v_0_4($v_from_version='') {
  if ($v_from_version == '0.2') {
    // ----- First upgrade to 0.3
    centralepilote_update_v_0_3($v_version);
  }
  
  // ----- Get centrale
  $v_centrale = centralepilote::cpCentraleGet();
  if ($v_centrale === null) {
    centralepilotelog::log('debug', "Missing default Centrale object. Abort update.");
    return;
  }
  
  // ----- Add icons pour Normal et Delestage ds cmd de Central
  $v_cmd = $v_centrale->getCmd(null, 'normal');
  if (is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing icon to cmd 'normal' to Centrale equipement.");
    $v_cmd->setDisplay('icon', '<i class="icon kiko-sun"></i>');
    $v_cmd->setDisplay('showIconAndNamedashboard', "1");          
  }
  $v_cmd = $v_centrale->getCmd(null, 'delestage');
  if (is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing icon to cmd 'delestage' to Centrale equipement.");
    $v_cmd->setDisplay('icon', '<i class="'.centralepilote::cpModeGetIconClass('off').'"></i>');
    $v_cmd->setDisplay('showIconAndNamedashboard', "1");          
  }

  // ----- Look for each equip
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
    $v_flag_save = false;
    
    if (!$v_eq->cpIsType(array('radiateur','zone'))) {
      continue;
    }
    
    // ----- Ajout de la conf bypass_type en fonction de la valeur de bypass_mode
    if ($v_eq->getConfiguration('bypass_type', '') == '') {
      centralepilotelog::log('debug', "Add missing config 'bypass_type' to equipement '".$v_eq->getName()."'.");
      $v_bypass_mode = $v_eq->getConfiguration('bypass_mode', '');
      if (($v_bypass_mode == 'no') || ($v_bypass_mode == '')) { // 'no', 'delestage', 'eco', 'horsgel'
        $v_eq->setConfiguration('bypass_type', 'no');
        $v_eq->setConfiguration('bypass_mode', 'no');
      }
      else {
        $v_eq->setConfiguration('bypass_type', 'delestage');
      }
      $v_flag_save = true;
    }
    
    // ----- Ajout de la configuration trigger_list mais pas la peine de l'initialisée cela se fera tout seul
    if ($v_eq->getConfiguration('trigger_list', '') == '') {
      $v_eq->setConfiguration('trigger_list', array());
      $v_flag_save = true;
    }
    
    if ($v_flag_save) {
      $v_eq->save();
    }
    
    // ----- Look to add cmd
    $v_cmd = $v_eq->getCmd(null, 'confort_1');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'confort_1'");
      $v_eq->cpCmdCreate('confort_1', ['name'=>'Confort -1', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>2, 'icon'=>centralepilote::cpModeGetIconClass('confort_1')]);
    }

    $v_cmd = $v_eq->getCmd(null, 'confort_2');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'confort_2'");
      $v_eq->cpCmdCreate('confort_2', ['name'=>'Confort -2', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>3, 'icon'=>centralepilote::cpModeGetIconClass('confort_2')]);
    }

    $v_cmd = $v_eq->getCmd(null, 'trigger');
    if (!is_object($v_cmd)) {
      centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'trigger'");
      $v_eq->cpCmdCreate('trigger', ['name'=>'Trigger', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>13, 'icon'=>'icon divers-circular114']);
    }
    
  }  

}

function centralepilote_update_v_0_3($v_from_version='') {
  // TBC
  
  // ----- Get centrale
  $v_centrale = centralepilote::cpCentraleGet();
  if ($v_centrale === null) {
    centralepilotelog::log('debug', "Missing default Centrale object. Abort update.");
    return;
  }
  
  // ----- Ajouter ces commandes à la centrale
  $v_cmd = $v_centrale->getCmd(null, 'normal');
  if (!is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing cmd 'normal' to Centrale equipement.");
    $v_centrale->cpCmdCreate('normal', ['name'=>'Normal', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>0]);
  }
  
  $v_cmd = $v_centrale->getCmd(null, 'horsgel');
  if (!is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing cmd 'horsgel' to Centrale equipement.");
    $v_centrale->cpCmdCreate('horsgel', ['name'=>'HorsGel', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>3, 'icon'=>centralepilote::cpModeGetIconClass('horsgel')]);
  }
  
  $v_cmd = $v_centrale->getCmd(null, 'eco');
  if (!is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing cmd 'eco' to Centrale equipement.");
    $v_centrale->cpCmdCreate('eco', ['name'=>'Eco', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>4, 'icon'=>centralepilote::cpModeGetIconClass('eco')]);
  }
  
  $v_cmd = $v_centrale->getCmd(null, 'etat');
  if (!is_object($v_cmd)) {
    centralepilotelog::log('debug', "Add missing cmd 'etat' to Centrale equipement.");
    $v_centrale->cpCmdCreate('etat', ['name'=>'Etat', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>1, 'isVisible'=>1, 'order'=>5]);
  }
  
  // ----- Look for each equip
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
  
    // ----- Actions for 'radiateur' and 'zone'
    if ($v_eq->cpIsType(array('radiateur','zone'))) {

      // ----- Set config values for each device
      if ($v_eq->getConfiguration('bypass_mode', '') == '') {
        centralepilotelog::log('debug', "Add missing config 'bypass_mode' to equipement '".$v_eq->getName()."'.");
        $v_eq->setConfiguration('bypass_mode', 'no');
        $v_eq->save();
      }

    }
  
  }
  
}


function centralepilote_update_v_0_2($v_from_version='') {
    
  // ----- Update from verion 0.1
  if ($v_from_version != '0.1') {
    // TBC : Is downgrade is allowed ???
    centralepilotelog::log('debug', "Unexpected upgrade from other version than 0.1 ... trying ...");
  }

  // ----- Create a default centrale object (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Global actions at each install (like cleanups)
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
  
    // ----- Actions for 'radiateur' and 'zone'
    if ($v_eq->cpIsType(array('radiateur','zone'))) {
    
      // ----- Look to rename cmd 'mode' cmd by cmd 'etat' (migration from 0.1 to 0.2+)
      $v_cmd = $v_eq->getCmd(null, 'mode');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Remove cmd 'mode' and create cmd 'etat'");
        $v_cmd->remove();
        $v_eq->cpCmdCreate('etat', ['name'=>'Etat', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>1, 'isVisible'=>1, 'order'=>7]);
      }
    
      // ----- Look to remove cmd 'manuel'
      $v_cmd = $v_eq->getCmd(null, 'manuel');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Remove cmd 'manuel'");
        $v_cmd->remove();
      }
      // ----- Look to remove cmd 'prog_select'
      $v_cmd = $v_eq->getCmd(null, 'prog_select');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Remove cmd 'prog_select'");
        $v_cmd->remove();
      }
      
      // ----- Look to add cmd
      $v_cmd = $v_eq->getCmd(null, 'programme_id');
      if (!is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'programme_id'");
        $v_eq->cpCmdCreate('programme_id', ['name'=>'Programme Id', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>9]);
      }
      $v_cmd = $v_eq->getCmd(null, 'programme_select');
      if (!is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing cmd 'programme_select'");
        $v_eq->cpCmdCreate('programme_select', ['name'=>'Programme Select', 'type'=>'action', 'subtype'=>'select', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>10]);
      }    
      
      // ----- Config to add
      if ($v_eq->getConfiguration('programme_id', '') == '') {
        centralepilotelog::log('debug', "Device '".$v_eq->getName()."' : Add missing configuration 'programme_id'");
        $v_eq->setConfiguration('programme_id', '0');
      }
      
      // ----- Reset commands display
      $v_eq->cpCmdResetDisplay();      
    }
    
    // ----- Actions for 'centrale'
    else if ($v_eq->cpIsType(array('centrale'))) {
    }  
  }
  
  // ----- Check that at least the default programm is ok
  centralepilote::cpProgCreateDefault();

  // ----- Update potential list of programm
  centralepilote::cpCmdAllProgrammeSelectUpdate();
    
}


// Fonction exécutée automatiquement après la suppression ou la désactivation du plugin
function centralepilote_remove() {

  log::add('centralepilote', 'info', "Plugin 'centralepilote' removed");
}

?>


