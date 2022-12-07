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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>


<form class="form-horizontal">

    <fieldset>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Affichage des modes : }}
            <sup><i class="fa fa-question-circle tooltips" title="{{Choisir comment seront affichés les modes fil-pilote}}"></i></sup>
            </label>
            <div class="col-lg-5">

                <select class="configKey form-control" data-l1key="prog_display_mode">
                  <option value="icon_color">{{Icones et Couleurs}}</option>
                  <option value="icon">{{Icones}}</option>
                  <option value="color">{{Couleurs}}</option>
                </select>

            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Widgets par d&eacute;faut : }}</label>
            <div class="col-lg-5">
                <input type="checkbox" class="configKey form-control" data-l1key="standard_widget">
            </div>
        </div>


</fieldset>
</form>

<?php include_file('desktop', 'centralepilote_configuration', 'js', 'centralepilote'); ?>

