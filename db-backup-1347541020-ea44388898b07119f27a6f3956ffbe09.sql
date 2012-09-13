DROP TABLE event_master;

CREATE TABLE `event_master` (
  `event_id` int(6) NOT NULL AUTO_INCREMENT,
  `event_name` char(100) DEFAULT NULL,
  `event_type` int(1) DEFAULT NULL,
  `event_year` int(4) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6008 DEFAULT CHARSET=latin1;

INSERT INTO event_master VALUES("6000","234","1","2012");
INSERT INTO event_master VALUES("6001","234","2","2012");
INSERT INTO event_master VALUES("6002","123","2","2012");
INSERT INTO event_master VALUES("6003","123","1","2012");
INSERT INTO event_master VALUES("6004","WSBK - Round 1","2","2012");
INSERT INTO event_master VALUES("6005","Moto GP - Round 3","2","2012");
INSERT INTO event_master VALUES("6006","Formula 1 - Round 5","2","2012");
INSERT INTO event_master VALUES("6007","test event","1","2012");



DROP TABLE event_result;

CREATE TABLE `event_result` (
  `regn_number` int(4) NOT NULL,
  `event_id` int(4) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`regn_number`,`event_id`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_result VALUES("200","6002","1");
INSERT INTO event_result VALUES("219","6004","1");
INSERT INTO event_result VALUES("219","6006","1");



DROP TABLE event_trans;

CREATE TABLE `event_trans` (
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `event_id` int(4) NOT NULL DEFAULT '0',
  `fee_paid` float DEFAULT NULL,
  `event_grade` char(1) DEFAULT NULL,
  `event_marks` int(4) DEFAULT '0',
  PRIMARY KEY (`regn_number`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_trans VALUES("200","6002","123","A","25");
INSERT INTO event_trans VALUES("201","6003","123","","6");
INSERT INTO event_trans VALUES("202","6001","234","","0");
INSERT INTO event_trans VALUES("202","6002","234","B","20");
INSERT INTO event_trans VALUES("202","6005","234","","0");
INSERT INTO event_trans VALUES("202","6006","234","","0");
INSERT INTO event_trans VALUES("204","6003","123","","0");
INSERT INTO event_trans VALUES("205","6002","123","","0");
INSERT INTO event_trans VALUES("206","6002","123","","0");
INSERT INTO event_trans VALUES("207","6000","123123","","0");
INSERT INTO event_trans VALUES("207","6002","123123","","0");
INSERT INTO event_trans VALUES("208","6000","123","","0");
INSERT INTO event_trans VALUES("208","6002","123","","0");
INSERT INTO event_trans VALUES("209","6002","12323","","0");
INSERT INTO event_trans VALUES("210","6000","123","","0");
INSERT INTO event_trans VALUES("210","6003","123","","0");
INSERT INTO event_trans VALUES("211","6000","123123","","0");
INSERT INTO event_trans VALUES("211","6001","123123","","0");
INSERT INTO event_trans VALUES("211","6002","123123","","0");
INSERT INTO event_trans VALUES("211","6003","123123","","0");
INSERT INTO event_trans VALUES("212","6000","123","","0");
INSERT INTO event_trans VALUES("212","6002","123","","0");
INSERT INTO event_trans VALUES("212","6003","123","","0");
INSERT INTO event_trans VALUES("213","6000","123","","0");
INSERT INTO event_trans VALUES("213","6002","123","","0");
INSERT INTO event_trans VALUES("214","6002","123","","0");
INSERT INTO event_trans VALUES("214","6003","123","","0");
INSERT INTO event_trans VALUES("215","6002","23","","0");
INSERT INTO event_trans VALUES("215","6003","23","","0");
INSERT INTO event_trans VALUES("216","6000","123","","0");
INSERT INTO event_trans VALUES("216","6003","123","","0");
INSERT INTO event_trans VALUES("217","6000","123","","0");
INSERT INTO event_trans VALUES("217","6003","123","","0");
INSERT INTO event_trans VALUES("218","6001","233333","","0");
INSERT INTO event_trans VALUES("218","6002","233333","","0");
INSERT INTO event_trans VALUES("219","6004","22222","A","25");
INSERT INTO event_trans VALUES("219","6005","22222","A","25");
INSERT INTO event_trans VALUES("219","6006","22222","A","25");
INSERT INTO event_trans VALUES("220","6002","123","","0");
INSERT INTO event_trans VALUES("221","6000","123","","0");
INSERT INTO event_trans VALUES("221","6003","123","","0");
INSERT INTO event_trans VALUES("222","6002","123","","0");
INSERT INTO event_trans VALUES("222","6006","123","","0");
INSERT INTO event_trans VALUES("223","6001","123","","0");
INSERT INTO event_trans VALUES("223","6002","123","","0");
INSERT INTO event_trans VALUES("224","6002","12","","0");



DROP TABLE group_master;

CREATE TABLE `group_master` (
  `group_id` int(4) NOT NULL AUTO_INCREMENT,
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `school_id` int(4) DEFAULT '0',
  `event_grade` char(1) DEFAULT NULL,
  `result` int(4) DEFAULT '0',
  PRIMARY KEY (`group_id`,`regn_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE participant_master;

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
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

INSERT INTO participant_master VALUES("200","123","0","2012-08-01","2","4000","","","","0","");
INSERT INTO participant_master VALUES("201","asdasd","0","2012-08-02","1","4000","","","","0","");
INSERT INTO participant_master VALUES("202","sonu Lukose","27","1985-11-06","1","4000","","","","0","2");
INSERT INTO participant_master VALUES("203","asd","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("204","123444","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("205","123123123","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("206","sdfsdfsdf","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("207","test","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("208","555","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("209","asdsda","0","2012-08-18","1","4000","","","","0","");
INSERT INTO participant_master VALUES("210","123123","0","2012-08-08","1","4000","","","","0","");
INSERT INTO participant_master VALUES("211","234234234","0","2012-08-04","1","4000","","","","0","");
INSERT INTO participant_master VALUES("212","dfsdfsdf","0","2012-08-16","1","4000","","","","0","");
INSERT INTO participant_master VALUES("213","fdg","0","2012-08-01","1","4000","as","","","0","");
INSERT INTO participant_master VALUES("214","asdasdasd","0","2012-08-03","1","4000","","","","0","");
INSERT INTO participant_master VALUES("215","asdasdasd","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("216","12331223123123","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("217","testererere","0","2012-08-02","1","4000","","","","0","");
INSERT INTO participant_master VALUES("218","dssdfdsdffsdfds","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("219","Sonu Lukose","26","1985-11-06","1","4004","","","","0","");
INSERT INTO participant_master VALUES("220","asd","0","2012-08-16","1","4000","","","","0","");
INSERT INTO participant_master VALUES("221","asdasdasdasd","0","2012-08-01","1","4000","","","","0","");
INSERT INTO participant_master VALUES("222","xzcvxzcvzxcv","0","2012-08-02","1","4000","","","","0","");
INSERT INTO participant_master VALUES("223","sonu test","14","1998-09-08","1","4000","","","","0","2");
INSERT INTO participant_master VALUES("224","asd","0","2012-09-05","1","4000","asd","","","0","2");



DROP TABLE school_master;

CREATE TABLE `school_master` (
  `school_id` int(6) NOT NULL AUTO_INCREMENT,
  `school_name` char(100) NOT NULL,
  `s_address` char(100) DEFAULT NULL,
  `princ_name` char(60) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `mail_id` char(60) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4005 DEFAULT CHARSET=latin1;

INSERT INTO school_master VALUES("4000","This is a test school","","","0","");
INSERT INTO school_master VALUES("4001","123","","","0","");
INSERT INTO school_master VALUES("4002","zxc","","","0","");
INSERT INTO school_master VALUES("4003","qwe","","","0","");
INSERT INTO school_master VALUES("4004","Awesome school for awesome people","","","0","");



