<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('centralepilote');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

  // ----- Passage des infos d'icone et de couleur pour la programmation
  // TBC : à configurer dans les propriétés du plugin
  sendVarToJS('g_prog_mode_display', 'icon');   // 'icon' ou 'color'


?>
<script type="text/javascript">

  $(document).ready(function() {
    // do this stuff when the HTML is all ready
    refreshDeviceList();
       
    
    
  });

</script>


<div class="row row-overflow">
   <div class="col-xs-12 eqLogicThumbnailDisplay">
  <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
  
<?php 
if (0) {
?>  
      <div class="cursor eqLogicAction logoPrimary" data-action="cp_add_centrale">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter Centrale}}</span>
      </div>
<?php 
}
?>  

      <div class="cursor eqLogicAction logoPrimary" data-action="cp_add_radiateur">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter Radiateur}}</span>
      </div>

      <div class="cursor eqLogicAction logoPrimary" data-action="cp_add_zone">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter Zone}}</span>
      </div>

<?php
  $v_id = '';
  foreach ($eqLogics as $eqLogic) {
    if ($eqLogic->getConfiguration('type', '') == 'centrale') {
      $v_id = $eqLogic->getId();
      break;
    }
  }
?>

<!-- 
Ici une ruse avec un div caché, car je veux utiliser la classe eqLogicAction pour garder le même style d'affichage
Mais j'ai besoin d'une classe  eqLogicDisplayCard, pour lancer l'ouverture de l'objet spécial "central".
Bon mais c'est juste pour que ce soit joli à l'affichage ...
-->
<!-- 
      <div class="cursor eqLogicDisplayCard logoSecondary" data-eqLogic_id="<?php echo $v_id; ?>" >
-->
      <div class="eqLogicDisplayCard" data-eqLogic_id="<?php echo $v_id; ?>" ></div>
      <div class="cursor eqLogicAction logoSecondary" onclick="$('.eqLogicDisplayCard[data-eqLogic_id=<?php echo $v_id; ?>]').click();" >
        <i class="fas fa-chalkboard-teacher"></i>
        <br>
        <span>{{Centrale Fil-Pilote}}</span>
      </div>

      <div class="cursor eqLogicAction logoSecondary" onclick="modal_programmation_display();">
        <i class="fa fa-calendar"></i>
        <br>
        <span>{{Programmations}}</span>
      </div>

      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
  </div>
<?php
// Here I moved this part of the display to a modal file : modal.device_list.php
// By doing that I can refresh the list automatically, for
// exemple when in inclusion mode

?>
        <div id="device_list"></div>
</div>



<?php
// This part if for displaying individual object (eqlogic)
?>


<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction cp_panel_radiateur_zone" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight cp_panel_radiateur_zone" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay" onClick="refreshDeviceList();"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">

    <div role="tabpanel" class="tab-pane active cp_panel_waiting">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
          <label class="control-label" ><i class="fa fa-spinner fa-spin"></i> {{Chargement en cours ...}}</label>
        </div>
        <div class="col-sm-4"></div>
      </div>
    </div>

    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <form class="form-horizontal">
        <fieldset>
          <input id="cp_id" type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
          <input id="cp_type" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" style="display : none;" />


<!-- Object display container -->
<div class="container-fluid">

  <!-- First Row -->
  <div class="row">
  
    <!-- Left column -->
    <div class="col-sm-6">

      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés Jeedom}}</div>
          </div>
      </div>

       <div class="form-group">
            <label class="col-sm-4 control-label">{{Nom}} <span class="cp_panel_radiateur">{{du radiateur}}</span><span class="cp_panel_zone">{{de la zone}}</span><span class="cp_panel_centrale">{{de la centrale}}</span></label>
            <div class="col-sm-8">
                <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="name" placeholder=""/>
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
      		<label class="col-sm-4 control-label"><span class="cp_panel_radiateur_zone">{{Activation}} & </span>{{Visibilité}}</label>
      		<div class="col-sm-8">
      			<label class="checkbox-inline cp_panel_radiateur_zone"><input type="checkbox" class="cp_attr_radiateur eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
      			<label class="checkbox-inline"><input type="checkbox" class="cp_attr_radiateur eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
      		</div>
      	</div>
            
    </div>
    <!-- End Left column -->
    
    <!-- Right column -->
    <div class="col-sm-6">
    
      <div class="form-group">
          <div class="col-sm-12">
                 <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés}} <span class="cp_panel_radiateur">{{du Radiateur}}</span><span class="cp_panel_zone">{{de la Zone}}</span><span class="cp_panel_centrale">{{de la Centrale}}</span></div>
          </div>
      </div>

      <div class="form-group cp_panel_radiateur_zone">
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

    <div class="row form-group cp_panel_radiateur_zone">
      <label class="col-sm-3 control-label">{{Température}}</label>
      <div class="col-sm-7">
        <textarea class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="temperature" style="height : 33px;" placeholder="{{Mesure de température associée au radiateur}}"></textarea>
      </div>
      <div class="col-sm-2">
        <a class="btn btn-default cursor cp_modal_select_cmd_info btn-sm" data-input="temperature"><i class="fas fa-list-alt"></i> {{Rechercher}}</a>
      </div>
    </div>


    <div class="row form-group cp_panel_radiateur">
      <label class="col-sm-3 control-label">{{Puissance}}</label>
      <div class="col-sm-9">
        <input type="text" class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="puissance" placeholder="{{Puissance en Watts du radiateur}}"/>
      </div>
    </div>


      <div class="form-group cp_panel_radiateur">
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

    <div class="row form-group cp_panel_radiateur_zone">
      <label class="col-sm-3 control-label">{{Sortie délestage}}</label>
      <div class="col-sm-9">
          <select class="cp_attr_radiateur eqLogicAttr form-control" data-l1key="configuration" data-l2key="delestage_sortie_delai">
            <option value="0">{{immédiate}}</option>
            <option value="5">{{délai 5 minutes}}</option>
            <option value="30">{{délai 30 minutes}}</option>
            <option value="60">{{délai 1h}}</option>
            <option value="90">{{délai 1h30}}</option>
            <option value="120">{{délai 2h}}</option>
            <option value="150">{{délai 2h30}}</option>
            <option value="180">{{délai 3h}}</option>
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
    <!-- Right column -->

  </div>
  <!-- End First row -->


  <!-- Additional rows depending on objects type -->
          <div class="cp_panel_radiateur" style="display:none;">
    	  <?php include_file('desktop', 'centralepilote_radiateur.inc', 'php', 'centralepilote'); ?>
          </div>
          <div id="cp_panel_zone" class="cp_panel_zone" style="display:none;">
    	  <?php include_file('desktop', 'centralepilote_zone.inc', 'php', 'centralepilote'); ?>
          </div>
          <div id="cp_panel_centrale" class="cp_panel_centrale" style="display:none;">
    	  <?php include_file('desktop', 'centralepilote_centrale.inc', 'php', 'centralepilote'); ?>
          </div>
          
</div>
<!-- End of Object display container -->
          
        </fieldset>
      </form>        
    </div>

    <div role="tabpanel" class="tab-pane" id="commandtab">
      <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a>
      <br/><br/>
      
      <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
              <tr>
                  <th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
    </div>
</div>

</div>




</div>


<?php include_file('desktop', 'centralepilote', 'js', 'centralepilote');?>
<?php include_file('core', 'plugin.template', 'js');?>
