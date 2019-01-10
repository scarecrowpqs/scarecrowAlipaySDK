<?php
$m = new AopSdk();
//H5支付
$m->WapPay('订单名称', '订单号', '支付金额');
//扫码支付
$m->NativePay('订单名称', '订单号', '支付金额');
//更多的API请到src/WechatSdk类中查看，你也可以自己封装尚未封装的接口
