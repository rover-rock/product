<?php
 $title='销售人员';

$newOrder=m('dealOrder')->getSalerOrder($user['id'],'newOrder');
$verifiedOrder=m('dealOrder')->getSalerOrder($user['id'],'verifiedOrder');
$contractOrder=m('dealOrder')->getSalerOrder($user['id'],'contractOrder');
$producingOrder=m('dealOrder')->getSalerOrder($user['id'],'producingOrder');
$finishedOrder=m('dealOrder')->getSalerOrder($user['id'],'finishedOrder');

 
include $this->template('salesman/index');

?>