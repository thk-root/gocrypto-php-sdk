<?php

	namespace GoCrypto\SDK;

	/**
	 * GoCryptoStaging
	 *
	 * @package GoCrypto\SDK
	 * @author THK <tilen@thk.si>
	 * @copyright 2020 THK
	 * @license http://www.opensource.org/licenses/MIT The MIT License
	 */
	class GoCryptoStaging extends GoCrypto {

		/**
		 * GoCryptoStaging constructor
		 *
		 * @param Db $db
		 * @param string $shopName
		 * @param string $returnUrl
		 * @param string|null $cancelUrl
		 */
		public function __construct(Db $db, string $shopName, string $returnUrl, ?string $cancelUrl = null) {
			parent::__construct(
				$db,
				'c83aa8ac-b29a-41a6-b039-3ed5ac69acff',
				'xzOOGVskFG2IeV2W3SFFFbEPC8vMDflz2k6LjrNXDCM4BFvH',
				false,
				$shopName,
				$returnUrl,
				$cancelUrl
			);
		}

	}