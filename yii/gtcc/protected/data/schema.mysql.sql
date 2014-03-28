CREATE TABLE tbl_user (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(128) NOT NULL,
    pwd VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL
);

CREATE TABLE tbl_book (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    isbn VARCHAR(48) NOT NULL,
    bookname VARCHAR(128) NOT NULL,
    price int(128) NOT NULL
);

CREATE TABLE tbl_book (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    isbn VARCHAR(48) NOT NULL,
    bookname VARCHAR(128) NOT NULL,
    price int(128) NOT NULL
);

INSERT INTO tbl_user (username, pwd, email) VALUES ('test1', 'pass1', 'test1@example.com');
