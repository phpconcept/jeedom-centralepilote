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
            <label class="col-sm-4 control-label">{{Nom de la Centrale Fil-Pilote}}</label>
            <div class="col-sm-8">
                <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de la Centrale Fil-Pilote}}"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-4 control-label" >{{Objet parent}}</label>
            <div class="col-sm-8">
                <select id="sel_object" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="object_id">
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
                    echo '<input type="checkbox" class="cp_attr_centrale eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                  ?>
               </div>
           </div>
           
      	<div class="form-group">
      		<label class="col-sm-4 control-label">{{Activation & Visibilité}}</label>
      		<div class="col-sm-8">
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_centrale eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_centrale eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
      		</div>
      	</div>
      
      
    </div>
    
    <div class="col-sm-6">
    
    </div>
    
  </div>
<?php
}
?>

  
  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Configuration de la Centrale Fil-Pilote}}</div>
    </div>
  </div>

    <div class="row form-group">
      <label class="col-sm-2 control-label">{{Température extérieure :}}</label>
      <div class="col-sm-8">
        <textarea class="cp_attr_centrale eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="temperature_externe" style="height : 33px;" ></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="temperature_externe"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-sm-2 control-label">{{Températures de référence :}}</label>
      <div class="col-sm-10">
      
        <div class="row form-group">
          <label class="col-sm-2 control-label">{{Mode Confort :}}</label>
          <div class="col-sm-1">
            <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature_confort"/>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label">{{Mode Confort -1 :}}</label>
          <div class="col-sm-1">
            <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature_confort_1"/>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label">{{Mode Confort -2 :}}</label>
          <div class="col-sm-1">
            <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature_confort_2"/>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label">{{Mode Eco :}}</label>
          <div class="col-sm-1">
            <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature_eco"/>
          </div>
        </div>
        <div class="row form-group">
          <label class="col-sm-2 control-label">{{Mode Hors-Gel :}}</label>
          <div class="col-sm-1">
            <input type="text" class="cp_attr_centrale eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature_horsgel"/>
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
    
