<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/18
 * Time: 20:46
 */

final class KUAIXUEPHP{

    public static function run(){

        self::_set_const();
        defined('DEBUG')||define('DEBUG',false);
       if(DEBUG){

           self::_create_dir();
           self::_import_file();
       }else{

           error_reporting(0);
           require TEMP_PATH.'/~boot.php';
       }


        Application::run();
    }


    private static function _set_const(){


        $path = str_replace('\\','/',__FILE__);

        define('KUAIXUEPHP_PATH',dirname($path));
        define('CONFIG_PATH',KUAIXUEPHP_PATH.'/Config');
        define('DATA_PATH',KUAIXUEPHP_PATH.'/Data');
        define('LIB_PATH',KUAIXUEPHP_PATH.'/Lib');
        define('CORE_PATH',LIB_PATH.'/Core');
        define('FUNCTION_PATH',LIB_PATH.'/Function');


        define('ROOT_PATH',dirname(KUAIXUEPHP_PATH));
        define('TEMP_PATH',ROOT_PATH.'/Temp');
        define('LOG_PATH',TEMP_PATH.'/Log');
        define('APP_PATH',ROOT_PATH.'/'.APP_NAME);
        define('APP_CONFIG_PATH',APP_PATH.'/Config');
        define('APP_CONTROLLER_PATH',APP_PATH.'/Controller');
        define('APP_TPL_PATH',APP_PATH.'/Tpl');
        define('APP_PUBLIC_PATH',APP_TPL_PATH.'/Public');

      //  echo APP_PUBLIC_PATH;

    }



    private static function _create_dir(){

        $arr = array(
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH,
            TEMP_PATH,
            LOG_PATH
        );

        foreach($arr as $key =>$value){

            is_dir($value) || mkdir($value,0777,true);

        }

        is_file(APP_TPL_PATH.'/success.html') || copy(DATA_PATH.'/Tpl/success.html',APP_TPL_PATH.'/success.html');
        is_file(APP_TPL_PATH.'/error.html') || copy(DATA_PATH.'/Tpl/success.html',APP_TPL_PATH.'/error.html');

    }

    private static  function _import_file(){

        $fileArr = array(
            CORE_PATH.'/Log.class.php',
            FUNCTION_PATH.'/function.php',
            CORE_PATH.'/Controller.class.php',
            CORE_PATH.'/Application.class.php',


        );

        $str = '';
        foreach($fileArr as $key=>$value){

            $str .=trim(substr(file_get_contents($value),5,-2));
            require_once $value;

        }

        $str ="<?php\r\n".$str;
        file_put_contents(TEMP_PATH.'/~boot.php',$str) || die('access not allow');




    }


}





KUAIXUEPHP::run();
