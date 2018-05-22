<?php
 $title='销售人员';

$newOrder=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and status=1 and detailstatus<5 and userid=:userid',array(':userid'=>$user['id']));

$verifiedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and not (status=1 and detailstatus<5)');

$allOrder=pdo_fetchall("select * from ims_ly_product_manage_order where type<3");


include $this->template('salesman/index');

?>