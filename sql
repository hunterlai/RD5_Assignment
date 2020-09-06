create database bank;

drop table if exists users;
create table users(
	userId int primary key auto_increment ,
    email varchar(64),
    userName varchar(20),
    birth date,
    phone char(10),
    passwd varchar(64),
    num varchar(10)
);

drop table if exists user_account;
create table user_account(
	userId int,
    accountName varchar(20),
    accountNum varchar(10),
    sta varchar(20),
    act varchar(20),
    balance decimal(15,2),
    showb int(1),
    foreign key (userId) references users (userId)
);

drop table if exists detail;
create table detail(
    num varchar(10),
    inorout char(10),
    balance decimal(15,2),
    handfee decimal(15,2),
    trandate date,
    handorauto char(10)
);



