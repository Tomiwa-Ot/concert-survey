CREATE TABLE concerts(
	id int AUTO_INCREMENT NOT NULL,
	title varchar(255) NOT NULL,
	active int DEFAULT 1,
	PRIMARY KEY(id)
);


CREATE TABLE contact(
	id int AUTO_INCREMENT NOT NULL,
	name varchar(45) NOT NULL,
	value varchar(200) NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO contact(name, value) VALUES("email", "San Francisco, CA 94126, USA");
INSERT INTO contact(name, value) VALUES("phone", "+ 01 234 567 89");
INSERT INTO contact(name, value) VALUES("address", "contact@mdbootstrap.com");

CREATE TABLE faq(
	id int AUTO_INCREMENT NOT NULL,
	question varchar(2000) NOT NULL,
	answer varchar(2000) NOT NULL,
	PRIMARY KEY(id)
);


CREATE TABLE messages(
	id int AUTO_INCREMENT NOT NULL,
	subject varchar(200) NOT NULL,
	name varchar(200) NOT NULL,
	email varchar(200) NOT NULL,
	message varchar(5000) NOT NULL,
	read int DEFAULT 0,
	date datetime
	PRIMARY KEY(id)
);


CREATE TABLE users(
	id int AUTO_INCREMENT NOT NULL,
	name varchar(45) NOT NULL,
	password varchar(200) NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO users(name, password) VALUES("admin", "$2y$10$URCqNYWSzxKQVca/etwFYeOnnb04WMf9R25mF6mone0bhgxL3eHP6");
