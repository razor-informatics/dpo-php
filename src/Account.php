<?php

namespace RazorInformatics\DPOPhp;

use RuntimeException;

class Account extends Service
{
	/**
	 * Account balance.
	 *KES
	 * @return array
	 */
	public function balance(string $currency = 'USD')
	{
		$xmlData = '<?xml version="1.0" encoding="utf-8"?>
                <API3G>
                  <Request>getBalance</Request>
                  <CompanyToken>' . $this->companyToken . '</CompanyToken>
                  <Currency>' . $currency . '</Currency>
                </API3G>';

		try {
			$response = json_decode($this->_transact($xmlData), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}

		return $this->success([
			'balance' => $response->CompanyBalance,
			'ExchangeRate' => $response->ExchangeRate
		]);
	}
}