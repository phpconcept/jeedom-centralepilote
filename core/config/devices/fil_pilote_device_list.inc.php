<?php 
/* Jeedom "Centrale-Pilote"
 *
 * Ce fichier contient la description des models d'objets support�s comme
 * fil-pilote nativement pas le plugin.
 * L'objectif �tant de simplement avoir � les s�lectionner sans �tre un expert 
 * de comment fonctionnent les commandes.
 * 
 * La structure des la suivante (si vous voulez ajouter des devices par vous m�me) :
 * - Chaque device a un nom arbitraire (le mieux c'est de mettre le nom du fabriquant et le model)
 * - Le nom du plugin qui le g�re (un objet zigbee par exemple pourrait �tre 
 *   g�r� de fa�on differente par deux plugin diff�rents)  
 * - Un cl� de recherche 'search_by_config_value' qui contient la liste des 
 *   configurations � regarder et les valeurs qu'elles doivent avoir pour reconnaitre le device concern�. 
 * - Deux champs d'information ('manufacturer' et 'model') qui permettent un affichage plus convivial eventuellement
 * - Enfin la liste des 12 commandes n�cessaire au plugin filpilote pour faire les actions.
 *   Chaque commande peut �tre d'un "type" diff�rent (pour essayer de couvrir un maximum de cas de figure).
 *   Le type "single_cmd", veut dire que l'on n'a basoin que du nom de la commande 
 *   � lancer sur le device pour r�aliser la commande.
 *   Le type "cmd_value", veut dire qu'il suffit de tester la valeur d'une seule 
 *   commande pour avoir le r�sultat. On y precise donc le nom de la commande et 
 *   la valeur de r�sultat attendu. C'est surtout utilis� pour savoir si un certain �tat est actif.
 *   Le type "double_cmd", va lancer les deux commandes (avec un &&)
 *   Le type "expression" est le plus g�n�rique. il va lancer l'expression demand�e. Il est possible d'indique __HUMAN_NAME__ qui sera remplac� par la bonne valeur au moment de l'execustion.
 *   
 * Exemple :
 * 
 *  "ceci_est_un_exemple" : {
 *    "plugin_id" : "ceci_est_un_exemple",
 *    "search_by_config_value" : {
 *      "config_name_1" : "value1",
 *      "config_name_2" : "value2"
 *    },
 *    "manufacturer" : "my_name",
 *    "model" : "my_name",
 *    "commands" : {
 *      "command_confort" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
 *      "command_confort_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-1"},
 *      "command_confort_2" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-2"},
 *      "command_eco" : {"type":"single_cmd", "cmd":"pilot_wire_mode eco"},
 *      "command_horsgel" : {"type":"single_cmd", "cmd":"pilot_wire_mode frost_protection"},
 *      "command_off" : {"type":"single_cmd", "cmd":"pilot_wire_mode off"},
 *      
 *      "statut_confort" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
 *      "statut_confort_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-1"},
 *      "statut_confort_2" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-2"},
 *      "statut_eco" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"eco"},
 *      "statut_horsgel" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"frost_protection"},
 *      "statut_off" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"off"},
 *      
 *      "exemple_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
 *      "exemple_2" : {"type":"double_cmd", "cmd_1":"pilot_wire_mode comfort", "cmd_2":"pilot_wire_mode comfort"},
 *      
 *      "exemple_info_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
 *      "exemple_info_2" : {"type":"expression", "expression":"(#__HUMAN_NAME__[pilot_wire_mode]# == 'off')"}
 *      }               
 *    }
 *    
 *         /!\ Attention /!\
 *         Si vous modifiez ce fichier,
 *         Il est vivement conseill� de v�rifier la validit� du format JSON de cette valeur
 *         par exemple en utilisant des outils de validation en ligne (en ajoutant {} autour si besoin)
 */

  static $v_device_list_json = '{';

  include dirname(__FILE__) . '/device_list_z2m.inc.php';
  $v_device_list_json .= $v_device_json;


  $v_device_list_json .= ',';
  include dirname(__FILE__) . '/device_list_virtual.inc.php';
  $v_device_list_json .= $v_device_json;


/*
  $v_device_list_json .= ',';
  include dirname(__FILE__) . '/device_list_SAMPLE.inc.php';
  $v_device_list_json .= $v_device_json;
*/

  $v_device_list_json .= '}';

?>


