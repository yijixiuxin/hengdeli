<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WEIWEI
 * Date: 13-7-4
 * Time: 下午11:10
 * To change this template use File | Settings | File Templates.
 */

class test extends base {
    public function __construct() {
        parent::__construct();
    }

    public function g_report() {
        $g_1 = model_export::g_report_2_3(4);
        echo '<pre>';
        print_r($g_1);
    }
}