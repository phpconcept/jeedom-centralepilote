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

try {
    //require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    require_once dirname(__FILE__) . "/../../../../plugins/centralepilote/core/php/centralepilote.inc.php";
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    
    ajax::init();

	if (init('action') == 'cpProgSave') {
		$v_val = centralepilote::cpProgSave(init('id'), init('prog'));
		//$return = array();
		//$return['count'] = $v_val;
      centralepilotelog::log('debug', "cpProgSave ajax pk");
		ajax::success($v_val);
	}

	if (init('action') == 'cpProgLoad') {
		$v_prog = centralepilote::cpProgLoad(init('id'));
        if (is_array($v_prog)) {
          $v_val = json_encode($v_prog, JSON_FORCE_OBJECT);
        }
        else {
          $v_val = "{}";
        }
		ajax::success($v_val);
	}

	if (init('action') == 'cpProgClean') {
		$v_val = centralepilote::cpProgClean();
		ajax::success(json_encode($v_val, JSON_FORCE_OBJECT));
	}

	if (init('action') == 'cpProgList') {
		$v_val = centralepilote::cpProgList();
		ajax::success(json_encode($v_val, JSON_FORCE_OBJECT));
	}

	if (init('action') == 'cpProgDelete') {
		$v_val = centralepilote::cpProgDelete(init('id'));
        if ($v_val) {
  		  ajax::success("{}");
        }
        else {
  		  ajax::error("{}");
        }
	}

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}

