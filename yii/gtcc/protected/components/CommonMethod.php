<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class CommonMethod
{
    public static function sendRequest($serviceName,$methodName,$paramArray)
    {
        $amfphpServerUrl = Yii::app()->request->hostInfo.'/gtcclibrary/amfphp/index.php';
        $data = array('serviceName' => $serviceName,
                      'methodName'=> $methodName,                
                      'parameters' => $paramArray
                 );

        $json = json_encode($data);
        $client = new EHttpClient($amfphpServerUrl);
        $response = $client->setRawData($json, 'application/json')->request('POST');
        if($response->isSuccessful())
        {
            $result = json_decode($response->getBody());   
        }else
        {
            $result->_returnCode = -1;
        }
        //var_dump($result);
        return $result;
    }
}
