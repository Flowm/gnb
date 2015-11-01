INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Barney", "Stinson", "barney.stinson@gnb.com", "0", "barney", 1, 1, 1);

INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Ted", "Mosby", "ted.mosby@tedmosbyisajerk.com", "0", "ted", 0, 1, 1);


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
