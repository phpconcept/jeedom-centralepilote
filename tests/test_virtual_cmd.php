<?php
/** Validation et exécution des commandes physiques — lot 1 (C2). */
require_once __DIR__.'/harness.php';

/** Bouchon de la classe cmd de Jeedom. */
class cmd {
  public static $reg = array(), $executees = array();
  public $id, $type, $eq, $echec;
  public function __construct($p_id, $p_type, $p_eq, $p_echec = false) {
    $this->id = $p_id; $this->type = $p_type; $this->eq = $p_eq; $this->echec = $p_echec;
    self::$reg[$p_id] = $this;
  }
  public static function byId($p_id) { return self::$reg[(int)$p_id] ?? null; }
  public function getType() { return $this->type; }
  public function getEqLogic() { return $this->eq; }
  public function getHumanName() { return '[Test][Sim][cmd'.$this->id.']'; }
  public function execCmd($o = null) {
    if ($this->echec === 'exception') { throw new Exception('délai dépassé'); }
    if ($this->echec === 'error') { $x = null; return $x->foo(); }
    self::$executees[] = $this->id;
  }
}
class fake_eq { public $en; public function __construct($e) { $this->en = $e; } public function getIsEnable() { return $this->en; } }

cp_build_class('
  public static $logs = array();
  public static function log($l, $m) { self::$logs[] = "[$l] $m"; }
  public function getIsEnable() { return true; }
  public function getName() { return "EQ"; }
', array('cpVirtualCmdCheck', 'cpExecuteVirtualCmd'));

new cmd(10, 'action', new fake_eq(true));
new cmd(11, 'action', new fake_eq(true));
new cmd(12, 'info',   new fake_eq(true));
new cmd(13, 'action', new fake_eq(false));
new cmd(14, 'action', null);
new cmd(15, 'action', new fake_eq(true), 'exception');
new cmd(16, 'action', new fake_eq(true), 'error');

$eq = new centralepilote();
function essai($p_expr) {
  global $eq;
  cmd::$executees = array(); centralepilote::$logs = array();
  $r = $eq->cpExecuteVirtualCmd($p_expr);
  return array($r, cmd::$executees);
}

cp_test::suite('cpVirtualCmdCheck / cpExecuteVirtualCmd');
cp_test::eq('commande simple',            array(1, array(10)),     essai('#10#'));
cp_test::eq('deux commandes',             array(1, array(10, 11)), essai('#10# && #11#'));
cp_test::eq('espaces superflus',          array(1, array(10, 11)), essai(' #10#&&#11# '));
cp_test::eq('expression vide',            array(0, array()),       essai(''));
cp_test::eq('syntaxe incomplète',         array(0, array()),       essai('#10# &&'));
cp_test::eq('séparateur manquant',        array(0, array()),       essai('#10# #11#'));
cp_test::eq('nom humain non converti',    array(0, array()),       essai('#[Test][Sim][On]#'));
cp_test::eq('identifiant sans dièses',    array(0, array()),       essai('10'));
cp_test::eq('commande absente : RIEN n\'est exécuté', array(0, array()), essai('#10# && #99999#'));
cp_test::eq('commande de type info',      array(0, array()),       essai('#12#'));
cp_test::eq('équipement désactivé',       array(0, array()),       essai('#10# && #13#'));
cp_test::eq('équipement absent',          array(0, array()),       essai('#14#'));
cp_test::eq('exception à l\'exécution',   array(0, array()),       essai('#15#'));
cp_test::eq('Error à l\'exécution (PHP 7+)', array(0, array(10)),  essai('#10# && #16#'));

centralepilote::$logs = array();
$eq->cpExecuteVirtualCmd('#10# && #99999#');
cp_test::ok('erreur journalisée en ERROR', strpos(implode('', centralepilote::$logs), '[error]') !== false);

exit(cp_test::bilan());
