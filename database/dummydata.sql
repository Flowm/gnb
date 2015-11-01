INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Barney", "Stinson", "barney.stinson@gnb.com", "329b4c76", "934f351a22d36e4b6aabc5a4137ef5ce6ca9100150ffd18ff6fcf0633289263b86e41320925a87fe93ab1e1eb46e35f0b38f9dd4b7c3b3b1a5a732a79c144d1b", 1, 1, 1);

INSERT INTO user (first_name, last_name, email, pw_salt, pw_hash, role, approved_by_user_id, status)
VALUES ("Ted", "Mosby", "ted.mosby@gnb.com", "b8bd380b", "5d22f5681710e13cacbac6a3bcc293013ee3a286cd8137e4244f768cad101d1a00dc9f052e23ac7cb0f389a4f8a38683f337fc5e5112a771aa084c015602b195", 1, 1, 1);


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
