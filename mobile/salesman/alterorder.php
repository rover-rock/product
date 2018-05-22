<?php
$title="停用订单";
$id=$_GPC['orderid'];
$operation=$_GPC['op'];

include $this->template('salesman/alterorder');

?>