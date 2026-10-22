<?php
/**
 * Contrôles statiques sur les sources : syntaxe, et garde-fous contre le retour
 * de défauts déjà corrigés.
 */
require_once __DIR__.'/harness.php';
$racine = realpath(__DIR__.'/..');
$src    = file_get_contents(CP_SRC);

cp_test::suite('Syntaxe PHP de tous les fichiers');
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($racine));
$v_ko = array();
foreach ($it as $f) {
  if ($f->isFile() && substr($f->getFilename(), -4) === '.php' && strpos($f->getPathname(), '/.git/') === false) {
    exec('php -l '.escapeshellarg($f->getPathname()).' 2>&1', $o, $rc);
    if ($rc !== 0) { $v_ko[] = str_replace($racine.'/', '', $f->getPathname()); }
    $o = array();
  }
}
cp_test::eq('aucune erreur de syntaxe', array(), $v_ko);

cp_test::suite('Cohérence des versions');
preg_match("/define\('CP_VERSION',\s*'([^']+)'\)/", file_get_contents($racine.'/core/php/centralepilote_const.inc.php'), $m1);
$info = json_decode(file_get_contents($racine.'/plugin_info/info.json'), true);
cp_test::eq('CP_VERSION == info.json', $m1[1], $info['version'] ?? '(absent)');

cp_test::suite('Traductions');
$en = json_decode(file_get_contents($racine.'/core/i18n/en_US.json'), true);
cp_test::ok('en_US.json lisible par json_decode()', $en !== null);
cp_test::ok('clés préfixées par plugins/centralepilote/',
  $en !== null && count(array_filter(array_keys($en), function($k){ return strpos($k, 'plugins/centralepilote/') !== 0; })) === 0);

cp_test::suite('Défauts corrigés : non-régression statique');
$maj = file_get_contents($racine.'/plugin_info/install.php');
cp_test::ok('install.php compare les versions avec version_compare()',
  strpos($maj, "version_compare(\$v_version") !== false);
cp_test::ok('install.php ne compare plus les versions comme des chaînes',
  !preg_match("/\\\$v_version\s*<\s*'/", $maj));
cp_test::ok("cpCmdHide n'utilise plus la comparaison lâche == 0",
  strpos($src, 'cpCmdHide($v_mode, ($v_value==0))') === false);
cp_test::ok('round() de la température est protégé par is_numeric()',
  preg_match('/is_numeric\(\$v_value\)[^;]*\)\s*\{\s*return\(\x27\x27\);/s', $src) === 1
  || strpos($src, 'if (!is_numeric($v_value))') !== false);
cp_test::ok('postInsert de la centrale initialise temp_ref_*',
  strpos($src, "checkAndUpdateCmd('temp_ref_confort', 19)") !== false
  && strpos($src, "checkAndUpdateCmd('temperature_confort_1'") === false);
cp_test::ok('la sortie de bypass par "no" est conditionnée ($p_force_exit)',
  strpos($src, '$p_force_exit') !== false);
// eqLogic::remove() supprime les commandes AVANT d'appeler preRemove() : le refus
// doit donc être posé dans remove(), pas dans preRemove().
cp_test::ok('la suppression de la centrale est refusée dans remove()',
  preg_match('/function remove\(\).{0,200}cpIsType\(\x27centrale\x27\).{0,200}throw new Exception.{0,200}parent::remove\(\)/s', $src) === 1);
cp_test::ok('preRemove() ne porte plus ce refus (il serait trop tardif)',
  preg_match('/function preRemove\(\).{0,300}cpIsType\(\x27centrale\x27\)/s', $src) !== 1);
cp_test::ok('les commandes manquantes de la centrale sont recréées',
  strpos($src, 'function cpCentraleCheckCmd(') !== false
  && strpos(file_get_contents($racine.'/plugin_info/install.php'), 'cpCentraleCheckCmd()') !== false);
cp_test::ok('cron15() vide supprimée', strpos($src, 'function cron15(') === false);
cp_test::ok("raccourci de développement 'tick' supprimé",
  strpos($src, "getName() == 'tick'") === false);

cp_test::suite('Hygiène du source');
$nb = 0;
foreach (explode("\n", $src) as $l) { if (preg_match('#^\s*//\s*\$(this|v_|eq)#', $l)) $nb++; }
cp_test::eq('aucune ligne de code morte en commentaire //', 0, $nb);

exit(cp_test::bilan());
