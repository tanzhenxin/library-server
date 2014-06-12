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

    private static $category = array(
        'E' => 'Engineering',
        'F' => 'Foreign Languages',
        'M' => 'Management',
        'S' => 'Self-improvement',
        'T' => 'Technical',
        'Z' => 'Miscellaneous'
        );
    
    public static function GetCategory($bianhao)
    {
        // Get the first letter of book tag
        if(!empty($bianhao))
            return self::$category[$bianhao[0]];
    }
    
    public static function GetCategoryArray()
    {
        return self::$category;
    }
}

