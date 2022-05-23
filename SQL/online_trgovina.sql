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
    sifra      int not null primary key auto_increment,
    naziv      varchar(50) not null,
    vrsta      varchar(50),
    cijena     decimal(18,2),
    boja       varchar(50),
    tezina     decimal(18,2),
    kategorija int
);

create table kategorija (
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    opis  text
);

create table narudzba (
    sifra          int not null primary key auto_increment,
    broj_pracenja  int not null,
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
alter table proizvod add foreign key (kategorija) references kategorija(sifra);

# unos podataka

# unos podataka u tablicu 'korisnik'

insert into korisnik (ime,prezime,oib,drzava,grad,postanski_broj,ulica,kucni_broj,email,datum_rodenja,broj_telefona,spol)
values 
('Anđel','Čindrak',61428986609,'Hrvatska','Osijek','31000','Vukovarska','20A','andel.cindrak@hotmail.com','1989-04-16','+385957654321','musko'),
('Ivan','Okun',57729164867,'Hrvatska','Zagreb','10000','Dolac','9','ivan.okun@gmail.com','1969-10-22','+385915335353','musko'),
('Ketrin','Širanović',58187045372,'Hrvatska','Požega','34000','Antuna Mihanovića','10','ketrin.siranovic@gmail.com','1995-01-01','+385971234567','zensko');

# unos podataka u tablicu 'proizvod'

insert into proizvod (naziv,vrsta,cijena,boja,tezina)
values
('ASUS G15DK','Stolno računalo','12229','crna','11'),
('Lenovo IdeaPad 5 Pro','Prijenosno računalo','6699','siva','1.9'),
('Apple iPhone 13 Pro','Smartphone','10499','siva','0.204');

# unos podataka u tablicu 'narudzba'

insert into narudzba (broj_pracenja,datum_narudzbe,datum_isporuke,korisnik)
values
(100,'2022-01-05','2022-01-10',1),
(101,'2022-03-21','2022-03-26',2),
(102,'2022-04-10','2022-04-19',3);

# unos podataka u tablicu 'stavke'

insert into stavke (proizvod,narudzba,kolicina)
values
(2,3,3),
(3,1,2),
(1,2,1);