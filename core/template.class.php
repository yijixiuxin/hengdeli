<?php
require ROOT_PATH.'/core/lib/smarty/Smarty.class.php';
//require dirname(__FILE__).'/lib/smarty/Smarty.class.php';
/**
 * 模板类
 * 封装Smarty模板引擎
 * 
 */
class template extends Smarty {
    function __construct(){
        parent::__construct();
        //Smarty相关配置
        if(!ONLINE_MODE){
            $this->compile_check = true;
        }

        $this->debugging = false;
        $this->left_delimiter='{';
        $this->right_delimiter='}';
        $this->template_dir = ROOT_PATH.'/template/';
        $this->compile_dir = ROOT_PATH.'/template_c/';
        $this->cache_dir = ROOT_PATH.'/template_c/';
        /*$this->template_dir = dirname(__FILE__).'/../template/';
        $this->compile_dir = dirname(__FILE__).'/../template_c/';
        $this->cache_dir = dirname(__FILE__).'/../template_c/';*/
    }

    public static function get(){
        return new template();
    }
}

