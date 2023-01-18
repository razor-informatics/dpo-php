<?php

namespace RazorInformatics\DPOPhp;


class DPOPhp
{
	protected const DPO_GROUP_URL_LIVE = 'https://secure.3gdirectpay.com';
	protected const ENDPOINT = self::DPO_GROUP_URL_LIVE . "/API/v6/";

	protected $endpoint, $companyToken, $serviceType;

	public function __construct(string  $companyToken, int $serviceType)
	{
		$this->companyToken = $companyToken;
		$this->serviceType = $serviceType;
		$this->endpoint =  self::ENDPOINT;
	}

}