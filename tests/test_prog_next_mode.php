<?php
/** Prochain changement de programme — points 7 et 18 (lot 4, C14). */
require_once __DIR__.'/harness.php';
cp_build_class('
  public static $logs = array();
  public static $prog = null;
  public static function log($l, $m) { self::$logs[] = "[$l] $m"; }
  public static function cpProgLoad($p_id) { return self::$prog; }
', array('cpProgNextModeFromClockTick'));

$jours = array('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche');

function prog($p_mode, array $p_set = array()) {
  global $jours;
  $v = array('id'=>99, 'mode_horaire'=>$p_mode, 'agenda'=>array());
  foreach ($jours as $j) {
    for ($h = 0; $h < 24; $h++) {
      if ($p_mode == 'demiheure') { $v['agenda'][$j][$h.'_00'] = 'eco'; $v['agenda'][$j][$h.'_30'] = 'eco'; }
      else { $v['agenda'][$j][(string)$h] = 'eco'; }
    }
  }
  foreach ($p_set as $k => $m) { list($j, $c) = explode('|', $k); $v['agenda'][$j][$c] = $m; }
  return $v;
}
function next_mode($p_prog, $p_jour, $p_heure, $p_minute) {
  centralepilote::$prog = $p_prog; centralepilote::$logs = array();
  $m = ''; $j = ''; $t = '';
  $r = centralepilote::cpProgNextModeFromClockTick(99, $m, $j, $t, $p_jour, $p_heure, $p_minute);
  return array($r, $m, $j, $t);
}

cp_test::suite('cpProgNextModeFromClockTick');

$alt = prog('horaire');
foreach ($jours as $j) for ($h = 0; $h < 24; $h += 2) $alt['agenda'][$j][(string)$h] = 'confort';
cp_test::eq('horaire alterné, vendredi 15h07 -> confort 16h',
  array(true,'confort','','16h'), next_mode($alt, 'vendredi', '15', '07'));

cp_test::eq('demi-heure, changement dans ~141 h (ex-"Loop detected")',
  array(true,'confort','jeudi','12h30'),
  next_mode(prog('demiheure', array('jeudi|12_30'=>'confort')), 'vendredi', '15', '07'));

cp_test::eq('programme uniforme -> pas de changement (minute 07)',
  array(false,'','',''), next_mode(prog('horaire'), 'vendredi', '15', '07'));
cp_test::eq('programme uniforme -> pas de changement (minute 00)',
  array(false,'','',''), next_mode(prog('horaire'), 'vendredi', '15', '00'));

cp_test::eq('même jour la semaine suivante -> le jour est renseigné',
  array(true,'confort','vendredi','10h'),
  next_mode(prog('horaire', array('vendredi|10'=>'confort')), 'vendredi', '15', '07'));
cp_test::eq('changement dans la journée -> jour non renseigné',
  array(true,'confort','','16h'),
  next_mode(prog('horaire', array('vendredi|16'=>'confort')), 'vendredi', '15', '07'));
cp_test::eq('passage de minuit',
  array(true,'confort','samedi','2h'),
  next_mode(prog('horaire', array('samedi|2'=>'confort')), 'vendredi', '23', '07'));
cp_test::eq('passage dimanche -> lundi',
  array(true,'confort','lundi','1h'),
  next_mode(prog('horaire', array('lundi|1'=>'confort')), 'dimanche', '23', '07'));
cp_test::eq('demi-heure, créneau suivant',
  array(true,'confort','','15h30'),
  next_mode(prog('demiheure', array('vendredi|15_30'=>'confort')), 'vendredi', '15', '07'));
cp_test::eq('demi-heure, minuit dimanche->lundi',
  array(true,'confort','lundi','0h'),
  next_mode(prog('demiheure', array('lundi|0_00'=>'confort')), 'dimanche', '23', '40'));

$v = next_mode(array('id'=>99,'mode_horaire'=>'horaire','agenda'=>array()), 'vendredi', '15', '07');
cp_test::eq('agenda vide -> refus sans boucle', false, $v[0]);

exit(cp_test::bilan());
