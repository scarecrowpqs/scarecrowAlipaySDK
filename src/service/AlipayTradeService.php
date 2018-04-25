<?php
namespace ScarecrowAlipay\service;

use ScarecrowAlipay\aop\AopClient;
use ScarecrowAlipay\aop\request\AlipayDataDataserviceBillDownloadurlQueryRequest;
use ScarecrowAlipay\aop\request\AlipayTradeCancelRequest;
use ScarecrowAlipay\aop\request\AlipayTradeCloseRequest;
use ScarecrowAlipay\aop\request\AlipayTradeFastpayRefundQueryRequest;
use ScarecrowAlipay\aop\request\AlipayTradePagePayRequest;
use ScarecrowAlipay\aop\request\AlipayTradeQueryRequest;
use ScarecrowAlipay\aop\request\AlipayTradeRefundRequest;
use ScarecrowAlipay\aop\request\AlipayTradeWapPayRequest;

class AlipayTradeService {

	//支付宝网关地址
	public $gateway_url = "https://openapi.alipay.com/gateway.do";

	//支付宝公钥
	public $alipay_public_key;

	//商户私钥
	public $private_key;

	//应用id
	public $appid;

	//编码格式
	public $charset = "UTF-8";

	public $token = NULL;
	
	//返回数据格式
	public $format = "json";

	//签名方式
	public $signtype = "RSA";

	//是否开启DEBUG
	private $dubug;

	function __construct($alipay_config){
		$this->gateway_url = $alipay_config['gatewayUrl'];
		$this->appid = $alipay_config['app_id'];
		$this->private_key = $alipay_config['merchant_private_key'];
		$this->alipay_public_key = $alipay_config['alipay_public_key'];
		$this->charset = $alipay_config['charset'];
		$this->signtype=$alipay_config['sign_type'];
		$this->dubug = is_bool($alipay_config['isDebug']) ? $alipay_config['isDebug'] : false;

		if(empty($this->appid)||trim($this->appid)==""){
			throw new \Exception("appid should not be NULL!");
		}
		if(empty($this->private_key)||trim($this->private_key)==""){
			throw new \Exception("private_key should not be NULL!");
		}
		if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
			throw new \Exception("alipay_public_key should not be NULL!");
		}
		if(empty($this->charset)||trim($this->charset)==""){
			throw new \Exception("charset should not be NULL!");
		}
		if(empty($this->gateway_url)||trim($this->gateway_url)==""){
			throw new \Exception("gateway_url should not be NULL!");
		}

	}
	function AlipayWapPayService($alipay_config) {
		$this->__construct($alipay_config);
	}

	/**
	 * alipay.trade.wap.pay
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @param $return_url 同步跳转地址，公网可访问
	 * @param $notify_url 异步通知地址，公网可以访问
	 * @return $response 支付宝返回的信息
 	*/
	function wapPay($builder,$return_url,$notify_url) {
	
		$biz_content=$builder->getBizContent();

		$request = new AlipayTradeWapPayRequest();
	
		$request->setNotifyUrl($notify_url);
		$request->setReturnUrl($return_url);
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request,true);
		// $response = $response->alipay_trade_wap_pay_response;
		return $response;
	}

	/**
	 * alipay.trade.pay
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @param $return_url 同步跳转地址，公网可访问
	 * @param $notify_url 异步通知地址，公网可以访问
	 * @return $response 支付宝返回的信息
	 */
	function NativePay($builder,$return_url,$notify_url) {

		$biz_content=$builder->getBizContent();

		$request = new AlipayTradePagePayRequest();

		$request->setNotifyUrl($notify_url);
		$request->setReturnUrl($return_url);
		$request->setBizContent ( $biz_content );

		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request,true);
		// $response = $response->alipay_trade_wap_pay_response;
		return $response;
	}

	 function aopclientRequestExecute($request,$ispage=false) {
		$aop = new AopClient ();
		$aop->gatewayUrl = $this->gateway_url;
		$aop->appId = $this->appid;
		$aop->rsaPrivateKey =  $this->private_key;
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$aop->apiVersion ="1.0";
		$aop->postCharset = $this->charset;
		$aop->format= $this->format;
		$aop->signType=$this->signtype;
		// 开启页面信息输出
		$aop->debugInfo=$this->dubug;
		if($ispage)
		{
			$result = $aop->pageExecute($request,"post");
			echo $result;
		}
		else 
		{
			$result = $aop->Execute($request);
		}

		return $result;
	}

	/**
	 * alipay.trade.query (统一收单线下交易查询)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
 	*/
	function Query($builder){
		$biz_content=$builder->getBizContent();
		$request = new AlipayTradeQueryRequest();
		$request->setBizContent ( $biz_content );

		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_query_response;
		return json_decode(json_encode($response),true);
	}
	
	/**
	 * alipay.trade.refund (统一收单交易退款接口)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function Refund($builder){
		$biz_content=$builder->getBizContent();
		$request = new AlipayTradeRefundRequest();
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_refund_response;
		return json_decode(json_encode($response),true);
	}

	/**
	 * alipay.trade.close (统一收单交易关闭接口)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function Close($builder){
		$biz_content=$builder->getBizContent();

		$request = new AlipayTradeCloseRequest();
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_close_response;
		return json_decode(json_encode($response),true);
	}
	
	/**
	 * 退款查询   alipay.trade.fastpay.refund.query (统一收单交易退款查询)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function refundQuery($builder){
		$biz_content=$builder->getBizContent();

		$request = new AlipayTradeFastpayRefundQueryRequest();
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		return json_decode(json_encode($response->alipay_trade_fastpay_refund_query_response),true);
	}

	/**
	 * alipay.trade.cancle (统一撤单交易关闭接口)
	 * @param $builder
	 * @return mixed
	 */
	public function CanclePay($builder)
	{
		$biz_content=$builder->getBizContent();

		$request = new AlipayTradeCancelRequest();
		$request->setBizContent ( $biz_content );

		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_trade_cancel_response;
		return json_decode(json_encode($response),true);
	}


	/**
	 * alipay.data.dataservice.bill.downloadurl.query (查询对账单下载地址)
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @return $response 支付宝返回的信息
	 */
	function downloadurlQuery($builder){
		$biz_content=$builder->getBizContent();
		$request = new AlipayDataDataserviceBillDownloadurlQueryRequest();
		$request->setBizContent ( $biz_content );
	
		// 首先调用支付api
		$response = $this->aopclientRequestExecute ($request);
		$response = $response->alipay_data_dataservice_bill_downloadurl_query_response;
		return $response;
	}

	/**
	 * 验签方法
	 * @param $arr 验签支付宝返回的信息，使用支付宝公钥。
	 * @return boolean
	 */
	function check($arr){
		$aop = new AopClient();
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$result = $aop->rsaCheckV1($arr, $this->alipay_public_key, $this->signtype);
		return $result;
	}
	
	//请确保项目文件有可写权限，不然打印不了日志。
	function writeLog($text) {
		file_put_contents ( dirname ( __FILE__ ).DIRECTORY_SEPARATOR."./../../log.txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
	}
}

?>