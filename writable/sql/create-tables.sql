-- create the keepnote databse

CREATE DATABASE keepnote;

-- users table
CREATE TABLE users 
(
    id VARCHAR(25) PRIMARY KEY NOT NULL,
    username VARCHAR(25) NOT NULL UNIQUE,
    password TINYTEXT NOT NULL,
    color VARCHAR(25) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW()
);

-- users's notes
CREATE TABLE users_notes
(
    id VARCHAR(25) PRIMARY KEY NOT NULL,
    user_id VARCHAR(25) NOT NULL,
    title  VARCHAR(255),
    body TEXT NOT NULL,
    font VARCHAR(25) NOT NULL,
    color VARCHAR(25) NOT NULL DEFAULT "#f2f2f27a",
    src VARCHAR(25) NOT NULL DEFAULT "myself",
    src_id VARCHAR(25),
    created_at DATETIME NOT NULL DEFAULT NOW(),

    CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)
);

-- list of share links of notes
CREATE TABLE share_links
(
    id VARCHAR(25) PRIMARY KEY NOT NULL
    user_id VARCHAR(25) NOT NULL,
    notes_id TINYTEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    expired_at DATETIME NOT NULL,

    CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)
);

-- List of downloads files
CREATE TABLE downloads
(
    id VARCHAR(25) PRIMARY KEY NOT NULL,
    user_id VARCHAR(25) NOT NULL,
    config VARCHAR(25) NOT NULL,
    notes_id TINYTEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),

    CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE redirections
(
    id VARCHAR(25) PRIMARY KEY NOT NULL,
    target VARCHAR(25) NOT NULL,
    ip VARCHAR(25) NOT NULL,
    ua TINYTEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW()
);