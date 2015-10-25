<?php

/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 25/10/15
 * Time: 12:14
 */

include "transaction.php";

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
}