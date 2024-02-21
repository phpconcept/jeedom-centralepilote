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
 * - Un cl� de recherche 'search_by_config_value' qui contient la liste des 
 *   configurations � regarder et les valeurs qu'elles doivent avoir pour reconnaitre le device concern�.
 * - Une alternative de cl� de recherche est 'search_by_command_name', qui permet de 
 *   trouver un objet s'il a toutes les commandes listees
 * - Trois champs d'information ('name', 'manufacturer' et 'model').
 * - Enfin la liste des (jusqu'�) 12 commandes n�cessaire au plugin filpilote pour faire les actions.
 *   Chaque commande peut �tre d'un "type" diff�rent (pour essayer de couvrir un maximum de cas de figure).
 *   Explication des types support�s (pour les 'commandes') :
 *   "single_cmd" : Simplement le nom de la commande � appeler dans 'cmd'.
 *   "double_cmd" : Simplement le nom des 2 commandes � appeler en les combinants avec un &&.
 *   "expression" : Une expression � executer. Le mot cl� '__HUMAN_NAME__' sera 
 *   automatiquement remplac� par le vrai nom de l'�quipement concern� au moment de l'execution.
 *   Explication des types support�s (pour les 'status') :
 *   'cmd_value' : On r�cup�re la valeur de la commande 'cmd' et on compare la valeur � 'value'.
 *   'double_cmd_value_and' : On r�cup�re les valeurs des deux commandes et on les comparent avec leurs valeurs avec un && pour les combiner.
 *   'double_cmd_value_or' : On r�cup�re les valeurs des deux commandes et on les comparent avec leurs valeurs avec un || pour les combiner.
 *   "expression" : Une expression � executer. Le mot cl� '__HUMAN_NAME__' sera 
 *   automatiquement remplac� par le vrai nom de l'�quipement concern� au moment de l'execution. Le r�sultat doit �tre un bool�en.
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
 *         Il est vivement conseill� de v�rifier la validit� du format JSON de cette valeur
 *         par exemple en utilisant des outils de validation en ligne (en ajoutant {} autour si besoin)
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

        "FilPilote_simulated_3" : {
          "search_by_command_name" : [
            "Cmd_une",
            "Cmd_quatre"
          ],
          "name" : "Simul Fil-pilote pour tests",
          "manufacturer" : "VB",
          "model" : "FP",
          "commands" : {
            "command_confort" : {"type":"double_cmd", "cmd_1":"Cmd_une", "cmd_2":"Cmd_deux"},
            "command_eco" : {"type":"expression", "expression":"#__HUMAN_NAME__[Cmd_trois]#"},
            "command_horsgel" : {"type":"single_cmd", "cmd":"Cmd_quatre"},
            "command_off" : {"type":"single_cmd", "cmd":"Cmd_quatre"},
            
            "statut_confort" : {"type":"double_cmd_value_and", "cmd_1":"etat_1", "value_1":"cmd_1", "cmd_2":"etat_2", "value_2":"cmd_2"},
            "statut_eco" : {"type":"expression", "expression":"(#__HUMAN_NAME__[etat_1]# == 'cmd_3')"},
            "statut_horsgel" : {"type":"cmd_value", "cmd":"etat_1", "value":"cmd_4"},
            "statut_off" : {"type":"cmd_value", "cmd":"etat_1", "value":"cmd_4"}
          }               
        }

      }

  MYTEXT;

?>


