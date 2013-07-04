<?php
class ajax extends base{
	public function get_mendian_by_area(){
		$acode = $_REQUEST['acode'];
		$res = model_mendian::Gets(array('acode'=>$acode));
		json_out(1, $res);
	}
}