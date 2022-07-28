<?php
  $type = (isset($_GET["format"]) && $_GET["format"] == "xml") ? "application/xml" : "application/gzip";
  $format = (isset($_GET["format"]) && $_GET["format"] == "xml") ? "&format=xml" : "";
  header("Content-Type: $type");

  if ($type == "application/gzip")
    header("Content-Disposition: attachment; filename=cryogenix.xml.gz");

  $headers = [
    "http" => [
    "method" => "GET",
    "header" =>
      "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjYXRlZ29yeSI6InBlcnNvbmFsX2NvbXB1dGVyIiwiY291bnRyeV9jb2RlIjoidXMiLCJleHBpcmVzIjoiMjAxNy0wOC0xMVQxNjo1MzoxNC44ODY0NzAyNzlaIiwib3NfZmFtaWx5IjoiaHR0cCIsInJlbW90ZV9pcCI6IjE5OC4yMi4xMzguNTIiLCJ1YSI6Ik1vemlsbGEvNS4wIChNYWNpbnRvc2g7IEludGVsIE1hYyBPUyBYIDEwXzExXzYpIEFwcGxlV2ViS2l0LzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZS81OS4wLjMwNzEuMTE1IFNhZmFyaS81MzcuMzYiLCJ1aWQiOiIyMDA4ODJiOC1mMDQ5LTRmMTQtYTdlMS04ZGZjMzQ0ZDE3ZjMifQ.8hKLf9OWdDAEcYdLoPcBnq7SwATwHyahL07WZHU8x-g\r\n" .
      "Origin: https://www.redbull.com\r\n" . 
      "Referer: https://www.redbull.com\r\n"
    ]
  ];

  $epgs = json_decode(file_get_contents("https://api.redbull.tv/v3/epg?complete=true", false, stream_context_create($headers)), JSON_PRETTY_PRINT);

  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  $xml .= "<tv date=\"" . date('Ymd') . "\" generator-info-name=\"AdoboTV\">\n";
  $xml .= "  <channel id=\"redbulltv.us\">\n";
  $xml .= "    <display-name>RedBull TV</display-name>\n";
  $xml .= "    <icon src=\"https://i.imgur.com/7NeBmWX.jpg\" />\n";
  $xml .= "    <url>". htmlspecialchars("https://www.redbull.com") . "</url>\n";
  $xml .= "  </channel>\n";

  foreach ($epgs["items"] as $programme) {
    $xml .= "  <programme start=\"" . date("YmdHis", strtotime($programme["start_time"])) . " +0000\" stop=\"" . date("YmdHis", strtotime($programme["end_time"])) . " +0000\" channel=\"redbulltv.us\">\n";
    $xml .= "    <title lang=\"en\">" . htmlspecialchars($programme["title"]) . "</title>\n";
    $xml .= "    <desc lang=\"en\">" . htmlspecialchars($programme["long_description"]) . "</desc>\n";
    $xml .= "    <category lang=\"en\">Sports</category>\n";
    $xml .= "  </programme>\n";
  }

  $xml .= "</tv>";
  echo ($type == "application/gzip") ? gzencode($xml, 9) : $xml;
//end redbulltv.php