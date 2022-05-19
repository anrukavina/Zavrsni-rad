# baza podataka za online trgovinu
# naredba za izvođenje:
# C:\xampp\mysql\bin\mysql -uroot --default_character_set=utf8 < C:\Users\antun\OneDrive\Dokumenti\GitHub\Zavrsni-rad\SQL\online_trgovina.sql

drop database if exists online_trgovina;
create database online_trgovina default charset utf8mb4;
use online_trgovina;

create table korisnik (
    sifra          int not null primary key auto_increment,
    ime            varchar(50) not null,
    prezime        varchar(50) not null,
    oib            char(11),
    drzava         varchar(50),
    grad           varchar(50),
    postanski_broj varchar(10),
    ulica          varchar(50),
    kucni_broj     varchar(10),
    email          varchar(100),
    datum_rodenja  datetime,
    broj_telefona  varchar(50),
    spol           varchar(20)
);

create table proizvod (
    sifra  int not null primary key auto_increment,
    naziv  varchar(50),
    vrsta  varchar(50),
    cijena decimal(18,2),
    boja   varchar(50),
    tezina decimal(18,2)
);

create table narudzba (
    sifra          int not null primary key auto_increment,
    broj_pracenja  int,
    datum_narudzbe datetime,
    datum_isporuke datetime,
    korisnik       int not null
);

create table stavke (
    sifra    int not null primary key auto_increment,
    proizvod int not null,
    narudzba int not null,
    kolicina int
);

# definiranje vanjskih ključeva

alter table narudzba add foreign key (korisnik) references korisnik(sifra);
alter table stavke add foreign key (narudzba) references narudzba(sifra);
alter table stavke add foreign key (proizvod) references proizvod(sifra);

# unos podataka


