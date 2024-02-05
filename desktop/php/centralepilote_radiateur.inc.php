<?php

/*
* Ce fichier est automatiquement inclu dans le fichier centralepilote.php
* 
* Il contient la partie configuration de l'équipement de type "radiateur"
*/
?>

  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés du Fil-pilote}}</div>
    </div>
  </div>

  <div class="form-group">
      <label class="col-lg-2 control-label">{{Constitution du Fil-pilote}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer comment est constitué le fil pilote, à partir de quels équipements et commandes.}}"></i></sup>
      </label>
      <div class="col-lg-10">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="nature_fil_pilote" onchange="cp_nature_change(event)">
        <option value="virtuel" selected>{{Virtuel}}</option>
        <option value="fp_device">{{Equipement natif fil-pilote}}</option>
        <option value="1_commutateur_c_o">{{Un commutateur modes Confort / Off}}</option>
        <option value="1_commutateur_c_h">{{Un commutateur modes Confort / Hors-Gel}}</option>
        <option value="2_commutateur">{{Deux commutateurs modes Confort / Eco / Hors-Gel / Off}}</option>

<?php 
/*
  $eqLogics = eqLogic::byType('z2m');
  foreach ($eqLogics as $v_eq) {
    $v_manuf = $v_eq->getConfiguration('manufacturer', '');
    //echo "Manufacturer : ".$v_manuf."<br>";
    $v_model = $v_eq->getConfiguration('model', '');
    //echo "Model : ".$v_model."<br>";
    if (($v_manuf == 'Adeo') && ($v_model == 'SIN-4-FP-21_EQU')) {
      echo '<option value="device_Adeo_SIN_4_FP_21_EQU">Fil-Pilote Adeo (SIN-4-FP-21_EQU)</option>';
    }
    if (($v_manuf == 'NodOn') && ($v_model == 'SIN-4-FP-21')) {
      echo '<option value="device_NodOn_SIN_4_FP_21">Fil-Pilote NodOn (SIN-4-FP-21)</option>';
    }
  }
*/
?>

      </select>
      </div>
  </div>


  <div class="row"><div class="col-sm-12">&nbsp;</div></div>

<div id="cp_disp_fp_device" style="display:none;">
  <div class="row">
  <div class="col-lg-6">
  
  <!-- 
  <div class="row">
      <label class="col-lg-4 control-label">{{Equipement associé}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer comment est constitué le fil pilote, à partir de quels équipements et commandes.}}"></i></sup>
      </label>
    <div class="col-sm-7">
      <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="fp_device_id" style="height : 33px;" placeholder="{{Référence équipement commutateur associé}}"></textarea>
      <a class="btn btn-default cursor cp_modal_select_equipement btn-sm" data-input="fp_device_id"><i class="fas fa-list-alt"></i> {{Rechercher équipement}}</a>
    </div>
    <div class="col-sm-1">
    </div>
  </div>
  -->


  <div class="row">
      <label class="col-lg-4 control-label">{{Equipement fil-pilote}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Choisir parmi la liste des équipements existants et supportés}}"></i></sup>
      </label>
    <div class="col-sm-7">

      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fp_device_id" onchange="cp_fp_device_change(event)">
        
<?php 
  
  $v_device_info_list = centralepilote::cpDeviceSupportedList();  
  
  $v_plugin_list = plugin::listPlugin(true);
  foreach ($v_plugin_list as $v_plugin) {
  	$v_plugin_id = $v_plugin->getId();
  	if (!isset($v_device_info_list[$v_plugin_id])) continue;
        
    //echo '<option value="xx">PlugIn : '.$v_plugin_id.'</option>';
    
    $eqLogics = eqLogic::byType($v_plugin_id);
    foreach ($eqLogics as $v_eq) {
      $v_device_info = centralepilote::cpDeviceSupportedInfo($v_eq);
      if ($v_device_info != null) {
        $v_human_name = $v_eq->getHumanName();
        $v_name = (isset($v_device_info['name'])?' ('.$v_device_info['name'].')':'');
        
        echo '<option value="#'.$v_human_name.'#">'.$v_human_name.$v_name.'</option>';
      }
    }
  }
  
?>

      </select>

    </div>
    <div class="col-sm-1">
    </div>
  </div>


  </div>
  <div class="col-lg-6">
  <img id="img_fp_device" src="plugins/centralepilote/desktop/images/fp_1_commutateur_c_o.png" style="display:none;"/>
  </div>
</div>
</div>

<div id="cp_disp_1_commutateur" style="display:none;">
  <div class="row">
  <div class="col-lg-6">
  <div class="row">
      <label class="col-lg-4 control-label">{{Equipement associé}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer comment est constitué le fil pilote, à partir de quels équipements et commandes.}}"></i></sup>
      </label>
    <div class="col-sm-7">
      <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="lien_commutateur" style="height : 33px;" placeholder="{{Référence équipement commutateur associé}}"></textarea>
      <a class="btn btn-default cursor cp_modal_select_equipement btn-sm" data-input="lien_commutateur"><i class="fas fa-list-alt"></i> {{Rechercher équipement}}</a>
    </div>
    <div class="col-sm-1">
    </div>
  </div>
  </div>
  <div class="col-lg-6">
  <img id="img_1_commutateur_c_o" src="plugins/centralepilote/desktop/images/fp_1_commutateur_c_o.png" style="display:none;"/>
  <img id="img_1_commutateur_c_h" src="plugins/centralepilote/desktop/images/fp_1_commutateur_c_h.png" style="display:none;"/>
  </div>
</div>
</div>

<div id="cp_disp_2_commutateur" style="display:none;">
  <div class="row">
  <div class="col-lg-6">
  <div class="row">
      <label class="col-lg-4 control-label">{{Equipement A}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer comment est constitué le fil pilote, à partir de quels équipements et commandes.}}"></i></sup>
      </label>
    <div class="col-sm-7">
      <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="lien_commutateur_a" style="height : 33px;" placeholder="{{Référence équipement commutateur associé}}"></textarea>
      <a class="btn btn-default cursor cp_modal_select_equipement btn-sm" data-input="lien_commutateur_a"><i class="fas fa-list-alt"></i> {{Rechercher équipement}}</a>
    </div>
    <div class="col-sm-1">
    </div>
  </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
  <div class="row">
      <label class="col-lg-4 control-label">{{Equipement B}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer comment est constitué le fil pilote, à partir de quels équipements et commandes.}}"></i></sup>
      </label>
    <div class="col-sm-7">
      <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="lien_commutateur_b" style="height : 33px;" placeholder="{{Référence équipement commutateur associé}}"></textarea>
      <a class="btn btn-default cursor cp_modal_select_equipement btn-sm" data-input="lien_commutateur_b"><i class="fas fa-list-alt"></i> {{Rechercher équipement}}</a>
    </div>
    <div class="col-sm-1">
    </div>
  </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
  </div>
  <div class="col-lg-6">
  <img src="plugins/centralepilote/desktop/images/fp_2_commutateurs.png"/>
  </div>
</div>
</div>
      			
<div id="cp_disp_virtuel" style="display:none;">

  <div class="row" >
  
      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Confort"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      
            
    <div id="cp_disp_support_confort" style="display:none;">
    
    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Confort"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Confort'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_confort" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_confort"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Confort"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Confort' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_confort" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_confort"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>

    </div>
    
    <div id="cp_disp_nosupport_confort" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Confort'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_confort" onchange="">
        <option value="eco">{{Eco}}</option>
        <option value="horsgel">{{Hors-Gel}}</option>
        <option value="off">{{Off}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
                    
      
              </div>
          </div>
      </div>
                    
  </div>






  <div class="row" >

      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Eco"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      
      
    <div id="cp_disp_support_eco" style="display:none;">
  
    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Eco"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Eco'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_eco" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_eco"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
      
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Eco"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Eco' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_eco" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_eco"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>
    </div>
    
    <div id="cp_disp_nosupport_eco" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Eco'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_eco" onchange="">
        <option value="confort">{{Confort}}</option>
        <option value="horsgel">{{Hors-Gel}}</option>
        <option value="off">{{Off}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
              </div>
          </div>
      </div>
                    
  </div>
  
  <div class="row" >

      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Hors-Gel"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      
      
  
    <div id="cp_disp_support_horsgel" style="display:none;">

    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Hors Gel"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Hors Gel'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_horsgel" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_horsgel"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
      
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Hors-Gel"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Hors-Gel' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_horsgel" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_horsgel"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>
    </div>
    
    
    <div id="cp_disp_nosupport_horsgel" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Hors-Gel'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_horsgel" onchange="">
        <option value="eco">{{Eco}}</option>
        <option value="confort">{{Confort}}</option>
        <option value="off">{{Off}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
              </div>
          </div>
      </div>
                    
  </div>
  
  <div class="row" >

      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Off"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      
      
  
    <div id="cp_disp_support_off" style="display:none;">

    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Off"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Off'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_off" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_off"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
      
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Off"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Off' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_off" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_off"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>
    </diV>

    <div id="cp_disp_nosupport_off" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Off'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_off" onchange="">
        <option value="eco">{{Eco}}</option>
        <option value="horsgel">{{Hors-Gel}}</option>
        <option value="confort">{{Confort}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
              </div>
          </div>
      </div>
                    
  </div>
  



  <div class="row" >
  
      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Confort -1"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      

    <div id="cp_disp_support_confort_1" style="display:none;">
    
    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Confort -1"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Confort -1'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_confort_1" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_confort_1"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Confort -1"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Confort -1' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_confort_1" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_confort_1"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>

    </div>
    
    <div id="cp_disp_nosupport_confort_1" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Confort -1'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_confort_1" onchange="">
        <option value="confort">{{Confort}}</option>
        <option value="eco">{{Eco}}</option>
        <option value="horsgel">{{Hors-Gel}}</option>
        <option value="off">{{Off}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
                    
      
              </div>
          </div>
      </div>
                    
  </div>




  <div class="row" >
  
      <div class="col-sm-1"></div>    
      <div class="col-md-11">
          <div class="panel panel-primary" >
              <div class="panel-heading" style="background-color: #039be5; padding: 2px 5px;" >
                 <div style=" padding: 2px 5px; color: white; ">{{Mode "Confort -2"}}</div>
              </div>
              <div class="panel-body" style="margin: 0px 10px;" >
      

    <div id="cp_disp_support_confort_2" style="display:none;">
    
    <div class="row">
      <label class="col-lg-12 ">{{Commandes pour réaliser le mode "Confort -2"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner la combinaison de commandes nécessaires pour réaliser la commande 'Confort -2'.}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="command_confort_2" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_command btn-sm" data-input="command_confort_2"><i class="fas fa-list-alt"></i> {{Rechercher commande}}</a>
      </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    <div class="row">
      <label class="col-lg-12 ">{{Calcul pour connaitre l'état du mode "Confort -2"}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Indiquer le calcul à réaliser pour savoir si le mode 'Confort -2' est actif (résultat binaire).}}"></i></sup>
      </label>
    </div>
  
    <div class="row">
      <div class="col-sm-10">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="statut_confort_2" style="height : 33px;" placeholder="{{Commandes à calculer}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="statut_confort_2"><i class="fas fa-list-alt"></i> {{Rechercher une info}}</a>
      </div>
    </div>

    </div>
    
    <div id="cp_disp_nosupport_confort_2" style="display:none;">
    <div class="row">
      <label class="col-lg-2 ">{{Mode alternatif}}
      <sup><i class="fa fa-question-circle tooltips" title="{{Sélectionner le mode alternatif à utiliser pour le mode 'Confort -2'.}}"></i></sup>
    </label>
      <div class="col-sm-2">
      <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="fallback_confort_2" onchange="">
        <option value="confort">{{Confort}}</option>
        <option value="eco">{{Eco}}</option>
        <option value="horsgel">{{Hors-Gel}}</option>
        <option value="off">{{Off}}</option>
      </select>
      
      </div>
    </div>
    </div>

    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
    
    
                    
      
              </div>
          </div>
      </div>
                    
  </div>

  
</div>


  <div class="row form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
  </div>


  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés avancées du Radiateur}}</div>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-12 control-label pull-left">{{Températures de référence :}}</label>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="radiateur_temperature_confort" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="radiateur_temperature_confort"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort -1 :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="radiateur_temperature_confort_1" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="radiateur_temperature_confort_1"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort -2 :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="radiateur_temperature_confort_2" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="radiateur_temperature_confort_2"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Eco :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="radiateur_temperature_eco" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="radiateur_temperature_eco"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Hors-Gel :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="radiateur_temperature_horsgel" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="radiateur_temperature_horsgel"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
  </div>


