<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mostofaBonds = array (
  '0026861~0026870',
  '0040402~0040405',  
  '0040409~0040411',
  '0040415~0040417',
  '0040433',
  '0040434',
  '0042860',
  '0042865',
  '0042869',
  '0042870',
  '0042885~0042888',
  '0042896~0043000',  
  '0761932~0761934',
  '0765360~0765393',
  '0780300'
);
$ruposhBonds = array ('0197967','0806570','0780291','0780290','0431387','0431385','0211705','0211709','0211710','0969301','0969302','0781589','0781590','0513950','0513951','0513952','0513953','0513954','0042831','0042832','0042833','0042834','0042866','0042868','0042898','0042895');

$myBonds = $ruposhBonds;
$searchResult = [];
foreach($myBonds as $bondNumber){
  $search = search($bondNumber);
  if(!empty($search)) $searchResult[$bondNumber] = $search;
}

echo "<pre>";
if(empty($searchResult)) echo "<h1>Not prize found</h1>";
else var_export($searchResult);

function search($bondNumber=''){
  if($bondNumber=='') return [];
  libxml_use_internal_errors(true);
  $url = 'https://www.bb.org.bd/en/index.php/investfacility/prizebond';
  $postData = array('gsearch' => $bondNumber);
  $userAgents = [
      // Desktop browsers
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.1 Safari/537.36',
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0',
      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.1 Safari/537.36',
      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15',
      'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.1 Safari/537.36',
      'Mozilla/5.0 (Linux; Android 11; SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.1 Mobile Safari/537.36',
      'Mozilla/5.0 (iPhone; CPU iPhone OS 15_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
      'Mozilla/5.0 (iPad; CPU OS 15_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
      'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
      'Mozilla/5.0 (compatible; Bingbot/2.0; +http://www.bing.com/bingbot.htm)',
      'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)',
      'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)',
      'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.1 Safari/537.36',
  ];

  $acceptLanguages = [
      'en-US,en;q=0.5',
      'en-GB,en;q=0.7',
      'en-AU,en;q=0.7',
      'en-CA,en;q=0.7',
      'en-NZ,en;q=0.7',
      'en-ZA,en;q=0.7'
  ];

  $acceptEncodings = [
      'gzip, deflate, br',
      'br, gzip, deflate',
      'gzip, br, deflate',
      'gzip, deflate',
      'deflate, gzip'
  ];


  $randomUserAgent = $userAgents[array_rand($userAgents)];
  $randomAcceptLanguage = $acceptLanguages[array_rand($acceptLanguages)];
  $randomAcceptEncoding = $acceptEncodings[array_rand($acceptEncodings)];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, $randomUserAgent);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: ' . $randomAcceptLanguage]);
  curl_setopt($ch, CURLOPT_ENCODING, $randomAcceptEncoding);
  usleep(rand(500000, 1500000));
  $html = curl_exec($ch);
  $tableRows = [];

  if ($html !== false) {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $tables = $dom->getElementsByTagName('table');
        if ($tables->length > 0) {
            $table = $tables->item(0);
            foreach ($table->getElementsByTagName('tr') as $row) {
                $row_data = array();
                foreach ($row->getElementsByTagName('td') as $cell) {
                    $row_data[] = $cell->nodeValue;
                }
                $tableRows[] = $row_data;
            }
        }
  }
  curl_close($ch);
  if(isset($tableRows[0])) unset($tableRows[0]);
  return $tableRows;
}


?>