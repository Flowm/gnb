<?php

require_once __DIR__."/../resource_mappings.php";
require_once getPageAbsolute("db_functions");

require_once getPageAbsolute("transaction");

class account {
    public $id;
    public $balance;
    public $transactions;

    public function __construct($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['balance'])) {
            $this->balance = $data['balance'];
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
			while(!insertTAN($newtan, $this->id)) {
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
