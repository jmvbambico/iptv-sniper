<?php
  require __DIR__ . "/../vendor/autoload.php";

  $parser = new \Smalot\PdfParser\Parser();
  $pdf = $parser->parseContent(file_get_contents("https://img.mytfc.com/cmsroot/abscms/media/mytfctv/grids/2022/tfc/jul/tfc-tv-guide-guam-" . week_range(date("Y-m-d")) . ".pdf"));

  $text = $pdf->getPages()[0]->getDataTm();;
  // die("<pre>" . print_r($text, true) . "</pre>");

  $shows = array();

  foreach ($text as $key => $entry) {
    if (strlen($entry[1]) < 5) continue;

    $add_coords = false;

    if (is_time($text[$key-1][1]) && !is_time($text[$key][1]) && is_time($text[$key+1][1])) {
      $add_coords = true;
      $shows[$key]["label"] = $text[$key][1];
      $shows[$key]["timeslot"] = $text[$key+1][1];
    }
    elseif (is_time($text[$key-1][1]) && !is_time($text[$key][1]) && !is_time($text[$key+1][1]) && is_time($text[$key+2][1])) {
      $add_coords = true;
      $shows[$key]["label"] = $text[$key][1] . $text[$key+1][1];
      $shows[$key]["timeslot"] = $text[$key+2][1];
    }

    if ($add_coords) {
      $shows[$key]["x"] = $entry[0][5];
      $shows[$key]["y"] = $entry[0][4];
    }
  }

  die("<pre>" . print_r($shows, true) . "</pre>");

  function week_range($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $start = (date('N', $dt) == 6) ? date('Fd-', $dt) : date('Fd-', strtotime('last saturday', $dt));
    $end   = (date('N', $dt) == 5) ? date('d', $dt) : date('d', strtotime('next friday', $dt));
    return (strtolower($start) . $end);
  }

  function is_time($string) {
    return (strpos($string, " PM") > -1 || strpos($string, " AM") > -1) ? true : false;
  }
//   echo week_range("2022-07-22") . "<br>";
//   echo week_range("2022-07-23") . "<br>";
//   echo week_range("2022-07-24") . "<br>";
//   echo week_range("2022-07-25") . "<br>";
//   echo week_range("2022-07-26") . "<br>";
//   echo week_range("2022-07-27") . "<br>";
//   echo week_range("2022-07-28") . "<br>";
//   echo week_range("2022-07-29") . "<br>";
//   echo week_range("2022-07-30");
//end tfc.php