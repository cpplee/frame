<?php
/**
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


}

?>