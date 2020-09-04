create database bank;

drop table users;
create table users(
	userId int primary key auto_increment ,
    email varchar(64),
    userName varchar(20),
    birth int,
    phone char(10),
    passwd varchar(64),
    balance decimal(15,2)
);

create table user_account(
	userId int,
    accountName varchar(20),
    phone char(10),
    balance decimal(15,2),
    showb int(1),
    foreign key (userId) references users (userId)
);



