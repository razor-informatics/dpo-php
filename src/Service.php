<?php

namespace RazorInformatics\DPOPhp;

use RuntimeException;

abstract class Service
{
	protected $endpoint, $companyToken;

	public function __construct(string $endPoint, string $companyToken)
	{
		$this->companyToken = $companyToken;
		$this->endpoint = $endPoint;
	}

	/**
	 * Remove <br> and <hr> confusing simplexml_load_string.
	 *
	 * @param string $string
	 * @return string
	 */
	private function _removeHtml(string $string)
	{
		$string = preg_replace("/<br\W*?\/>/", " ", $string);
		$string = preg_replace("/<br>/", " ", $string);
		$string = preg_replace("/<hr>/", " ", $string);
		return preg_replace("/<hr\W*?\/>/", " ", $string);
	}

	/**
	 * @param $statusCode
	 * @param string $message
	 * @param array $data
	 * @return array
	 */
	protected function error($statusCode, $message = '', $data = [])
	{
		switch ($statusCode) {
			case 0:
				$message = "Application could not reach our servers.";
				break;
			case 404:
				$message = "Data requested was not found";
				break;
			case 429:
				$message = "Too many requests.";
				break;
			case 500:
				$message = "Server Error - Unhandled error happen on our end";
				break;
			case 503:
				$message = "Server Maintenance";
				break;
			default:
				if (is_null($message)) {
					$message = "some error occurred !";
				}
		}
		return [
			'status' => Constants::STATUS_ERROR,
			'code' => $statusCode,
			'message' => $message,
			'data' => $data
		];
	}

	protected function _transact(string $xmlData)
	{
		$curl = curl_init();

		if (!$curl) {
			throw new RuntimeException('Could not load curl, check server configuration.');
		}

		curl_setopt($curl, CURLOPT_URL, $this->endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, "");
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ["cache-control: no-cache", 'Content-Type: application/xml']);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlData);

		$result = curl_exec($curl);
		curl_close($curl);

		if (curl_errno($curl)) {
			throw new RuntimeException('DPO Service Transact : ' . curl_errno($curl));
		}

		$result_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($result_code>=300){
			throw new RuntimeException('Service error ',$result_code);
		}

		return json_encode(simplexml_load_string($this->_removeHtml($result)));
	}


	/**
	 * @param array $data
	 * @return array
	 */
	protected function success(array $data)
	{
		return [
			'status' => Constants::STATUS_SUCCESS,
			'data' => $data
		];
	}
}