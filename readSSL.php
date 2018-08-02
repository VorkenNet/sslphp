<?php

function getFileToArray($filePath){
  $file = fopen($filePath, "r") or exit("Unable to open file!");
  while(!feof($file)) {
    $result[]=preg_replace("/[\n\r]/","",fgets($file));
  }
  fclose($file);
  return $result;
}

function echoArrayToWiki($array){
  foreach($array as $key=> $value){
    echo "|".$key."|".$value."|\n";
  }
}

//$filePath="domainlist.txt";
//$domains=getFileToArray($filePath);

//$certFilePath="a5e92cf5478ef9a1.crt";
//$certFilePath="certssls.com";

$certFilePath=trim($argv[1]);

$cert=openssl_x509_parse(file_get_contents($certFilePath));
///print_r($cert);
//echo $cert["extensions"]["subjectAltName"];
$RawDomains=explode(",",$cert["extensions"]["subjectAltName"]);

foreach($RawDomains as $RawDomain){
  $domains[]=trim(preg_replace("/DNS:/","",$RawDomain));
}
//$domains=array_map(function ($a) { return reg_replace("/DNS:/","",$a); },$RawDomains);

echo "**Soggetto:**\n";
echoArrayToWiki($cert["subject"]);
echo "\n";
echo "**Rilasciato:**\n";
echoArrayToWiki($cert["issuer"]);
echo "\n";
echo "**Usage:**\n";
echo "|KeyUsage:|".$cert["extensions"]["keyUsage"]."|\n";
echo "|ExtendedKeyUsage:|".$cert["extensions"]["extendedKeyUsage"]."|\n";
echo "\n";
echo "**Domini:**\n";
foreach($domains as $domain){
  echo "|".$domain."|".gethostbyname($domain)."|\n";
}

 ?>
