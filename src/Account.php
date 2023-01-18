<?php

namespace RazorInformatics\DPOPhp;

class Account extends Service
{
	/**
	 * Account balance.
	 *KES
	 * @return array
	 */
	public function balance(string  $currency = 'USD')
	{
		$xmlData = '<?xml version="1.0" encoding="utf-8"?>
                <API3G>
                  <Request>getBalance</Request>
                  <CompanyToken>' . $this->companyToken . '</CompanyToken>
                  <Currency>'.$currency.'</Currency>
                </API3G>';

		$response = json_decode($this->_transact($xmlData), false);

		return $this->success([
			'balance' => $response->CompanyBalance,
			'ExchangeRate' => $response->ExchangeRate
		]);
	}
}