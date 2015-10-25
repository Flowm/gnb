<?php

/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 25/10/15
 * Time: 17:06
 */
class transaction {
    public $id;
    public $approved_at;
    public $approved_by;
    public $src;
    public $dst;
    public $creation_date;
    public $amount;
    public $description;
    public $tan_id;

    public function __construct($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['approved_at'])) {
            $this->approved_at = $data['approved_at'];
        }
        if (isset($data['approved_by_user_id'])) {
            $this->approved_by = $data['approved_by_user_id'];
        }
        if (isset($data['source_account_id'])) {
            $this->src = $data['source_account_id'];
        }
        if (isset($data['destination_account_id'])) {
            $this->dst = $data['destination_account_id'];
        }
        if (isset($data['creation_timestamp'])) {
            $this->creation_date = $data['creation_timestamp'];
        }
        if (isset($data['amount'])) {
            $this->amount = $data['amount'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['tan_id'])) {
            $this->tan_id = $data['tan_id'];
        }
    }
}