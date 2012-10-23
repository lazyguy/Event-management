DROP TABLE IF EXISTS event_master;

CREATE TABLE `event_master` (
  `event_id` int(6) NOT NULL AUTO_INCREMENT,
  `event_name` char(100) DEFAULT NULL,
  `event_type` int(1) DEFAULT NULL,
  `event_year` int(4) DEFAULT NULL,
  `isgroup` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6002 DEFAULT CHARSET=latin1;

INSERT INTO event_master VALUES("6000","asd","1","2012","0");
INSERT INTO event_master VALUES("6001","asdasd","1","2012","1");



DROP TABLE IF EXISTS event_result;

CREATE TABLE `event_result` (
  `regn_number` int(4) NOT NULL,
  `event_id` int(4) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`regn_number`,`event_id`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_result VALUES("200","6000","1");
INSERT INTO event_result VALUES("201","6000","3");



DROP TABLE IF EXISTS event_trans;

CREATE TABLE `event_trans` (
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `event_id` int(4) NOT NULL DEFAULT '0',
  `event_marks` int(4) DEFAULT '0',
  `event_grade` char(2) DEFAULT NULL,
  `fee_paid` float DEFAULT NULL,
  PRIMARY KEY (`regn_number`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_trans VALUES("200","6000","5","A+","13");
INSERT INTO event_trans VALUES("201","6000","1","A+","1");
INSERT INTO event_trans VALUES("202","6001","0","","1");



DROP TABLE IF EXISTS group_master;

CREATE TABLE `group_master` (
  `group_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(4) NOT NULL DEFAULT '0',
  `school_id` int(4) DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80001 DEFAULT CHARSET=latin1;

INSERT INTO group_master VALUES("80000","6001","4000");



DROP TABLE IF EXISTS group_result;

CREATE TABLE `group_result` (
  `group_id` int(4) NOT NULL AUTO_INCREMENT,
  `event_id` int(4) NOT NULL DEFAULT '0',
  `grade` char(2) DEFAULT NULL,
  `marks` int(4) DEFAULT '0',
  `result` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80001 DEFAULT CHARSET=latin1;

INSERT INTO group_result VALUES("80000","6001","A+","10","1");



DROP TABLE IF EXISTS group_trans;

CREATE TABLE `group_trans` (
  `group_id` int(5) NOT NULL,
  `event_id` int(4) NOT NULL,
  `regn_number` int(4) NOT NULL,
  PRIMARY KEY (`group_id`,`regn_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO group_trans VALUES("80000","6001","200");
INSERT INTO group_trans VALUES("80000","6001","202");



DROP TABLE IF EXISTS participant_master;

CREATE TABLE `participant_master` (
  `regn_number` int(6) NOT NULL AUTO_INCREMENT,
  `student_name` char(100) NOT NULL,
  `age` int(3) NOT NULL,
  `dob` date NOT NULL,
  `sex` char(1) NOT NULL,
  `school_id` int(4) NOT NULL,
  `parent_name` char(100) DEFAULT NULL,
  `st_adress` char(100) DEFAULT NULL,
  `pa_mail_id` char(60) DEFAULT NULL,
  `pa_phone_number` int(12) DEFAULT NULL,
  `category` char(1) NOT NULL,
  PRIMARY KEY (`regn_number`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;

INSERT INTO participant_master VALUES("200","asd","0","2012-10-01","1","4000","","","","0","1");
INSERT INTO participant_master VALUES("201","wwww","0","2012-10-08","1","4002","","","","0","1");
INSERT INTO participant_master VALUES("202","dsdsds","0","2012-10-01","1","4000","","","","0","1");



DROP TABLE IF EXISTS school_master;

CREATE TABLE `school_master` (
  `school_id` int(6) NOT NULL AUTO_INCREMENT,
  `school_name` char(100) NOT NULL,
  `s_address` char(100) DEFAULT NULL,
  `princ_name` char(60) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `mail_id` char(60) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4003 DEFAULT CHARSET=latin1;

INSERT INTO school_master VALUES("4000","asd","","","0","");
INSERT INTO school_master VALUES("4001","dddd","","","0","");
INSERT INTO school_master VALUES("4002","wwww","","","0","");



