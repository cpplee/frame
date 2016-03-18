<?php
class Log
{

     static public function write($msg,$level='ERROR',$type=3,$dest=NULL){


         if(!C('SAVE_LOG')) return;
         if(is_null($dest)){

            $dest = LOG_PATH.'/'.date('Y_m_d').".log";

         }

         if(is_dir(LOG_PATH)){

             error_log("[time]".date('Y-m-d H:i:s')."{$level}:{$msg}\r\n",$type,$dest);

         }






     }





}function halt($error,$level='ERROR',$type=3,$dest=NULL){

                if(is_array($error)){

                    Log::write($error['message'],$level,$type,$dest);
                }else{

                    Log::write($error,$level,$type,$dest);
                }

    $e = array();
    if(DEBUG){

        if(!is_array($error)){

            $trace = debug_backtrace();
            $e['message']=$error;
            $e['file']=$trace[0]['file'];
            $e['line']=$trace[0]['line'];
            $e['class']=isset($trace[0]['class'])?$trace[0]['class']:'';
            $e['function']=isset($trace[0]['function'])?$trace[0]['function']:'';
               ob_start();
             debug_print_backtrace();
              $e['trace']=htmlspecialchars(ob_get_clean());
        }else{

            $e = $error;
        }


    }else{

        if($url = C('ERROR_URL')){

            go($url);
        }else{
            $e['message']=C('ERROR_MESSAGE');
        }
    }

    include  DATA_PATH.'/Tpl/halt.html';
}



function p($arr){

     if(is_bool($arr)){

          var_dump($arr);
     }else if(is_null($arr)){

         var_dump(NULL);

     }else{

         echo '<pre style="padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #ccc;font-size:14px;">';
         print_r($arr);
         echo  '</pre>';

     }

}

function C($var = NULL,$value = NUll){

         static $config = array();

           if(is_array($var)){

               $config = array_merge($config,array_change_key_case($var,CASE_UPPER));

                  return;
           }

      if(is_string($var)){

          $var = strtoupper($var);
          if(!is_null($value)){
                  $config[$var]=$value;
              return;
          }

          return isset($config[$var])?$config[$var]:NULL;

      }

    if(is_null($var) && is_null($value)){
        return $config;
    }

}


function go($url,$time=0,$msg=''){



    if(!headers_sent()){

        $time ==0?header('Location:'.$url):header("refresh:{$time};url={$url}");

        die($msg);
    }else{

        echo "<meta http-equiv='Refresh'content='{$time};URL={$url}'>";
        if($time) die($msg);
    }
}

 function  print_const(){

   $const =get_defined_constants(true);


     p($const['user']);
 }/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/19
 * Time: 19:06
 */


class Controller{

    private $var = array();

    public function __construct(){

        echo '父类执行构造器'.'<br/>';
        if(method_exists($this,'__init')){

            $this->__init();

        }
        if(method_exists($this,'__auto')){

            $this->__auto();

        }

    }

    protected function success($msg,$url=NULL,$time=3){


        $url = $url?"window.location.href='".$url."'":'window.history.back(-1)';

        include APP_TPL_PATH.'/success.html';

             die;
    }
    protected function error($msg,$url=NULL,$time=3){


        $url = $url?"window.location.href='".$url."'":'window.history.back(-1)';

        include APP_TPL_PATH.'/error.html';
        die;
    }



    public function  display($tpl=NULL){

       if(is_null($tpl)){

           $path =APP_TPL_PATH.'/'.CONTROLLER.'/'.ACTION.'.html';

           }else{

           $suffix = strrchr($tpl,'.');
           $tpl = empty($suffix)?$tpl.'.html':$tpl;
           $path = APP_TPL_PATH.'/'.CONTROLLER.'/'.$tpl;
       }

        if(!is_file($path)) halt($path.'模板文件不存在');

        extract($this->var);
        include $path;

    }


    public function assign($var,$value){

         $this->var[$var] =$value;


    }


}class Application
{

    public static function run(){

        self::_init();
        self::_set_url();
        spl_autoload_register(array(__CLASS__,'_autoload'));

        self::_create_demo();

        self::_app_run();

    }


    private static function _app_run(){

        $c = isset($_GET[C('VAR_CONTROLLER')])?$_GET[C('VAR_CONTROLLER')]:'Index';
        $a = isset($_GET[C('VAR_ACTION')])?$_GET[C('VAR_ACTION')]:'index';



        define('CONTROLLER',$c);

        define('ACTION',$a);



        $c .='Controller';
        $obj = new $c();
        $obj->$a();

    }


    private static function _create_demo(){

        $path = APP_CONTROLLER_PATH.'/IndexController.class.php';

        $str=<<<str
<?php

class IndexController extends Controller{

     public function index(){
           header('Content-type:text/html;charset=utf-8');
           echo '<h2>欢迎使用XXXX框架(:!</h2>';
     }
}
str;

        is_file($path)||file_put_contents($path,$str);

    }

    private static function _autoload($className){


      include APP_CONTROLLER_PATH.'/'.$className.'.class.php';


    }


    public static function _init(){

          //初始化配置项
        C(include CONFIG_PATH.'/config.php');

        $userPath = APP_CONFIG_PATH.'/config.php';

        $userConfig =<<<str
<?php

 return array(
     //配置项==>配置值
 );
str;

        is_file($userPath)|| file_put_contents($userPath,$userConfig);

        C(include $userPath);

        //设置默认时区
        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));

        C('SESSION_AUTO_START') && session_start();



    }

    private static function _set_url(){

      $path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

       $path = str_replace('\\','/',$path);

        define('__APP__',$path);
        define('__ROOT__',dirname($path));
        define('__TPL__',__ROOT__.'/'.APP_NAME.'/Tpl');
      define('__PUBLIC__',__TPL__.'/Public');
      //  p(__PUBLIC__);


    }


}