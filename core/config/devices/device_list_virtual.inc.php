<?php 
/* Jeedom "Centrale-Pilote"
 *
 * Ce fichier contient la description des models d'objets supportés comme
 * fil-pilote nativement pas le plugin.
 * L'objectif étant de simplement avoir à les sélectionner sans être un expert 
 * de comment fonctionnent les commandes.
 * 
 * La structure des la suivante (si vous voulez ajouter des devices par vous même) :
 * - Chaque device a un nom arbitraire (le mieux c'est de mettre le nom du fabriquant et le model)
 * - Le nom du plugin qui le gère (un objet zigbee par exemple pourrait être 
 *   géré de façon differente par deux plugin différents)  
 * - Un clé de recherche 'search_by_config_value' qui contient la liste des 
 *   configurations à regarder et les valeurs qu'elles doivent avoir pour reconnaitre le device concerné. 
 * - Deux champs d'information ('manufacturer' et 'model') qui permettent un affichage plus convivial eventuellement
 * - Enfin la liste des 12 commandes nécessaire au plugin filpilote pour faire les actions.
 *   Chaque commande peut être d'un "type" différent (pour essayer de couvrir un maximum de cas de figure).
 *   Le type "single_cmd", veut dire que l'on n'a basoin que du nom de la commande 
 *   à lancer sur le device pour réaliser la commande.
 *   Le type "cmd_value", veut dire qu'il suffit de tester la valeur d'une seule 
 *   commande pour avoir le résultat. On y precise donc le nom de la commande et 
 *   la valeur de résultat attendu. C'est surtout utilisé pour savoir si un certain état est actif.
 *   Le type "double_cmd", va lancer les deux commandes (avec un &&)
 *   Le type "expression" est le plus générique. il va lancer l'expression demandée. Il est possible d'indique __HUMAN_NAME__ qui sera remplacé par la bonne valeur au moment de l'execustion.
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
 *         Il est vivement conseillé de vérifier la validité du format JSON de cette valeur
 *         par exemple en utilisant des outils de validation en ligne (en ajoutant {} autour si besoin)
 */


/*
  $v_json = <<<MYTEXT
    {
      "Adeo SIN-4-FP-21_EQU" : {
        "plugin_id" : "z2m",
        "search_by_config_value" : {
          "manufacturer" : "Adeo",
          "model" : "SIN-4-FP-21_EQU"
        },
        "manufacturer" : "Adeo",
        "model" : "SIN-4-FP-21_EQU",
        "commands" : {
          "command_confort" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
          "command_confort_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-1"},
          "command_confort_2" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-2"},
          "command_eco" : {"type":"single_cmd", "cmd":"pilot_wire_mode eco"},
          "command_horsgel" : {"type":"single_cmd", "cmd":"pilot_wire_mode frost_protection"},
          "command_off" : {"type":"single_cmd", "cmd":"pilot_wire_mode off"},
          
          "statut_confort" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
          "statut_confort_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-1"},
          "statut_confort_2" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-2"},
          "statut_eco" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"eco"},
          "statut_horsgel" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"frost_protection"},
          "statut_off" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"off"}
        }               
      },

      "NodOn SIN-4-FP-21" : {
        "plugin_id" : "z2m",
        "search_by_config_value" : {
          "manufacturer" : "NodOn",
          "model" : "SIN-4-FP-21"
        },
        "manufacturer" : "NodOn",
        "model" : "SIN-4-FP-21",
        "commands" : {
          "command_confort" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
          "command_confort_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-1"},
          "command_confort_2" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-2"},
          "command_eco" : {"type":"single_cmd", "cmd":"pilot_wire_mode eco"},
          "command_horsgel" : {"type":"single_cmd", "cmd":"pilot_wire_mode frost_protection"},
          "command_off" : {"type":"single_cmd", "cmd":"pilot_wire_mode off"},
          
          "statut_confort" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
          "statut_confort_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-1"},
          "statut_confort_2" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-2"},
          "statut_eco" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"eco"},
          "statut_horsgel" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"frost_protection"},
          "statut_off" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"off"}
        }               
      },


      "ceci_est_un_exemple" : {
        "plugin_id" : "ceci_est_un_exemple",
        "search_by_config_value" : {
          "config_name_1" : "value1",
          "config_name_2" : "value2"
        },
        "manufacturer" : "my_name",
        "model" : "my_name",
        "commands" : {
          "command_confort" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
          "command_confort_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-1"},
          "command_confort_2" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort_-2"},
          "command_eco" : {"type":"single_cmd", "cmd":"pilot_wire_mode eco"},
          "command_horsgel" : {"type":"single_cmd", "cmd":"pilot_wire_mode frost_protection"},
          "command_off" : {"type":"single_cmd", "cmd":"pilot_wire_mode off"},
          
          "statut_confort" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
          "statut_confort_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-1"},
          "statut_confort_2" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort_-2"},
          "statut_eco" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"eco"},
          "statut_horsgel" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"frost_protection"},
          "statut_off" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"off"},
          
          "exemple_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
          "exemple_2" : {"type":"double_cmd", "cmd_1":"pilot_wire_mode comfort", "cmd_2":"pilot_wire_mode comfort"},
          
          "exemple_info_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
          "exemple_info_2" : {"type":"expression", "expression":"(#__HUMAN_NAME__[pilot_wire_mode]# == 'off')"}
        }               
      }

    }
  MYTEXT;
*/

  $v_device_json = <<<MYTEXT

      "virtual" : {

        "FilPilote_simulated_1" : {
          "search_by_command_name" : [
            "confort",
            "confort_1",
            "confort_2",
            "eco",
            "horsgel",
            "off"
          ],
          "name" : "Simul Fil-pilote 6 cmd",
          "manufacturer" : "VB",
          "model" : "FP",
          "commands" : {
            "command_confort" : {"type":"single_cmd", "cmd":"confort"},
            "command_confort_1" : {"type":"single_cmd", "cmd":"confort_1"},
            "command_confort_2" : {"type":"single_cmd", "cmd":"confort_2"},
            "command_eco" : {"type":"single_cmd", "cmd":"eco"},
            "command_horsgel" : {"type":"single_cmd", "cmd":"horsgel"},
            "command_off" : {"type":"single_cmd", "cmd":"off"},
            
            "statut_confort" : {"type":"cmd_value", "cmd":"etat", "value":"confort"},
            "statut_confort_1" : {"type":"cmd_value", "cmd":"etat", "value":"confort_1"},
            "statut_confort_2" : {"type":"cmd_value", "cmd":"etat", "value":"confort_2"},
            "statut_eco" : {"type":"cmd_value", "cmd":"etat", "value":"eco"},
            "statut_horsgel" : {"type":"cmd_value", "cmd":"etat", "value":"horsgel"},
            "statut_off" : {"type":"cmd_value", "cmd":"etat", "value":"off"}
          }               
        },

        "FilPilote_simulated_2" : {
          "search_by_command_name" : [
            "confort",
            "eco",
            "horsgel",
            "off"
          ],
          "name" : "Simul Fil-pilote 4 cmd",
          "manufacturer" : "VB",
          "model" : "FP",
          "commands" : {
            "command_confort" : {"type":"single_cmd", "cmd":"confort"},
            "command_eco" : {"type":"single_cmd", "cmd":"eco"},
            "command_horsgel" : {"type":"single_cmd", "cmd":"horsgel"},
            "command_off" : {"type":"single_cmd", "cmd":"off"},
            
            "statut_confort" : {"type":"cmd_value", "cmd":"etat", "value":"confort"},
            "statut_eco" : {"type":"cmd_value", "cmd":"etat", "value":"eco"},
            "statut_horsgel" : {"type":"cmd_value", "cmd":"etat", "value":"horsgel"},
            "statut_off" : {"type":"cmd_value", "cmd":"etat", "value":"off"}
          }               
        },

        "ceci_est_un_exemple" : {
          "search_by_config_value" : {
            "config_name_1" : "value1",
            "config_name_2" : "value2"
          },
          "search_by_command_name" : [
            "command_name_1",
            "command_name_2"
          ],
          "name" : "ceci_est_un_exemple",
          "manufacturer" : "my_name",
          "model" : "my_name",
          "commands" : {
            "command_confort" : {"type":"single_cmd", "cmd":"confort"},
            "command_confort_1" : {"type":"single_cmd", "cmd":"confort_1"},
            "command_confort_2" : {"type":"single_cmd", "cmd":"confort_2"},
            "command_eco" : {"type":"single_cmd", "cmd":"eco"},
            "command_horsgel" : {"type":"single_cmd", "cmd":"horsgel"},
            "command_off" : {"type":"single_cmd", "cmd":"off"},
            
            "statut_confort" : {"type":"cmd_value", "cmd":"etat", "value":"confort"},
            "statut_confort_1" : {"type":"cmd_value", "cmd":"etat", "value":"confort_1"},
            "statut_confort_2" : {"type":"cmd_value", "cmd":"etat", "value":"confort_2"},
            "statut_eco" : {"type":"cmd_value", "cmd":"etat", "value":"eco"},
            "statut_horsgel" : {"type":"cmd_value", "cmd":"etat", "value":"horsgel"},
            "statut_off" : {"type":"cmd_value", "cmd":"etat", "value":"off"},
            
            "exemple_1" : {"type":"single_cmd", "cmd":"pilot_wire_mode comfort"},
            "exemple_2" : {"type":"double_cmd", "cmd_1":"pilot_wire_mode comfort", "cmd_2":"pilot_wire_mode comfort"},
            
            "exemple_info_1" : {"type":"cmd_value", "cmd":"pilot_wire_mode", "value":"comfort"},
            "exemple_info_2" : {"type":"expression", "expression":"(#__HUMAN_NAME__[pilot_wire_mode]# == 'off')"}
          }               
        }

      }

  MYTEXT;

?>


