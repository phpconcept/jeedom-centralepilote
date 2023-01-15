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


$plugin = plugin::byId('centralepilote');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());


?>
<script type="text/javascript">        
    
    $(document).ready(function ($) {


    });

</script>
  <legend><i class="fas fa-table"></i> {{Mes Equipements}}</legend>
	   <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
  <div class="eqLogicThumbnailContainer"></div>

<?php

  // ------------------------ Temporaire à supprimer
  if (0) {
?>
  
  <legend><i class="fas fa-table"></i> {{Centrales Fil-Pilote}}</legend>
  <div class="eqLogicThumbnailContainer">

<?php
  foreach ($eqLogics as $eqLogic) {
    if ($eqLogic->getConfiguration('type', '') == 'centrale') {
    	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
    	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
    	echo '<img src="' . $eqLogic->getImage() . '"/>';
    	echo '<br>';
    	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
    	echo '</div>';
    }
  }
?>
</div>
<?php
  // ------------------------
  }
?>

  <legend><i class="fas fa-table"></i> {{Radiateurs}}</legend>
  <div class="eqLogicThumbnailContainer">

<?php
  $v_list = centralepilote::cpRadList();
  //$v_list = centralepilote::cpRadList(['_isEnable'=>true]);
  foreach ($v_list as $eqLogic) {
  //foreach ($eqLogics as $eqLogic) {
    //if ($eqLogic->getConfiguration('type', '') == 'radiateur') {
    if (1) {
    	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
    	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
    	echo '<img src="' . $eqLogic->getImage() . '"/>';
    	echo '<br>';
    	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
    	echo '</div>';
    }
  }
?>
</div>

  <legend><i class="fas fa-table"></i> {{Zones}}</legend>
  <div class="eqLogicThumbnailContainer">

<?php
  foreach ($eqLogics as $eqLogic) {
    if ($eqLogic->getConfiguration('type', '') == 'zone') {
    	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
    	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '" >';
    	echo '<img src="' . $eqLogic->getImage() . '"/>';
    	echo '<br>';
    	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
    	echo '</div>';
    }
  }
?>
</div>


<?php include_file('desktop', 'centralepilote', 'js', 'centralepilote'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>

