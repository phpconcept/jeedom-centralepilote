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
            <label class="col-sm-4 control-label">{{Nom de la zone}}</label>
            <div class="col-sm-8">
                <input type="text" class="cp_attr_zone eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de la zone}}"/>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-4 control-label" >{{Objet parent}}</label>
            <div class="col-sm-8">
                <select id="sel_object" class="cp_attr_zone eqLogicAttr form-control" data-l1key="object_id">
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
                    echo '<input type="checkbox" class="cp_attr_zone eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                  ?>
               </div>
           </div>
           
      	<div class="form-group">
      		<label class="col-sm-4 control-label">{{Activation & Visibilité}}</label>
      		<div class="col-sm-8">
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_zone eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_zone eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
      		</div>
      	</div>
      
      
    </div>
    
    <div class="col-sm-6">
    
      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Pilotage de la zone}}</div>
          </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">{{Modes supportées}}</label>
        <div class="col-sm-9">
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_confort" /> {{Confort}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_confort_1" /> {{Confort -1}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_confort_2" /> {{Confort -2}}</label><br>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_eco" /> {{Eco}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_horsgel" /> {{Hors-Gel}}</label>
          <label class="checkbox-inline"><input type="checkbox" class="cp_support_mode cp_attr_zone eqLogicAttr" data-l1key="configuration" data-l2key="support_off" /> {{Off}}</label>
        </div>
      </div>

    <div class="row form-group">
      <label class="col-sm-3 control-label">{{Température}}</label>
      <div class="col-sm-7">
        <textarea class="cp_attr_zone eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="temperature" style="height : 33px;" placeholder="{{Mesure de température associée au radiateur}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="temperature"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-sm-3 control-label">{{Notes}}</label>
      <div class="col-sm-9">
        <textarea class="cp_attr_zone eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="notes" style="height : 33px;" ></textarea>
      </div>
    </div>

    </div>
  </div>

  

             
</div>
      			
    
<?php
}
?>
