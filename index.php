<?php
include_once 'vendor/autoload.php';
//注：配置文件在 src/config.php  也可以作为AopSdk类的参数传过去。

$alipay = new \ScarecrowAlipay\AopSdk();
//H5支付
$alipay->WapPay('订单名称', '订单号', '支付金额');
//扫码支付
$alipay->NativePay('订单名称', '订单号', '支付金额');
//更多的API请到src/AopSdk类中查看，你也可以自己封装尚未封装的接口