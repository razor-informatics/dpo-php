<?php

namespace RazorInformatics\DPOPhp;

use RuntimeException;

class Payment extends Service
{
	/**
	 * Make a direct card charge.
	 *
	 * @param string $reference
	 * @param int $serviceType
	 * @param float $paymentAmount
	 * @param int $cardNumber
	 * @param int $cardExpiry
	 * @param int $cardCvv
	 * @param string $customerFirstName
	 * @param string $customerLastName
	 * @param string $customerPhone
	 * @param string $customerEmail
	 * @param string $currency
	 * @param string $description
	 * @return array
	 */
	public function card(string $reference, int $serviceType, float $paymentAmount, int $cardNumber, int $cardExpiry, int $cardCvv, string $customerFirstName, string $customerLastName, string $customerPhone, string $customerEmail, string $currency = 'USD', string $description = '')
	{
		$token = $this->generateToken($reference, $serviceType, $paymentAmount, $customerFirstName, $customerLastName, $customerPhone, $customerEmail, $currency, $description);

		if ($token['status'] === Constants::STATUS_ERROR) {
			return $token;
		}

		$xmlData = '<?xml version="1.0" encoding="utf-8"?>
            <API3G>
              <CompanyToken>' . $this->companyToken . '</CompanyToken>
              <Request>chargeTokenCreditCard</Request>
              <TransactionToken>' . $token['data']['transToken'] . '</TransactionToken>
              <CreditCardNumber>' . $cardNumber . '</CreditCardNumber>
              <CreditCardExpiry>' . $cardExpiry . '</CreditCardExpiry>
              <CreditCardCVV>' . $cardCvv . '</CreditCardCVV>
              <CardHolderName>' . $customerFirstName . ' ' . $customerLastName . '</CardHolderName>
            </API3G>';

		try {
			$response = json_decode($this->_transact($xmlData), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}

		if (isset($response->Result)) {
			if ($response->Result === "000") {
				return $this->success(['result' => $response->Result, 'transToken' => $token['data']['transToken'], 'resultExplanation' => $response->ResultExplanation, 'transRef' => $token['data']['transRef'],]);
			}

			return $this->error($response->Result, $response->ResultExplanation);
		}

		return $this->error(400, "Unknown error occurred");
	}

	/**
	 * @param string $reference
	 * @param int $serviceType
	 * @param float $paymentAmount
	 * @param string $customerFirstName
	 * @param string $customerLastName
	 * @param string $customerPhone
	 * @param string $customerEmail
	 * @param string $currency
	 * @param string $description
	 * @return array
	 */
	protected function generateToken(string $reference, int $serviceType, float $paymentAmount, string $customerFirstName, string $customerLastName, string $customerPhone, string $customerEmail, string $currency, string $description = '')
	{
		return (new Token($this->endpoint, $this->companyToken))->create($reference, $serviceType, $paymentAmount, $customerFirstName, $customerLastName, $customerPhone, $customerEmail, $currency, $description);
	}

	/**
	 * Make an Mpesa Express Payment.
	 *
	 * @param string $reference
	 * @param int $serviceType
	 * @param float $paymentAmount
	 * @param string $customerFirstName
	 * @param string $customerLastName
	 * @param string $customerMpesaPhoneNumber
	 * @param string $customerEmail
	 * @param string $description
	 * @return array
	 */
	public function chargeMpesa(string $reference, int $serviceType, float $paymentAmount, string $customerFirstName, string $customerLastName, string $customerMpesaPhoneNumber, string $customerEmail, string $description = '')
	{

		$token = $this->generateToken($reference, $serviceType, $paymentAmount, $customerFirstName, $customerLastName, $customerMpesaPhoneNumber, $customerEmail, 'KES', $description);

		if ($token['status'] === Constants::STATUS_ERROR) {
			return $token;
		}

		$xmlData = '<?xml version="1.0" encoding="UTF-8"?>
            <API3G>
               <CompanyToken>' . $this->companyToken . '</CompanyToken>
              <Request>ChargeTokenMobile</Request>
               <TransactionToken>' . $token['data']['transToken'] . '</TransactionToken>
              <PhoneNumber>' . $customerMpesaPhoneNumber . '</PhoneNumber>
              <MNO>mpesa</MNO>
              <MNOcountry>kenya</MNOcountry>
            </API3G>';

		try {
			$response = json_decode($this->_transact($xmlData), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}


		if (isset($response->StatusCode) && $response->StatusCode === "130") {
			return $this->success(['result' => $response->StatusCode, 'transToken' => $token['data']['transToken'], 'resultExplanation' => 'Request sent to Mpesa', 'transRef' => $token['data']['transRef'],]);
		}

		if (isset($response->Result)) {
			return $this->error($response->Result, (isset($response->ResultExplanation)) ? $response->ResultExplanation : "Unknown error occurred");
		}

		return $this->error(400, "Unknown error occurred");
	}


	/**
	 * Issue a refund.
	 *
	 * @param string $transToken
	 * @param float $amount
	 * @param string $description
	 * @return array
	 */
	public function refund(string $transToken, float $amount, string $description = 'Refund')
	{
		$xmlData = '<?xml version="1.0" encoding="utf-8"?>
			<API3G>
			  <Request>refundToken</Request>
			  <CompanyToken>' . $this->companyToken . '</CompanyToken>
			  <TransactionToken>' . $transToken . '</TransactionToken>
			  <refundAmount>' . $amount . '</refundAmount>
			  <refundDetails>' . $description . '</refundDetails>
			</API3G>';

		try {
			$response = json_decode($this->_transact($xmlData), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}

		if (isset($response->Result)) {
			if ($response->Result === "000") {
				return $this->success(['result' => $response->Result, 'resultExplanation' => $response->ResultExplanation]);
			}

			return $this->error($response->Result, $response->ResultExplanation);
		}

		return $this->error(400, "Unknown error occurred");
	}
}