DROP DATABASE IF EXISTS notesPointsChecker;
CREATE DATABASE notesPointsChecker;
USE notesPointsChecker;

CREATE TABLE subjects (
  id VARCHAR(30) NOT NULL,
  displayName VARCHAR(30) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE users (
  id INT NOT NULL,
  displayName VARCHAR(30) NOT NULL,
  email VARCHAR(30) NOT NULL,
  password VARCHAR(128) NOT NULL,
  createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(id)
);

CREATE TABLE grades (
  grade INT NOT NULL,
  subjectId VARCHAR(30) NOT NULL,
  semester VARCHAR(30) NOT NULL,
  userid INT NOT NULL,
  FOREIGN KEY (subjectId) REFERENCES subjects(id),
  FOREIGN KEY (userid) REFERENCES users(id)
);

CREATE TABLE performance_courses (
  performance_course INT NOT NULL,
  subjectId VARCHAR(30) NOT NULL,
  userid INT NOT NULL,
  FOREIGN KEY (subjectId) REFERENCES subjects(id),
  FOREIGN KEY (userid) REFERENCES users(id)
);

CREATE TABLE exams (
  examsGrade INT NOT NULL,
  subjectId VARCHAR(30) NOT NULL,
  userid INT NOT NULL,
  FOREIGN KEY (subjectId) REFERENCES subjects(id),
  FOREIGN KEY (userid) REFERENCES users(id)
);

INSERT INTO subjects (id, displayName) VALUES ('deutsch', 'Deutsch');
INSERT INTO subjects (id, displayName) VALUES ('wun', 'Werte und Normen');
INSERT INTO subjects (id, displayName) VALUES ('mathe', 'Mahte');
INSERT INTO subjects (id, displayName) VALUES ('englisch', 'Englisch');
INSERT INTO subjects (id, displayName) VALUES ('int', 'Informations Technik');
INSERT INTO subjects (id, displayName) VALUES ('bifim', 'Berufliche Informatik');
INSERT INTO subjects (id, displayName) VALUES ('praxis', 'Fach Praxis');
INSERT INTO subjects (id, displayName) VALUES ('physik', 'Physik');
INSERT INTO subjects (id, displayName) VALUES ('bvi', 'Wirtschaft');
INSERT INTO subjects (id, displayName) VALUES ('sport', 'Sport');