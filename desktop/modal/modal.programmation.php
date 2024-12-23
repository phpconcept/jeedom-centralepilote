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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

require_once dirname(__FILE__) . "/../../../../plugins/centralepilote/core/php/centralepilote.inc.php";

  $v_days = array('lundi' => __('Lundi', __FILE__), 
                  'mardi' => __('Mardi', __FILE__),
                  'mercredi' => __('Mercredi', __FILE__),
                  'jeudi' => __('Jeudi', __FILE__),
                  'vendredi' => __('Vendredi', __FILE__),
                  'samedi' => __('Samedi', __FILE__),
                  'dimanche' => __('Dimanche', __FILE__));

?>

<script>
    // ----- Récupération du paramètre passé en argument PHP
    var prog_id = <?php if (isset($_GET['prog_id'])) {echo json_encode($_GET['prog_id']);} else { echo 0;} ?>;
    //console.log('ID récupéré en PHP :', prog_id);
</script>


<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background-color: #039be5;">
            <h3 class="panel-title">{{Liste des Programmations}}

       <span class="dropdown pull-right" style="top: -2px !important; right: -6px !important;">
       <i class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>      
       <ul class="dropdown-menu">
         <li><label style="padding: 0px 5px;"><a id="cp_prog_delete_all">{{Détruire tous les programmes}}</a></label></li>
       </ul>
       </span>

            <a id="cp_prog_add" class="btn btn-success btn-xs pull-right" style="top: -2px !important; right: -6px !important;"><i class="fa fa-plus-circle icon-white"></i>&nbsp;&nbsp;{{Ajouter}}</a>
            </h3>
        </div>
        <div class="panel-body">
        
        
  <div class="row">
    <label class="col-sm-3 control-label" style="margin-left: 20px;">{{Sélectionner une programmation : }}</label>
    <div class="col-sm-7">
      <select id="cp_prog_select" class=" form-control">
        <option value="0">Filled by javascript</option>
     </select>
    </div>
  </div>
  <br>

        </div>
    </div>
</div>


<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading" style="background-color: #039be5;">
            <h3 class="panel-title">{{Programmation}}
            <a class="cp_mode_save btn btn-success btn-xs pull-right" style="top: -2px !important; right: -6px !important;"><i class="far fa-check-circle icon-white"></i>&nbsp;&nbsp;{{Sauvegarder}}</a>
            <a class="cp_mode_load btn  btn-xs pull-right" style="top: -2px !important; right: -6px !important;"><i class="fa fa-ban icon-white"></i>&nbsp;&nbsp;{{Annuler}}</a>
            <a class="cp_prog_delete btn btn-danger btn-xs pull-right" style="top: -2px !important; right: -6px !important;"><i class="fa fa-trash icon-white"></i>&nbsp;&nbsp;{{Supprimer}}</a>
            <a class="cp_mode_duplicate btn btn-success btn-xs pull-right" style="top: -2px !important; right: -6px !important;"><i class="fa fa-clone icon-white"></i>&nbsp;&nbsp;{{Dupliquer}}</a>
            </h3>
        </div>
        <div class="panel-body">


  <div class="row">
    <div class="form-group">
        <label class="col-sm-3 control-label" style="margin-left: 10px;">{{Nom de la programmation}}</label>
        <div class="col-sm-3">
            <input type="hidden" class="form-control " id="cp_prog_id">
            <input type="text" class="form-control" id="cp_prog_name">
        </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group">
        <label class="col-sm-3 control-label" style="margin-left: 10px;">{{Nom court (optionel)}}</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="cp_prog_short_name">
        </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group">
        <label class="col-sm-3 control-label" style="margin-left: 10px;">{{Précision}}</label>
        <div class="col-sm-3">
          <input type="hidden" class="form-control " id="cp_prog_mode_horaire">
          <select id="cp_prog_mode_horaire_select" class=" form-control" >
              <option value="horaire">{{Horaire}}</option>
              <option value="demiheure">{{Demi-Heure}}</option>
         </select>
        </div>        
        
    </div>
  </div>


  <div class="col-sm-12" id="cp_prog_table_horaire" style="overflow-x:scroll;">

  <div class="row">
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Agenda}}
      <div id="cp_prog_bt_horaire" class="btn-group pull-right">
      Filled by javascript
      </div>
      </div>
    </div>
  </div>

  <div class="row" >
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10">
    
    <table class="table table-bordered">
    <tbody>
     <tr>
     <td></td>

     <td>     
       <div class="dropdown">
       <i class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>      
       <ul class="dropdown-menu">
         <li><label style="padding: 0px 5px;"><a class="cp_mode_reset" href="#">{{Réinitialiser}}</a></label></li>
       </ul>
       </div>
     </td>

<?php

for ($hour = 0; $hour < 24; $hour++) {
    echo "<td style='text-align: center; vertical-align: middle;'>{$hour}h</td>";
}
  
?>

     </tr>
     
<?php 

/*
  $v_days = array('lundi' => __('Lundi', __FILE__), 
                  'mardi' => __('Mardi', __FILE__),
                  'mercredi' => __('Mercredi', __FILE__),
                  'jeudi' => __('Jeudi', __FILE__),
                  'vendredi' => __('Vendredi', __FILE__),
                  'samedi' => __('Samedi', __FILE__),
                  'dimanche' => __('Dimanche', __FILE__));
*/

  // ----- Get display mode
  //$v_display_mode = centralepilote::cpGetProgDisplayMode();
  
  // Display each day, each hour 
  foreach ($v_days as $v_day => $v_day_name) {
    echo '<tr><td>'.$v_day_name.'</td>';
    //echo '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>';
    
    echo '<td><div class="dropdown"><i class="fa fa-clone dropdown-toggle" data-toggle="dropdown"></i><ul class="dropdown-menu">';
    echo '<li><label class="cursor cp_prog_reset_line" style="padding: 0px 5px;" data-jour="'.$v_day.'">{{Réinitialiser}}</label></li>';
       
    foreach ($v_days as $v_day_copy => $v_day_name_copy) {
      if ($v_day_copy == $v_day) continue;
      echo '<li><label class="cursor cp_prog_copy_line" style="padding: 0px 5px;" data-jour="'.$v_day.'" data-jour_cible="'.$v_day_copy.'">{{Copier}} '.$v_day_name_copy.'</label></li>';
    }
       
    echo '</ul></div></td>';

/*    
    echo '<td><div class="dropdown"><i class="fa fa-clone dropdown-toggle" data-toggle="dropdown"></i>';
    echo '<div class="dropdown-menu" style="width:400px;">';
    echo '<div class="btn-group" >';
    foreach ($v_days as $v_day_copy => $v_day_name_copy) {
      if ($v_day_copy == $v_day) continue;
      //echo '<label class="cursor cp_prog_copy_line" style="padding: 0px 5px;" data-jour="'.$v_day.'" data-jour_cible="'.$v_day_copy.'">{{Copier}} '.$v_day_name_copy.'</label>-&nbsp;';
      echo '<button type="button" class="btn btn-xs cp_prog_copy_line" style="top: -1px !important; right: -6px !important;" data-jour="'.$v_day.'" data-jour_cible="'.$v_day_copy.'">'.$v_day_name_copy.'</button>';
    }
    echo '</div></div></div></td>';
*/    
    
    for ($i=0; $i<24; $i++) {
      // TBC
      $v_mode = 'eco';
      
/*      if ($v_display_mode == 'color') {
        $v_icon = '';
        $v_color = "background-color: ".centralepilote::cpModeGetColor($v_mode).";";
        $v_color_icon = "";
      }
      else {
      */
        $v_icon = centralepilote::cpModeGetIconClass($v_mode);
        $v_color = '';
        $v_color_icon = "color: ".centralepilote::cpModeGetColor($v_mode).";";
/*
      }
      */

      echo '<td style="'.$v_color.'"><i id="cp_'.$v_day.'_'.$i.'" style="'.$v_color_icon.'" class="'.$v_icon.' cp_mode_select cp_mode_'.$v_mode.'" data-mode_horaire="horaire" data-jour="'.$v_day.'" data-heure="'.$i.'" data-minute="00" data-mode="'.$v_mode.'"></i></td>';
    }
    echo '</tr>';
  }
?>

        </tbody>    
  </table>
            
    </div>
  </div>
  </div>


  <div class="col-sm-12" id="cp_prog_table_demiheure" style="overflow-x:scroll;">
  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Agenda}}
      <div id="cp_prog_bt_demiheure" class="btn-group pull-right" >
      Filled by javascript
      </div>
      </div>
    </div>
  </div>

            
  <div class="row">
    <div class="col-sm-12" >
    
    <table class="table table-bordered">
    <tbody>
     <tr>
     <td></td>

     <td>     
       <div class="dropdown">
       <i class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>      
       <ul class="dropdown-menu">
         <li><label style="padding: 0px 5px;"><a class="cp_mode_reset" href="#">{{Réinitialiser}}</a></label></li>
       </ul>
       </div>
     </td>

<?php
for ($hour = 0; $hour < 24; $hour++) {
    echo "<td style='text-align: center; vertical-align: middle;'>{$hour}h<br>00/30</td>";
}
?>

     </tr>
     
<?php 

/*
  $v_days = array('lundi' => __('Lundi', __FILE__), 
                  'mardi' => __('Mardi', __FILE__),
                  'mercredi' => __('Mercredi', __FILE__),
                  'jeudi' => __('Jeudi', __FILE__),
                  'vendredi' => __('Vendredi', __FILE__),
                  'samedi' => __('Samedi', __FILE__),
                  'dimanche' => __('Dimanche', __FILE__));
*/


  // ----- Get display mode
  //$v_display_mode = centralepilote::cpGetProgDisplayMode();
  
  // Display each day, each hour 
  foreach ($v_days as $v_day => $v_day_name) {
    echo '<tr><td>'.$v_day_name.'</td>';
    //echo '<td><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>';
    echo '<td><div class="dropdown"><i class="fa fa-clone dropdown-toggle" data-toggle="dropdown"></i><ul class="dropdown-menu">';
    echo '<li><label class="cursor cp_prog_reset_line" style="padding: 0px 5px;" data-jour="'.$v_day.'">{{Réinitialiser}}</label></li>';
       
    foreach ($v_days as $v_day_copy => $v_day_name_copy) {
      if ($v_day_copy == $v_day) continue;
      echo '<li><label class="cursor cp_prog_copy_line" style="padding: 0px 5px;" data-jour="'.$v_day.'" data-jour_cible="'.$v_day_copy.'">{{Copier}} '.$v_day_name_copy.'</label></li>';
    }
       
    echo '</ul></div></td>';
    
    for ($i=0; $i<24; $i++) {
      // TBC
      $v_mode = 'eco';
      
/*      if ($v_display_mode == 'color') {
        $v_icon = '';
        $v_color = "background-color: ".centralepilote::cpModeGetColor($v_mode).";";
        $v_color_icon = "";
      }
      else {
      */
        $v_icon = centralepilote::cpModeGetIconClass($v_mode);
        $v_color = '';
        $v_color_icon = "color: ".centralepilote::cpModeGetColor($v_mode).";";
/*
      }
      */

//      echo '<td style="'.$v_color.'"><i id="cp_'.$v_day.'_'.$i.'" style="'.$v_color_icon.'" class="'.$v_icon.' cp_mode_select cp_mode_'.$v_mode.'" data-jour="'.$v_day.'" data-heure="'.$i.'" data-minute="00" data-mode="'.$v_mode.'"></i></td>';

      echo '<td style="'.$v_color.'">';
      echo '<i id="cp_'.$v_day.'_'.$i.'_00" style="'.$v_color_icon.'" class="'.$v_icon.' cp_mode_select cp_mode_'.$v_mode.'" data-mode_horaire="demiheure" data-jour="'.$v_day.'" data-heure="'.$i.'_00" data-minute="00" data-mode="'.$v_mode.'"></i>';
      echo '/';
      echo '<i id="cp_'.$v_day.'_'.$i.'_30" style="'.$v_color_icon.'" class="'.$v_icon.' cp_mode_select cp_mode_'.$v_mode.'" data-mode_horaire="demiheure" data-jour="'.$v_day.'" data-heure="'.$i.'_30" data-minute="30" data-mode="'.$v_mode.'"></i>';
      echo '</td>';
    }
    echo '</tr>';
  }
?>

        </tbody>    
  </table>
            
    </div>
  </div>
  </div>


    </div>
  </div>
</div>


<div id="cp_debug_value" style="display:none;"></div>


<?php include_file('desktop', 'modal_programmation', 'js', 'centralepilote'); ?>

<script type="text/javascript">

  cp_prog_modal_init();
  
</script>

