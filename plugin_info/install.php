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

  // ----- Default parameters
  config::save('prog_display_mode', 'icon_color', 'centralepilote');  // 'icon_color' or icon' or 'color'
  config::save('prog_list', '', 'centralepilote');  
  
  // ----- Create a default centrale objetc (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Créer le programme par défaut, l'ajouter dans la liste
  //centralepilote::cpProgCreateDefault();

  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished installation/activation of plugin 'centralepilote'");
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function centralepilote_update() {
    
  $v_version = config::byKey('version', 'centralepilote', '');
  log::add('centralepilote', 'info', "Update plugin 'centralepilote' from version ".$v_version." to ".CP_VERSION);
  
  // ----- Look for specific upgrade from versions
  if (CP_VERSION == '0.2') {
    centralepilote_update_v_0_2($v_version);
  }
  
  /*
  // ----- Update from verion 0.1
  if ($v_version == '0.1') {
    // ----- Create a default centrale object (if not exists)
    centralepilote::cpCentraleCreateDefault();
    
    // ----- Check that at least the default programm is ok
    centralepilote::cpProgCreateDefault();
  }
  // ----- From version 0.2
  else if ($v_version == '0.2') {
    // TBC : For future use
  }
  // ----- ALl other versions
  else {
  }
  
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
  
  // ----- Update potential list of programm
  centralepilotelog::cpCmdAllProgrammeSelectUpdate();
  
  */
    
  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished update of plugin 'centralepilote' to ".CP_VERSION);  
}


function centralepilote_update_v_0_2($v_from_version='') {
    
  // ----- Update from verion 0.1
  if ($v_from_version != '0.1') {
    // TBC : Is downgrade is allowed ???
    centralepilotelog::log('debug', "Unexpected upgrade from other version than 0.1 ... trying ...");
  }

  // ----- Create a default centrale object (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Check that at least the default programm is ok
  centralepilote::cpProgCreateDefault();

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
  
  // ----- Update potential list of programm
  centralepilotelog::cpCmdAllProgrammeSelectUpdate();
    
}


// Fonction exécutée automatiquement après la suppression ou la désactivation du plugin
function centralepilote_remove() {

  log::add('centralepilote', 'info', "Plugin 'centralepilote' removed");
}

?>
