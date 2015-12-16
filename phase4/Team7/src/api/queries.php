<?php
global $queries;
$queries = array(
     "createUser" => function () {
        return  "INSERT INTO Login (Email, Password, salt, pin, TanMethod, isApproved, IsEmployee, Name)
            VALUES (?, ?, ?, ?, ?, False, ?, ?)";
    },
    "getUserByCredentials" => function () {
        return "Select UserId, isApproved From Login
            where Password = ? and Email = ?";
    },
    "getUserSalt" => function () { //TODO Secure?
        return "Select Name, salt From Login
            where Email = ?;";
    },
    "updateSessionId" => function(){
        return "Update Login
            set SessionId=?
            where UserId=?;";
    },
    "extendSessionTime" => function(){
        $date = new DateTime("now");
        $date->add(new DateInterval("PT20M"));
        return "Update Login
            set Session_expire=\"".$date->format('Y-m-d H:i:s')."\"
            where SessionId=?";
    },
    "getUserById" => function(){
        return "select Login.pin,Login.TanMethod, Login.UserId, Login.email,   Login.IsApproved, Login.IsEmployee, Login.Name,    
                Login.SessionId,   Login.Session_expire,  Login.wrong_password_counter,  Login.ban_end_time,  Account.Account_no, Account.Account_bal from Login
            Left Join Account
            On Login.UserId = Account.Account_LoginId
            where UserId = ?";
    },
    "getUserBySessionId" => function(){
        return "select Login.pin,Login.TanMethod, Login.UserId, Login.email,   Login.IsApproved, Login.IsEmployee, Login.Name,    
                Login.SessionId,   Login.Session_expire,  Login.wrong_password_counter,  Login.ban_end_time,  Account.Account_no, Account.Account_bal from Login
            Left Join Account
            On Login.UserId = Account.Account_LoginId
            where SessionId = ?
            and SessionId is not NULL";
    },
    "getUsersToApprove" => function(){
        return "select * from Login
          where IsApproved = False";
    },
    "approveUser" => function(){
        return "Update Login
          set isApproved=TRUE
          where UserId=?;";
    },
    "createAccount" => function(){
        return "INSERT INTO Account (Account_no, Account_LoginId, Account_bal)
	      VALUES (?, ?, ?);";
    },
    "addTAN" => function(){
        return "INSERT INTO ActiveTAN (ActiveTAN_number, ActiveTAN_AccountNo, ActiveTAN_Status, ActiveTAN_genDate)
            VALUES (?, ?, True, CURRENT_TIMESTAMP);";
    },
    "addUsedTAN" => function(){
        return "INSERT INTO ActiveTAN (ActiveTAN_number, ActiveTAN_AccountNo, ActiveTAN_Status, ActiveTAN_genDate)
            VALUES (?, ?, False, CURRENT_TIMESTAMP);";
    },
    "getTANs" => function(){
        return "SELECT ActiveTAN_number from ActiveTAN where
          ActiveTAN_AccountNo = ?;";
    },
    "deleteUser" => function(){
        return "DELETE FROM Login
          where UserId = ?;";
    },
    "getUsers" => function(){
        return "select * from Login";
    },
    "getTransactionHistory" => function($userId) {
        return "Select Txn_id, Txn_amount, SndAccount.Account_no as SndAccountNo, RcvAccount.Account_no as RcvAccountNo,
            SndLogin.Name as SndName, RcvLogin.Name as RcvName, Txn_date, Txn_description, Txn_ApprovalStatus as Status
            from Txn Join Account SndAccount On Txn.Txn_SndAccountNo = SndAccount.Account_no
              Join Login SndLogin on SndAccount.Account_LoginId = SndLogin.UserId
            Join Account RcvAccount On Txn.Txn_RcvAccountNo = RcvAccount.Account_no
              Join Login RcvLogin On RcvLogin.UserId = RcvAccount.Account_LoginId
            where SndAccount.Account_LoginId = $userId or RcvAccount.Account_LoginId = $userId

          order by Txn_date Desc ";
    },
    "getTransactionHistoryStmt" => function() { //TODO only keep one
        return "Select Txn_id, Txn_amount, Txn_TAN_used, SndAccount.Account_no as SndAccountNo, RcvAccount.Account_no as RcvAccountNo,
            SndLogin.Name as SndName, RcvLogin.Name as RcvName, Txn_date, Txn_description, Txn_ApprovalStatus, Txn_Status
            from Txn Join Account SndAccount On Txn.Txn_SndAccountNo = SndAccount.Account_no
              Join Login SndLogin on SndAccount.Account_LoginId = SndLogin.UserId
            Join Account RcvAccount On Txn.Txn_RcvAccountNo = RcvAccount.Account_no
              Join Login RcvLogin On RcvLogin.UserId = RcvAccount.Account_LoginId
            where SndAccount.Account_LoginId = ? or RcvAccount.Account_LoginId = ?

          order by Txn_date Desc ";
    },
    "getTransactionInfo" => function($txId) {
        return "Select Txn_amount, SndAccount.Account_no as SndAccountNo, RcvAccount.Account_no as RcvAccountNo,
            SndLogin.Name as SndName, RcvLogin.Name as RcvName, Txn_date
            from Txn Join Account SndAccount On Txn.Txn_SndAccountNo = SndAccount.Account_no
              Join Login SndLogin on SndAccount.Account_LoginId = SndLogin.UserId
            Join Account RcvAccount On Txn.Txn_RcvAccountNo = RcvAccount.Account_no
              Join Login RcvLogin On RcvLogin.UserId = RcvAccount.Account_LoginId
            where Txn_id = $txId;";
    },
    "getActiveTANStatus" => function($account, $tan){
        return "SELECT ActiveTAN_Status from ActiveTAN
          where ActiveTAN_number = \"$tan\" AND ActiveTAN_AccountNo = $account;";
    },
    "getInActiveTANStatus" => function($account, $tan){
        return "SELECT ActiveTAN_Status from ActiveTAN
          where ActiveTAN_number = \"$tan\" AND ActiveTAN_AccountNo = $account AND ActiveTAN_Status = 0;";
    },
    "createTx" => function(){
        return "INSERT INTO Txn (Txn_description, Txn_amount, Txn_TAN_used, Txn_date, Txn_SndAccountNo,
          Txn_RcvAccountNo, Txn_ApprovalStatus, Txn_Status)
          VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?,
          ?, \"PENDING\", \"PENDING\");";
    },
    "updateTAN" => function($tan, $accountNo){
        return "UPDATE ActiveTAN
          set ActiveTAN_Status = False
          where ActiveTAN_number = \"$tan\" AND ActiveTAN_AccountNo = $accountNo;";
    },
    "updateTxStatus" => function($txId, $approved, $status) {
        return "UPDATE Txn
          set Txn_Status=\"$status\", Txn_ApprovalStatus=\"$approved\" WHERE Txn_id = $txId;";
    },
    "updateTxSuccessful" => function($txId){
        return "UPDATE Txn
          set Txn_Status=\"APPROVED\", Txn_ApprovalStatus=\"APPROVED\"  WHERE Txn_id = $txId;";
    },
    "updateAccount" => function($account, $amount){
        return "UPDATE Account
          set Account_bal=Account_bal + $amount  WHERE Account_no = $account;";
    },
    "getTxById" => function($txId){
        return "Select * from Txn where Txn_id = $txId";
    },
    "getTxToApprove" => function(){
        return "Select * from Txn
          where Txn_Status=\"PENDING\" AND Txn_ApprovalStatus=\"PENDING\" AND Txn_amount >= 10000";
    },
    "getAccountNoOfUser" => function($userId){
        return "Select Account_no from Login join Account on UserId = Account_LoginId where UserId = $userId";
    },
    "getNumberOfTries" => function(){
        return "SELECT `wrong_password_counter`, `ban_end_time` FROM `Login` WHERE Email=?";
    },
    "updateNumberOfTries" => function(){
        return "UPDATE Login SET wrong_password_counter=?  WHERE Email=?;";
    },
    "updateBanTime" => function() {
        return "UPDATE Login SET ban_end_time=? WHERE Email=?;";
    },
    "getPasswordByUserId" => function(){
        return "SELECT salt, Name, Email, Password FROM Login WHERE UserId =?";
    },
    "updateUserPassword" => function(){
        return "UPDATE Login 
          set Password=?, salt=? WHERE UserId=?;";
    },
    "updateUserPasswordbyEmail" => function(){
        return "UPDATE Login 
          set Password=?, salt=?, Resetkey='NULL' WHERE Email=?"; 
    },
    "updateResetkey" => function () {
        return "UPDATE Login
            set Resetkey=? WHERE Email=?;";
    },
    "getUserEmail" => function(){
        return "SELECT Email, Name FROM Login WHERE Resetkey =?;";
    },
    "getReceiverAccount" => function(){
        return "SELECT * FROM Account WHERE Account_no =?;";
    }
)
?>
