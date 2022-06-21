<?php

function audioExists($url) {
  $headers = get_headers($url);

  return stripos($headers[0], "200") ? true : false;
}

function makeDir($path) {
     return is_dir($path) || mkdir($path);
}


// Let's get all the page URLs
$list = file_get_contents('reciters.html');
$list = str_replace(['Morocco', 'Egypt', 'Kuwait', 'Iraq',
    'United States of America', 'Syria', 'Saudi Arabia', 'Algeria', 'Indonesia',
    'Malaysia', 'Libya', 'Tajikistan', 'Iran', 'South Africa', 'Eritrea', 'Pakistan',
    'Sudan', 'Sri Lanka', 'Bahrain', 'UAE', 'Nigeria', 'Zimbabwe', 'Jordan', 'Palestine', 'India', 'Yemen',
    'Turkey', 'Tanzania', 'England', 'Lebanon', 'Tunisia', 'Bosnia and Herzegovina'],
    ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '','','','','','','','',],
    $list);
$list = preg_replace(
    '~[\r\n]+~',
    "\r\n", trim($list));

$rPage = explode("<a class=\"name\" href=\"", $list);
$urls =[];
foreach ($rPage as $aV) {
    $x = explode("\"", $aV);
    $urls[] = $x[0];
}

echo "Total URLS: " . count($urls) . "\n";
// Let's visit every url and see if it has 114 episodes
$count = 0;
foreach ($urls as $reciterPage) {
    $count++;
    echo "$count. Getting $reciterPage...";
    $page = file_get_contents ('https://qurancentral.com' . $reciterPage);
    if (strpos($page, '<p><strong>Audio Episodes:</strong> 114</p>') !== false) {
        echo "Found 114 episodes!\n";
        // This is a reciter to download
        $keys[] = str_replace('/audio/', '', $reciterPage);
    } else {
        echo "DID NOT Find 114 episodes!\n";
    }
}

echo "Total Keys: " . count($keys) . "\n";

// $keys = ['ali-abdur-rahman-al-huthaify'];
echo "...................START DOWNLOADING.........\n";

$bitrate = "reciters";
$suffix = 'muslimcentral.com';

$dCount = 0;
foreach ($keys as $key) {
    $key = substr($key, 0, -1);
    $dCount++;
    $language = 'ar';
  $keyExists = true;
  if (strpos($key, 'english') !== false) {
      $language = 'en';
  }
  $folder = $language . '.' . str_replace('-', '', $key);
  for($i=1; $i<=114; $i++) {
    if ($keyExists) {
      if ($i <= 9) {
        $surah = '00'.$i;
      }

      if ($i <= 99 && $i >= 10) {
        $surah = '0'.$i;
      }

      if ($i >=100) {
        $surah = $i;
      }

      $files = [
        "https://media.blubrry.com/muslim_central_quran/podcasts.qurancentral.com/$key/$key-$surah-$suffix.mp3",
        "https://media.blubrry.com/muslim_central_quran/podcasts.qurancentral.com/$key/$key-$surah.mp3",
        "https://media.blubrry.com/muslim_central_quran/podcasts.qurancentral.com/$key/$surah.mp3",
        "https://podcasts.qurancentral.com/$key/$surah.mp3",
        "https://podcasts.qurancentral.com/$key/$key-$surah.mp3",
          "https://podcasts.qurancentral.com/$key/$key-$surah-$suffix.mp3"
      ];
      
      if ($i === 1) {
        $keyExists = false;
        foreach ($files as $k => $file) {
            echo "$dCount. Trying $file...";
            if (audioExists($file)) {
                echo "Found!\n";
              makeDir("$bitrate/$folder/");
              $fileIndex = $k;
              $keyExists = true;
                echo "Writing surah " .$files[$fileIndex]."\n";
                file_put_contents("$bitrate/$folder/$i.mp3", file_get_contents($files[$fileIndex]));
            } else {
                echo "Not found!\n";
            }
        } 
      }
      if ($keyExists && $i > 1) {
          echo "Writing surah " . $files[$fileIndex] . "\n";

          file_put_contents("$bitrate/$folder/$i.mp3", file_get_contents($files[$fileIndex]));
      }
    }
  }
}
