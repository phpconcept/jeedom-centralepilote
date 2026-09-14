<?php
/** Robustesse des triggers horodatés — lot 6 (C23). */
require_once __DIR__.'/harness.php';
cp_build_class('
  public static $logs = array();
  public $conf = array();
  public $appliques = array();
  public static function log($l, $m) { self::$logs[] = "[$l] $m"; }
  public function cpIsType($t) { return true; }
  public function getName() { return "EQ"; }
  public function cpGetConf($k) { return $this->conf[$k] ?? ""; }
  public function setConfiguration($k, $v) { $this->conf[$k] = $v; }
  public function save() {}
  public function cpPilotageChangeTo($m) { $this->appliques[] = $m; }
', array('cpEqClockTriggerTick'));

function tick(array $p_list, $p_now = '2026-09-12-12-00') {
  $e = new centralepilote(); $e->conf['trigger_list'] = $p_list;
  centralepilote::$logs = array();
  $e->cpEqClockTriggerTick($p_now);
  return array($e->appliques, count($e->conf['trigger_list']));
}
$valide = array('type'=>'trigger_time', 'mode'=>'eco', 'time'=>'2026-09-12-10-00');

cp_test::suite('cpEqClockTriggerTick');
cp_test::eq('trigger échu -> appliqué puis retiré',
  array(array('eco'), 0), tick(array('2026-09-12-10-00'=>$valide)));
cp_test::eq('trigger à venir -> conservé',
  array(array(), 1), tick(array('2026-09-13-10-00'=>array('type'=>'trigger_time','mode'=>'eco','time'=>'2026-09-13-10-00'))));
cp_test::eq("clé 'time' absente -> la date sert de repli",
  array(array('eco'), 0), tick(array('2026-09-12-10-00'=>array('type'=>'trigger_time','mode'=>'eco'))));
cp_test::eq("clé 'mode' absente -> retiré sans être appliqué",
  array(array(), 0), tick(array('2026-09-12-10-00'=>array('type'=>'trigger_time','time'=>'x'))));
cp_test::eq('type inconnu -> retiré sans être appliqué',
  array(array(), 0), tick(array('2026-09-12-10-00'=>array('type'=>'autre','mode'=>'eco','time'=>'x'))));
cp_test::eq('valeur non tabulaire -> retirée',
  array(array(), 0), tick(array('2026-09-12-10-00'=>'nimportequoi')));
cp_test::ok('entrée invalide -> warning journalisé',
  strpos(implode('', centralepilote::$logs), '[warning]') !== false);
cp_test::eq('liste vide -> rien',
  array(array(), 0), tick(array()));
cp_test::eq('deux triggers, un seul échu',
  array(array('eco'), 1), tick(array(
    '2026-09-12-10-00'=>$valide,
    '2026-09-13-10-00'=>array('type'=>'trigger_time','mode'=>'confort','time'=>'2026-09-13-10-00'))));

exit(cp_test::bilan());
