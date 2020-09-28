<?php

	namespace GoCrypto\SDK;

	use PDO;
	use PDOException;

	/**
	 * Db
	 *
	 * @package GoCrypto\SDK
	 * @author THK <tilen@thk.si>
	 * @copyright 2020 THK
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class Db {


		/**
		 * @var null|PDO PDO instance
		 */
		private $instance = null;


		/**
		 * @var string name of the table where nonces are stored
		 */
		private $storageTbl = 'payment_nonce';


		/**
		 * Db constructor
		 *
		 * @param string $dbHost
		 * @param string $dbName
		 * @param string $dbUser
		 * @param string $dbPassword
		 * @param string|null $storageTbl
		 */
		public function __construct(

			string $dbHost,
			string $dbName,
			string $dbUser,
			string $dbPassword,
			?string $storageTbl = null

		) {

			if(!empty($storageTbl)) $this->storageTbl = $storageTbl;

			//Create PDO instance
			$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false,
			];
			try {
				$this->instance = new PDO($dsn, $dbUser, $dbPassword, $options);
			} catch(PDOException $e) {
				throw new PDOException($e->getMessage(), (int) $e->getCode());
			}

		}


		/**
		 * @return PDO|null
		 */
		public function get() : ?PDO {
			return $this->instance;
		}


		/**
		 * @return string fetches storage table name
		 */
		public function getStorageTbl() : string {
			return $this->storageTbl;
		}
	}