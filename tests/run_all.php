<?php
/**
 * Lance toutes les suites de tests du plugin centralepilote.
 *
 *   php tests/run_all.php
 *
 * Chaque suite s'exécute dans son propre processus PHP : elles construisent
 * toutes une classe bouchon nommée 'centralepilote', qui ne peut être définie
 * qu'une fois par processus.
 * Aucune dépendance : ni Jeedom, ni base de données, ni réseau.
 */
$v_suites = glob(__DIR__.'/test_*.php');
sort($v_suites);
$v_ko = 0;
foreach ($v_suites as $v_suite) {
  echo "\n########## ", basename($v_suite), "\n";
  passthru(PHP_BINARY.' '.escapeshellarg($v_suite), $v_rc);
  if ($v_rc !== 0) { $v_ko++; }
}
echo "\n==========================================\n";
if ($v_ko === 0) { echo count($v_suites), " suite(s) : tout est vert.\n"; exit(0); }
echo $v_ko, " suite(s) en échec sur ", count($v_suites), ".\n";
exit(1);
