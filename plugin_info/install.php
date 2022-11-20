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

// Fonction ex�cut�e automatiquement apr�s l'installation ou activation du plugin 
function centralepilote_install() {

  log::add('centralepilote', 'info', "Start installation/activation of plugin 'centralepilote' version ".CP_VERSION);

  // ----- Default parameters
  config::save('prog_display_mode', 'icon_color', 'centralepilote');  // 'icon_color' or icon' or 'color'
  config::save('prog_list', '', 'centralepilote');  
  
  // ----- Create a default centrale objetc (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Cr�er le programme par d�faut, l'ajouter dans la liste
  //centralepilote::cpProgCreateDefault();

  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished installation/activation of plugin 'centralepilote'");
}

// Fonction ex�cut�e automatiquement apr�s la mise � jour du plugin
function centralepilote_update() {
    
  $v_version = config::byKey('version', 'centralepilote', '');
  log::add('centralepilote', 'info', "Update plugin 'centralepilote' from version ".$v_version." to ".CP_VERSION);
  
  // ----- Fresh install
  if ($v_version == '') {
    // ----- Create a default centrale object (if not exists)
    centralepilote::cpCentraleCreateDefault();
    
    // ----- Check that at least the default programm is ok
    //centralepilote::cpProgCreateDefault();
  }
  else if ($v_version == '0.1') {
  }
  else {
  }
  
  // ----- Global actions at each install (like cleanups)
  $eqLogics = eqLogic::byType('centralepilote');
  foreach ($eqLogics as $v_eq) {
  
    // ----- Actions for 'radiateur' and 'zone'
    if ($this->cpIsType(array('radiateur','zone'))) {
    
      // ----- Look to rename cmd 'mode' cmd by cmd 'etat' (migration from 0.1 to 0.2+)
      $v_cmd = $this->getCmd(null, 'mode');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Change cmd 'mode' to 'etat'");
        // ----- Change logical ID
        $v_cmd->setLogicalId('etat');
        // ----- Change name
        $v_cmd->setName('Etat');
      }
    
      // ----- Look to remove cmd 'manuel'
      $v_cmd = $this->getCmd(null, 'manuel');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Remove cmd 'manuel'");
        $v_cmd->remove();
      }
      // ----- Look to remove cmd 'prog_select'
      $v_cmd = $this->getCmd(null, 'prog_select');
      if (is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Remove cmd 'prog_select'");
        $v_cmd->remove();
      }
      
      // ----- Look to add cmd
      $v_cmd = $this->getCmd(null, 'programme_id');
      if (!is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Add missing cmd 'programme_id'");
        $this->cpCmdCreate('programme_id', ['name'=>'Programme Id', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++]);
      }
      $v_cmd = $this->getCmd(null, 'programme_select');
      if (!is_object($v_cmd)) {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Add missing cmd 'programme_select'");
        $this->cpCmdCreate('programme_select', ['name'=>'Programme Select', 'type'=>'action', 'subtype'=>'select', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }    
      
      // ----- Config to add
      if ($this->getConfiguration('programme_id', '') == '') {
        centralepilotelog::log('debug', "Device '".$this->getName()."' : Add missing configuration 'programme_id'");
        $this->setConfiguration('programme_id', '0');
      }
      
    }
    
    // ----- Actions for 'centrale'
    else if ($this->cpIsType(array('centrale'))) {
    }  
  }
    
  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');

  log::add('centralepilote', 'info', "Finished update of plugin 'centralepilote' to ".CP_VERSION);  
}


// Fonction ex�cut�e automatiquement apr�s la suppression ou la d�sactivation du plugin
function centralepilote_remove() {

  log::add('centralepilote', 'info', "centralepilote plugin removed");
}

?>
