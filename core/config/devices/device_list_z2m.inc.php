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
 * - Un clé de recherche 'search_by_config_value' qui contient la liste des 
 *   configurations à regarder et les valeurs qu'elles doivent avoir pour reconnaitre le device concerné.
 * - Une alternative de clé de recherche est 'search_by_command_name', qui permet de 
 *   trouver un objet s'il a toutes les commandes listees
 * - Trois champs d'information ('name', 'manufacturer' et 'model').
 * - Enfin la liste des (jusqu'à) 12 commandes nécessaire au plugin filpilote pour faire les actions.
 *   Chaque commande peut être d'un "type" différent (pour essayer de couvrir un maximum de cas de figure).
 *   Explication des types supportés (pour les 'commandes') :
 *   "single_cmd" : Simplement le nom de la commande à appeler dans 'cmd'.
 *   "double_cmd" : Simplement le nom des 2 commandes à appeler en les combinants avec un &&.
 *   "expression" : Une expression à executer. Le mot clé '__HUMAN_NAME__' sera 
 *   automatiquement remplacé par le vrai nom de l'équipement concerné au moment de l'execution.
 *   Explication des types supportés (pour les 'status') :
 *   'cmd_value' : On récupère la valeur de la commande 'cmd' et on compare la valeur à 'value'.
 *   'double_cmd_value_and' : On récupère les valeurs des deux commandes et on les comparent avec leurs valeurs avec un && pour les combiner.
 *   'double_cmd_value_or' : On récupère les valeurs des deux commandes et on les comparent avec leurs valeurs avec un || pour les combiner.
 *   "expression" : Une expression à executer. Le mot clé '__HUMAN_NAME__' sera 
 *   automatiquement remplacé par le vrai nom de l'équipement concerné au moment de l'execution. Le résultat doit être un booléen.
 *   
 * Exemple :
 * 
 *       "ceci_est_un_exemple" : {
 *         "search_by_config_value" : {
 *           "config_name_1" : "value1",
 *           "config_name_2" : "value2"
 *         },
 *         "search_by_command_name" : [
 *           "command_name_1",
 *           "command_name_2"
 *         ],
 *         "name" : "ceci_est_un_exemple",
 *         "manufacturer" : "my_name",
 *         "model" : "my_name",
 *         "commands" : {
 *           "command_confort" : {"type":"single_cmd", "cmd":"la_commande_pour_confort"},
 *           "command_confort_1" : {"type":"single_cmd", "cmd":"la_commande_pour_confort_1"},
 *           "command_confort_2" : {"type":"single_cmd", "cmd":"la_commande_pour_confort_2"},
 *           "command_eco" : {"type":"single_cmd", "cmd":"la_commande_pour_eco"},
 *           "command_horsgel" : {"type":"single_cmd", "cmd":"la_commande_pour_horsgel"},
 *           "command_off" : {"type":"single_cmd", "cmd":"la_commande_pour_off"},
 *           
 *           "statut_confort" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_confort"},
 *           "statut_confort_1" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_confort_1"},
 *           "statut_confort_2" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_confort_2"},
 *           "statut_eco" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_eco"},
 *           "statut_horsgel" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_horsgel"},
 *           "statut_off" : {"type":"cmd_value", "cmd":"la_commande_pour_l_etat", "value":"la_valeur_de_off"},
 *           
 *           "exemple_cmd_1" : {"type":"single_cmd", "cmd":"la_commande_a_utiliser"},
 *           "exemple_cmd_2" : {"type":"double_cmd", "cmd_1":"la_commande_a_utiliser", "cmd_2":"la_commande_a_utiliser"},
 *           "exemple_cmd_3" : {"type":"expression", "expression":"#__HUMAN_NAME__[la_commande_a_utiliser]#"},
 *
 *           "exemple_statut_1" : {"type":"cmd_value", "cmd":"la_commande_a_utiliser", "value":"la_valeur"},
 *           "exemple_statut_2" : {"type":"double_cmd_value_and", "cmd_1":"la_commande_a_utiliser_1", "value_1":"la_valeur_1", "cmd_2":"la_commande_a_utiliser_2", "value_2":"la_valeur_2"},
 *           "exemple_statut_3" : {"type":"double_cmd_value_or", "cmd_1":"la_commande_a_utiliser_1", "value_1":"la_valeur_1", "cmd_2":"la_commande_a_utiliser_2", "value_2":"la_valeur_2"},
 *           "exemple_statut_4" : {"type":"expression", "expression":"(#__HUMAN_NAME__[la_commande_a_utiliser]# == 'la_valeur')"}
 *         }               
 *       }
 *
 *    
 *         /!\ Attention /!\
 *         Si vous modifiez ce fichier,
 *         Il est vivement conseillé de vérifier la validité du format JSON de cette valeur
 *         par exemple en utilisant des outils de validation en ligne (en ajoutant {} autour si besoin)
 */
 
  $v_device_json = <<<MYTEXT

      "z2m" : {
      
        "Adeo SIN-4-FP-21_EQU" : {
          "search_by_config_value" : {
            "manufacturer" : "Adeo",
            "model" : "SIN-4-FP-21_EQU"
          },
          "name" : "Adeo SIN-4-FP-21_EQU",
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
          "search_by_config_value" : {
            "manufacturer" : "NodOn",
            "model" : "SIN-4-FP-21"
          },
          "name" : "NodOn SIN-4-FP-21",
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
        }

      }

  MYTEXT;

?>


