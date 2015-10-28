INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Barney", "Stinson", "barney.stinson@gnb.com", "awesomesalt", "awesomehash", 1, 1, 1);

INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Ted", "Mosby", "ted.mosby@tedmosbyisajerk.com", "awesomesalt2", "awesomehash2", 0, 1, 1);



INSERT INTO account (balance, user_id)
VALUES (3000.00, 1);

INSERT INTO account (balance, user_id)
VALUES (1000.00, 2);



INSERT INTO tan (id, account_id)
VALUES ("ASDFGHJKL987JHT", 10000001);

INSERT INTO tan (id, account_id)
VALUES ("A345GHJKL987JHT", 10000001);

INSERT INTO tan (id, account_id)
VALUES ("A345GHJKL127JOT", 10000001);

INSERT INTO tan (id, account_id)
VALUES ("A345GHJ67ZT7JOT", 10000001);

INSERT INTO tan (id, account_id)
VALUES ("AZUIGHJ67ZT7JOT", 10000001);

INSERT INTO tan (id, account_id)
VALUES ("A12FGHJKL987JHT", 10000002);

INSERT INTO tan (id, account_id)
VALUES ("A345GHJKL98756T", 10000002);

INSERT INTO tan (id, account_id)
VALUES ("A345GHJKL12755T", 10000002);

INSERT INTO tan (id, account_id)
VALUES ("A3KRGHJ67ZT7JOT", 10000002);

INSERT INTO tan (id, account_id)
VALUES ("AZU34HJ67ZT0JOT", 10000002);



INSERT INTO transaction (source_account_id, destination_account_id, creation_timestamp, amount, description, tan_id, approved_at, approved_by_user_id)
VALUES (10000001, 10000002, "2015-10-01 15:00:00", 2000.00, "Take that money!", "A345GHJ67ZT7JOT", "2015-10-01 15:00:00", 1);
