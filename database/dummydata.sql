INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Barney", "Stinson", "barney.stinson@gnb.com", "329b4c76", "747114b33a0e07f6a6d6d292955678b81d91346fee8e2b6146ba2c0f889a0a78525d280675214ed81b9f45863a85390dc2c6c754c6a5dcbc7c7a69465c86f913", 1, 1, 1);

INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Ted", "Mosby", "ted.mosby@gnb.com", "b8bd380b", "75d512fae191058b320dd7341f45ebf03c6ed04b887cb3a9a4e0175cacab3a185e3ad1c331e557acba2ed140689e577f06c2c62478cbf15a5b5eccc4b9ce7b8e", 1, 1, 1);


INSERT INTO account (user_id)
VALUES (1);

INSERT INTO account (user_id)
VALUES (2);


INSERT INTO tan (id, account_id, used_timestamp)
VALUES ("e78fdd5ba612275", 10000001, "2000-01-01 12:00:00");


INSERT INTO transaction (
	approved_by_user_id,
	approved_at,
	status,
	source_account_id,
	destination_account_id,
	creation_timestamp,
	amount,
	description,
	tan_id
)
VALUES (
	"1",
	"2000-01-01 12:00:00",
	"1",
	"10000001",
	"10000002",
	"2000-01-01 12:00:00",
	"15000,00",
	"For my best friend",
	"e78fdd5ba612275"
);
