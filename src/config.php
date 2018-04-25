<?php
return array (
	//应用ID,您的APPID。
	'app_id' => "",

	//商户私钥，您的原始格式RSA私钥
	'merchant_private_key' => "",

	//异步通知地址
	'notify_url' => "",

	//同步跳转
	'return_url' => "",

	//编码格式
	'charset' => "UTF-8",

	//签名方式
	'sign_type'=>"RSA2",

	'isDebug'=>true,
	//支付宝网关https://openapi.alipaydev.com/gateway.do
	//'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
	'gatewayUrl'=>"https://openapi.alipaydev.com/gateway.do",

	//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
	'alipay_public_key' => ""
);