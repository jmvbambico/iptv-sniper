<?php
  require __DIR__ . '/vendor/autoload.php';

  use Inspirum\XML\Builder\DefaultDocumentFactory;
  use Inspirum\XML\Builder\DefaultDOMDocumentFactory;
  use Inspirum\XML\Reader\DefaultReaderFactory;
  use Inspirum\XML\Reader\DefaultXMLReaderFactory;
  use Inspirum\XML\Reader\XMLReaderFactory;

  // $channel_list   = array();
  // $programme_list = array();

  // $epgs_json = json_decode(file_get_contents($_GET["json"]));

  // foreach ($epgs_json as $epg_json) {
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_URL, $epg_json->url);
    // $epg_json_contents = curl_exec($ch);
    // curl_close($ch);

    $file = file_put_contents("temp_xml", file_get_contents("https://iptv-org.github.io/epg/guides/ph/clickthecity.com.epg.xml"));
    $reader = newReader("temp_xml");
    $node = $reader->nextNode('tv');
    unlink("temp_xml");

    // $epg_xml = new EpgParser($epg_json_contents);
    // $epg_xml_channels = $epg_xml->array["tv"]["channel"];
    // $epg_xml_programmes = $epg_xml->array["tv"]["programme"];

    // foreach ($epg_json->channels as $epg_json_channel) {
    //   foreach ($epg_xml_programmes as $epg_xml_programme) {
    //     if ($epg_xml_programme["attrib"]["channel"] ==  $epg_json_channel) {
    //       $programme_list[] = [
    //         "start_raw" => $epg_xml_programme["attrib"]["start"],
    //         "stop_raw"  => $epg_xml_programme["attrib"]["stop"],
    //         "channel"   => $epg_xml_programme["attrib"]["channel"],
    //         "title"     => $epg_xml_programme["title"]["cdata"]
    //       ];
    //     }
    //   }
    // }
  // }

  die("<pre>" . print_r($node, true) . "</pre>");

  function newReader(
    string $filepath,
    ?string $version = null,
    ?string $encoding = null,
    ?XMLReaderFactory $readerFactory = null
  ): Reader {
      $readerFactory = new DefaultReaderFactory(
          $readerFactory ?? new DefaultXMLReaderFactory(),
          new DefaultDocumentFactory(new DefaultDOMDocumentFactory()),
      );

      return $readerFactory->create($filepath, $version, $encoding);
  }
//end epg.php