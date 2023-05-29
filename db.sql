/*
create table users(
    id integer primary key auto_increment,
    nome varchar(30) not null,
    cognome varchar(30) not null,
    dataNascita date not null,
    genere varchar(6),
    email varchar(255) not null unique,
    username varchar(20) not null unique,
    password varchar(255) not null,
    newsletter boolean,
    propic varchar(255) default 'images/user.jpg',
    canzone varchar(255)
)Engine=InnoDB;

create table schede(
    id integer primary key auto_increment,
    id_creatore integer not null,
    nome varchar(20) not null,
    tipo varchar(20) default 'Non specificato',
    categorie varchar(255) default 'Non specificato',
    giorno_settimana varchar(20) default 'Non specificato',
    pubblica boolean default false,
    pic varchar(255) default 'images/default.jpg',
    n_like integer default 0,
	foreign key (id_creatore) references users(id)
)Engine=InnoDB;

create table esercizi(
	id integer auto_increment primary key,
    idOnline integer not null,
    id_scheda integer not null,
    n_serie integer not null,
    n_ripetizioni integer not null,
    foreign key (id_scheda) references schede(id) ON DELETE CASCADE
)Engine=InnoDB;
*/

/*
Utenti: 
Gp99 Peccatore99
Ka79 Austen79
Bs73 Legendary1
Mc5 Catteo01
*/
