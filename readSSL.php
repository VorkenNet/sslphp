<?php

$certFilePath=trim($argv[1]);
$cert=openssl_x509_parse(file_get_contents($certFilePath));
//print_r($cert);

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
echo "**Seriale:**\n";
echo "|Seriale:|".$cert["serialNumber"]."|\n";
echo "\n";
echo "**Validita:**\n";
echo "|Dal:|".date("Y-m-d",$cert["validFrom_time_t"])."|\n";
echo "|Al:|".date("Y-m-d",$cert["validTo_time_t"])."|\n";
echo "\n";
echo "**Usage:**\n";
echo "|KeyUsage:|".$cert["extensions"]["keyUsage"]."|\n";
echo "|ExtendedKeyUsage:|".$cert["extensions"]["extendedKeyUsage"]."|\n";
echo "\n";
echo "**Domini:**\n";
foreach($domains as $domain){
  echo "|".$domain."|".gethostbyname($domain)."|\n";
}

function echoArrayToWiki($array){
  foreach($array as $key=> $value){
    echo "|".$key."|".$value."|\n";
  }
}

?>
