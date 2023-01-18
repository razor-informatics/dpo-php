<?php
namespace RazorInformatics\DPOPhp;

class DPOPhp
{
	protected const DPO_GROUP_URL_LIVE = 'https://secure.3gdirectpay.com';
	protected const ENDPOINT = self::DPO_GROUP_URL_LIVE . "/API/v6/";

	protected $endpoint, $companyToken;

	public function __construct(string $companyToken)
	{
		$this->companyToken = $companyToken;
		$this->endpoint = self::ENDPOINT;
	}

	/**
	 * @return Account
	 */
	public function account()
	{
		return new Account($this->endpoint, $this->companyToken);
	}

	/**
	 * @return Token
	 */
	public function token()
	{
		return new Token($this->endpoint, $this->companyToken);
	}

	/**
	 * @return Payment
	 */
	public function payment()
	{
		return new Payment($this->endpoint, $this->companyToken);
	}
}