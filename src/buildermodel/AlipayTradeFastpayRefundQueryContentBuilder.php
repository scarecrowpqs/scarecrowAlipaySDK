<?php
namespace ScarecrowAlipay\buildermodel;


class AlipayTradeFastpayRefundQueryContentBuilder
{

    // 商户订单号.
    private $outTradeNo;
    // 支付宝交易号
    private $tradeNo;  
    // 请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
    private $outRequestNo;
    
    private $bizContentarr = array();

    private $bizContent = NULL;

    public function getBizContent()
    {
        if(!empty($this->bizContentarr)){
            $this->bizContent = json_encode($this->bizContentarr,JSON_UNESCAPED_UNICODE);
        }
        return $this->bizContent;
    }

    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;
        $this->bizContentarr['trade_no'] = $tradeNo;
    }

    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->bizContentarr['out_trade_no'] = $outTradeNo;
    }
    public function getOutRequestNo()
    {
    	return $this->outRequestNo;
    }
    public function setOutRequestNo($outRequestNo)
    {
    	$this->outRequestNo = $outRequestNo;
    	$this->bizContentarr['out_request_no'] = $outRequestNo;
    }
}

?>