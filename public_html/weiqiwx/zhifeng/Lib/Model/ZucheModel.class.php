<?php
    class ZucheModel extends Model{
    protected $_validate = array(
            array('name','require','名称不能为空',1),
            array('price','require','价格不能为空',1),
            array('storeid','require','请选择店铺',1),
            array('baseprice','checkBaseprice','总价格小于基本保险费用',2,'callback',3)
     );
    protected $_auto = array (
		array('token','gettoken',1,'callback'),
        array('baseprice','getBaseprice',3,'callback')
    );
    function gettoken(){
		return session('token');
	}
	function getBaseprice(){
		$price=$_POST['price'];
		$insurance=$_POST['insurance'];
		$baseprice=round($price-$insurance,0);
		return $baseprice;	
	}
	function checkBaseprice(){
		$price=$_POST['price'];
		$insurance=$_POST['insurance'];
		$baseprice=round($price-$insurance,0);
		if($baseprice >=0){
			return false;
		}else{
			return true;
		}
		
	}
}

?>