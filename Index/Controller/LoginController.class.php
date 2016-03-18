<?php

class LoginController extends CommonController{


    public function __auto(){



         echo '应用Login构造方法执行';

    }



     public function index(){

      //   $this->success('跳转成功');

         //  echo '<h2>欢迎使用XXXX框架(:!</h2>';

      //  Log::write('您好');

//         $var =array('a','b');
//         p($var);
       //  go('http://www.baidu.com',5,'正在跳转...');
        // halt('您好');
        $var ='测试';
         $this->assign('var',$var);
        $this->display();

     }
}