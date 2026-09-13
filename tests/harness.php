<?php
/**
 * Banc de test hors Jeedom pour le plugin centralepilote.
 *
 * Principe : les méthodes à tester sont extraites telles quelles du fichier source
 * (core/class/centralepilote.class.php) et injectées dans une classe bouchon qui
 * fournit uniquement les dépendances nécessaires. Aucun Jeedom, aucune base de
 * données : les tests valident la logique métier pure.
 */

define('CP_SRC', __DIR__.'/../core/class/centralepilote.class.php');

/**
 * Extrait le code source de méthodes de la classe, par leur nom.
 * S'appuie sur la convention d'indentation du fichier : une méthode se termine
 * par une ligne contenant exactement quatre espaces puis '}'.
 */
function cp_extract_methods(array $p_names) {
  $v_lines = file(CP_SRC, FILE_IGNORE_NEW_LINES);
  if ($v_lines === false) {
    throw new Exception('Source introuvable : '.CP_SRC);
  }
  $v_code = '';
  foreach ($p_names as $v_name) {
    $v_start = null;
    foreach ($v_lines as $i => $v_line) {
      if (strpos($v_line, 'function '.$v_name.'(') !== false) { $v_start = $i; break; }
    }
    if ($v_start === null) {
      throw new Exception("Méthode introuvable dans la source : ".$v_name);
    }
    $v_end = null;
    for ($i = $v_start + 1; $i < count($v_lines); $i++) {
      if (rtrim($v_lines[$i]) === '    }') { $v_end = $i; break; }
    }
    if ($v_end === null) {
      throw new Exception("Fin de méthode introuvable : ".$v_name);
    }
    $v_code .= implode("\n", array_slice($v_lines, $v_start, $v_end - $v_start + 1))."\n";
  }
  return $v_code;
}

/** Construit la classe bouchon 'centralepilote' : $p_stub + les méthodes extraites. */
function cp_build_class($p_stub, array $p_methods) {
  if (class_exists('centralepilote', false)) {
    throw new Exception('La classe bouchon a déjà été construite dans ce processus.');
  }
  eval('class centralepilote { '.$p_stub."\n".cp_extract_methods($p_methods).' }');
}

/* ---------------------------------------------------------------- assertions */
class cp_test {
  public static $ok = 0, $ko = 0, $suite = '';
  public static function suite($p_name) { self::$suite = $p_name; echo "\n== ", $p_name, "\n"; }
  public static function eq($p_label, $p_expected, $p_actual) {
    $v_e = var_export($p_expected, true); $v_a = var_export($p_actual, true);
    if ($v_e === $v_a) { self::$ok++; printf("   ok   %s\n", $p_label); }
    else { self::$ko++; printf("  ECHEC %s\n        attendu : %s\n        obtenu  : %s\n", $p_label, $v_e, $v_a); }
  }
  public static function ok($p_label, $p_cond) { self::eq($p_label, true, (bool)$p_cond); }
  public static function bilan() {
    printf("\n---- %d test(s) réussi(s), %d échec(s)\n", self::$ok, self::$ko);
    return self::$ko === 0 ? 0 : 1;
  }
}
