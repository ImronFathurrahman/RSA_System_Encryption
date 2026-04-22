<?php
class RSA {
  const P = 151;
  const Q = 173;
  const N = 26123;
  const PHI = 25800;
  const E = 16397;
  const D = 6533;

  public static function modPow($base, $exp, $mod) {
    $result = 1;
    $base = $base % $mod;
    while ($exp > 0) {
      if ($exp % 2 == 1) $result = ($result * $base) % $mod;
      $exp = intdiv($exp, 2);
      $base = ($base * $base) % $mod;
    }
    return $result;
  }

  public static function encryptChar($ascii) {
    return self::modPow($ascii, self::E, self::N);
  }

  // ARTIKEL MODE: sesuai artikel (mod256 -> 2-char hex)
  public static function encrypt($plaintext) {
    $out = '';
    foreach (str_split($plaintext) as $ch) {
      $c = self::encryptChar(ord($ch));
      $out .= str_pad(dechex($c % 256), 2, '0', STR_PAD_LEFT);
    }
    return $out;
  }

  // FULL MODE: proper RSA (full cipher -> 4-char hex, fully reversible)
  public static function encryptFull($plaintext) {
    $out = '';
    foreach (str_split($plaintext) as $ch) {
      $c = self::encryptChar(ord($ch));
      $out .= str_pad(dechex($c), 4, '0', STR_PAD_LEFT);
    }
    return $out;
  }

  public static function decryptFull($hex) {
    $out = '';
    foreach (str_split($hex, 4) as $chunk) {
      $m = self::modPow(hexdec($chunk), self::D, self::N);
      $out .= chr($m);
    }
    return $out;
  }

  // Reverse lookup for article mode (prioritizes a-z, 0-9)
  private static function buildLookup() {
    static $lut = null;
    if ($lut === null) {
      $lut = [];
      $priority = array_merge(range(ord('a'),ord('z')), range(ord('0'),ord('9')),
                              range(ord('A'),ord('Z')), range(32,126));
      foreach ($priority as $m) {
        $r = self::encryptChar($m) % 256;
        if (!isset($lut[$r])) $lut[$r] = $m;
      }
    }
    return $lut;
  }

  public static function decrypt($hex) {
    $lut = self::buildLookup();
    $out = '';
    foreach (str_split($hex, 2) as $chunk) {
      $r = hexdec($chunk);
      $out .= isset($lut[$r]) ? chr($lut[$r]) : '?';
    }
    return $out;
  }

  public static function encryptDetail($plaintext) {
    $steps = [];
    foreach (str_split($plaintext) as $ch) {
      $ascii  = ord($ch);
      $cipher = self::encryptChar($ascii);
      $red    = $cipher % 256;
      $steps[] = [
        'char' => $ch, 'ascii' => $ascii,
        'cipher' => $cipher, 'reduced' => $red,
        'hex' => str_pad(dechex($red), 2, '0', STR_PAD_LEFT),
      ];
    }
    return $steps;
  }

  public static function decryptDetail($hex) {
    $steps = [];
    // Detect mode by block size (4=full, 2=article)
    $isFullMode = false;
    if (strlen($hex) % 4 === 0 && strlen($hex) >= 4) {
      $c = hexdec(substr($hex, 0, 4));
      $m = self::modPow($c, self::D, self::N);
      $isFullMode = ($m >= 32 && $m <= 126 && $c > 255);
    }

    $bsize = $isFullMode ? 4 : 2;
    $lut   = self::buildLookup();

    foreach (str_split($hex, $bsize) as $chunk) {
      if ($isFullMode) {
        $c = hexdec($chunk);
        $m = self::modPow($c, self::D, self::N);
        $steps[] = [
          'hex' => $chunk, 'decimal' => $c, 'mode' => 'full',
          'char' => ($m >= 32 && $m <= 126) ? chr($m) : '?',
          'ascii' => $m, 'cipher' => $c,
          'formula' => "{$c}^".self::D." mod ".self::N." = {$m}",
          'note' => "Dekripsi RSA standar: M = C^d mod n",
        ];
      } else {
        $dec  = hexdec($chunk);
        $m    = isset($lut[$dec]) ? $lut[$dec] : 0;
        $c_orig = $m > 0 ? self::encryptChar($m) : 0;
        $steps[] = [
          'hex' => $chunk, 'decimal' => $dec, 'mode' => 'article',
          'char' => $m > 0 ? chr($m) : '?',
          'ascii' => $m, 'cipher' => $c_orig,
          'formula' => "Cari m sehingga (m^".self::E." mod ".self::N.") mod 256 = {$dec}",
          'note' => $m > 0 ? "Ditemukan m={$m}, karakter='".chr($m)."'" : "Tidak ditemukan",
        ];
      }
    }
    return $steps;
  }

  public static function gcd($a, $b) {
    while ($b) { $t=$b; $b=$a%$b; $a=$t; }
    return $a;
  }
}
