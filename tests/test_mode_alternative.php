<?php
/** Repli de mode — point 17 (lot 1, C1). */
require_once __DIR__.'/harness.php';
cp_build_class('
  public static $logs = array();
  public $conf = array();
  public static function log($l, $m) { self::$logs[] = "[$l] $m"; }
  public function cpIsType($t) { return true; }
  public function getName() { return "EQ"; }
  public function cpGetConf($k) { return $this->conf[$k] ?? ""; }
', array('cpModeAlternative'));

$modes = array('confort','confort_1','confort_2','eco','horsgel','off');
function eq_avec($p_support, array $p_fallback = array()) {
  global $modes;
  $e = new centralepilote();
  foreach ($modes as $i => $m) {
    $e->conf['support_'.$m]  = substr($p_support, $i, 1);
    $e->conf['fallback_'.$m] = $p_fallback[$m] ?? '';
  }
  return $e;
}
function replis($p_eq) {
  global $modes; $r = array();
  foreach ($modes as $m) $r[$m] = $p_eq->cpModeAlternative($m);
  return $r;
}

cp_test::suite('cpModeAlternative');

// Replis par défaut posés par l'interface du banc de test
$fb = array('confort'=>'eco','eco'=>'confort','horsgel'=>'eco','off'=>'eco','confort_1'=>'confort','confort_2'=>'confort');

cp_test::eq('C/O avec replis : hors-gel -> off (ne reste pas en confort)',
  array('confort'=>'confort','confort_1'=>'confort','confort_2'=>'confort','eco'=>'confort','horsgel'=>'off','off'=>'off'),
  replis(eq_avec('100001', $fb)));

cp_test::eq('C/H avec replis : off -> hors-gel (ne chauffe pas pendant un délestage)',
  array('confort'=>'confort','confort_1'=>'confort','confort_2'=>'confort','eco'=>'confort','horsgel'=>'horsgel','off'=>'horsgel'),
  replis(eq_avec('100010', $fb)));

cp_test::eq('sans repli configuré : mode supporté le plus proche',
  array('confort'=>'confort','confort_1'=>'confort','confort_2'=>'eco','eco'=>'eco','horsgel'=>'horsgel','off'=>'off'),
  replis(eq_avec('100111')));

cp_test::eq('repli configuré mais non supporté -> ignoré',
  'off', eq_avec('100001', array('horsgel'=>'eco'))->cpModeAlternative('horsgel'));

cp_test::eq('mode supporté -> inchangé', 'eco', eq_avec('100111')->cpModeAlternative('eco'));

centralepilote::$logs = array();
cp_test::eq("aucun mode supporté -> '' (rien n'est exécuté)", '', eq_avec('000000')->cpModeAlternative('eco'));
cp_test::ok('aucun mode supporté -> warning journalisé',
  strpos(implode('', centralepilote::$logs), '[warning]') !== false);

exit(cp_test::bilan());
