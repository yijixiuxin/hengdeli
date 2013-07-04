<?php
/**
 * 导出报告
 * Created by JetBrains PhpStorm.
 * User: Xavier
 * Date: 13-7-4
 * Time: 上午10:09
 * To change this template use File | Settings | File Templates.
 */

class export extends base {
    public function __construct() {
        parent::__construct();
        $user_info = model_user::cuser();
    }
}