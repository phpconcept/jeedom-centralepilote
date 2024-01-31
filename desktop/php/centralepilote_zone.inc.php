<?php

/*
* Ce fichier est automatiquement inclu dans le fichier centralepilote.php
* 
* Il contient la partie configuration de l'équipement de type "radiateur"
*/
?>



  <div class="row form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
  </div>


  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés avancées de la Zone}}</div>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-12 control-label pull-left">{{Températures de référence :}}</label>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_zone eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="zone_temperature_confort" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="zone_temperature_confort"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort -1 :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_zone eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="zone_temperature_confort_1" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="zone_temperature_confort_1"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Confort -2 :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_zone eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="zone_temperature_confort_2" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="zone_temperature_confort_2"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Eco :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_zone eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="zone_temperature_eco" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="zone_temperature_eco"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Mode Hors-Gel :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_zone eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="zone_temperature_horsgel" placeholder="{{Par défaut utilise la valeur globale}}"/>
    </div>
    <div class="col-sm-2">
      <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm " data-input="zone_temperature_horsgel"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
  </div>


