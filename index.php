<?php
header('Content-Type: application/json; charset=utf8');

require_once 'class/client.php';

class Rest
{
    public  static function open($requisition)
    {
        $url    =   explode('/', $requisition['url']);
        $class  =   ucfirst($url[0]);
        array_shift($url);
        $method =   $url[0];
        array_shift($url);
        $parameters =   array();
        if (!empty($url)) {
            $parameters = $url;
        }

        try {
            if(class_exists($class)){
                if(method_exists($class,$method)){
                    $return = call_user_func(array($class,$method),$parameters);
                    return json_encode(array('status'=>'sucesso','dados' => $return));
                }else{
                    return json_encode(array('status'=>'sucesso','dados'=>'Metódo inexistente'));
                }
            }else{
                return json_encode(array('status'=>'sucesso','dados'=>'Classe inexistente'));
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>'erro','dados'=>$e->getMessage()));
        }
    }
}
if(isset($_REQUEST)){
    echo Rest::open($_REQUEST);
}
?>
