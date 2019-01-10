<?php
namespace ScarecrowAlipay;

use ScarecrowAlipay\buildermodel\AlipayDataDataserviceBillDownloadurlQueryContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeCancleContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeCloseContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeFastpayRefundQueryContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradePagePayContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeQueryContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeRefundContentBuilder;
use ScarecrowAlipay\buildermodel\AlipayTradeWapPayContentBuilder;
use ScarecrowAlipay\service\AlipayTradeService;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/9 0009
 * Time: 上午 9:12
 */
class AopSdk{
	private $config;

	public function __construct($config=[])
	{
		if (empty($config)) {
			$this->config = require_once "config.php";
		}else{
			$this->config=$config;
		}
	}

	/**
	 * 手机网站支付接口
	 * @param $subject 订单名称，必填
	 * @param $out_trade_no 商户订单号，商户网站订单系统中唯一订单号，必填
	 * @param $total_amount 付款金额，必填
	 * @param $body 商品描述
	 * @param int $timeout_express 超时时间
	 * @return mixed
	 */
	public function WapPay($subject, $out_trade_no, $total_amount, $body="", $timeout_express="5m")
	{
		$payRequestBuilder = new AlipayTradeWapPayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setOutTradeNo($out_trade_no);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setTimeExpress($timeout_express);

		$payResponse = new AlipayTradeService($this->config);
		$result=$payResponse->wapPay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);
		return $result;
	}

	/**
	 * PC网站扫码支付接口
	 * @param $subject 订单名称，必填
	 * @param $out_trade_no 商户订单号，商户网站订单系统中唯一订单号，必填
	 * @param $total_amount 付款金额，必填
	 * @param $body 商品描述
	 * @param int $timeout_express 超时时间
	 * @return mixed
	 */
	public function NativePay($subject, $out_trade_no, $total_amount, $body="", $timeout_express="5m")
	{
		$payRequestBuilder = new AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setOutTradeNo($out_trade_no);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setTimeExpress($timeout_express);

		$payResponse = new AlipayTradeService($this->config);
		$result=$payResponse->NativePay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);
		return $result;
	}


	/**
	 * 交易查询接口
	 * @param $trade_no 支付宝交易号，和商户订单号二选一
	 * @param $out_trade_no 商户订单号，和支付宝交易号二选一
	 * @return [] 查询结果
	 */
	public function Query($trade_no = "", $out_trade_no = "")
	{
		if (empty($trade_no) && empty($out_trade_no)) {
			return [];
		}
		$RequestBuilder = new AlipayTradeQueryContentBuilder();
		$RequestBuilder->setTradeNo($trade_no);
		$RequestBuilder->setOutTradeNo($out_trade_no);
		$Response = new AlipayTradeService($this->config);
		$result=$Response->Query($RequestBuilder);
		return $result;
	}

	/**
	 * 退款接口
	 * @param $trade_no 支付宝交易号，和商户订单号二选一
	 * @param $out_trade_no 商户订单号，和支付宝交易号二选一
	 * @param $refund_amount 退款金额，不能大于订单总金额
	 * @param $refund_reason 退款的原因说明
	 * @param $out_request_no 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
	 * @return [] 退款结果
	 */
	public function Refund($trade_no, $out_trade_no, $refund_amount, $refund_reason = "", $out_request_no = "")
	{
		if (empty($trade_no) && empty($out_trade_no)) {
			return [];
		}
		$RequestBuilder = new AlipayTradeRefundContentBuilder();
		$RequestBuilder->setTradeNo($trade_no);
		$RequestBuilder->setOutTradeNo($out_trade_no);
		$RequestBuilder->setRefundAmount($refund_amount);
		$RequestBuilder->setRefundReason($refund_reason);
		$RequestBuilder->setOutRequestNo($out_request_no);

		$Response = new AlipayTradeService($this->config);
		$result=$Response->Refund($RequestBuilder);
		return $result;
	}

	/**
	 * 退款详情查询接口
	 * @param $trade_no 支付宝交易号，和商户订单号二选一
	 * @param $out_trade_no 商户订单号，和支付宝交易号二选一
	 * @param $out_request_no 请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
	 * @return [] 查询结果
	 */
	public function RefundQuery($trade_no, $out_trade_no, $out_request_no = "")
	{
		if (empty($trade_no) && empty($out_trade_no)) {
			return [];
		}
		$RequestBuilder = new AlipayTradeFastpayRefundQueryContentBuilder();
		$RequestBuilder->setTradeNo($trade_no);
		$RequestBuilder->setOutTradeNo($out_trade_no);
		$RequestBuilder->setOutRequestNo($out_request_no);

		$Response = new AlipayTradeService($this->config);
		$result=$Response->refundQuery($RequestBuilder);
		return $result;
	}

	/**
	 * 关闭交易接口
	 * @param $trade_no 支付宝交易号，和商户订单号二选一
	 * @param $out_trade_no 商户订单号，和支付宝交易号二选一
	 * @return [] 请求结果
	 */
	public function ClosePay($trade_no = "", $out_trade_no = "")
	{
		if (empty($trade_no) && empty($out_trade_no)) {
			return [];
		}
		$RequestBuilder = new AlipayTradeCloseContentBuilder();
		$RequestBuilder->setTradeNo($trade_no);
		$RequestBuilder->setOutTradeNo($out_trade_no);

		$Response = new AlipayTradeService($this->config);
		$result=$Response->Close($RequestBuilder);
		return $result;
	}

	/**
	 * 账单下载接口
	 * @param $bill_type trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
	 * @param $bill_date 账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
	 * @return bool|mixed|aop\提交表单HTML文本|\SimpleXMLElement|\SimpleXMLElement[]|string
	 */
	public function dataDownload($bill_type, $bill_date)
	{
		$RequestBuilder = new AlipayDataDataserviceBillDownloadurlQueryContentBuilder();
		$RequestBuilder->setBillType($bill_type);
		$RequestBuilder->setBillDate($bill_date);
		$Response = new AlipayTradeService($this->config);
		$result=$Response->downloadurlQuery($RequestBuilder);
		return $result;
	}

	/**
	 * 撤单接口
	 * @param string $trade_no 支付宝交易号
	 * @param string $out_trade_no 商家订单号
	 * @return array|mixed
	 */
	public function CanclePay($trade_no = "", $out_trade_no = "")
	{
		if (empty($trade_no) && empty($out_trade_no)) {
			return [];
		}
		$RequestBuilder = new AlipayTradeCancleContentBuilder();
		$RequestBuilder->setTradeNo($trade_no);
		$RequestBuilder->setOutTradeNo($out_trade_no);
		$Response = new AlipayTradeService($this->config);
		$result=$Response->CanclePay($RequestBuilder);
		return $result;
	}

	/**
	 * 验签接口
	 * @param array $arr
	 * @return bool
	 */
	public function check($arr = [])
	{
		$Response = new AlipayTradeService($this->config);
		$bool = $Response->check($arr);
		return $bool;
	}

	/**
	 * 作者
	 */
	public function author()
	{
		echo "Scarecrow";
	}
}