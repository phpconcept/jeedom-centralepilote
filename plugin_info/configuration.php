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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../../plugins/centralepilote/core/php/centralepilote_const.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>


<form class="form-horizontal">

    <fieldset>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Widgets par d&eacute;faut : }}</label>
            <div class="col-lg-5">
                <input type="checkbox" class="configKey form-control" data-l1key="standard_widget">
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Icônes colorées pour les modes}} : </label>
            <div class="col-lg-5">
                <input type="checkbox" class="configKey form-control" data-l1key="mode_icon_color"> {{Tableau de bord}}
                <br>
                <input type="checkbox" class="configKey form-control" data-l1key="mode_icon_color_mobile"> {{Mobile}}
            </div>
        </div>
        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Github Version}} : <?php echo CP_VERSION;?></label>
        </div>


</fieldset>
</form>

<?php include_file('desktop', 'centralepilote_configuration', 'js', 'centralepilote'); ?>

