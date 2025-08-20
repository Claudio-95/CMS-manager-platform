CREATE DATABASE gestore_blog CHARACTER SET utf8 COLLATE utf8_general_ci;	

CREATE TABLE blog (
	blog_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
	nomeBlog VARCHAR(20) UNIQUE NOT NULL,
    graficaBlog VARCHAR(20) NOT NULL DEFAULT "Default.css"
)ENGINE=INNODB;


CREATE TABLE utenti ( 
	nickname VARCHAR(20) PRIMARY KEY, 
	password VARCHAR(255) NOT NULL,
	email VARCHAR(30) NOT NULL UNIQUE,
	telefono VARCHAR(20) NOT NULL,
	nDocumento VARCHAR(20) NOT NULL,
	dataDocumento DATE NOT NULL,
	luogoDocumento VARCHAR(20) NOT NULL,
    enteDocumento VARCHAR(20) NOT NULL,
    inizioAbb DATE DEFAULT NULL,
    fineAbb DATE DEFAULT NULL
)ENGINE=INNODB;


CREATE TABLE creazione (
	blog_idC SMALLINT,
    autoreC VARCHAR(20),
    coautoreC VARCHAR(20) DEFAULT NULL,
	PRIMARY KEY (blog_idC, autoreC),
	FOREIGN KEY(blog_idC) REFERENCES blog(blog_id) ON DELETE CASCADE,
	FOREIGN KEY(autoreC) REFERENCES utenti(nickname) ON DELETE CASCADE
)ENGINE=INNODB;


CREATE TABLE post (
	post_id INTEGER AUTO_INCREMENT PRIMARY KEY,
	dataPost date,
    oraPost time,
    titoloPost VARCHAR(40) NOT NULL,
    nLike SMALLINT DEFAULT 0,
    nDislike SMALLINT DEFAULT 0,
    testoPost VARCHAR(400)
)ENGINE=INNODB;


CREATE TABLE articolazione (
	blog_idAr SMALLINT,
	post_idAr INTEGER,
	PRIMARY KEY (blog_idAr, post_idAr),
	FOREIGN KEY(blog_idAr) REFERENCES blog(blog_id) ON DELETE CASCADE,
	FOREIGN KEY(post_idAr) REFERENCES post(post_id) ON DELETE CASCADE
)ENGINE=INNODB;


CREATE TABLE immagine (
	img_id INTEGER AUTO_INCREMENT PRIMARY KEY,
	dataImg date,
	oraImg time,
	pathImg VARCHAR(150)
)ENGINE=INNODB;


CREATE TABLE mipiace (
	nicknameL VARCHAR(20),
	post_idL INTEGER,
	PRIMARY KEY (nicknameL, post_idL),
	FOREIGN KEY(nicknameL) REFERENCES utenti(nickname) ON DELETE CASCADE,
	FOREIGN KEY(post_idL) REFERENCES post(post_id) ON DELETE CASCADE
)ENGINE=INNODB;


CREATE TABLE nonmipiace (
	nicknameDL VARCHAR(20),
	post_idDL INTEGER,
	PRIMARY KEY (nicknameDL, post_idDL),
	FOREIGN KEY(nicknameDL) REFERENCES utenti(nickname) ON DELETE CASCADE,
	FOREIGN KEY(post_idDL) REFERENCES post(post_id) ON DELETE CASCADE
)ENGINE=INNODB;


CREATE TABLE allegato (
	post_idAl INTEGER,
	img_idAl INTEGER, 
	PRIMARY KEY(post_idAl, img_idAl),
	FOREIGN KEY (post_idAl) REFERENCES  post(post_id) ON DELETE CASCADE,
	FOREIGN KEY(img_idAl) REFERENCES immagine(img_id) ON DELETE CASCADE
)ENGINE=INNODB;


CREATE TABLE commento (
	commento_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
	dataCommento date NOT NULL,
	oraCommento time NOT NULL, 
	testoCommento VARCHAR(140) NOT NULL
)ENGINE=INNODB;


CREATE TABLE appunto (
	commento_idAp SMALLINT, 
	post_idAp INTEGER,
	PRIMARY KEY(commento_idAp, post_idAp),
	FOREIGN KEY(commento_idAp) REFERENCES commento(commento_id) ON DELETE CASCADE,
    FOREIGN KEY(post_idAp) REFERENCES post(post_id) ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE discussione (
	commento_idD SMALLINT, 
	autoreD VARCHAR(20),
	PRIMARY KEY (commento_idD, autoreD),
	FOREIGN KEY(commento_idD) REFERENCES commento(commento_id)ON DELETE CASCADE,
	FOREIGN KEY(autoreD) REFERENCES utenti(nickname)ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE tema (
	nome VARCHAR(20) PRIMARY KEY, 
	popolarita SMALLINT NOT NULL DEFAULT 0
)ENGINE=INNODB;


CREATE TABLE argomento (
	nomeTema VARCHAR(20), 
	blog_idArg SMALLINT,
    PRIMARY KEY (nomeTema, blog_idArg),
    FOREIGN KEY(nomeTema) REFERENCES tema(nome)ON DELETE CASCADE,
	FOREIGN KEY(blog_idArg) REFERENCES blog(blog_id)ON DELETE CASCADE
)ENGINE=INNODB;

