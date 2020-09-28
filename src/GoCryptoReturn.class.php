<?php

	namespace GoCrypto\SDK;

	use PDO;

	/**
	 * GoCryptoReturn
	 *
	 * @package GoCrypto\SDK
	 * @author THK <tilen@thk.si>
	 * @copyright 2020 THK
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class GoCryptoReturn {


		/**
		 * @var null|Db
		 */
		private $db;


		/**
		 * @var array get parameters
		 */
		private $get;


		/**
		 * GoCryptoReturn constructor
		 *
		 * @param Db $db
		 * @param array|null $get
		 */
		public function __construct(Db $db, ?array $get = null) {

			$this->db = $db;

			//Set to default $_GET
			if($get === null) $get = $_GET;
			$this->get = $get;

		}


		/**
		 * Determines whether payment is valid or not
		 *
		 * @return bool
		 */
		public function isValid() : bool {

			$nonce = isset($this->get['nonce']) ? trim($this->get['nonce']) : null;

			//Basic nonce syntax validation
			$nonceLen = strlen($nonce);
			if(empty($nonce) || $nonceLen < 220 || $nonceLen > 280) return false;

			$stmt =
				$this
					->getDb()
					->get()
					->prepare('
						SELECT id 
						FROM `' . $this->getDb()->getStorageTbl() . '` 
						WHERE 
							token=:nonce AND
							expires > :time
						LIMIT 1
					');

			$timeNow = time();
			$stmt->bindParam(':nonce', $nonce, PDO::PARAM_STR);
			$stmt->bindParam(':time', $timeNow, PDO::PARAM_INT);
			$stmt->execute();

			//Fetch nonce ID
			$nonceId = $stmt->fetch();

			//Determine success state
			$paymentSuccess = $nonceId !== false && isset($nonceId['id']);
			if($paymentSuccess) {

				//Delete nonce
				$this
					->getDb()
					->get()
					->prepare('DELETE FROM `' . $this->getDb()->getStorageTbl() . '` WHERE id=:id')
					->execute([
						'id' => $nonceId['id']
					]);
			}

			return $paymentSuccess;
		}


		/**
		 * @return Db
		 */
		public function getDb() : Db {
			return $this->db;
		}
	}