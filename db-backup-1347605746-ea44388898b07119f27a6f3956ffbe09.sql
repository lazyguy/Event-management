DROP TABLE IF EXISTS event_master;

CREATE TABLE `event_master` (
  `event_id` int(6) NOT NULL AUTO_INCREMENT,
  `event_name` char(100) DEFAULT NULL,
  `event_type` int(1) DEFAULT NULL,
  `event_year` int(4) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6023 DEFAULT CHARSET=latin1;

INSERT INTO event_master VALUES("6000","asd","1","2012");
INSERT INTO event_master VALUES("6001","123","1","2012");
INSERT INTO event_master VALUES("6003","zxc","1","2012");
INSERT INTO event_master VALUES("6004","zxc","2","2012");
INSERT INTO event_master VALUES("6005","rty","2","2012");
INSERT INTO event_master VALUES("6006","rty","1","2012");
INSERT INTO event_master VALUES("6007","Group Dance","1","2012");
INSERT INTO event_master VALUES("6008","Single Dance","1","2012");
INSERT INTO event_master VALUES("6009","1","1","2012");
INSERT INTO event_master VALUES("6010","2","1","2012");
INSERT INTO event_master VALUES("6011","3","1","2012");
INSERT INTO event_master VALUES("6012","4","1","2012");
INSERT INTO event_master VALUES("6013","5","1","2012");
INSERT INTO event_master VALUES("6014","6","1","2012");
INSERT INTO event_master VALUES("6015","8","1","2012");
INSERT INTO event_master VALUES("6016","9","1","2012");
INSERT INTO event_master VALUES("6017","10","1","2012");
INSERT INTO event_master VALUES("6018","11","1","2012");
INSERT INTO event_master VALUES("6019","12","1","2012");
INSERT INTO event_master VALUES("6020","13","1","2012");
INSERT INTO event_master VALUES("6021","14","1","2012");
INSERT INTO event_master VALUES("6022","15","1","2012");



DROP TABLE IF EXISTS event_result;

CREATE TABLE `event_result` (
  `regn_number` int(4) NOT NULL,
  `event_id` int(4) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`regn_number`,`event_id`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_result VALUES("246","6005","1");
INSERT INTO event_result VALUES("247","6000","1");
INSERT INTO event_result VALUES("249","6000","3");
INSERT INTO event_result VALUES("251","6000","2");



DROP TABLE IF EXISTS event_trans;

CREATE TABLE `event_trans` (
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `event_id` int(4) NOT NULL DEFAULT '0',
  `event_marks` int(4) DEFAULT '0',
  `event_grade` char(1) DEFAULT NULL,
  `fee_paid` float DEFAULT NULL,
  PRIMARY KEY (`regn_number`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO event_trans VALUES("246","6005","25","A","100");
INSERT INTO event_trans VALUES("247","6000","25","A","1000");
INSERT INTO event_trans VALUES("247","6001","12","","1000");
INSERT INTO event_trans VALUES("247","6006","0","","1000");
INSERT INTO event_trans VALUES("248","6004","0","","100");
INSERT INTO event_trans VALUES("248","6005","0","","100");
INSERT INTO event_trans VALUES("249","6000","15","C","123");
INSERT INTO event_trans VALUES("249","6001","0","","123");
INSERT INTO event_trans VALUES("250","6001","0","","123");
INSERT INTO event_trans VALUES("251","6000","20","B","12");
INSERT INTO event_trans VALUES("252","6001","0","","123");
INSERT INTO event_trans VALUES("253","6001","0","","123");
INSERT INTO event_trans VALUES("253","6009","0","","123");
INSERT INTO event_trans VALUES("253","6010","0","","123");
INSERT INTO event_trans VALUES("253","6011","0","","123");
INSERT INTO event_trans VALUES("253","6012","0","","123");
INSERT INTO event_trans VALUES("253","6013","0","","123");
INSERT INTO event_trans VALUES("253","6014","0","","123");
INSERT INTO event_trans VALUES("253","6015","0","","123");
INSERT INTO event_trans VALUES("253","6016","0","","123");
INSERT INTO event_trans VALUES("253","6017","3","C","123");
INSERT INTO event_trans VALUES("253","6018","10","A","123");
INSERT INTO event_trans VALUES("253","6019","0","","123");
INSERT INTO event_trans VALUES("253","6020","0","","123");
INSERT INTO event_trans VALUES("253","6021","0","","123");
INSERT INTO event_trans VALUES("253","6022","0","","123");
INSERT INTO event_trans VALUES("254","6000","5","B","123");



DROP TABLE IF EXISTS group_master;

CREATE TABLE `group_master` (
  `group_id` int(4) NOT NULL AUTO_INCREMENT,
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `school_id` int(4) DEFAULT '0',
  `event_grade` char(1) DEFAULT NULL,
  `result` int(4) DEFAULT '0',
  PRIMARY KEY (`group_id`,`regn_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




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
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=latin1;

INSERT INTO participant_master VALUES("246","sonu","0","2012-08-01","1","4003","","","","0","2");
INSERT INTO participant_master VALUES("247","testerer","0","2012-03-06","1","4003","","","","0","1");
INSERT INTO participant_master VALUES("248","tester","18","1994-08-03","1","4003","","","","0","2");
INSERT INTO participant_master VALUES("249","tester","11","2012-08-01","2","4001","","","","0","1");
INSERT INTO participant_master VALUES("250","222222","15","1997-08-05","1","4000","","","","0","1");
INSERT INTO participant_master VALUES("251","asdasdasdasd","0","2012-08-02","2","4000","","","","0","1");
INSERT INTO participant_master VALUES("252","1212121212","0","2012-08-02","1","4000","","","","0","1");
INSERT INTO participant_master VALUES("253","test","17","1995-08-02","1","4003","","","","0","1");
INSERT INTO participant_master VALUES("254","tscddd dsdfgsdf d sdf","0","2012-09-01","1","4003","","","","0","1");



DROP TABLE IF EXISTS school_master;

CREATE TABLE `school_master` (
  `school_id` int(6) NOT NULL AUTO_INCREMENT,
  `school_name` char(100) NOT NULL,
  `s_address` char(100) DEFAULT NULL,
  `princ_name` char(60) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `mail_id` char(60) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4062 DEFAULT CHARSET=latin1;

INSERT INTO school_master VALUES("4000","asd","","","0","");
INSERT INTO school_master VALUES("4001","qwe","","","0","");
INSERT INTO school_master VALUES("4002","zxc","","","0","");
INSERT INTO school_master VALUES("4003","St Joseph\'s Public School, Pattanakad","","","0","");
INSERT INTO school_master VALUES("4004","this is a test","","","0","");
INSERT INTO school_master VALUES("4005","this is a test1","","","0","");
INSERT INTO school_master VALUES("4006","this is a test2","","","0","");
INSERT INTO school_master VALUES("4007","this is a test3","","","0","");
INSERT INTO school_master VALUES("4008","this is a test4","","","0","");
INSERT INTO school_master VALUES("4009","this is a test5","","","0","");
INSERT INTO school_master VALUES("4010","this is a test6","","","0","");
INSERT INTO school_master VALUES("4011","abcdefg 1","","","0","");
INSERT INTO school_master VALUES("4012","abcdefg 2","","","0","");
INSERT INTO school_master VALUES("4013","abcdefg 3","","","0","");
INSERT INTO school_master VALUES("4014","abcdefg 4","","","0","");
INSERT INTO school_master VALUES("4015","abcdefg 5","","","0","");
INSERT INTO school_master VALUES("4016","abcdefg 6","","","0","");
INSERT INTO school_master VALUES("4017","abcdefg 7","","","0","");
INSERT INTO school_master VALUES("4018","abcdefg 8","","","0","");
INSERT INTO school_master VALUES("4019","abcdefg 9","","","0","");
INSERT INTO school_master VALUES("4020","1","","","0","");
INSERT INTO school_master VALUES("4021","2","","","0","");
INSERT INTO school_master VALUES("4022","3","","","0","");
INSERT INTO school_master VALUES("4023","4","","","0","");
INSERT INTO school_master VALUES("4024","5","","","0","");
INSERT INTO school_master VALUES("4025","6","","","0","");
INSERT INTO school_master VALUES("4026","7","","","0","");
INSERT INTO school_master VALUES("4027","8","","","0","");
INSERT INTO school_master VALUES("4028","0","","","0","");
INSERT INTO school_master VALUES("4029","11","","","0","");
INSERT INTO school_master VALUES("4030","22","","","0","");
INSERT INTO school_master VALUES("4031","33","","","0","");
INSERT INTO school_master VALUES("4032","55","","","0","");
INSERT INTO school_master VALUES("4033","67","","","0","");
INSERT INTO school_master VALUES("4034","77","","","0","");
INSERT INTO school_master VALUES("4035","99","","","0","");
INSERT INTO school_master VALUES("4036","999","","","0","");
INSERT INTO school_master VALUES("4037","000","","","0","");
INSERT INTO school_master VALUES("4038","1111","","","0","");
INSERT INTO school_master VALUES("4039","11111","","","0","");
INSERT INTO school_master VALUES("4040","222","","","0","");
INSERT INTO school_master VALUES("4041","2333","","","0","");
INSERT INTO school_master VALUES("4042","33334","","","0","");
INSERT INTO school_master VALUES("4043","4444","","","0","");
INSERT INTO school_master VALUES("4044","5555","","","0","");
INSERT INTO school_master VALUES("4045","6666","","","0","");
INSERT INTO school_master VALUES("4046","7777","","","0","");
INSERT INTO school_master VALUES("4047","8888","","","0","");
INSERT INTO school_master VALUES("4048","9999","","","0","");
INSERT INTO school_master VALUES("4049","0000","","","0","");
INSERT INTO school_master VALUES("4050","22222","","","0","");
INSERT INTO school_master VALUES("4051","33333","","","0","");
INSERT INTO school_master VALUES("4052","44444","","","0","");
INSERT INTO school_master VALUES("4053","55555","","","0","");
INSERT INTO school_master VALUES("4054","66666","","","0","");
INSERT INTO school_master VALUES("4055","77777","","","0","");
INSERT INTO school_master VALUES("4056","88888","","","0","");
INSERT INTO school_master VALUES("4057","99999","","","0","");
INSERT INTO school_master VALUES("4058","00000","","","0","");
INSERT INTO school_master VALUES("4059","111112","","","0","");
INSERT INTO school_master VALUES("4060","11111111","","","0","");
INSERT INTO school_master VALUES("4061","2222222","","","0","");



