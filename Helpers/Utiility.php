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
  public function generate_token($length = 64)
  {
    return bin2hex(random_bytes($length));
  }

  /**
   * *Creating a url with a valid transfer protocol
   * @param string  $url url which runs in browser
   * @return string
   */
  public function set_http_protocol($url = "")
  {
    return !preg_match("~^(?:f|ht)tps?://~i", $url) ? "http://" . $url : $url;
  }

  /**
   * *Functions to traverse directory and its sub directories.
   * @param string  $dir directory which needs to traverse.
   * @return mixed[] list of all directories and files
   */
  public function traverse_directory($dir = "")
  {
    $separator = '/';
    $result = [];
    $currentDir = scandir($dir, 1);
    foreach ($currentDir as $key => $value) {
      if (!in_array($value, [".", ".."])) {
        if (is_dir($dir . $separator . $value)) {
          $result[$value] = $this->traverse_directory(
            $dir . $separator . $value
          );
        } else {
          $result[] = $value;
        }
      }
    }
    return $result;
  }

  /**
   * *Functions to delete directory and its files
   * @param string  $dir directory which needs to deleted.
   */
  public function delete_directory($dirPath = '')
  {
    if (!is_dir($dirPath)) {
      throw new InvalidArgumentException($dirPath . " must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
      if (is_dir($file)) {
        $this->delete_directory($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
  }

  /**
   * *Merging multiple array recursively
   * @param mixed[]  $arr $arr[0] and $arr[1] ... All the array to be merged
   * @return mixed[] merged array
   */
  public function multiple_array_merge_recursive($arr = "")
  {
    if (is_array($arr)) {
      $temp = $arr[0];
      foreach ($arr as $key => $each_slot) {
        if ($arr[$key + 1]) {
          $temp = array_merge_recursive($temp, $arr[$key + 1]);
        }
      }
      return $temp;
    }
    return '';
  }

  /**
   * *Structuring the array
   * @param mixed[] array( 0 => array('key1' => value1,'key2' => value2), 1 => array('key1' => value1,'key1' => value1) );
   * @return mixed[] structure array array('key1' => [0 => value1,1 => value2], 'key2' => [0 => value1, 1 => value2] );
   */
  public function array_structure($arr = [])
  {
    $arr_keys = array_keys($arr[0]);
    foreach ($arr_keys as $field) {
      foreach ($arr as $key => $data) {
        $new_arr[$field][$key] = $data[$field];
      }
    }
    return $new_arr;
  }

  /**
   * *Structuring the array
   * @param mixed[] array('key1' => [0 => value1,1 => value2], 'key2' => [0 => value1, 1 => value2] );
   * @param string $first_field key of the first field
   * @return mixed[] structure array array( 0 => array('key1' => value1,'key2' => value2), 1 => array('key1' => value1,'key1' => value1) );
   */
  public function array_structure_reverse($arr = [], $first_field = '')
  {
    $arr_keys = array_keys($arr);
    $new_structured_arr = [];
    foreach ($arr[$first_field] as $key => $val) {
      foreach ($arr_keys as $field) {
        $new_structured_arr[$key][$field] = $arr[$field][$key];
      }
    }
    return $new_structured_arr;
  }

  /**
   * *Multi dimensional sort
   * @param mixed[] $arr array to be sort array( 0 => array('key1' => value1,'key2' => value2,'key3' => value3));
   * @param string $sort_key key for key the sort should perform
   * @param int $sorting Sorting order
   * @return mixed[]
   */
  public function array_multi_dimension_sort(
    $arr = [],
    $sort_key = '',
    $sorting = SORT_DESC
  ) {
    foreach ($arr as $index => $my_arr) {
      $sorter[$index] = $arr[$index][$sort_key];
    }
    array_multisort($sorter, $sorting, $arr);
    return $arr;
  }

  /**
   * *Multi dimensional reverse sort
   * @param mixed[] $arr array to be sort
   * @param string $sort_key key for key the sort should perform
   * @param int $sorting Sorting order
   * @return mixed[]
   */
  public function array_multi_dimension_reverse_sort(
    $arr = [],
    $sort_key = '',
    $sorting = SORT_DESC
  ) {
    $new_structured_arr = $this->array_structure_reverse($arr, $sort_key);
    $new_structured_arr = $this->array_multi_dimension_sort(
      $new_structured_arr,
      $sort_key,
      $sorting
    );
    return $this->array_structure($new_structured_arr);
  }

  /**
   * *Set Cookie
   * @param string $name name of the cookie
   * @param string $value value which will be stored in the cookie
   * @param boolean $secure secure flag for the cookie
   * @param boolean $httponly httponly flag for the cookie
   */
  public function set_cookie(
    $name = "",
    $value = "",
    $secure = false,
    $httponly = false
  ) {
    setcookie(
      $name,
      $value,
      time() + 3600,
      "/",
      "",
      $secure ? 1 : 0,
      $httponly ? 1 : 0
    );
  }

  /**
   * *Get Cookie
   * @param string $name
   * @return string
   */
  public function get_cookie($name = "")
  {
    return $_COOKIE[$name];
  }

  /**
   * *Clear Cookie
   * @param string $name
   */
  public function unset_cookie($name = "")
  {
    setcookie($name, "", time() - 60, "/", "", 0);
    unset($_COOKIE[$name]);
  }

  /**
   * *Get all the set headers
   * @param string $key Optional, If you want to fetch specific the header then key can be passed
   * @return string|mixed[] Headers
   */
  public function getHeaders($key = "")
  {
    $headers = getallheaders();
    return $key ? $headers[$key] : $headers;
  }

  /**
   * *Create array batches
   * @param mixed[] $data_arr multi dimensional array
   * @param int $batch_size size of the batch
   * @return mixed[]
   */
  public function create_batches($data_arr = [], $batch_size = 500)
  {
    return array_chunk($data_arr, $batch_size);
  }

  /**
   * *subtract day from the date
   * @param string $date date from which the difference should be taken
   * @param string $format format of the display date
   * @return string formatted date
   */
  public function subtract_day_from_date($date = "", $format = 'Y-m-d')
  {
    return date($format, strtotime('-1 day', strtotime($date)));
  }

  /**
   * *Add give seconds to date
   * @param string $sec seconds to be added to the date
   * @param string $format format of the display date
   * @return mixed[] date related data
   */
  public function add_second_to_date($sec = '', $format = '')
  {
    if ($sec) {
      $date = new DateTime();
      $date->add(new DateInterval('PT' . $sec . 'S'));
      $timestamp = $date->getTimestamp();
      $res = [
        'timestamp' => $timestamp,
        'date' => date('Y-m-d', $timestamp),
        'datetime' => date('Y-m-d H:i:s', $timestamp),
      ];
      if ($format) {
        $res['specified_format'] = date($format, $timestamp);
      }
      return $res;
    }
    return false;
  }
}
