<?php

include "account.php";

/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 24/10/15
 * Time: 08:57
 */
class user {
    public $id;
    public $email;
    public $firstname;
    public $lastname;
    public $status;
    public $role;
    public $approved_by;
    public $password;
    public $accounts;

    public function __construct($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['first_name'])) {
            $this->firstname = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $this->lastname = $data['last_name'];
        }
        if (isset($data['pw_hash'])) {
            $this->password = $data['pw_hash'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }
        $this->accounts = array();
        if (isset($data['accounts'])) {
            //SET ACCOUNTS
        }
    }

    public function setAccounts($data) {
        for ($i=0; $i<count($data); $i++) {
            $element = $data[$i];
            $account = new account($element);
            array_push($this->accounts, $account);
        }
    }

    public function getBasicInfo() {
        return array('id'=>$this->id,
            'email'=>$this->email,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'status'=>$this->status,
            'role'=>$this->role);
    }
}