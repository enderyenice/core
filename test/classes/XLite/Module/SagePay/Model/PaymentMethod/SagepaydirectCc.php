<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* SagePay processor unit. This implementation complies the following
*
* @package Module_SagePay
* @access public
* @version $Id$
*/
class XLite_Module_SagePay_Model_PaymentMethod_SagepaydirectCc extends XLite_Model_PaymentMethod_CreditCard
{
	var $configurationTemplate = "modules/SagePay/config.tpl";
    var $hasConfigurationForm = true;
    var $processorName = "SagePay VSP Direct";

    function process(&$cart)
    {
		require_once("modules/SagePay/encoded.php");
		return func_SagePayDirect_process($this, $cart);
    }

	function prepareUrl($url)
	{
		return htmlspecialchars($url);
	}

	function getReturnUrl() // {{{ 
	{
		$url = $this->xlite->shopURL("cart.php?target=sagepaydirect_checkout&action=return", $this->get("config.Security.customer_security"));
		return $this->prepareUrl($url);
	}   // }}}

	function getServiceUrl($type="purchase", $is_simulator=false)
	{
		if ($is_simulator) {
			switch ($type) {
				case "callback":
                    return "https://test.sagepay.com:443/Simulator/VSPDirectCallback.asp";
				case "refund":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorRefundTx";
				case "release":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorReleaseTx";
				case "repeat":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorRepeatTx";
				 case "purchase":
				 default:
                     return "https://test.sagepay.com:443/Simulator/VSPDirectGateway.asp";
			}
		}

		$subtag = (($this->get("params.testmode") == "N") ? "live" : "test");
		switch ($type) {
			case "callback":
                return "https://$subtag.sagepay.com:443/gateway/service/direct3dcallback.vsp";
			case "refund":
                return "https://$subtag.sagepay.com:443/gateway/service/refund.vsp";
			case "release":
                return "https://$subtag.sagepay.com:443/gateway/service/release.vsp";
			case "repeat":
                return "https://$subtag.sagepay.com:443/gateway/service/repeat.vsp";
			case "purchase":
			default:
                return "https://$subtag.sagepay.com:443/gateway/service/vspdirect-register.vsp";
		}
	}

	function getOrderAuthStatus() // {{{
	{
		$status = "auth";
		$param = "status_$status";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return "Q";
		}
	} // }}}

	function getOrderRejectStatus() // {{{
	{
		$status = "reject";
		$param = "status_$status";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return "F";
		}
	} // }}}

	function getOrderSuccessNo3dStatus() // {{{
	{
		$status = "success_no3d";
		$param = "status_$status";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return "P";
		}
	} // }}}

	function getOrderSuccess3dOkStatus() // {{{
	{
		$status = "success_3dok";
		$param = "status_$status";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return "P";
		}
	} // }}}

	function getOrderSuccess3dFailStatus() // {{{
	{
		$status = "success_3dfail";
		$param = "status_$status";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return "Q";
		}
	} // }}}

	function handleConfigRequest() // {{{
	{
		$params = $_POST["params"];

		$statuses = array("auth", "reject", "success_no3d", "success_3dok", "success_3dfail");
		foreach ($statuses as $name) {
			$field = "status_" . $name;
			$result = $params[$field];
			if ($this->xlite->AOMEnabled) {
				$status = new XLite_Module_AOM_Model_OrderStatus();
				if ($status->find("status='".$params[$field]."'")) {
					if ($status->get("parent")) {
						$params[$field] = $status->get("parent");
						$result = $status->get("status");
					}
				}
			}
			$params["sub".$field] = $result;
		}

		$pm = new XLite_Model_PaymentMethod("sagepaydirect_cc");
		$pm->set("params", $params);
		$pm->update();
	} // }}}

    function getCCDetails()
    {
		$request = array();

        $request["CardHolder"] = $this->cc_info["cc_name"];
        $request["CardNumber"] = $this->cc_info["cc_number"];
        $request["ExpiryDate"] = $this->cc_info["cc_date"];
        $request["CV2"]        = $this->cc_info["cc_cvv2"];
        $request["CardType"]   = $this->cc_info["cc_type"];

        // Add additional informations
        switch ($request["CardType"]) {
            case "SW":
                $request["CardType"] = "SWITCH";
            break;
            case "SO":
                $request["CardType"] = "SOLO";
                if (isset($this->cc_info["cc_start_date"])) {
                    $request["StartDate"] = $this->cc_info["cc_start_date"];
                }
                if (isset($this->cc_info["cc_issue"])) {
                    $request["IssueNumber"] = $this->cc_info["cc_issue"];
                }
            break;
            case "AMEX":
                if (isset($this->cc_info["cc_start_date"])) {
                    $request["StartDate"] = $this->cc_info["cc_start_date"];
                }
            break;
        }

		return $request;
    }

	function getClientIP()
	{
		return $_SERVER["REMOTE_ADDR"];
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
