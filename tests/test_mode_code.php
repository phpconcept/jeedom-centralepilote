<?php
/** Lecture du mode courant, indépendante de la langue — point 4 (lot 5, C19). */
require_once __DIR__.'/harness.php';
cp_build_class('
  public static $logs = array();
  public $vals = array();
  public static function log($l, $m) { self::$logs[] = "[$l] $m"; }
  public static function cpModeGetList($o = array()) {
    return array("confort"=>array("name"=>"Confort"), "confort_1"=>array("name"=>"Confort -1"),
                 "confort_2"=>array("name"=>"Confort -2"), "eco"=>array("name"=>"Eco"),
                 "horsgel"=>array("name"=>"Hors-Gel"), "off"=>array("name"=>"Arrêt"));
  }
  public static function cpModeExist($m) { return isset(self::cpModeGetList()[$m]); }
  public function cpCmdGetValue($id) { return $this->vals[$id] ?? ""; }
', array('cpModeGetFromCmd', 'cpModeGetCodeFromName'));

function lecture(array $p_vals) {
  $e = new centralepilote(); $e->vals = $p_vals; centralepilote::$logs = array();
  return $e->cpModeGetFromCmd();
}

cp_test::suite('cpModeGetFromCmd / cpModeGetCodeFromName');
cp_test::eq('mode_code présent -> utilisé',
  'horsgel', lecture(array('mode_code'=>'horsgel', 'etat'=>'Hors-Gel')));
cp_test::eq('équipement antérieur (mode_code vide) -> repli sur etat',
  'horsgel', lecture(array('etat'=>'Hors-Gel')));
cp_test::eq("équipement neuf -> '' (et non 'eco')",
  '', lecture(array()));
cp_test::eq('langue changée sans mode_code -> mode inconnu',
  '', lecture(array('etat'=>'Off-Gel')));
cp_test::ok('langue changée -> warning journalisé',
  strpos(implode('', centralepilote::$logs), '[warning]') !== false);
cp_test::eq('langue changée MAIS mode_code présent -> pas de dérive',
  'horsgel', lecture(array('mode_code'=>'horsgel', 'etat'=>'Off-Gel')));
cp_test::eq('mode_code corrompu -> repli sur etat',
  'eco', lecture(array('mode_code'=>'nimportequoi', 'etat'=>'Eco')));
cp_test::eq('libellé connu -> code', 'off', centralepilote::cpModeGetCodeFromName('Arrêt'));

exit(cp_test::bilan());
