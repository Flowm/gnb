<?php

require_once __DIR__."/../resource_mappings.php";
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
            array_push($this->transactions, $data[i]);
        }
    }

	public function generateTANs($cnt=100) {
		$tans = array();
		for($i=0;$i<$cnt;$i++) {
			$newtan = $this->genRandString(15);
			while(!insertTAN($newtan, $this->id)) {
				$newtan = genString(15);
			}
			$tans[$i] = $newtan;
		}
		return $tans;
	}

	private function genRandString($length) {
		return bin2hex(openssl_random_pseudo_bytes($length));
	}
}
