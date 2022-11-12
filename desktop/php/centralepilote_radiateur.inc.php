<?php

/*
* Ce fichier est automatiquement inclu dans le fichier centralepilote.php
* 
* Il contient la partie configuration de l'équipement de type "radiateur"
*/
?>

<?php
if (0) {
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6">

      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés Jeedom}}</div>
          </div>
      </div>

       <div class="form-group">
            <label class="col-sm-4 control-label">{{Nom du radiateur}}</label>
            <div class="col-sm-8">
                <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du radiateur}}"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-4 control-label" >{{Objet parent}}</label>
            <div class="col-sm-8">
                <select id="sel_object" class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                      foreach (jeeObject::all() as $object) {
                      	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                      }
                    ?>
               </select>
           </div>
       </div>
           
	   <div class="form-group">
                <label class="col-sm-4 control-label">{{Catégorie}}</label>
                <div class="col-sm-8">
                 <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="cp_attr_radiateur eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                  ?>
               </div>
           </div>
           
      	<div class="form-group">
      		<label class="col-sm-4 control-label">{{Activation & Visibilité}}</label>
      		<div class="col-sm-8">
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_radiateur eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_radiateur eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
      		</div>
      	</div>
            
    </div>
    
    <div class="col-sm-6">
    
      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés du Radiateur}}</div>
          </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">{{Modes supportées}}</label>
        <div class="col-sm-9">
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_confort" /> {{Confort}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_confort_1" /> {{Confort -1}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_confort_2" /> {{Confort -2}}</label><br>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_eco" /> {{Eco}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_horsgel" /> {{Hors-Gel}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_radiateur eqLogicAttr" data-l1key="configuration" data-l2key="support_off" /> {{Off}}</label>
        </div>
      </div>

    <div class="row form-group">
      <label class="col-sm-3 control-label">{{Température}}</label>
      <div class="col-sm-7">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="temperature" style="height : 33px;" placeholder="{{Mesure de température associée au radiateur}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="temperature"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
      </div>
    </div>



      <div class="form-group">
        <label class="col-sm-3 control-label">{{Zone}}</label>
        <div class="col-sm-9">
          <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="zone">
            <option value="">{{Aucune}}</option>
<?php
  $eqLogics = centralepilote::cpZoneList();
  foreach ($eqLogics as $eqLogic) {
    echo '<option value="'.$eqLogic->getId().'">'.$eqLogic->getName().'</option>';
  }
?>
          </select>
        </div>
      </div>

    <div class="row form-group">
      <label class="col-sm-3 control-label">{{Notes}}</label>
      <div class="col-sm-9">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="notes" style="height : 33px;" ></textarea>
      </div>
    </div>


    </div>
  </div>
<?php
}
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
        <option value="1_commutateur_c_o">{{Un commutateur modes Confort / Off}}</option>
        <option value="1_commutateur_c_h">{{Un commutateur modes Confort / Hors-Gel}}</option>
        <option value="2_commutateur">{{Deux commutateurs modes Confort / Eco / Hors-Gel / Off}}</option>
      </select>
      </div>
  </div>

  <div class="row"><div class="col-sm-12">&nbsp;</div></div>

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
      			
         
<?php
if (0) {
?>
                
</div>

<?php
}
?>


