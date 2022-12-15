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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../../../plugins/centralepilote/core/php/centralepilote.inc.php';

  // ----- Current version
  define('CP_VERSION', '0.4');
  

class centralepilote extends eqLogic {

    /*     * *************************Attributs****************************** */
    /*
    * Attributs de configuration :
    *  type : 'centrale' ou 'radiateur' ou 'zone'
    *  admin_mode (deprecated) : stock la valeur configurée en mode 'manuel' du mode 'confort', 'eco', etc 
    *  admin_pilotage (deprecated) : stock la valeur configurée en mode bouton commande du pilotage 'manuel' ou 'auto'. Sachant que le status peut aussi avoir la valeur 'zone'
    *  pilotage : mémorise le mode de pilotage souhaité : 'confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off', 'auto'.
    *  programme_id : mémorise le programme_id associé au radiateur ou à la zone.
    *  support_confort,
    *  support_confort_1,
    *  support_confort_2,
    *  support_eco,
    *  support_horsgel,
    *  support_off : 1 ou 0 : indique si le mode fil pilote associé est supporté par ce radiateur ou pas.
    *  fallback_confort,
    *  fallback_eco,
    *  fallback_horsgel,
    *  fallback_off : valeur alternative du mode lorsque celui-ci n'est pas supporté,
    *  nature_fil_pilote : 'virtuel', '1_commutateur_c_o', '1_commutateur_c_h' ou '2_commutateur' : mode d'association des commandes à réaliser
    *  lien_commutateur : human name de l'équipement lié (si nature = 1_commutateur)
    *  lien_commutateur_a,
    *  lien_commutateur_b : human name des deux équipements liés (si nature = 2_commutateur)
    *  command_confort,
    *  command_eco,
    *  command_horsgel,
    *  command_off : commandes 'actions' composites à réaliser pour les différentes commandes fil pilote (si nature = virtuel)
    *  statut_confort,
    *  statut_eco,
    *  statut_horsgel,
    *  statut_off : commandes 'info' composites à lire pour avoir le statut de chaque commande fil pilote (si nature = virtuel)
    *  zone : id de la zone du radiateur, ou '' si pas dans une zone.
    *  temperature : commande permettant d'obtenir la température associée à un radiateur (optionel)
    *  puissance : puissance en watts du radiateur (optionel)
    *  notes : juste des notes pour s'y retrouver ...
    *  bypass_type : Bypass le mode de pilotage admin du radiateur. Prend les valeurs : 'no', delestage' (Mode forcé au global par l'objet 'centrale'), 
    *                 'open_window' 
    *  bypass_mode : Mode du bypass. Prend les valeurs : 
    *                  pour bypass_type 'no' : bypass_mode = 'no'
    *                  pour bypass_type 'delestage' : bypass_mode = 'delestage', 'eco', 'horsgel'
    *                  pour bypass_type 'open_window' : bypass_mode = 'off'
    *  
    *  trigger_list : an array with triggers information. Possible values are :
    *                  trigger_list['0000-00-00-00-00'] = ['type'=>'trigger_mode', 'mode'=>'mode']
    *                  + future use
    */
    var $_pre_save_cache;

    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
	public static function cron() {
    
    /*
      // ----- Recalculate mode for each radiateur
      $v_list = centralepilote::cpRadList(['_isEnable'=>true]);
      foreach ($v_list as $v_radiateur) {
        $v_radiateur->cpRefresh();
      }
      */
      
	}


    /*
     * Fonction exécutée automatiquement toutes les 5,10,15 minutes par Jeedom
      public static function cron5() {}
      public static function cron10() {}
     */
      public static function cron15() {
        centralepilote::cpClockTick();
      }

    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */

    /*
    public static function deamon_info()
    {
    }
     */

    /*
    public static function deamon_start($_debug = false)
    {
    }
     */

    /*
    public static function deamon_stop()
    {
    }
     */

    /*
	public static function dependancy_info() {
	}
     */

    /*
	public static function dependancy_install() {
	}
     */


    /*     * ***********************Methodes specifiques centralepilote*************************** */

    /**---------------------------------------------------------------------------
     * Method : cpCentraleCreateDefault()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpCentraleCreateDefault() {
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->cpGetType() == 'centrale') {
          return(true);
        }
      }
      
      centralepilote::log('info',  "Creation de l'unique équipement 'Centrale'.");      
      
      // ----- Create a new 'centrale'
      $v_centrale = new centralepilote();
      $v_centrale->setEqType_name('centralepilote');
      $v_centrale->setName('Centrale Fil-Pilote');
      $v_centrale->setLogicalId('centrale');
      $v_centrale->setIsEnable(1);
      $v_centrale->setIsVisible(0);
      $v_centrale->setConfiguration('type', 'centrale');
      $v_centrale->save();

      centralepilote::cpProgCreateDefault();
      
      return(true);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCentraleGet()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpCentraleGet() {
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->cpGetType() == 'centrale') {
          return($v_eq);
        }
      }      
      
      // TBC : Create default uniq central eqLogic
      centralepilote::log('error',  "Missing default central object");
      
      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCentraleGetConfig()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpCentraleGetConfig($p_key) {
      //centralepilote::log('debug',  "cpCentralGetConfig()");
      $v_centrale = centralepilote::cpCentraleGet(); 
      if (!is_object($v_centrale)) {
        centralepilote::log('error',  "cpCentralGetConfig() : Missing default centrale eqLogic");
        return('');
      }
      $v_value = $v_centrale->getConfiguration($p_key, '');
      
      return($v_value);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCentraleSetConfig()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpCentraleSetConfig($p_key, $p_value) {
      centralepilote::log('debug',  "cpCentralSetConfig('".$p_key."', '".$p_value."')");
      $v_centrale = centralepilote::cpCentraleGet(); 
      if (!is_object($v_centrale)) {
        centralepilote::log('error',  "cpCentralSetConfig() : Missing default centrale eqLogic");
        return(0);
      }
      $v_centrale->setConfiguration($p_key, $p_value);
      $v_centrale->save();
      centralepilote::log('debug',  "cpCentralSetConfig() ok");
      return(1);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpGetProgDisplayMode()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpGetProgDisplayMode() {
      $v_value = config::byKey('prog_display_mode', 'centralepilote');
      $v_result = ($v_value == 'color' ? 'color' : 'icon');
      return $v_result;
   }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetAtt()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetAtt($p_mode, $p_att) {
      $v_list = centralepilote::cpModeGetList(true);
      if ((!isset($v_list[$p_mode])) || (!isset($v_list[$p_mode][$p_att]))) {
        return('');
      }
      $v_result = $v_list[$p_mode][$p_att];
      return $v_result;
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetIconClass()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetIconClass($p_mode) {
      return(centralepilote::cpModeGetAtt($p_mode, 'icon'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetLargeIconClass()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetLargeIconClass($p_mode) {
      return(centralepilote::cpModeGetAtt($p_mode, 'large_icon'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetColor()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetColor($p_mode) {
      return(centralepilote::cpModeGetAtt($p_mode, 'color'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetName()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetName($p_mode) {
      return(centralepilote::cpModeGetAtt($p_mode, 'name'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeGetList()
     * Description :
     *   cpModeGetList() will return the full array.
     *   cpModeGetList(true) will return an array with only the mode id 'confort', 'eco', ...
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetList($p_detail=false) {
      $v_mode_list = ['confort'   => ['name'=> __('Confort', __FILE__),
                                      'icon' => 'fab fa-hotjar',
                                      'large_icon' => 'icon jeedom-pilote-conf',
                                      'color' => 'red'],
                      'confort_1' => ['name'=> __('Confort -1', __FILE__),
                                      'icon' => 'fab fa-hotjar',
                                      'large_icon' => 'icon jeedom-pilote-conf',
                                      'color' => 'red'],
                      'confort_2' => ['name'=> __('Confort -2', __FILE__),
                                      'icon' => 'fab fa-hotjar',
                                      'large_icon' => 'icon jeedom-pilote-conf',
                                      'color' => 'red'],
                      'eco'       => ['name'=> __('Eco', __FILE__),
                                      'icon' => 'fas fa-leaf',
                                      'large_icon' => 'icon jeedom-pilote-eco',
                                      'color' => 'green'],
                      'horsgel'   => ['name'=> __('Hors-Gel', __FILE__),
                                      'icon' => 'far fa-snowflake',
                                      'large_icon' => 'icon jeedom-pilote-hg',
                                      'color' => 'blue'],
                      'off'       => ['name'=> __('Off', __FILE__),
                                      'icon' => 'fas fa-power-off',
                                      'large_icon' => 'icon jeedom-pilote-off',
                                      'color' => '']
                     ];
    
      if ($p_detail) {
        return($v_mode_list);
      }
        
      $v_short_list = array();
      foreach ($v_mode_list as $v_key => $v_data) {
        $v_short_list[] = $v_key;
      }
    
      return($v_short_list); 
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpModeGetCodeFromName()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeGetCodeFromName($p_mode_name) {
      $v_list = centralepilote::cpModeGetList(true);
      foreach ($v_list as $v_key => $v_data) {
        if (isset($v_data['name']) && ($v_data['name'] == $p_mode_name)) {
          return($v_key);
        }
      }
      
      centralepilote::log('debug', "!! Unexpected mode name '".$p_mode_name."' here (".__FILE__.",".__LINE__.")");
      $v_result = 'eco';
      return $v_result;
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeExist()
     * Description :
     *   Will return true if the mode id $p_mode exists.
     *   Sample : if (centralepilote::cpModeExist('horsgel')) {}
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeExist($p_mode) {
      $v_list = centralepilote::cpModeGetList(true);
      return(isset($v_list[$p_mode]));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeSupported()
     * Description :
     *   Look for supported mode by configuration.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpModeSupported($p_mode) {
      if (!centralepilote::cpModeExist($p_mode)) {
        return(false);
      }
      
      $v_result = false;
      $v_mode_list = ['confort'=>true,
                      'confort_1'=>true,
                      'confort_2'=>true,
                      'eco'=>true,
                      'horsgel'=>true,
                      'off'=>true
                      ];
      if (!isset($v_mode_list[$p_mode])) {
        return(false);
      }
      
      switch ($p_mode) {
        case 'confort' :
          $v_result = true;
        break;
        case 'confort_1' :
          $v_result = true;
        break;
        case 'confort_2' :
          $v_result = true;
        break;
        case 'eco' :
          $v_result = true;
        break;
        case 'horsgel' :
          $v_result = true;
        break;
        case 'off' :
          $v_result = true;
        break;
        default :
          $v_result = false;
      }
      return $v_result;
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageExist()
     * Description :
     *   Will return true if the mode id $p_mode exists.
     *   Sample : if (centralepilote::cpPilotageExist('horsgel')) {}
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpPilotageExist($p_mode) {
      if ($p_mode == 'auto') return(true);
      $v_list = centralepilote::cpModeGetList(true);
      return(isset($v_list[$p_mode]));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageGetName()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpPilotageGetName($p_pilotage_mode) {
      if ($p_pilotage_mode == 'auto') return(__('Auto', __FILE__));
      $v_name = centralepilote::cpModeGetName($p_pilotage_mode);
      return($v_name);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgGetList()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgGetList() {
      $v_prog_list_str = centralepilote::cpCentraleGetConfig('prog_list');
      if (!is_string($v_prog_list_str) || ($v_prog_list_str == '')) {
        centralepilotelog::log('debug', "cpProgGetList() : missing or empty prog_list.");
        return(array());
      }
      
      // ----- Parse string value
      try {
        $v_prog_list = json_decode($v_prog_list_str, true);
      }
      catch (Exception $exc) {
        centralepilotelog::log('debug', "cpProgGetList() : error parsing JSON prog_list.");
        return(array());
      }
      
      return($v_prog_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgSaveList()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgSaveList($p_prog_list) {
    
      // ----- Sanity checks
      if (!is_array($p_prog_list)) {
        centralepilotelog::log('debug', "cpProgSaveList() : prog_list is not an array. Use empty array instead.");
        $p_prog_list = array();
      }
    
      // ----- Encode & save
      $v_prog_list_json = json_encode($p_prog_list, JSON_FORCE_OBJECT);
      centralepilote::cpCentraleSetConfig('prog_list', $v_prog_list_json);
      
      return;
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgCreateDefault()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgCreateDefault() {
      $v_prog = '';

      // ----- Read existing value
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      centralepilotelog::log('debug', "cpProgCreateDefault : ".$v_prog_list);
      
      if (is_array($v_prog_list)) {
        if (isset($v_prog_list[0])) {
          centralepilotelog::log('debug', "Default program already exists, nothing to do");
          return;
        }
      }
      else {
        $v_prog_list = array();
      }
      
      $v_prog0  = '{"id":"0","name":"'.__("Programmation par défaut",__FILE__).'",';
      $v_prog0 .= '"agenda":{';
      $v_prog0 .= ' "lundi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "mardi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "mercredi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "jeudi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "vendredi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "samedi":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"},';
      $v_prog0 .= ' "dimanche":{"0":"eco","1":"eco","2":"eco","3":"eco","4":"eco","5":"eco","6":"eco","7":"eco","8":"eco","9":"eco","10":"eco","11":"eco","12":"eco","13":"eco","14":"eco","15":"eco","16":"eco","17":"eco","18":"eco","19":"eco","20":"eco","21":"eco","22":"eco","23":"eco"}';
      $v_prog0 .= ' }';
      $v_prog0 .= '}';
      
      // ----- Encode & save
      $v_prog_list[0] = json_decode($v_prog0);
      //$v_prog_list_json = json_encode($v_prog_list, JSON_FORCE_OBJECT);
      //config::save('prog_list', $v_prog_list_json, 'centralepilote');
      //centralepilote::cpCentraleSetConfig('prog_list', $v_prog_list_json);
      centralepilote::cpProgSaveList($v_prog_list);
      
      // ----- Update equipement cmds
      centralepilote::cpCmdAllProgrammeSelectUpdate();

      centralepilotelog::log('debug', "Creating default program 0");
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgSave()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgSave($p_id, $p_prog) {
      // ----- Read existing value
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      if (is_object($v_prog_list)) {
        centralepilotelog::log('debug', "cpProgSave(), list is object not array !");
      }
      if (is_string($v_prog_list)) {
        centralepilotelog::log('debug', "cpProgSave(), list is string not array !");
      }
      if (!is_array($v_prog_list)) {
        // TBC : normally a default programm should exists
        centralepilotelog::log('debug', "cpProgSave(), missing default program");
        $v_prog_list = array();
      }
      
      // ----- Program par defaut cant be modified
      if ($p_id === 0) {
        centralepilotelog::log('info', __("Le programme par défaut ne peut pas être modifié",__FILE__));
        $v_prog_json = json_encode($v_prog_list[$p_id], JSON_FORCE_OBJECT);
        return($v_prog_json);
      }
      
      // ----- Parse string value
      try {
        $v_info_obj = json_decode($p_prog);
      }
      catch (Exception $exc) {
      	centralepilotelog::log('error', __('Erreur parsing JSON', __FILE__));
      }
    
      // ----- Look for empty id -> means new programm
      if ($p_id == '') {
        $p_id = 10;
        $v_found = false;
        while (!$v_found) {
          $v_found = true;
          foreach ($v_prog_list as $v_key => $v_prog) {
            if ($p_id == $v_key) {
              $p_id++;
              $v_found = false;
              break;
            }
          }
        }
      }
      
      // ----- Check no missing name
      if ($v_info_obj->name == '') {
        $v_info_obj->name = __("Programme", __FILE__)." ".$p_id;
      }
      
      // ----- Look if existing prog
      if (isset($v_prog_list[$p_id])) {
        //message::add('centralepilote',  date("s").':Prog already exists' );
        $v_prog_list[$p_id] = $v_info_obj;
      }
      else {
        //message::add('centralepilote',  date("s").':New prog' );
        $v_info_obj->id = "".$p_id."";
        $v_prog_list[$p_id] = $v_info_obj;
      }
      
      // ----- Encode & save
      //$v_prog_list_json = json_encode($v_prog_list, JSON_FORCE_OBJECT);
      //config::save('prog_list', $v_prog_list_json, 'centralepilote');  
      //centralepilote::cpCentraleSetConfig('prog_list', $v_prog_list_json);
      centralepilote::cpProgSaveList($v_prog_list);

      // ----- Update equipement cmds
      centralepilote::cpCmdAllProgrammeSelectUpdate();   
      
      // ----- Perform a tick to update modes if there is change for the current time
      centralepilote::cpClockTick();
      
      // ----- Return the single programmation
      $v_prog_json = json_encode($v_prog_list[$p_id], JSON_FORCE_OBJECT);
      
      return($v_prog_json);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgLoad()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgLoad($p_id) {
      $v_prog = null;
      
      // ----- Get existing list
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      
      //message::add('centralepilote',  date("s").':Load id '.$p_id );
      if (($p_id == '') || (!isset($v_prog_list[$p_id]))) {
        // ----- load first one
        foreach ($v_prog_list as $v_prog) {break;}
      }
      else /*if (isset($v_prog_list[$p_id]))*/ {
        $v_prog = $v_prog_list[$p_id];
      }

      return($v_prog);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgGetName()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgGetName($p_id) {
      if (($v_prog = centralepilote::cpProgLoad($p_id)) === null) {
        return('');
      }
      
      return($v_prog['name']);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgNextId()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgNextId($p_id) {
      $v_prog = null;
      $v_next_id = -1;
      
      // ----- Get existing list
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      
      $v_next = false;
      foreach ($v_prog_list as $v_key => $v_prog) {
        if ($v_next_id == -1) {
          $v_next_id = $v_key;
        }
        if ($v_next) {
          $v_next_id = $v_key;
          break;
        }
        if ($v_key == $p_id) {
          $v_next = true;
        }
      }
      
      if ($v_next_id == -1) $v_next_id=0;
      
      return($v_next_id);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgClean()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgClean() {
      // ----- Get existing list
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      
      // ----- Remove all except default
      // Needed to disassociate all program from all radiateur and all zone
      foreach ($v_prog_list as $v_key => $v_prog) {
        if ($v_key != 0) {
          centralepilote::cpProgDelete($v_key);
        }
      }

      // ----- For sanity check I also remove all the list and recreate the default one
      //$v_prog_list_json = '{}';
      //config::save('prog_list', $v_prog_list_json, 'centralepilote');  
      //centralepilote::cpCentraleSetConfig('prog_list', $v_prog_list_json);
      centralepilote::cpProgSaveList(array());
      
      // ----- Recreate default one
      centralepilote::cpProgCreateDefault();      
      
      // ----- Return new list
      return(centralepilote::cpProgList());
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpProgDelete()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgDelete($p_id) {
      // ----- Look for 0 value which can not be deleted
      if ($p_id == 0) {
        centralepilote::log('debug', "Not allowed to remove default program.");
        return(false);
      }
      
      // ----- Read existing value
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
            
      // ----- Look unknown prog
      if (!isset($v_prog_list[$p_id])) {
        centralepilote::log('debug', "Fail to remove unknown programme id '".$p_id."'.");
        return(false);
      }
      
      // ----- Remove from all zone/radiateur
      // met la zone à 0 (default) et repasse en pilotage manuel, mode mémorisé avant passage en auto
      // si radiateur piloté par zone, alors laisse faire la zone, mais change tout de même le programme pour le mettre sur 0
      // TBC
      
      // ----- Remove from radiateurs
      $v_list = centralepilote::cpRadList();
      foreach ($v_list as $v_radiateur) {
        $v_radiateur->cpPilotageProgRemove($p_id);
      }
      
      // ----- Remove from zones
      $v_list = centralepilote::cpZoneList();
      foreach ($v_list as $v_zone) {
        $v_zone->cpPilotageProgRemove($p_id);
      }
      
      // ----- Remove from list
      unset($v_prog_list[$p_id]);
      
      // ----- Reencode and save
      //$v_prog_list_json = json_encode($v_prog_list, JSON_FORCE_OBJECT);      
      //config::save('prog_list', $v_prog_list_json, 'centralepilote');  
      //centralepilote::cpCentraleSetConfig('prog_list', $v_prog_list_json);
      centralepilote::cpProgSaveList($v_prog_list);

      // ----- Update equipement cmds
      centralepilote::cpCmdAllProgrammeSelectUpdate();      
      
      // ----- Perform a tick to update modes if there is change for the current time
      centralepilote::cpClockTick();
      
      return(true);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgList()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgList($p_details=false) {    
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();
      return($v_prog_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgValueList()
     * Description :
     *   The method return a string with the list of programmes in a format
     *   suitable for being used in a 'select' command.
     *   Sample :
     *   $this->cpCmdProgrammeSelectUpdate(centralepilote::cpProgValueList());
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgValueList() {    
      $v_str = '';

      // ----- Get existing list
      //$v_prog_list = config::byKey('prog_list', 'centralepilote');
      //$v_prog_list = centralepilote::cpCentraleGetConfig('prog_list');
      $v_prog_list = centralepilote::cpProgGetList();

      // ----- Generate the list
      $v_str_list = array();
      foreach ($v_prog_list as $v_key => $v_prog) {
        $v_str_list[] = $v_prog['id'].'|'.$v_prog['name'];
      }
      
      $v_str = implode(';', $v_str_list);
      
      return($v_str);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpProgModeFromClockTick()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpProgModeFromClockTick($p_id, $p_jour, $p_heure, $p_minute='') {
      // TBC : conifgurable defautl value ?
      $v_result = 'eco';
      
      $v_prog = centralepilote::cpProgLoad($p_id);
      if (!is_array($v_prog)) {
        // Error
        return($v_result);
      }
      
      // ----- Get mode from jour/heure/minute
      if (isset($v_prog['agenda'][$p_jour][$p_heure])) {
        $v_result = $v_prog['agenda'][$p_jour][$p_heure];
        //centralepilote::log('debug', 'Prog '.$p_id.' Mode for tick '.$p_jour.','.$p_heure.'='.$v_result);
      }
      
      return($v_result);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqList()
     * Description :
     *   centralepilote::cpEqList('radiateur', ['zone'=>'', 'ddd'=>'vvv'])
     *   centralepilote::cpEqList('radiateur', ['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpEqList($p_type, $p_filter_list=array()) {
      $v_result = array();
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->cpGetConf('type') != $p_type) {
          continue;
        }
        
        // ----- Look for filtering
        if (is_array($p_filter_list)) {
          $v_filter_ok = true;
          foreach ($p_filter_list as $v_key => $v_value) {
            if ($v_key == '_isEnable') {
              if ($v_eq->getIsEnable() != $v_value) {
                $v_filter_ok = false;
                break;
              }
            }
            else if ($v_eq->cpGetConf($v_key) != $v_value) {
              $v_filter_ok = false;
              break;
            }
          }
          if ($v_filter_ok) {
            $v_result[] = $v_eq;          
          }
        }
        else {
          $v_result[] = $v_eq;
        }
      }
      return($v_result);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpRadList()
     * Description :
     *   centralepilote::cpRadList(['zone'=>'', 'ddd'=>'vvv'])
     *   centralepilote::cpRadList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpRadList($p_filter_list=array()) {
      return(centralepilote::cpEqList('radiateur', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpZoneList()
     * Description :
     *   centralepilote::cpZoneList(['att'=>'', 'ddd'=>'vvv'])
     *   centralepilote::cpZoneList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpZoneList($p_filter_list=array()) {
      return(centralepilote::cpEqList('zone', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpClockTick()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function cpClockTick() {
    
      $v_now = date("Y-m-d-H-i");
           
      $v_jour = date("N");  // lundi:1 ... dimanche:7
      $v_heure = date("G");  // de 0 à 23
      $v_minute = date("i");  // de 00 à 59
      
      $v_jour_nom = [1=>'lundi',2=>'mardi',3=>'mercredi',4=>'jeudi',5=>'vendredi',6=>'samedi',7=>'dimanche'];
      $v_jour = $v_jour_nom[$v_jour];
      
      centralepilote::log('debug', 'Clock tick : '.$v_jour.', '.$v_heure.'h, '.$v_minute.'m');
      
      // ----- Parcourir toutes les zones et fixer le mode
      $v_list = centralepilote::cpZoneList(['_isEnable'=>true]);
      foreach ($v_list as $v_zone) {
        $v_zone->cpZoneClockTick($v_jour, $v_heure, $v_minute);
        $v_zone->cpEqClockTriggerTick($v_now);
      }
      
      // ----- Parcourir tous les radiateurs qui ne sont pas dans une zone et fixer le mode
      $v_list = centralepilote::cpRadList(['_isEnable'=>true, 'zone'=>'']);
      foreach ($v_list as $v_radiateur) {
        $v_radiateur->cpRadClockTick($v_jour, $v_heure, $v_minute);
        $v_radiateur->cpEqClockTriggerTick($v_now);
      }
    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : log()
     * Description :
     *   A placeholder to encapsulate log message, and be able do some
     *   troubleshooting locally.
     * ---------------------------------------------------------------------------
     */
    public static function log($p_level, $p_message) {
      
      log::add('centralepilote', $p_level, $p_message);

    }
    /* -------------------------------------------------------------------------*/


    /*     * *********************Méthodes d'instance************************* */
    
    /*
      preInsert ⇒ Méthode appellée avant la création de votre objet
      postInsert ⇒ Méthode appellée après la création de votre objet
      preUpdate ⇒ Méthode appellée avant la mise à jour de votre objet
      postUpdate ⇒ Méthode appellée après la mise à jour de votre objet
      preSave ⇒ Méthode appellée avant la sauvegarde (creation et mise à jour donc) de votre objet
      postSave ⇒ Méthode appellée après la sauvegarde de votre objet
      preRemove ⇒ Méthode appellée avant la supression de votre objet
      postRemove ⇒ Méthode appellée après la supression de votre objet    
      
      Lorsque l'on créé un equipement, il demande son nom, puis il fait les fonctions suivantes :
        preSave()
        preInsert()
        postInsert()
        postSave()
      il propose ensuite l'écran des paramètres de configuration :
    */

    public function preInsert() {
      centralepilotelog::log('debug', "preInsert()");
    }

    public function postInsert() {
      centralepilotelog::log('debug', "postInsert()");

      // ----- Vérifier qu'il y a bien un type d'indentifié, sinon forcer radiateur
      $v_type = $this->getConfiguration('type', '');
      if (($v_type != 'centrale') && ($v_type != 'radiateur') && ($v_type != 'zone')) {
        centralepilote::log('error', "Equipement avec un type non reconnu : '".$v_type."', type 'radiateur' forcé.");
        $this->setConfiguration('type', 'radiateur');
      }
      
      if (($v_type == 'radiateur') || ($v_type == 'zone')) {
      
        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        $this->cpCmdCreate('confort', ['name'=>'Confort', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('confort')]);
        $this->cpCmdCreate('confort_1', ['name'=>'Confort -1', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('confort_1')]);
        $this->cpCmdCreate('confort_2', ['name'=>'Confort -2', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('confort_2')]);
        $this->cpCmdCreate('eco', ['name'=>'Eco', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('eco')]);
        $this->cpCmdCreate('horsgel', ['name'=>'HorsGel', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('horsgel')]);
        $this->cpCmdCreate('off', ['name'=>'Off', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('off')]);
        $this->cpCmdCreate('auto', ['name'=>'Auto', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>'far fa-clock']);
          
        $this->cpCmdCreate('etat', ['name'=>'Etat', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>1, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
  
        $this->cpCmdCreate('pilotage', ['name'=>'Pilotage', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>1, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
  
        $this->cpCmdCreate('programme', ['name'=>'Programme', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
        $this->cpCmdCreate('programme_id', ['name'=>'Programme Id', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++]);
        $this->cpCmdCreate('programme_select', ['name'=>'Programme Select', 'type'=>'action', 'subtype'=>'select', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>'icon divers-calendar2']);
        
        $this->cpCmdCreate('trigger', ['name'=>'Trigger', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>0, 'order'=>$v_cmd_order++, 'icon'=>'fas fa-edit']);
        
        // ----- Update value list for the command 'programme_select' which is of subtype 'select'
        $this->cpCmdProgrammeSelectUpdate(centralepilote::cpProgValueList());
      }
            
      // ----- Initialisations spécifiques
      if ($v_type == 'radiateur') {
      }

      else if ($v_type == 'zone') {
      }

      else if ($v_type == 'centrale') {
        $v_cmd_order=1;

        // ----- Création des commandes par défaut
        $this->cpCmdCreate('normal', ['name'=>'Normal', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>'icon kiko-sun']);
        $this->cpCmdCreate('delestage', ['name'=>'Délestage', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('off')]);
        $this->cpCmdCreate('eco', ['name'=>'Eco', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('eco')]);
        $this->cpCmdCreate('horsgel', ['name'=>'HorsGel', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++, 'icon'=>centralepilote::cpModeGetIconClass('horsgel')]);
        
        $this->cpCmdCreate('etat', ['name'=>'Etat', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>1, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
        
        // ----- Here I can change the value because the centrale eq is created in "enable" status.
        $this->checkAndUpdateCmd('etat', 'Normal');
      }

      else {
        // TBC : error
      }

    }

    public function preSave() {
      //centralepilotelog::log('debug', "preSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'radiateur') {
        $this->preSaveRadiateur();
      }
      else if ($v_type == 'zone') {
        $this->preSaveZone();
      }
      else if ($v_type == 'centrale') {
        $this->preSaveCentrale();
      }
    }

    public function preSaveRadiateur() {
      centralepilotelog::log('debug', "preSaveRadiateur()");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'radiateur', 'centrale' or 'zone'
      if ($this->getId() == '') {
        centralepilotelog::log('debug', "preSave() : new radiateur ...");
        
        // ----- Set default values
        $this->setConfiguration('support_confort', '1');
        $this->setConfiguration('support_confort_1', '0');
        $this->setConfiguration('support_confort_2', '0');
        $this->setConfiguration('support_eco', '1');
        $this->setConfiguration('support_horsgel', '1');
        $this->setConfiguration('support_off', '1');        
        $this->setConfiguration('nature_fil_pilote', '2_commutateur');        
        
        $this->setConfiguration('pilotage', 'eco');
        $this->setConfiguration('programme_id', '0');

        $this->setConfiguration('bypass_type', 'no');
        $this->setConfiguration('bypass_mode', 'no');

        // ----- Information concernant les caractéristiques du radiateur        
        $this->setConfiguration('temperature', '');
        $this->setConfiguration('puissance', '');
        
        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
      	$eqLogic = self::byId($this->getId());
        
        $v_support_modes  = $eqLogic->getConfiguration('support_confort','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_confort_1','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_confort_2','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_eco','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_horsgel','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_off','').',';

        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable(),
          'zone'                  => $eqLogic->getConfiguration('zone',''),
          'nature_fil_pilote'     => $eqLogic->getConfiguration('nature_fil_pilote',''),
          'support_modes'       => $v_support_modes
        );
        
        // ----- Look if nature_fil_pilote change
        // Doing change before the save
        $v_nature_fil_pilote = $this->cpGetConf('nature_fil_pilote');
        if (   ($eqLogic->cpGetConf('nature_fil_pilote') != $v_nature_fil_pilote)
            || ($eqLogic->cpGetConf('lien_commutateur') != $this->cpGetConf('lien_commutateur'))
            || ($eqLogic->cpGetConf('lien_commutateur_a') != $this->cpGetConf('lien_commutateur_a'))
            || ($eqLogic->cpGetConf('lien_commutateur_b') != $this->cpGetConf('lien_commutateur_b')) ) {
          $this->cpNatureChangeTo($v_nature_fil_pilote);
        }
  
        
      }
      centralepilotelog::log('debug', "preSaveRadiateur() done");
    }

    public function preSaveZone() {
      //centralepilotelog::log('debug', "preSave()");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'radiateur', 'centrale' or 'zone'
      if ($this->getId() == '') {
        centralepilotelog::log('debug', "preSave() : new zone ...");
        
        // ----- Set default values
        $this->setConfiguration('support_confort', '1');
        $this->setConfiguration('support_confort_1', '0');
        $this->setConfiguration('support_confort_2', '0');
        $this->setConfiguration('support_eco', '1');
        $this->setConfiguration('support_horsgel', '1');
        $this->setConfiguration('support_off', '1');        
        $this->setConfiguration('nature_fil_pilote', 'virtuel');        
        
        //$this->setConfiguration('admin_pilotage', 'manuel');
        //$this->setConfiguration('admin_mode', 'eco');
        $this->setConfiguration('pilotage', 'eco');
        $this->setConfiguration('programme_id', '0');
        
        // ----- Information concernant les caractéristiques de la zone        
        $this->setConfiguration('temperature', '');

        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
      	$eqLogic = self::byId($this->getId());

        $v_support_modes  = $eqLogic->getConfiguration('support_confort','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_confort_1','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_confort_2','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_eco','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_horsgel','').',';
        $v_support_modes .= $eqLogic->getConfiguration('support_off','').',';

        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable(),
          'support_modes'       => $v_support_modes
        );
      }
    }

    public function preSaveCentrale() {
      centralepilotelog::log('debug', "preSave() : centrale ...");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'radiateur', 'centrale' or 'zone'
      if ($this->getId() == '') {
        centralepilotelog::log('debug', "preSave() : new centrale ...");
        
        // ----- Set default values
        $this->setConfiguration('temperature_confort', '19');
        $this->setConfiguration('temperature_confort_1', '18');
        $this->setConfiguration('temperature_confort_2', '17');
        $this->setConfiguration('temperature_eco', '15');
        $this->setConfiguration('temperature_horsgel', '3');

        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
      	$eqLogic = self::byId($this->getId());

        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable()
        );
      }

      centralepilotelog::log('debug', "preSave() end");
    }

    public function postSave() {
      //centralepilotelog::log('debug', "postSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'radiateur') {
        $this->postSaveRadiateur();
      }
      else if ($v_type == 'zone') {
        $this->postSaveZone();
      }
      else if ($v_type == 'centrale') {
        $this->postSaveCentrale();
      }
    }

    public function postSaveRadiateur() {

      centralepilotelog::log('debug', "postSave() radiateur");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        centralepilotelog::log('debug', "postSave() : new radiateur");

/* Herité du plugIn Virtual */
		$createRefreshCmd = true;
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = cmd::byEqLogicIdCmdName($this->getId(), __('Rafraichir', __FILE__));
			if (is_object($refresh)) {
				$createRefreshCmd = false;
			}
		}
		if ($createRefreshCmd) {
			if (!is_object($refresh)) {
				$refresh = new centralepiloteCmd();
				$refresh->setLogicalId('refresh');
				$refresh->setIsVisible(1);
				$refresh->setName(__('Rafraichir', __FILE__));
			}
			$refresh->setType('action');
			$refresh->setSubType('other');
			$refresh->setEqLogic_id($this->getId());
			$refresh->save();
		}
        
        
      }
      
      // ----- Look for existing device
      else {
        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // ----- Change to enable
          if ($this->getIsEnable()) {
            $this->cpEqChangeToEnable();
          }
          
          // ----- Look for disable actions
          else {
            $this->cpEqChangeToDisable();
          }
        }
        
        // ----- Look for change in supported modes, hide or show the right commands
        $v_support_modes  = $this->getConfiguration('support_confort','').',';
        $v_support_modes .= $this->getConfiguration('support_confort_1','').',';
        $v_support_modes .= $this->getConfiguration('support_confort_2','').',';
        $v_support_modes .= $this->getConfiguration('support_eco','').',';
        $v_support_modes .= $this->getConfiguration('support_horsgel','').',';
        $v_support_modes .= $this->getConfiguration('support_off','').',';
        
        if ($this->_pre_save_cache['support_modes'] != $v_support_modes) {
          centralepilotelog::log('debug', "Modes are changed, look for cammands");
          $v_list = centralepilote::cpModeGetList();
          foreach ($v_list as $v_mode) {
            $v_value = $this->getConfiguration('support_'.$v_mode,'');
            $this->cpCmdHide($v_mode, ($v_value==0));
          }
          
          // ----- Force current saved mode because it may not exist anymore
          //$v_pilote_mode = $this->getConfiguration('pilotage','');
          $v_pilote_mode = $this->cpPilotageGetAdminValue();
          $this->cpPilotageChangeTo($v_pilote_mode);
        }

        // si zone passe de vide à set : alors changer le mode à celui de la zone
        // à l'inverse reforcer le mode mémorisé dans la commande mode
        // ----- Look if zone change
        if ($this->_pre_save_cache['zone'] != $this->cpGetConf('zone')) {
          // ----- Zone change to no zone
          if ($this->cpGetConf('zone') == '') {
            $this->cpPilotageExitFromZone();
          }
          // ----- Zone change to some zone
          else {
            $this->cpPilotageChangeToZone();
          }
        }
  
      }
      
      centralepilotelog::log('debug', "postSave() radiateur done");
    }

    public function postSaveZone() {

      //centralepilotelog::log('debug', "postSave()");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        centralepilotelog::log('debug', "postSave() : new equipement");

/* Herité du plugIn Virtual */
		$createRefreshCmd = true;
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = cmd::byEqLogicIdCmdName($this->getId(), __('Rafraichir', __FILE__));
			if (is_object($refresh)) {
				$createRefreshCmd = false;
			}
		}
		if ($createRefreshCmd) {
			if (!is_object($refresh)) {
				$refresh = new centralepiloteCmd();
				$refresh->setLogicalId('refresh');
				$refresh->setIsVisible(1);
				$refresh->setName(__('Rafraichir', __FILE__));
			}
			$refresh->setType('action');
			$refresh->setSubType('other');
			$refresh->setEqLogic_id($this->getId());
			$refresh->save();
		}
        
        
      }
      
      // ----- Look for existing device
      else {
        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // ----- Change to enable
          if ($this->getIsEnable()) {
            $this->cpEqChangeToEnable();
          }
          
          // ----- Look for disable actions
          else {
            $this->cpEqChangeToDisable();
          }
        }
  
        // ----- Look for change in supported modes, hide or show the right commands
        $v_support_modes  = $this->getConfiguration('support_confort','').',';
        $v_support_modes .= $this->getConfiguration('support_confort_1','').',';
        $v_support_modes .= $this->getConfiguration('support_confort_2','').',';
        $v_support_modes .= $this->getConfiguration('support_eco','').',';
        $v_support_modes .= $this->getConfiguration('support_horsgel','').',';
        $v_support_modes .= $this->getConfiguration('support_off','').',';
        
        if ($this->_pre_save_cache['support_modes'] != $v_support_modes) {
          centralepilotelog::log('debug', "Modes are changed, look for cammands");
          $v_list = centralepilote::cpModeGetList();
          foreach ($v_list as $v_mode) {
            $v_value = $this->getConfiguration('support_'.$v_mode,'');
            $this->cpCmdHide($v_mode, ($v_value==0));
          }
          // ----- Force current saved mode because it may not exist anymore
          //$v_pilote_mode = $this->getConfiguration('pilotage','');
          $v_pilote_mode = $this->cpPilotageGetAdminValue();
          //$this->cpModeChangeTo($v_admin_mode);
          $this->cpPilotageChangeTo($v_pilote_mode);
        }

      }
      
    }

    public function postSaveCentrale() {

      centralepilotelog::log('debug', "postSave() : centrale");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        centralepilotelog::log('debug', "postSave() : new centrale");

        /* already done in create default centrale
        if ($this->cpGetType() == 'centrale') {
          centralepilote::cpProgCreateDefault();
        }
        */
        
      }
      
      // ----- Look for existing device
      else {
        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // ----- Change to enable
          if ($this->getIsEnable()) {
            // ----- Look for etat initial value
            // TBC : normalement l'eq centrale est créé en mode 'enable' et n'est jamais desctivé, donc ne vient jamais là ... sauf peut être lors de l'activation/desactivation du plugin lui-même
            $v_value = $this->cpCmdGetValue('etat');
            if ($v_value == '') {
              $this->checkAndUpdateCmd('etat', 'Normal');
            }
          }
          
          // ----- Not allowed
          else {
            //centralepilotelog::log('error', "Not allowed to disable 'Centrale' in CentralePilote PlugIn.");
            // ----- Change to enable
            // TBC : comment forcer la désactivation ???
            //$this->setIsEnable();
            //$this->save();        
          }
        }
  
      }
      
      centralepilotelog::log('debug', "postSave() : end");
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
    }

    public function preRemove() {
    }

    public function postRemove() {
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */
    public function toHtml($_version = 'dashboard') {
    
      // ----- Look for use of standard widget or not
      if (config::byKey('standard_widget', 'centralepilote') == 1) {
        return parent::toHtml($_version);
      }
      
      if ($this->cpIsType('radiateur')) {
        return $this->toHtml_radiateur($_version);
      }
      else if ($this->cpIsType('zone')) {
        //return parent::toHtml($_version);
        return $this->toHtml_radiateur($_version);
      }
      else {
        return $this->toHtml_centrale($_version);
      }


      
    }

    /**---------------------------------------------------------------------------
     * Method : toHtml_centrale()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function toHtml_centrale($_version = 'dashboard') {
      centralepilote::log('debug',  "Call toHtml_centrale()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
  
  // TBC : $replace['#refresh_id#'] = $this->getCmd('action', 'refresh')->getId();

      //$replace['#name_display#'] = 'La Centrale Pilote';
     
      $v_cmd = $this->getCmd(null, 'etat');
      if (is_object($v_cmd)) {         
        $v_etat = $v_cmd->execCmd();
        $replace['#cmd_etat_id#'] = $v_cmd->getId();
        $replace['#cmd_etat_code#'] = $v_etat;
        switch ($v_etat) {
          case 'normal' :
            $replace['#cmd_etat_name#'] = __("Normal", __FILE__);
            break;
          case 'delestage' :
            $replace['#cmd_etat_name#'] = __("Delestage", __FILE__);
            break;
          default :
            $replace['#cmd_etat_name#'] = centralepilote::cpModeGetName($v_etat);          
        }
        
        $replace['#cmd_'.$v_etat.'_style#'] = "background-color: #2C941A!important; color: white!important;";
      }
     
      $v_cmd = $this->getCmd(null, 'normal');
      if (is_object($v_cmd)) {         
         $replace['#cmd_normal_id#'] = $v_cmd->getId();
         $replace['#cmd_normal_name#'] = __("Normal", __FILE__);
         $replace['#cmd_normal_icon#'] = 'icon kiko-sun';
      }
      $v_cmd = $this->getCmd(null, 'delestage');
      if (is_object($v_cmd)) {         
         $replace['#cmd_delestage_id#'] = $v_cmd->getId();
         $replace['#cmd_delestage_name#'] = __("Delestage", __FILE__);
         $replace['#cmd_delestage_icon#'] = centralepilote::cpModeGetIconClass('off');
      }
      $v_cmd = $this->getCmd(null, 'eco');
      if (is_object($v_cmd)) {         
         $replace['#cmd_eco_id#'] = $v_cmd->getId();
         $replace['#cmd_eco_name#'] = centralepilote::cpModeGetName('eco');
         $replace['#cmd_eco_icon#'] = centralepilote::cpModeGetIconClass('eco');
      }
      $v_cmd = $this->getCmd(null, 'horsgel');
      if (is_object($v_cmd)) {         
         $replace['#cmd_horsgel_id#'] = $v_cmd->getId();
         $replace['#cmd_horsgel_name#'] = centralepilote::cpModeGetName('horsgel');
         $replace['#cmd_horsgel_icon#'] = centralepilote::cpModeGetIconClass('horsgel');
      }
      
      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'centralepilote-centrale.template', __CLASS__)));  
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : toHtml_radiateur()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function toHtml_radiateur($_version = 'dashboard') {
      //centralepilote::log('debug',  "Call toHtml_radiateur()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
      
      $v_cmd = $this->getCmd(null, 'pilotage');
      if (is_object($v_cmd)) {         
        $v_pilotage_value = $v_cmd->execCmd();
        $replace['#cmd_pilotage_value#'] = $v_pilotage_value;
      }
      
      $v_cmd = $this->getCmd(null, 'etat');
      if (is_object($v_cmd)) {         
        $v_etat_name = $v_cmd->execCmd();
        $v_etat = centralepilote::cpModeGetCodeFromName($v_etat_name);
        $replace['#cmd_etat_id#'] = $v_cmd->getId();
        $replace['#cmd_etat_value#'] = $v_etat;
        $replace['#cmd_etat_name#'] = $v_etat_name;     
      }
      
      $v_pilotage_current = $this->cpCmdGetValue('pilotage');
      if ($v_pilotage_current == 'auto') {
        $replace['#cmd_auto_style#'] = "background-color: #4ABAF2!important; color: white!important;";
        $replace['#cmd_'.$v_etat.'_style#'] = "background-color: #2C941A!important; color: white!important;";
      }
      else {
        $replace['#cmd_'.$v_etat.'_style#'] = "background-color: #2C941A!important; color: white!important;";
      }

      $v_list = centralepilote::cpModeGetList();
      foreach ($v_list as $v_mode) {
        $v_cmd = $this->getCmd(null, $v_mode);
        if (is_object($v_cmd)) {         
           $replace['#cmd_'.$v_mode.'_id#'] = $v_cmd->getId();
           $replace['#cmd_'.$v_mode.'_logicalid#'] = $v_mode;
           $replace['#cmd_'.$v_mode.'_name#'] = centralepilote::cpModeGetName($v_mode);
           $replace['#cmd_'.$v_mode.'_icon#'] = centralepilote::cpModeGetIconClass($v_mode);
           $replace['#cmd_'.$v_mode.'_large_icon#'] = centralepilote::cpModeGetLargeIconClass($v_mode);
           if ($this->cpGetConf('support_'.$v_mode) == 1) {
             $replace['#cmd_'.$v_mode.'_show#'] = 'show';
           }
           else {
             $replace['#cmd_'.$v_mode.'_show#'] = 'no_show';
           }
        }
        else {
          // TBC : should not occur ...
          $replace['#cmd_'.$v_mode.'_show#'] = 'no_show';
        }
      }
      $v_cmd = $this->getCmd(null, 'auto');
      if (is_object($v_cmd)) {         
         $replace['#cmd_auto_id#'] = $v_cmd->getId();
         $replace['#cmd_auto_logicalid#'] = 'auto';
         $replace['#cmd_auto_name#'] = __("Auto", __FILE__);
         $replace['#cmd_auto_icon#'] = 'far fa-clock';
      }

      $v_cmd = $this->getCmd(null, 'programme_select');
      if (is_object($v_cmd)) {         
         $replace['#cmd_programme_select_id#'] = $v_cmd->getId();
      }

      // ----- Look for triggers
      $v_cmd = $this->getCmd(null, 'trigger');
      if (is_object($v_cmd)) {         
         $replace['#cmd_trigger_id#'] = $v_cmd->getId();
      }
      if ($this->cpEqHasTrigger() > 0) {
        $replace['#cmd_trigger_style#'] = "background-color: #4ABAF2!important; color: white!important;";
        
        $v_trigger_list = $this->cpGetConf('trigger_list');
        $v_str = '';
        foreach ($v_trigger_list as $v_trigger) {
          if ($v_str != '') $v_str .='|';
          // TBC
          $it = explode('-', $v_trigger['time']);
          $v_time_formatted = $it[3].'h'.$it[4].' ('.$it[2].'/'.$it[1].'/'.$it[0].')';
          $v_name = 
          $v_str .= $v_trigger['type'].','.$v_trigger['time'].','.$v_time_formatted.','.$v_trigger['mode'].','.centralepilote::cpPilotageGetName($v_trigger['mode']);
        }
        $replace['#cmd_trigger_list#'] = $v_str;
      }
      else {
        $replace['#cmd_trigger_style#'] = '';
        $replace['#cmd_trigger_list#'] = "";
      }
      
      // ----- List of programmation
      $replace['#list_programmation#'] = centralepilote::cpProgValueList();
      $replace['#programme_id#'] = $this->cpGetConf('programme_id');
      $replace['#programme_name#'] = centralepilote::cpProgGetName($this->cpGetConf('programme_id'));
      
      // ----- Bypass mode
      $replace['#bypass_type#'] = $this->cpGetConf('bypass_type');
      $replace['#bypass_mode#'] = $this->cpGetConf('bypass_mode');
        
      // ----- Zone mode
      $replace['#zone_mode#'] = $this->cpRadGetZoneId();
      $replace['#cmd_zone_name#'] = $this->cpRadGetZoneName();
        
      // ----- Temperatures
      // TBC
      $replace['#temperature_cible#'] = $this->cpEqGetTemperatureCible();      
      $replace['#temperature_actuelle#'] = $this->cpEqGetTemperatureActuelle();      
      
      // ----- Texte divers
      $replace['#title_programme#'] = __("Prog", __FILE__);
      $replace['#title_select_programmation#'] = __("Choisir la programmation", __FILE__);
      $replace['#title_pilotage_zone#'] = __("Pilotage par Zone", __FILE__);
      $replace['#title_delestage_centralise#'] = __("Délestage Centralisé", __FILE__);
      $replace['#title_Retour#'] = __("Retour", __FILE__);
      $replace['#title_Annuler#'] = __("Annuler", __FILE__);
      $replace['#title_Valider#'] = __("Valider", __FILE__);
      $replace['#title_Ajouter#'] = __("Ajouter", __FILE__);
      $replace['#title_etat#'] = __("Etat", __FILE__);
      $replace['#title_cible#'] = __("Cible", __FILE__);
      $replace['#title_actuelle#'] = __("Actuelle", __FILE__);
      $replace['#title_Mode#'] = __("Mode", __FILE__);
      $replace['#title_a#'] = __("A", __FILE__);
      $replace['#title_a_min#'] = __("à", __FILE__);
      $replace['#title_missing_mode#'] = __("Missing mode", __FILE__);
      
      
      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'centralepilote-radiateur.template', __CLASS__)));  
    }
    /* -------------------------------------------------------------------------*/

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */
    public static function postConfig_standard_widget() {
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        $v_eq->refreshWidget();
      }    
    }

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire permet d'associer une icone custom pour l'objet
     */
	public function getImage() {
        $v_type = $this->cpGetType();
	  	$file = 'plugins/centralepilote/desktop/images/'.$v_type.'_icon.png';
		if(!file_exists(__DIR__.'/../../../../'.$file)){
			return 'plugins/centralepilote/plugin_info/centralepilote_icon.png';
		}
		return $file;
	}





    /*     * **********************Getteur Setteur*************************** */


    /**---------------------------------------------------------------------------
     * Method : cpGetConf()
     * Description :
     *   Récupère la valeur stockée pour un attribut de configuration.
     *   Ou la valeur par defaut si absent.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	private function cpGetConf($p_key) {
	  return $this->getConfiguration($p_key, $this->cpGetDefaultConfiguration($p_key));
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpGetConf()
     * Description :
     *   Retourne l'un des 3 types majeurs d'équipement : 'centrale', 'radiateur' ou 'zone'.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	private function cpGetType() {
	  return $this->getConfiguration('type', $this->cpGetDefaultConfiguration($p_key));
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpIsType()
     * Description :
     *   example : if ($this->cpIsType(array('radiateur','zone')))
     *   ou if ($this->cpIsType('radiateur'))
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function cpIsType($p_value) {
      $v_type = $this->cpGetType();
      if (is_array($p_value)) {
        foreach ($p_value as $v_item) {
          if ($v_type == $v_item) {
            return(true);
          }
        }
      }
      else if ($v_type == $p_value) {
        return(true);
      }
      return(false);
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpGetDefaultConfiguration()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	private function cpGetDefaultConfiguration($p_key) {
    
    // TBC : splitt per type
		$v_conf_keys = array(
			'type' => 'radiateur',
			'bypass_type' => 'no',
			'bypass_mode' => 'no',
			'trigger_list' => array(),
            'zone' => '',
            'temperature' => '',
            
            // For centrale
            'temperature_confort' => '19',
            'temperature_confort_1' => '18',
            'temperature_confort_2' => '17',
            'temperature_eco' => '15',
            'temperature_horsgel' => '3'
		);
		// If not in list, default value is ''
		return(array_key_exists($p_key, $v_conf_keys) ? $v_conf_keys[$p_key] : '');
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCmdCreate()
     * Description :
     *   cpCmdCreate('confort', ['name'=>'Confort', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>1]);
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpCmdCreate($p_cmd_id, $p_att_list=array()) {

      // ----- Look for existing command
      $v_cmd = $this->getCmd(null, $p_cmd_id);

      // ----- Look if command already exists in device
      if (is_object($v_cmd)) {
        centralepilote::log('debug', "Command '".$p_cmd_id."' already defined in device.");
        return(true);
      }

      // ----- Create Command

      centralepilotelog::log('debug', "Create Cmd '".$p_cmd_id."' for device '".$this->getName()."'.");
      $v_cmd = new centralepiloteCmd();
      $v_cmd->setLogicalId($p_cmd_id);
      $v_cmd->setEqLogic_id($this->getId());
      
      foreach ($p_att_list as $v_key => $v_value) {
        if ($v_key == 'name') {
          $v_cmd->setName($v_value);
        }
        else if ($v_key == 'type') {
          $v_cmd->setType($v_value);
        }
        else if ($v_key == 'subtype') {
          $v_cmd->setSubType($v_value);
        }
        else if ($v_key == 'isHistorized') {
          $v_cmd->setIsHistorized($v_value);
        }
        else if ($v_key == 'isVisible') {
          $v_cmd->setIsVisible($v_value);
        }
        else if ($v_key == 'order') {
          $v_cmd->setOrder($v_value);
        }
        else if ($v_key == 'icon') {
          $v_cmd->setDisplay('icon', '<i class="'.$v_value.'"></i>');
          $v_cmd->setDisplay('showIconAndNamedashboard', "1");          
        }
      }

      /* Parametres de display des commandes :
      {"showStatsOnmobile":0,"showStatsOndashboard":0,"icon":"<i class=\"fab fa-hotjar \"><\/i>","showNameOndashboard":"1","showNameOnmobile":"1","showIconAndNamedashboard":"1","showIconAndNamemobile":"1","forceReturnLineBefore":"0","forceReturnLineAfter":"0","parameters":[]}
      fas fa-power-off
      fas fa-leaf
      far fa-snowflake
      */

/*
        if (isset($v_cmd_info['max_value'])) {
          $v_cmd->setConfiguration('maxValue', $v_cmd_info['max_value']);
        }
        if (isset($v_cmd_info['min_value'])) {
          $v_cmd->setConfiguration('minValue', $v_cmd_info['min_value']);
        }

        if (isset($v_cmd_info['generic_type']) && ($v_cmd_info['generic_type'] != '')) {
          $v_cmd->setGeneric_type($v_cmd_info['generic_type']);
        }
*/

      $v_cmd->save();

      return(true);
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpCmdResetDisplay()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function cpCmdResetDisplay() {
      // ----- No need for Central for now
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return;
      }
    
      // ----- Get mode list
      $v_mode_list = centralepilote::cpModeGetList();
      
      // ----- Look for pilotage mode
      $v_pilotage = $this->cpGetConf('pilotage');
    
      // ----- Look if device is in delestage bypass mode
      $v_bypass_type = $this->cpGetConf('bypass_type');
      $v_bypass_mode = $this->cpGetConf('bypass_mode');
      if ($v_bypass_type == 'delestage') {
        // ----- Hide all mode commands
        foreach ($v_mode_list as $v_mode) {
           $this->cpCmdHide($v_mode, true);
        }

        // ----- Hide all others
        $this->cpCmdHide('auto', true);
        $this->cpCmdHide('programme_select', true);
        $this->cpCmdHide('programme', true);
        return;
      }

      // ----- Look for radiateur
      if ($this->cpIsType('radiateur')) {
        // ----- Look if radiateur is in zone
        if ($this->cpPilotageIsZone())  {
          // ----- When radiateur is in a zone, commanda are through the zone, hide local command
          
          foreach ($v_mode_list as $v_mode) {
             $this->cpCmdHide($v_mode, true);
          }

          // ----- Look for auto/manuel commands
          //$this->cpCmdHide('manuel', true);
          $this->cpCmdHide('auto', true);
          //$this->cpCmdHide('prog_select', true);
          $this->cpCmdHide('programme_select', true);
          $this->cpCmdHide('programme', true);
        }
        
        // ----- Radiateur not in a zone
        else {
          // ----- Display only mode commands depending on supported mode from the radiateur
          foreach ($v_mode_list as $v_mode) {
            $v_value = $this->getConfiguration('support_'.$v_mode,'');
            $this->cpCmdHide($v_mode, ($v_value==0));
          }

          // ----- Radiateur in mode auto : hide command for all mode
          if ($v_pilotage == 'auto') {
          /*
            foreach ($v_mode_list as $v_mode) {
               $this->cpCmdHide($v_mode, true);
            }
            */
            
            // ----- Hide auto cmd and show manuel cmd, and programm selection
            //$this->cpCmdHide('manuel', false);
            //$this->cpCmdHide('auto', true);
            $this->cpCmdHide('auto', false);
            //$this->cpCmdHide('prog_select', false);
            $this->cpCmdHide('programme_select', false);
            $this->cpCmdHide('programme', false);
          }
          // ----- Radiateur in mode manuel : hide command if not supported
          else /*if ($v_pilotage == 'manuel')*/ {
          /*
            // ----- Display only mode commands depending on supported mode from the radiateur
            foreach ($v_mode_list as $v_mode) {
              $v_value = $this->getConfiguration('support_'.$v_mode,'');
              $this->cpCmdHide($v_mode, ($v_value==0));
            }
            */
            
            // ----- Hide manuel cmd and programm selection, and show auto cmd, 
            //$this->cpCmdHide('manuel', true);
            $this->cpCmdHide('auto', false);
            //$this->cpCmdHide('prog_select', true);
            $this->cpCmdHide('programme_select', true);
            $this->cpCmdHide('programme', true);
          }
        }
      }
    
      // ----- Look for zone
      else if ($this->cpIsType('zone')) {
        // ----- Display only mode commands depending on supported mode from the radiateur
        foreach ($v_mode_list as $v_mode) {
          $v_value = $this->getConfiguration('support_'.$v_mode,'');
          $this->cpCmdHide($v_mode, ($v_value==0));
        }

        // ----- Zone in mode auto : hide command for all mode
        if ($v_pilotage == 'auto') {
        /*
          foreach ($v_mode_list as $v_mode) {
             $this->cpCmdHide($v_mode, true);
          }
          */
          
          // ----- Hide auto cmd and show manuel cmd, and programm selection
          //$this->cpCmdHide('manuel', false);
          //$this->cpCmdHide('auto', true);
          $this->cpCmdHide('auto', false);
          //$this->cpCmdHide('prog_select', false);
          $this->cpCmdHide('programme_select', false);
          $this->cpCmdHide('programme', false);
        }
        // ----- Zone in mode manuel : hide command if not supported
        else /*if ($v_pilotage == 'manuel')*/ {
        /*
          // ----- Display only mode commands depending on supported mode from the radiateur
          foreach ($v_mode_list as $v_mode) {
            $v_value = $this->getConfiguration('support_'.$v_mode,'');
            $this->cpCmdHide($v_mode, ($v_value==0));
          }
          */
          
          // ----- Hide manuel cmd and programm selection, and show auto cmd, 
          //$this->cpCmdHide('manuel', true);
          $this->cpCmdHide('auto', false);
          //$this->cpCmdHide('prog_select', true);
          $this->cpCmdHide('programme_select', true);
          $this->cpCmdHide('programme', true);
        }
      }
    
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpCmdHide()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function cpCmdHide($p_cmd_logicalId, $p_hide=true) {
      $v_cmd = $this->getCmd(null, $p_cmd_logicalId);
      if (!is_object($v_cmd)) {
        // TBC Error
      }
      else {
        $v_cmd->setIsVisible(($p_hide?0:1));
        $v_cmd->save();
      }
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCmdGetValue()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function cpCmdGetValue($p_cmd_logicalId) {
      if (!is_object($v_cmd = $this->getCmd(null, $p_cmd_logicalId))) {
        centralepilote::log('debug',  "Missing command '".$p_cmd_logicalId."' for equipement '".$this->getName()."'.");
        return('');
      }
      $v_value = $v_cmd->execCmd();
      return($v_value);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCmdProgrammeSelectUpdate()
     * Description :
     *   This function update the value list of the command "ProgrammeSelect".
     *   Sample :
     *   $this->cpCmdProgrammeSelectUpdate(centralepilote::cpProgValueList());
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function cpCmdProgrammeSelectUpdate($p_value_list_str) {
    
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return;
      }
      
      $v_cmd_tmp = $this->getCmd(null, 'programme_select');
      if (!is_object($v_cmd_tmp)) {
        centralepilote::log('debug',  "Fail to find command '".'programme_select'."' for equipement '".$this->getName()."'.");
        return;
      } 
      $v_cmd_tmp->setConfiguration('listValue', $p_value_list_str);
      
      // ----- Refresh list in widget
      $this->refreshWidget();    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpCmdAllProgrammeSelectUpdate()
     * Description :
     *   This function update the value list of the command "ProgrammeSelect" for all the objects.
     *   Sample :
     *   centralepilote::cpCmdAllProgrammeSelectUpdate();
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public static function cpCmdAllProgrammeSelectUpdate() {
    
      // ----- Get the programme list in right string format
      $v_value_list = centralepilote::cpProgValueList();
      centralepilote::log('debug',  "New list for cmd '".'programme_select'."' : '".$v_value_list."'.");
      
      // ----- Update for all eQuip
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        $v_eq->cpCmdProgrammeSelectUpdate($v_value_list);
      }      
    
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpRefresh()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function cpRefresh() {
      // ----- No need for Central for now
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return;
      }

      if (jeedom::evaluateExpression($this->getConfiguration('statut_confort', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('confort'));
      }
      else if (jeedom::evaluateExpression($this->getConfiguration('statut_confort_1', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('confort_1'));
      }
      else if (jeedom::evaluateExpression($this->getConfiguration('statut_confort_2', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('confort_2'));
      }
      else if (jeedom::evaluateExpression($this->getConfiguration('statut_eco', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('eco'));
      }
      else if (jeedom::evaluateExpression($this->getConfiguration('statut_horsgel', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('horsgel'));
      }
      else if (jeedom::evaluateExpression($this->getConfiguration('statut_off', '')) == 1) {
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName('off'));
      }
      else {
        // ----- Do not change the mode if no valid status
        centralepilotelog::log('debug', "Unable to find the mode from the status evaluation for device '".$this->getName()."'.");
      }
        
	}
    /* -------------------------------------------------------------------------*/
    
    /**---------------------------------------------------------------------------
     * Method : cpExecuteVirtualCmd()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpExecuteVirtualCmd($p_virtual_cmd, $p_options=null) {
      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, ignore virtual command execution");
        return(0);
      }
    
      if ($p_virtual_cmd == '') {
        return(0);
      }
   
      $v_result = 1;
      $cmds = explode('&&', $p_virtual_cmd);
      if (is_array($cmds)) {
        foreach ($cmds as $cmd_id) {
          $cmd = cmd::byId(str_replace('#', '', $cmd_id));
          if (is_object($cmd)) {
            try {
              $cmd->execCmd($p_options);
            }
            catch (\Exception $e) {   
              $v_result=0;       
            }
          }
          else {
            $v_result=0;
          }
        }
      }
      else {
        $cmd = cmd::byId(str_replace('#', '', $p_virtual_cmd));
        $cmd->execCmd($p_options);
        $v_result=0;
      }
      return($v_result);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeAlternative()
     * Description :
     *   look if mode is supported by device, if not, look for alternative.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpModeAlternative($p_mode) {
      // ----- No need for Central for now
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return($p_mode);
      }
      
      // TBC : Look and improve ?
      if ($this->cpGetConf('support_'.$p_mode) == 0) {
        centralepilote::log('debug',  "mode '".$p_mode."' not supported");
        if (($v_fallback = $this->cpGetConf('fallback_'.$p_mode)) != '') {
          $p_mode = $v_fallback;
          centralepilote::log('debug',  "fallback to '".$p_mode."'");
        }
        else {
          // TBC : should not occur
        }
      }
      
      return($p_mode);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpModeChangeTo()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpModeChangeTo($p_mode, $p_force=false) {
      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no change to mode '".$p_mode."' (".__FILE__.",".__LINE__.")");
        return;
      }
      
      if (!centralepilote::cpModeSupported($p_mode)) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' mode '".$p_mode."' not supported (".__FILE__.",".__LINE__.")");
        return;
      }
      
      if ($this->cpGetType() == 'radiateur') {

        // ----- Look if already the same mode        
        if (($this->cpModeGetFromCmd() == $p_mode) && (!$p_force)) {
          centralepilote::log('debug',  "Equipement '".$this->getName()."' is already in mode '".$p_mode."'. skip.");
          return;
        }

        // ----- Look for alternative mode
        $p_mode = $this->cpModeAlternative($p_mode);
        
        $v_command = '';
        switch ($p_mode) {
          case 'confort':
            $v_command = $this->getConfiguration('command_confort', '');
          break;
          case 'confort_1':
            $v_command = $this->getConfiguration('command_confort_1', '');
          break;
          case 'confort_2':
            $v_command = $this->getConfiguration('command_confort_2', '');
          break;
          case 'eco':
            $v_command = $this->getConfiguration('command_eco', '');
          break;
          case 'horsgel':
            $v_command = $this->getConfiguration('command_horsgel', '');
          break;
          case 'off':
            $v_command = $this->getConfiguration('command_off', '');
          break;
        }
        
        // ----- Start the actions
        if ($v_command != '') {
          if ($this->cpExecuteVirtualCmd($v_command) === 1) {
            $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName($p_mode));
          }
          else {
            centralepilote::log('error',  "Impossible d'executer la commande : '".$v_command."'");
          }
        }
        else {
          centralepilote::log('warning',  "Impossible d'executer une commande vide pour '".$this->getName()."'");
          $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName($p_mode));
        }
        
      }

      else if ($this->cpGetType() == 'zone') {
        // ----- Get all radiateurs in zone and chage mode
        $v_list = centralepilote::cpRadList(['_isEnable'=>true, 'zone'=>$this->getId()]);
        foreach ($v_list as $v_rad) {
          $v_rad->cpModeChangeTo($p_mode);
        }
         
        // ----- Update zone status
        $this->checkAndUpdateCmd('etat', centralepilote::cpModeGetName($p_mode));
      }
      
      else if ($this->cpGetType() == 'centrale') {
        return;
      }
      
      // ----- Update widget
      $this->refreshWidget();

      centralepilote::log('info',  "Equipement '".$this->getName()."' change mode to '".$p_mode."'");
              
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpModeGetFromCmd()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpModeGetFromCmd() {
    
      $v_mode_name = $this->cpCmdGetValue('etat');
      // ----- At first enable of the eq the value will be empty
      if ($v_mode_name == '') {
        $v_mode = 'eco';
      }
      else {
        $v_mode = centralepilote::cpModeGetCodeFromName($v_mode_name);
      }
      return($v_mode);
    }
    /* -------------------------------------------------------------------------*/


    /**---------------------------------------------------------------------------
     * Method : cpPilotageIsZone()
     * Description :
     *   This function returns 'true' if the device is a radiateur and if it is
     *   inside a zone. And 'false' in any other cases.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageIsZone() {
      if ( $this->cpIsType('radiateur') && ($this->cpGetConf('zone') != '')) {
        return(true);
      }
      return(false);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageGetAdminValue()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageGetAdminValue() {
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageGetAdminValue() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return('eco');
      }

      $v_pilotage = $this->getConfiguration('pilotage', '');
      if (($v_pilotage != 'auto') && (!$this->cpModeExist($v_pilotage))) {
        // ----- force mode to default 'eco'
        centralepilote::log('debug', "Unexpected pilotage mode '".$v_pilotage."', force 'eco' (".__FILE__.",".__LINE__.")");
        $v_pilotage = 'eco';
      }
      
      return($v_pilotage);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageChangeTo()
     * Description :
     *   Change le mode de pilotage pour l'un des modes admin supportés : 
     *   'confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off', 'auto'.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageChangeTo($p_pilotage, $p_force=false) {
    
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageChangeTo() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Check valid $p_pilotage value
      if (!in_array($p_pilotage, array('confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off', 'auto'))) {
        centralepilote::log('debug',  "Erreur unknown pilotage mode '".$p_pilotage."' here (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Check that the device is enable
      if ((!$this->getIsEnable()) && (!$p_force)) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no change to pilotage mode '".$p_pilotage."' (".__FILE__.",".__LINE__.")");
        return;
      }

      if ($this->cpPilotageIsZone()) {
        // ---- Force if needed the pilotage by zone
        $this->cpPilotageChangeToZone();
        return;
      }
      
      // ----- Look if device is in bypass mode
      if (($v_bypass_type = $this->cpGetConf('bypass_type')) == 'delestage') {
        centralepilote::log('info',  "Equipement '".$this->getName()."' is in bypass mode '".$v_bypass_type."', exit from bypass mode before changing pilotage mode to '".$p_pilotage."'.");
        return;
      }
      
      // ----- Get current real pilotage mode
      $v_pilotage_current = $this->cpCmdGetValue('pilotage');
      
      // TBC : ici il ne faut pas comparer les modes admin mais le mode admin cible par rapport au mode réel
      // ----- look if already the same pilotage mode
      if (($v_pilotage_current == $p_pilotage) && (!$p_force)) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is already in pilotage mode '".$p_pilotage."', skip.");
        return;
      }
      
      centralepilote::log('info',  "Equipement '".$this->getName()."' change pilotage mode from '".$v_pilotage_current."' to '".$p_pilotage."'");
      
      // ----- Look for specific 'auto' pilotage mode
      if ($p_pilotage == 'auto') {
        // ----- Change info command
        $this->checkAndUpdateCmd('pilotage', 'auto');
        
        // ----- Force display of current programme
        $v_prog_id = $this->cpGetConf('programme_id');
        $this->cpPilotageProgSelect($v_prog_id);
                
        // ----- Call the clock tick to get the good mode depending on programme and clock
        // TBC : already done in previous
        //$this->cpRadClockTick();
      }
      
      // ----- Look for pilotage mode 'confort', 'confort_1', 'confort_2', 'eco', 'horsgel', 'off'
      else {
        if (!centralepilote::cpModeSupported($p_pilotage)) {
          centralepilote::log('debug',  "Equipement '".$this->getName()."' mode '".$p_pilotage."' not supported (".__FILE__.",".__LINE__.")");
          return;
        }

        // ----- Do the actions to change to the requested mode
        $this->cpModeChangeTo($p_pilotage, $p_force);

        // ----- Change info command
        $this->checkAndUpdateCmd('pilotage', $p_pilotage);
      }

      // ----- Store new pilotage admin status
      $this->setConfiguration('pilotage', $p_pilotage);
      
      // ----- Change commands visibility
      $this->cpCmdResetDisplay();
      
      // ----- Save data
      $this->save();              

      // ----- Update widget
      $this->refreshWidget();
    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageChangeToZone()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageChangeToZone() {
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageChangeToZone() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no change to pilotage by zone");
        return;
      }

      if ($this->cpGetType() != 'radiateur') {
        // Ignore change pilotage request, only radiateur have a zone att
        return(false);
      }
      
      // ----- Get zone
      $v_zone = $this->cpGetConf('zone');
      if ($v_zone == '') {
        centralepilote::log('debug', "!! Unexpected empty zone here (".__FILE__.",".__LINE__.")");
        return(false);
      }
      
      // ----- Look if device is in bypass mode
      if (($v_bypass_type = $this->cpGetConf('bypass_type')) == 'delestage') {
        centralepilote::log('info',  "Equipement '".$this->getName()."' is in bypass mode '".$v_bypass_type."', pilotage by zone will be active later.");
        $this->cpCmdResetDisplay();
        return;
      }

      // ----- Get the current mode of the zone
      $v_zone_object = eqLogic::byId($v_zone);
      if (!is_object($v_zone_object)) {
        centralepilote::log('debug', "!! Unexpected missing zone object '".$v_zone."' here (".__FILE__.",".__LINE__.")");
        return(false);
      }
      $v_mode_name = $v_zone_object->cpCmdGetValue('etat');
      
      centralepilote::log('info',  "Radiateur '".$this->getName()."' change pilotage to 'zone'");      

      // ----- Swap name to mode id (value in command is the name not the internal code)
      $v_mode = centralepilote::cpModeGetCodeFromName($v_mode_name);
      
      // ----- Apply the mode to the radiateur
      $this->cpModeChangeTo($v_mode, true);
            
      // ----- Change display of pilotage mode
      $this->checkAndUpdateCmd('pilotage', 'zone');
      
      // ----- Change commands visibility of the radiateur
      $this->cpCmdResetDisplay();
        
      // ----- Update widget
      $this->refreshWidget();
        
      return(true);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageExitFromZone()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageExitFromZone() {
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageExitFromZone() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no exit from pilotage by zone");
        return;
      }

      if ($this->cpGetType() != 'radiateur') {
        // Ignore change pilotage request
        return(false);
      }
      
      // ----- Look if device is in bypass mode
      if (($v_bypass_type = $this->cpGetConf('bypass_type')) == 'delestage') {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is in bypass mode '".$v_bypass_type."'");
        // ----- Do  not change the admin pilotage, but change the displayed value
        $this->checkAndUpdateCmd('pilotage', 'bypass');
 
        // ----- Update widget
        $this->refreshWidget();
        
        return;
      }

      // ----- Change to last stored admin pilotage mode
      $v_pilotage = $this->cpPilotageGetAdminValue();
      $this->cpPilotageChangeTo($v_pilotage);
      
      return(true);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageChangeToBypass()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageChangeToBypass($p_bypass_type, $p_bypass_mode) {
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageChangeToBypass() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no change to bypass mode.");
        return;
      }
      
      /*
      // ----- Look of device is a radiateur, inside a zone
      if ($this->cpPilotageIsZone()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is under a zone, mode will be changed by the zone.");
        return;
      }
      */
      
      //if ($p_bypass_mode == 'normal') {
      if ($p_bypass_type == 'no') {
        $this->cpPilotageExitFromBypass();
        return;
      }
      else if (($p_bypass_type == 'delestage') && ($p_bypass_mode == 'delestage')) {
        $v_mode = 'off';
      }
      else if (($p_bypass_type == 'delestage') && ($p_bypass_mode == 'eco')) {
        $v_mode = 'eco';
      }
      else if (($p_bypass_type == 'delestage') && ($p_bypass_mode == 'horsgel')) {
        $v_mode = 'horsgel';
      }
      else {
        centralepilote::log('debug',  "Error : unexpected bypass mode '".$p_bypass_mode."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      centralepilote::log('info',  "Radiateur or Zone '".$this->getName()."' change pilotage to 'bypass->".$v_mode."'");      
      
      // ----- Apply the mode to the radiateur
      $this->cpModeChangeTo($v_mode, true);
            
      // ----- Change display of pilotage mode
      $this->checkAndUpdateCmd('pilotage', 'bypass');
      
      // ----- Store bypass mode
      $this->setConfiguration('bypass_type', $p_bypass_type);
      $this->setConfiguration('bypass_mode', $p_bypass_mode);
      
      // ----- Change commands visibility
      $this->cpCmdResetDisplay();
      
      // ----- Save data
      $this->save();              

      // ----- Update widget
      $this->refreshWidget();
        
      return;
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageExitFromBypass()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageExitFromBypass() {
      centralepilote::log('info',  "Radiateur or Zone '".$this->getName()."' exit from 'bypass' mode.");      

      // ----- Store bypass mode
      $this->setConfiguration('bypass_type', 'no');
      $this->setConfiguration('bypass_mode', 'no');
      $this->save();

      // ----- Change to last stored admin pilotage mode
      $v_pilotage = $this->cpPilotageGetAdminValue();
      $this->cpPilotageChangeTo($v_pilotage);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageSetTriggerTime()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageSetTriggerTime($p_mode, $p_trigger_time) {
      centralepilote::log('debug', "[".$this->getName()."]->cpPilotageSetTriggerTime('".$p_mode."', '".$p_trigger_time."')");
      
      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, not possible to set a trigger. (".__FILE__.",".__LINE__.")");
        return;
      }

      if ($this->cpPilotageIsZone()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is in zone pilotage, not possible to set a trigger. (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Look if device is in bypass mode
      if (($v_bypass_type = $this->cpGetConf('bypass_type')) == 'delestage') {
        centralepilote::log('info',  "Equipement '".$this->getName()."' is in bypass mode, not possible to set a trigger.");
        return;
      }      
      
      // ----- Check $p_mode & $p_return_mode
      if (!centralepilote::cpPilotageExist($p_mode)) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' invalid mode for trigger.");
        return;
      }
      
      // ----- Get existing trigger list
      $v_trigger_list = $this->cpGetConf('trigger_list');
      centralepilote::log('debug', "Current trigger list : '".print_r($v_trigger_list,true)."'");
      
      // ----- Look for not list
      if (!is_array($v_trigger_list)) {
        $v_trigger_list = array();
      }

      // ----- Format date from mUNIX time      
      $v_date = date("Y-m-d-H-i", $p_trigger_time);        
      //centralepilote::log('debug', "date:".date("Y-m-d-H-i").", trigger date:".$v_date);
      
      // ----- Add new trigger
      $v_trigger_item = array();
      $v_trigger_item['type'] = 'trigger_time';
      $v_trigger_item['mode'] = $p_mode;
      $v_trigger_item['time'] = $v_date;
      
      // ----- Look if existing trigger
      if (isset($v_trigger_list[$v_date])) {
        centralepilote::log('info', "Equipement '".$this->getName()."', trigger at '".$v_date."' already exists, will be overrided.");
      }
      
      // ----- Add  in list and save
      $v_trigger_list[$v_date] = $v_trigger_item;
      ksort($v_trigger_list);
      $this->setConfiguration('trigger_list', $v_trigger_list);
      $this->save();
      
      // ----- Update widget
      $this->refreshWidget();
      
      centralepilote::log('info', "Equipement '".$this->getName()."', new trigger at '".$v_date."' change to mode '".$p_mode."'.");      
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageRemoveTrigger()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageRemoveTrigger($p_id) {
      centralepilote::log('debug', "[".$this->getName()."]->cpPilotageRemoveTrigger('".$p_id."')");
      
      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, not possible to delete a trigger. (".__FILE__.",".__LINE__.")");
        return;
      }

      if ($this->cpPilotageIsZone()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is in zone pilotage, not possible to delete a trigger. (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Look if device is in bypass mode
      if (($v_bypass_type = $this->cpGetConf('bypass_type')) == 'delestage') {
        centralepilote::log('info',  "Equipement '".$this->getName()."' is in bypass mode, not possible to delete a trigger.");
        return;
      }      
            
        // ----- Get existing trigger list
        $v_trigger_list = $this->cpGetConf('trigger_list');
        centralepilote::log('debug', "Current trigger list : '".print_r($v_trigger_list,true)."'");
        
        // ----- Look for not empty list
        if ((sizeof($v_trigger_list) == 0) || (!isset($v_trigger_list[$p_id]))) {
          centralepilote::log('debug', "No trigger with this id for this device.");
          return;
        }
        
        // ----- Remove the trigger
        unset($v_trigger_list[$p_id]);
             
        $this->setConfiguration('trigger_list', $v_trigger_list);
        $this->save();
        
        // ----- Update widget
        $this->refreshWidget();
        
        centralepilote::log('info', "Equipement '".$this->getName()."', remove trigger '".$p_id."'.");
      
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageProgSelect()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageProgSelect($p_selected_id=-1) {
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageProgSelect() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, no change in programme selection");
        return;
      }
      
      // ----- Load existing programme with this ID (if any)
      $v_prog = centralepilote::cpProgLoad($p_selected_id);
      
      // ----- No programme with this ID
      if ($v_prog !== null) {
      
        // ----- Logs
        centralepilotelog::log('info', "Equipement '".$this->getName()."' : Changing programmation to '".$v_prog['name']."' (".$v_prog['id'].")");
        
        // ----- Store new programme id
        $this->setConfiguration('programme_id', $v_prog['id']);
        $this->save();
        
        // ----- Change programme name and id in info command
        $this->checkAndUpdateCmd('programme_id', $v_prog['id']);
        $this->checkAndUpdateCmd('programme', $v_prog['name']);
        
        // ----- Force a 'tick' to update radiateur status (will be ignored if not in auto mode)
        $this->cpEqClockTick();
      }

      // ----- No programme with this ID
      else {
        centralepilote::log('debug',  "Unknown programmation id '".$v_prog['id']."'");
      }
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpPilotageProgRemove()
     * Description :
     *   Cette fonction est appelée lorsqu'un id de programme est supprimé.
     *   La fonction va voir si le device est concerné.Si oui il va remettre 
     *   le programme à 0 (defaut) et forcer le pilotage en mode "manuel".
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpPilotageProgRemove($p_prog_remove_id) {
      // ----- Only for 'radiateur' or 'zone'
      if (!$this->cpIsType(array('radiateur','zone'))) {
        centralepilote::log('debug', "This method cpPilotageProgRemove() should not be used for a device other than a radiateur/zone  '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Get the current program id for this device
      $v_prog_id = $this->cpGetConf('programme_id');
      
      if ($v_prog_id != $p_prog_remove_id) {
        // ----- device not using removed programm, nothing to do
        centralepilote::log('debug', "cpPilotageProgRemove() : device '".$this->getName()."' is using prog (".$v_prog_id."), not using removed prog (".$p_prog_remove_id."), nothing to do");
        return;
      }
      centralepilote::log('debug', "cpPilotageProgRemove() : device '".$this->getName()."' is using prog (".$v_prog_id."), change to default prog.");
      
      // ----- Load default programme
      $v_prog = centralepilote::cpProgLoad(0);
      if ($v_prog === null) {
        // ----- Should not occur : 0 is default programme
        centralepilote::log('debug', "cpPilotageProgRemove() : Fail to find a default programme here (".__FILE__.",".__LINE__.")");
        return;
      }
      
      // ----- Store new programme id
      $this->setConfiguration('programme_id', $v_prog['id']);
      $this->save();
      
      // ----- Change programme name and id in info command
      $this->checkAndUpdateCmd('programme_id', $v_prog['id']);
      $this->checkAndUpdateCmd('programme', $v_prog['name']);
      
      // ----- Force a 'tick' to update radiateur status (will be ignored if not in auto mode)
      $this->cpEqClockTick();
      
    }
    /* -------------------------------------------------------------------------*/



    /**---------------------------------------------------------------------------
     * Method : cpRadClockTick()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpRadClockTick($p_jour='', $p_heure='', $p_minute='') {
      // ----- Only for 'radiateur' 
      if (!$this->cpIsType('radiateur')) {
        centralepilote::log('debug', "This method cpRadClockTick() should not be used for not radiateur device '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, ignore clock tick");
        return;
      }
      
      $this->cpEqClockTick($p_jour, $p_heure, $p_minute);      
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpRadChangeToEnable()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpRadChangeToEnable() {
      // ----- Only for 'radiateur' 
      if (!$this->cpIsType('radiateur')) {
        centralepilote::log('debug', "This method cpRadChangeToEnable() should not be used for not radiateur device '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check current programme id of the device not empty
      /*
      $v_programme_id = $this->cpCmdGetValue('programme_id');
      if ($v_programme_id == '') {
        $this->checkAndUpdateCmd('programme_id', 0);
      }
      */
          
      // ----- Look for pilotage by zone
      if ($this->cpPilotageIsZone()) {
        $this->cpPilotageChangeToZone();
        return;
      }
      
      // ----- Force pilotage to the one stored in conf
      $v_pilotage = $this->cpGetConf('pilotage');
      $this->cpPilotageChangeTo($v_pilotage, true);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpRadGetZoneId()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpRadGetZoneId() {
      return($this->cpGetConf('zone'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpRadGetZoneName()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpRadGetZoneName() {
      // ----- Get the current mode of the zone
      if (($v_zone_id = $this->cpRadGetZoneId()) == '') {
        return('');
      }
      $v_zone_object = eqLogic::byId($v_zone_id);
      if (!is_object($v_zone_object)) {
        centralepilote::log('debug', "!! Unexpected missing zone object '".$v_zone_id."' here (".__FILE__.",".__LINE__.")");
        return('');
      }
      $v_zone_name = $v_zone_object->getName();
      return($v_zone_name);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpZoneChangeToEnable()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpZoneChangeToEnable() {
    
      // ----- Only for 'zone' 
      if (!$this->cpIsType('zone')) {
        centralepilote::log('debug', "This method cpZoneChangeToEnable() should not be used for not zone device '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }
          
      // ----- Force pilotage (hence mode) to the one stored in conf
      $v_pilotage = $this->cpGetConf('pilotage');
      $this->cpPilotageChangeTo($v_pilotage, true);
    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpZoneClockTick()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpZoneClockTick($p_jour, $p_heure, $p_minute='') {
      // ----- Only for 'zone' 
      if (!$this->cpIsType('zone')) {
        centralepilote::log('debug', "This method cpZoneClockTick() should not be used for not zone device '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      // ----- Check that the device is enable
      if (!$this->getIsEnable()) {
        centralepilote::log('debug',  "Equipement '".$this->getName()."' is disable, ignore clock tick");
        return;
      }

      $this->cpEqClockTick($p_jour, $p_heure, $p_minute);      
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqChangeToEnable()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqChangeToEnable() {    
      switch ($this->cpGetType()) {
        case 'radiateur' :
          $this->cpRadChangeToEnable();
        break;
        case 'zone' :
          $this->cpZoneChangeToEnable();
        break;        
      }    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqChangeToDisable()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqChangeToDisable() {
    
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqGetTemperatureCible()
     * Description :
     *   Get the target temperature for the mode $p_mode, or if $p_mode = '' 
     *   the current mode.
     *   Return '' if none or mode is 'off'.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqGetTemperatureCible($p_mode='') {
      if ($p_mode == '') {
        // ----- Get current mode 
        $v_mode = $this->cpModeGetFromCmd();
      }
      else {
        $v_mode = $p_mode;
      }
      
      // ----- Get target temperature for the mode for this radiateur (future)
      // TBC
      
      // ----- Get target temperature for the mode globally at the centrale level
      $v_value = centralepilote::cpCentraleGetConfig('temperature_'.$v_mode);
      //centralepilote::log('debug',  "Temp ".'temperature_'.$v_mode." '".$v_value."'.");
      
      return($v_value);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqGetTemperatureActuelle()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqGetTemperatureActuelle() {
      //centralepilote::log('debug',  "cpEqGetTemperatureActuelle()");
      
      $v_virtual_cmd = $this->cpGetConf('temperature');
      if ($v_virtual_cmd == '') {
        return('');
      }
      //centralepilote::log('debug',  "Virtual temp ".$v_virtual_cmd." ");
      
      $cmd = cmd::byId(str_replace('#', '', $v_virtual_cmd));
      if (!is_object($cmd)) {        
        return('');
      }
      $v_value = $cmd->execCmd($p_options);
      $v_value_round = round($v_value,1);
      //centralepilote::log('debug',  "Value '".$v_value."', rounded : '".$v_value_round."'");
      
      return($v_value_round);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqClockTick()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqClockTick($p_jour='', $p_heure='', $p_minute='') {
    
      // ----- Check only radiateur & zone
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return;
      }
      
      // ----- Look for empty info
      if ($p_jour == '') {
        $v_jour = date("N");  // lundi:1 ... dimanche:7
        $v_jour_nom = [1=>'lundi',2=>'mardi',3=>'mercredi',4=>'jeudi',5=>'vendredi',6=>'samedi',7=>'dimanche'];
        $p_jour = $v_jour_nom[$v_jour];
      }
      if ($p_heure == '') {
        $p_heure = date("G");  // de 0 à 23
      }
      if ($p_minute == '') {
        $p_minute = date("i");  // de 00 à 59
      }

      // ----- Regarder si l'équipement est en mode manuel (cmd)
      if (!is_object($v_cmd = $this->getCmd(null, 'pilotage'))) {
        // TBC : error 
        return;
      }
      $v_value = $v_cmd->execCmd();
      if ($v_value != 'auto') {
        centralepilote::log('debug',  "ClockTick ignored, device '".$this->getName()."' not in 'auto'  mode.");
        return;
      }

      // ----- Récupérer l'id programmation
      $v_prog_id = $this->cpGetConf('programme_id');
      
      centralepilote::log('debug',  "ClockTick current programme_id for '".$this->getName()."' is ".$v_prog_id);

      // ----- Récupérer le mode en fonction du tick horloge
      $v_mode = centralepilote::cpProgModeFromClockTick($v_prog_id, $p_jour, $p_heure, $p_minute);
      
      centralepilote::log('debug',  "ClockTick mode for '".$this->getName()."' is ".$v_mode);

      // ----- Appliquer le mode à l'équipement (fct qui check fenetre ouverte, etc )
      $this->cpModeChangeTo($v_mode);      
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqHasTrigger()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqHasTrigger() {
      $v_trigger_list = $this->cpGetConf('trigger_list');
      return((is_array($v_trigger_list) ? sizeof($v_trigger_list) : 0));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpEqClockTriggerTick()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpEqClockTriggerTick($p_now) {
    
      // ----- Check only radiateur & zone
      if (!$this->cpIsType(array('radiateur','zone'))) {
        return;
      }
      
      // ----- Get existing trigger list
      $v_trigger_list = $this->cpGetConf('trigger_list');
      //centralepilote::log('debug', "Current trigger list : '".print_r($v_trigger_list,true)."'");
      
      // ----- Look for triggers to do
      $v_flag_trigger = false;
      foreach ($v_trigger_list as $v_date => $v_trigger) {
        if ($p_now >= $v_date) {
          // ----- Do the action
          centralepilote::log('debug',  "At '".$p_now."', start trigger type '".$v_trigger['type']."', scheduled '".$v_trigger['time']."', mode '".$v_trigger['mode']."'");
          $this->cpPilotageChangeTo($v_trigger['mode']);
          
          // ----- Remove the trigger
          unset($v_trigger_list[$v_date]);
          $v_flag_trigger = true;
        }
        else {
          centralepilote::log('debug',  "At '".$p_now."', ignore trigger type '".$v_trigger['type']."', scheduled '".$v_trigger['time']."', mode '".$v_trigger['mode']."'");
        }
      }
      
      // ----- Look for list to update
      if ($v_flag_trigger) {
        $this->setConfiguration('trigger_list', $v_trigger_list);
        $this->save();
      }
        
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : cpNatureChangeTo()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function cpNatureChangeTo($p_nature) {
      // ----- Only for 'radiateur' 
      if (!$this->cpIsType('radiateur')) {
        centralepilote::log('debug', "This method cpNatureChangeTo() should not be used for not radiateur device '".$this->getName()."' here (".__FILE__.",".__LINE__.")");
        return;
      }

      centralepilote::log('debug', "Change fil-pilote nature of radiateur '".$this->getName()."' to '".$p_nature."'");
      
      // ----- Look for natures
      if ($p_nature == 'virtuel') {
        // TBC : I may check that commands exists ??
      }
      
      else if (($p_nature == '1_commutateur_c_o') || ($p_nature == '1_commutateur_c_h')) {
        // ----- Get commutateur id
        $v_eq_id = $this->cpGetConf('lien_commutateur');
        $v_eq_id = str_replace('#', '', $v_eq_id);
        $v_eq_id = str_replace('eqLogic', '', $v_eq_id);
        centralepilote::log('debug', "Commutateur is '".$v_eq_id."'");
        
        // ----- Look if eq exists
        /*
        No need , the code will fill empty values for commands
        if (($v_eq_id == '') || !is_object(($v_eq = eqLogic::byId($v_eq_id)))) {
          centralepilote::log('debug', "Fail to find an equipement with id '".$v_eq_id."', return to virtual.");
          $this->setConfiguration('nature_fil_pilote', 'virtuel');
          return;
        }
        */
                
        // ----- Get action command by logicalId
        $v_cmd_off_hname = '';
        $v_cmd_off = cmd::byEqLogicIdCmdName($v_eq_id, __('Off', __FILE__));
        if (!is_object($v_cmd_off)) {
          centralepilote::log('debug', "Fail to find cmd Off for eq '".$v_eq_id."'");
        }
        else {
          $v_cmd_off_hname = '#'.$v_cmd_off->getId().'#';
        }
        $v_cmd_on_hname = '';
        $v_cmd_on = cmd::byEqLogicIdCmdName($v_eq_id, __('On', __FILE__));
        if (!is_object($v_cmd_on)) {
          centralepilote::log('debug', "Fail to find cmd On for eq '".$v_eq_id."'");
        }
        else {
          $v_cmd_on_hname = '#'.$v_cmd_on->getId().'#';
        }
        $v_cmd_etat_hname = '';
        $v_cmd_etat = cmd::byEqLogicIdCmdName($v_eq_id, __('Etat', __FILE__));
        if (!is_object($v_cmd_etat)) {
          centralepilote::log('debug', "Fail to find cmd Etat for eq '".$v_eq_id."'");
        }
        else {
          $v_cmd_etat_hname = '#'.$v_cmd_etat->getId().'#';
        }
               
        // ----- Set command for confort
        $this->setConfiguration('command_confort', $v_cmd_off_hname);
        if ($v_cmd_etat_hname != '') {
          $this->setConfiguration('statut_confort', '('.$v_cmd_etat_hname.'==0)');
        }
        else {
          $this->setConfiguration('statut_confort', '');
        }
        
        if ($p_nature == '1_commutateur_c_o') {
          $this->setConfiguration('command_off', $v_cmd_on_hname);
          if ($v_cmd_etat_hname != '') {
            $this->setConfiguration('statut_off', '('.$v_cmd_etat_hname.'==1)');
          }
          else {
            $this->setConfiguration('statut_off', '');
          }
          
          // ----- Force modes
          $this->setConfiguration('support_confort', 1);
          $this->setConfiguration('support_confort_1', 0);
          $this->setConfiguration('support_confort_2', 0);
          $this->setConfiguration('support_eco', 0);
          $this->setConfiguration('support_horsgel', 0);
          $this->setConfiguration('support_off', 1);
        }
        else if ($p_nature == '1_commutateur_c_h') {
          $this->setConfiguration('command_horsgel', $v_cmd_on_hname);
          if ($v_cmd_etat_hname != '') {
            $this->setConfiguration('statut_horsgel', '('.$v_cmd_etat_hname.'==1)');
          }
          else {
            $this->setConfiguration('statut_horsgel', '');
          }

          // ----- Force modes
          $this->setConfiguration('support_confort', 1);
          $this->setConfiguration('support_confort_1', 0);
          $this->setConfiguration('support_confort_2', 0);
          $this->setConfiguration('support_eco', 0);
          $this->setConfiguration('support_horsgel', 1);
          $this->setConfiguration('support_off', 0);
        }
      }

      else if ($p_nature == '2_commutateur') {
        // ----- Get commutateur id A
        $v_eq_id_a = $this->cpGetConf('lien_commutateur_a');
        $v_eq_id_a = str_replace('#', '', $v_eq_id_a);
        $v_eq_id_a = str_replace('eqLogic', '', $v_eq_id_a);
        centralepilote::log('debug', "Commutateur A is '".$v_eq_id_a."'");
        
        // ----- Get commutateur id B
        $v_eq_id_b = $this->cpGetConf('lien_commutateur_b');
        $v_eq_id_b = str_replace('#', '', $v_eq_id_b);
        $v_eq_id_b = str_replace('eqLogic', '', $v_eq_id_b);
        centralepilote::log('debug', "Commutateur B is '".$v_eq_id_b."'");
        
        // ----- Get action command by logicalId
        $v_cmd_off_hname_a = '';
        $v_cmd_off_a = cmd::byEqLogicIdCmdName($v_eq_id_a, __('Off', __FILE__));
        if (!is_object($v_cmd_off_a)) {
          centralepilote::log('debug', "Fail to find cmd Off for eq '".$v_eq_id_a."', return to virtual.");
          $this->setConfiguration('nature_fil_pilote', 'virtuel');
          return;
        }
        else {
          $v_cmd_off_hname_a = '#'.$v_cmd_off_a->getId().'#';
        }
        $v_cmd_on_hname_a = '';
        $v_cmd_on_a = cmd::byEqLogicIdCmdName($v_eq_id_a, __('On', __FILE__));
        if (!is_object($v_cmd_on_a)) {
          centralepilote::log('debug', "Fail to find cmd On for eq '".$v_eq_id_a."', return to virtual.");
          $this->setConfiguration('nature_fil_pilote', 'virtuel');
          return;
        }
        else {
          $v_cmd_on_hname_a = '#'.$v_cmd_on_a->getId().'#';
        }
        $v_cmd_etat_hname_a = '';
        $v_cmd_etat_a = cmd::byEqLogicIdCmdName($v_eq_id_a, __('Etat', __FILE__));
        if (!is_object($v_cmd_etat_a)) {
          centralepilote::log('debug', "Fail to find cmd Etat for eq '".$v_eq_id_a."'");
        }
        else {
          $v_cmd_etat_hname_a = '#'.$v_cmd_etat_a->getId().'#';
        }
               
        // ----- Get action command by logicalId
        $v_cmd_off_hname_b = '';
        $v_cmd_off_b = cmd::byEqLogicIdCmdName($v_eq_id_b, __('Off', __FILE__));
        if (!is_object($v_cmd_off_b)) {
          centralepilote::log('debug', "Fail to find cmd Off for eq '".$v_eq_id_b."'");
        }
        else {
          $v_cmd_off_hname_b = '#'.$v_cmd_off_b->GetId().'#';
        }
        $v_cmd_on_hname_b = '';
        $v_cmd_on_b = cmd::byEqLogicIdCmdName($v_eq_id_b, __('On', __FILE__));
        if (!is_object($v_cmd_on_b)) {
          centralepilote::log('debug', "Fail to find cmd On for eq '".$v_eq_id_b."'");
        }
        else {
          $v_cmd_on_hname_b = '#'.$v_cmd_on_b->getId().'#';
        }
        $v_cmd_etat_hname_b = '';
        $v_cmd_etat_b = cmd::byEqLogicIdCmdName($v_eq_id_b, __('Etat', __FILE__));
        if (!is_object($v_cmd_etat_b)) {
          centralepilote::log('debug', "Fail to find cmd Etat for eq '".$v_eq_id_b."'");
        }
        else {
          $v_cmd_etat_hname_b = '#'.$v_cmd_etat_b->getId().'#';
        }
        
        // ----- Set action commands     
        $this->setConfiguration('command_confort', $v_cmd_off_hname_a.' && '.$v_cmd_off_hname_b);              
        $this->setConfiguration('command_eco', $v_cmd_on_hname_a.' && '.$v_cmd_on_hname_b);              
        $this->setConfiguration('command_horsgel', $v_cmd_off_hname_a.' && '.$v_cmd_on_hname_b);              
        $this->setConfiguration('command_off', $v_cmd_on_hname_a.' && '.$v_cmd_off_hname_b);              
        $this->setConfiguration('command_confort_1', '');              
        $this->setConfiguration('command_confort_2', '');              

        // ----- Set info commands   
        if (($v_cmd_etat_hname_a != '') && ($v_cmd_etat_hname_b != '')) {
          $this->setConfiguration('statut_confort', '('.$v_cmd_etat_hname_a.' == 0) && ('.$v_cmd_etat_hname_b.' == 0)');              
          $this->setConfiguration('statut_eco', '('.$v_cmd_etat_hname_a.' == 1) && ('.$v_cmd_etat_hname_b.' == 1)');              
          $this->setConfiguration('statut_horsgel', '('.$v_cmd_etat_hname_a.' == 0) && ('.$v_cmd_etat_hname_b.' == 1)');              
          $this->setConfiguration('statut_off', '('.$v_cmd_etat_hname_a.' == 1) && ('.$v_cmd_etat_hname_b.' == 0)');              
        }
        else if ($v_cmd_etat_hname_a != '') {
          // TBC : s'il manque l'un des deux alors pas de statut
        }
        else if ($v_cmd_etat_hname_b != '') {
          // TBC : s'il manque l'un des deux alors pas de statut
        }
        else {
        }
        $this->setConfiguration('statut_confort_1', '');              
        $this->setConfiguration('statut_confort_2', '');              

        // ----- Force modes
        $this->setConfiguration('support_confort', 1);
        $this->setConfiguration('support_confort_1', 0);
        $this->setConfiguration('support_confort_2', 0);
        $this->setConfiguration('support_eco', 1);
        $this->setConfiguration('support_horsgel', 1);
        $this->setConfiguration('support_off', 1);               
      }

      
    }
    /* -------------------------------------------------------------------------*/



}

class centralepiloteCmd extends cmd {
    /*     * *************************Attributs****************************** */
    /* Commandes pour un radiateur :
    *  De type'info' :
    *  mode : 'Confort', ...
    *  pilotage : 'manuel' ou 'prog'
    *  
    *  De type 'action' :
    *  
    */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        if ($this->getType() != 'action') {
			return;
		}                
        
        // ----- Get associated equipment
		$eqLogic = $this->getEqlogic();
                
        // ----- Get command logical id
        $v_logical_id = $this->getLogicalId();
        
        // ----- Look for refresh command
		if ($v_logical_id == 'refresh') {
			$eqLogic->cpRefresh();
			return;
		}

        // ---- Look fos specific cmds per object type
        if ($eqLogic->cpIsType('centrale')) {
          return($this->execute_centrale($eqLogic, $v_logical_id, $_options));
        }
        
        
        
		if ($v_logical_id == 'programme_select') {
          centralepilotelog::log('debug', 'Options : '.json_encode($_options).' !');
          if (isset($_options['select'])) {
			$eqLogic->cpPilotageProgSelect($_options['select']);
          }
          else {
            centralepilotelog::log('warning', "Missing option 'select' while receiving command '".$v_logical_id."'."); 
          }
		  return;
		}
        
		if ($v_logical_id == 'trigger') {
          centralepilotelog::log('debug', 'Options : '.json_encode($_options).' !');
          if (!isset($_options['trigger_type'])) {
            centralepilotelog::log('warning', "Missing option 'trigger_type' while receiving command '".$v_logical_id."'."); 
          }
          else if ($_options['trigger_type'] == 'trigger_time') {
            if (!isset($_options['mode'])) {
              centralepilotelog::log('warning', "Missing option 'mode' while receiving command '".$v_logical_id."'."); 
            }
            else if (!isset($_options['trigger_time'])) {
              centralepilotelog::log('warning', "Missing option 'trigger_time' while receiving command '".$v_logical_id."'."); 
            }
            else {
              $v_mode = $_options['mode'];
              $v_trigger_time = $_options['trigger_time'];
              $eqLogic->cpPilotageSetTriggerTime($v_mode, $v_trigger_time);
            }
          }
          else if ($_options['trigger_type'] == 'trigger_delete') {
            if (!isset($_options['id'])) {
              centralepilotelog::log('warning', "Missing option 'id' while receiving command '".$v_logical_id."'."); 
            }
            else {
              $eqLogic->cpPilotageRemoveTrigger($_options['id']);
            }
          }
		  return;
		}
        
		if ($v_logical_id == 'auto') {        
          $eqLogic->cpPilotageChangeTo($v_logical_id);
		  return;
		}

        // ----- Look for all other commands that should be a mode
        if (centralepilote::cpModeExist($v_logical_id)) {
          //$eqLogic->cpModeChangeTo($v_logical_id);
          $eqLogic->cpPilotageChangeTo($v_logical_id);
          return;
        }
        
        
        
        // ----- Trcik to generate a clock tick for dev
		if ($this->getName() == 'tick') {
			centralepilote::cpClockTick();
			return;
		}
        
		if ($v_logical_id == 'manuel') {        
          centralepilotelog::log('warning', 'This command '.$v_logical_id.' is deprecated !');    
		  return;
		}
		if ($v_logical_id == 'prog_select') {
          centralepilotelog::log('warning', 'This command '.$v_logical_id.' is deprecated !');    
			return;
		}

        centralepilotelog::log('error', 'Unknown command '.$v_logical_id.' !');        
    }
    
    public function execute_centrale($p_centrale, $p_logical_id, $_options) {

      $v_bypass_type = 'no';
      $v_bypass_mode = 'no';
      
      if ($p_logical_id == 'normal') {
        $p_centrale->checkAndUpdateCmd('etat', 'normal');
        centralepilotelog::log('info', "Change Centrale to mode 'normal'.");
        $v_bypass_type = 'no';
        $v_bypass_mode = 'no';
      }
      else if ($p_logical_id == 'eco') {
        $p_centrale->checkAndUpdateCmd('etat', 'eco');
        centralepilotelog::log('info', "Change Centrale to mode 'eco'.");
        $v_bypass_type = 'delestage';
        $v_bypass_mode = 'eco';
      }
      else if ($p_logical_id == 'horsgel') {
        $p_centrale->checkAndUpdateCmd('etat', 'horsgel');
        centralepilotelog::log('info', "Change Centrale to mode 'horsgel'.");
        $v_bypass_type = 'delestage';
        $v_bypass_mode = 'horsgel';
      }
      else if ($p_logical_id == 'delestage') {
        $p_centrale->checkAndUpdateCmd('etat', 'delestage');
        centralepilotelog::log('info', "Change Centrale to mode 'delestage'.");
        $v_bypass_type = 'delestage';
        $v_bypass_mode = 'delestage';
      }
      else {
        centralepilotelog::log('info', "Unexpected commad '".$p_logical_id."' for centrale.");
        return;
      }
      
      // ----- Update all equip
      $eqLogics = eqLogic::byType('centralepilote');
      foreach ($eqLogics as $v_eq) {
        $v_eq->cpPilotageChangeToBypass($v_bypass_type, $v_bypass_mode);
      }      
      
      $p_centrale->refreshWidget();
      

    }

    /*     * **********************Getteur Setteur*************************** */
}


