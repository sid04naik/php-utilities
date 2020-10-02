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
  public function adjust_color_brightness($hexCode = "", $adjustPercent = "")
  {
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

  /**
   * *Generating random string either character or numbers
   * @param int  $length length of the string - default 6
   * @param int $chartype type of string to be generated character or number
   * @return string
   */
  public function generate_random_code($length = 6, $chartype = '')
  {
    if ($chartype == 1) {
      //only numbers
      $characters = '0123456789';
    } elseif ($chartype == 2) {
      //only alphabets
      $characters = 'abcdefghijklmnopqrstuvwxyz';
    } else {
      $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    }

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  /**
   * *Generating random token for any kind of authentication purpose
   * @param int  $length length of the string - default 64
   * @return string
   */
  public static function generate_token($length = 64)
  {
    return bin2hex(random_bytes($length));
  }

  /**
   * *Creating a url with a valid transfer protocol
   * @param string  $url url which runs in browser
   * @return string
   */
  public static function set_http_protocol($url = "")
  {
    return !preg_match("~^(?:f|ht)tps?://~i", $url) ? "http://" . $url : $url;
  }
}
