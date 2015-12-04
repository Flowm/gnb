<?php

require_once __DIR__."/../resource_mappings.php";
require_once getPageAbsolute("db_functions");

require_once getPageAbsolute("transaction");

class account {
    public $id;
    public $balance;
    public $last_tan_time;
    public $transactions;

    public function __construct($data) {
        if (isset($data[DB::i()->ACCOUNTOVERVIEW_TABLE_KEY])) {
            $this->id = $data[DB::i()->ACCOUNTOVERVIEW_TABLE_KEY];
        }
        if (isset($data[DB::i()->ACCOUNTOVERVIEW_TABLE_BALANCE])) {
            $this->balance = $data[DB::i()->ACCOUNTOVERVIEW_TABLE_BALANCE];
        }
        if (isset($data[DB::i()->ACCOUNTOVERVIEW_TABLE_TAN_TIME])) {
            $this->last_tan_time = $data[DB::i()->ACCOUNTOVERVIEW_TABLE_TAN_TIME];
        }

        $this->transactions = array();
    }

    public function setTransactions($data) {
        for ($i=0; $i<count($data); $i++) {
            array_push($this->transactions, new transaction($data[$i]));
        }
    }

	public function generateTANs($cnt=100) {
		$tans = array();
		for($i=0;$i<$cnt;$i++) {
			$retry=5;
			$newtan = $this->genRandString(15);
			while(!DB::i()->insertTAN($newtan, $this->id)) {
				$newtan = $this->genRandString(15);
				if ($retry-- <= 0) {
					return false;
				}
			}
			$tans[$i] = $newtan;
		}
		return $tans;
	}

	public function genRandString($length) {
		return substr(bin2hex(openssl_random_pseudo_bytes(round($length/2))), 0, $length);
	}
}
