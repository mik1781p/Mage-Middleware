<?php
/**
 * Mage-Middleware
 */
require_once ('../Debugger/Debugger.php');
require_once ('../CSVer/CSVer.php');

if (!isset($_POST) && !isset($_POST['actionmethod']))
{
    errorHandling(1);
}


$logger = new Debugger('log.txt',Debugger::LEVEL_INFO);
$CSVer = new CSVer('products.csv');

//$url = $_SERVER['SERVER_NAME'].'/api/soap/?wsdl';
$url = 'http://demo.clixcommerce.com.br/api/soap/?wsdl';

//TODO far passare i dati di login tramite header post



$client = new SoapClient($url);
$session = $client->login('thinkopen', 'a123456');

$method = trim($_POST['actionmethod']);
$config = unserialize(trim($_POST['config']));
$fullConfig = $config;

if (isset($_POST) && isset($_POST['productsku'])) {
    $productsku = unserialize($_POST['productsku']);
    if (count($productsku) != count($config)) {
        errorHandling(2);
    }
}
if (isset($_POST['config'])) {

    foreach ($config as $single) {


        $attributeSets = $client->call($session, 'product_attribute_set.list');
        var_dump($attributeSets);
        $attributeSet = current($attributeSets);
        $fullConfig = array('simple', $attributeSet['set_id'], current($productsku));
        $fullConfig[] = $single;
        next($productsku);
        $result = $client->call($session, $method, $fullConfig);


        $logger->write('--------------------------');
        $logger->write('Session: ' . $session);
        $logger->write('Action: ' . $method);
        if (isset($_POST['config'])) {
            $logger->write('Config:');
            $logger->write($fullConfig);
        }
        $logger->write('Result:');
        $logger->write($result);
        $logger->write('--------------------------');
    }
} else {

    $result = $client->call($session, $method);
    if($method =='catalog_product.list'){
        $CSVer->createCSV($result);
        $logger->write('--------------------------');
        $logger->write('Session: ' . $session);
        $logger->write('Action: ' . $method);
        $logger->write('Result:');
        $logger->write($result);
        $logger->write('--------------------------');
    }
}









function errorHandling($error = 0){

}