create database bank;

drop table users;
create table users(
	userId int primary key auto_increment ,
    email varchar(64),
    userName varchar(20),
    birth int,
    phone char(10),
    passwd varchar(64)
);


