<?php

	namespace GoCrypto\SDK;

	use Throwable;

	/**
	 * GoCrypto
	 *
	 * @package GoCrypto\SDK
	 * @author THK <tilen@thk.si>
	 * @copyright 2020 THK
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class GoCrypto {


		/**
		 * @var string|null client ID
		 */
		private $clientId;


		/**
		 * @var string|null client secret key
		 */
		private $clientSecret;


		/**
		 * @var array API urls
		 */
		private $apiUrls = ['https://ecommerce.staging.gocrypto.com/api', 'https://ecommerce.gocrypto.com/api'];


		/**
		 * @var string|null active API url
		 */
		private $apiUrl;


		/**
		 * @var string locale key
		 */
		private $locale = 'en';


		/**
		 * @var string currency ISO
		 */
		private $currency = 'EUR';


		/**
		 * @var string shop name
		 */
		private $shopName;


		/**
		 * @var array list of all items
		 */
		private $items = [];


		/**
		 * @var string|null URL to which request will be redirected upon success
		 */
		private $returnUrl;


		/**
		 * @var string|null URL to which request will be redirected upon cancellation
		 */
		private $cancelUrl;


		/**
		 * GoCrypto constructor
		 *
		 * @param string $clientId
		 * @param string $clientSecret
		 * @param bool $useProduction
		 * @param string $shopName
		 * @param string|null $returnUrl
		 * @param string|null $cancelUrl
		 */
		public function __construct(
			string $clientId,
			string $clientSecret,
			bool $useProduction,
			string $shopName,
			string $returnUrl,
			?string $cancelUrl = null
		) {

			$this->clientId = $clientId;
			$this->clientSecret = $clientSecret;
			$this->apiUrl = $this->apiUrls[$useProduction ? 1 : 0];
			$this->shopName = $shopName;
			$this->returnUrl = $returnUrl;
			$this->cancelUrl = empty($cancelUrl) ? $returnUrl : $cancelUrl;

		}


		/**
		 * Fetches auth token for further requests
		 *
		 * @return string|null
		 */
		private function getAuthToken() : ?string {
			$res = $this->post('auth', [], [
				'Content-Type: application/json',
				'X-ELI-Client-Id: ' . $this->getClientId(),
				'X-ELI-Client-Secret: ' . $this->getClientSecret()
			]);

			return (empty($res) || !isset($res['data']) || !isset($res['data']['access_token'])) ? null : $res['data']['access_token'];
		}


		/**
		 * Fetches payment redirect
		 *
		 * @return string|null
		 */
		public function requestPayment() : ?string {

			//Preliminary checks
			$token = $this->getAuthToken();
			if(empty($token)) return null;


			//Build request headers
			$headers = [
				'X-ELI-Access-Token: ' . $token,
				'X-ELI-Locale: ' . $this->getLocale(),
				'Content-Type: application/json',
				'User-Agent: ' . isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
			];


			//Build request body
			$body = [
				'shop_name' => $this->getShopName(),
				'amount' => $this->getAmounts(),
				'items' => $this->items,
				'return_url' => $this->getReturnUrl(),
				'cancel_url' => $this->getCancelUrl(),
			];


			//Submit charges
			$res = $this->post('charges', $body, $headers);

			//Check success state
			if(empty($res || !isset($res['data']) || !isset($res['data']['access_token']) || intval($res['data']['status']) !== 1)) return null;

			//We need to output only the URL to which client must be redirected in order to complete payment
			return $res['data']['redirect_url'];
		}


		/**
		 * @return array
		 */
		private function getAmounts() : array {

			$total = 0;
			$feeablePart = 0;

			//Dynamically calculate total amounts
			foreach($this->items as $item) {
				$total += round($item['price'] * $item['qty'], 2);
				$feeablePart += round($item['feeable'] * $item['qty'], 2);
			}

			return [
				'total' => $total,
				'feeable_part' => $feeablePart,
				'currency' => $this->getCurrency()
			];
		}


		/**
		 * Makes a post request to the API
		 *
		 * @param string $endpoint
		 * @param array $postData
		 * @param array $headers
		 *
		 * @return array|null
		 */
		private function post(string $endpoint, array $postData = [], array $headers = []) : ?array {

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $this->getApiUrl() . '/' . $endpoint);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 70);

			if(!empty($postData)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
			if(!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


			$response = curl_exec($ch);
			curl_close($ch);

			try {

				//Obtain JSON response and decode it to an array
				return json_decode($response, true);

			} catch(Throwable $e) {
				return null;
			}
		}


		/**
		 * @return string|null
		 */
		public function getClientId() : ?string {
			return $this->clientId;
		}


		/**
		 * @return string|null
		 */
		public function getClientSecret() : ?string {
			return $this->clientSecret;
		}


		/**
		 * @return string|null
		 */
		public function getApiUrl() : ?string {
			return $this->apiUrl;
		}


		/**
		 * @return string
		 */
		public function getLocale() : string {
			return $this->locale;
		}


		/**
		 * @param string $locale
		 *
		 * @return GoCrypto
		 */
		public function setLocale(string $locale) : GoCrypto {
			$this->locale = $locale;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getCurrency() : string {
			return $this->currency;
		}


		/**
		 * @param string $currency
		 *
		 * @return GoCrypto
		 */
		public function setCurrency(string $currency) : GoCrypto {
			$this->currency = $currency;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getShopName() : string {
			return $this->shopName;
		}


		/**
		 * Adds an item to the checkout
		 *
		 * @param string $name
		 * @param string|null $description
		 * @param int $quantity
		 * @param float $price
		 * @param float|null $feeable
		 * @param string|null $ean
		 *
		 * @return $this
		 */
		public function addItem(string $name, ?string $description, int $quantity, float $price, ?float $feeable = null, ?string $ean = null) : GoCrypto {

			//Append the item
			$this->items[] = [
				'name' => $name,
				'description' => $description,
				'qty' => $quantity,
				'price' => $price,
				'ean' => $ean,
				'feeable' => $feeable === null ? $price : $feeable
			];

			return $this;
		}


		/**
		 * @return string|null
		 */
		public function getReturnUrl() : ?string {
			return $this->returnUrl;
		}


		/**
		 * @return string|null
		 */
		public function getCancelUrl() : ?string {
			return $this->cancelUrl;
		}

	}