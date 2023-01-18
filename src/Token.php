<?php
namespace RazorInformatics\DPOPhp;

use RuntimeException;

class Token extends Service
{
	/**
	 * Verify Token.
	 *
	 * @param string $transToken
	 * @return array
	 */
	public function verify(string $transToken)
	{
		$xmlData = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<API3G>\r\n  <CompanyToken>" . $this->companyToken . "</CompanyToken>\r\n  <Request>verifyToken</Request>\r\n  <TransactionToken>" . $transToken . "</TransactionToken>\r\n</API3G>";

		try {
			$response = json_decode($this->_transact($xmlData), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}

		if (isset($response->Result)) {
			return $this->success(['result' => $response->Result, 'resultExplanation' => $response->ResultExplanation,]);
		}

		return $this->error(400, 'Data mismatch in one of the fields - TransactionToken');
	}

	/**
	 * Create a DPO_Group token for payment processing
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function create(string $reference, int $serviceType, float $paymentAmount, string $customerFirstName, string $customerLastName, string $customerPhone, string $customerEmail, string $currency = 'USD', string $description = ''): array
	{

		$customerPhone = preg_replace('/\D/', '', $customerPhone);
		// Truncate number if over 20 characters
		$customerPhone = strlen($customerPhone) > 20 ? substr($customerPhone, 0, 20) : $customerPhone;
		// Pad left with zeros if under 6 characters
		$customerPhone = str_pad($customerPhone, 6, "0", STR_PAD_LEFT);


		$postXml = '<?xml version="1.0" encoding="utf-8"?>
            <API3G> <CompanyToken>' . $this->companyToken . '</CompanyToken> <Request>createToken</Request> 
            <Transaction> 
            	<PaymentAmount>' . $paymentAmount . '</PaymentAmount>
             	<PaymentCurrency>' . $currency . '</PaymentCurrency> 
             	<CompanyRef>' . $reference . '</CompanyRef>
           		<customerFirstName>' . $customerFirstName . '</customerFirstName>
           		<customerLastName>' . $customerLastName . '</customerLastName>
           		<customerPhone>' . $customerPhone . '</customerPhone>
           		<customerEmail>' . $customerEmail . '</customerEmail> 
           		<TransactionSource>API</TransactionSource> 
            </Transaction>
             <Services> 
             	<Service> 
             		<ServiceType>' . $serviceType . '</ServiceType> 
             		<ServiceDescription>' . $description . '</ServiceDescription> 
             		<ServiceDate>' . date('Y/m/d H:i') . '</ServiceDate> 
                </Service> 
            </Services>
          </API3G>';
		try {
			$response = json_decode($this->_transact($postXml), false);
		} catch (RuntimeException $exception) {
			return $this->error($exception->getCode(), $exception->getMessage());
		}

		if (isset($response->Result)) {
			if ($response->Result === "000") {
				return $this->success([
					'result' => $response->Result,
					'transToken' => $response->TransToken,
					'resultExplanation' => $response->ResultExplanation,
					'transRef' => $response->TransRef,
				]);
			}

			return $this->error($response->Result, $response->ResultExplanation);
		}

		return $this->error(400, "Unknown error occurred");
	}
}