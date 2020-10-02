<?php
/**
 * *Utility file which contains some most used and common php utility functions
 * *All the utility functions at one place
 * *Customizable
 * @author Siddhant Naik (Software Engineer)
 */
namespace Helpers\Utility;

/**
 * *Utility class for all utility functions
 */
class Utility
{
  /**
   * *Increases or decreases the brightness of a color by a percentage of the current brightness.
   * @param   string  $hexCode Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
   * @param   float   $adjustPercent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
   * @return  string
   */
  public static function adjustColorBrightness(
    $hexCode = "",
    $adjustPercent = ""
  ) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
      $hexCode =
        $hexCode[0] .
        $hexCode[0] .
        $hexCode[1] .
        $hexCode[1] .
        $hexCode[2] .
        $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as &$color) {
      $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
      $adjustAmount = ceil($adjustableLimit * $adjustPercent);

      $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
  }
}
