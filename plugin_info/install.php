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

// Fonction ex�cut�e automatiquement apr�s l'installation du plugin
function centralepilote_install() {


  // ----- Default parameters
  config::save('prog_display_mode', 'icon_color', 'centralepilote');  // 'icon_color' or icon' or 'color'
  config::save('prog_list', '', 'centralepilote');  
  
  // ----- Create a default centrale objetc (if not exists)
  centralepilote::cpCentraleCreateDefault();
  
  // ----- Cr�er le programme par d�faut, l'ajouter dans la liste
  //centralepilote::cpProgCreateDefault();

  log::add('centralepilote', 'info', "centralepilote plugin installed");

  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');
}

// Fonction ex�cut�e automatiquement apr�s la mise � jour du plugin
function centralepilote_update() {
    
  $v_version = config::byKey('version', 'centralepilote', '');
  
  // ----- Fresh install
  if ($v_version == '') {
    // ----- Create a default centrale objetc (if not exists)
    centralepilote::cpCentraleCreateDefault();
    
    // ----- Check that at least the default programm is ok
    //centralepilote::cpProgCreateDefault();
  }
  
  // ----- Save current version
  config::save('version', CP_VERSION, 'centralepilote');
}


// Fonction ex�cut�e automatiquement apr�s la suppression du plugin
function centralepilote_remove() {

  log::add('centralepilote', 'info', "centralepilote plugin removed");
}

?>
