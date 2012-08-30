<!DOCTYPE html>
<?php
$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'home.php';
        header("Location: http://$host$uri/$extra");
        mysql_close($con);
        exit;
    }
}
?>
<html lang="en">
    <head>
        <?php include("include.php"); ?>
        <meta charset="utf-8">
        <title>RotaryBalolsav-2012</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="/twitter-bootstrap/js/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="bootstrap.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-modal.js"></script>
        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="images/logo.ico">

    </head>

    <body>

        <div class="topbar">
            <div class="fill">
                <div class="container">
                    <a class="brand" href="index.php">Balolsav 2012</a>
                    <ul class="nav">
                        <li class="active"><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <p>Initializing please wait</p>
            </div>
            <?php
            $isSuccess = 1;
            $con = mysql_connect("localhost", "root", "");
            if (!$con) {
                die('Could not connect: ' . mysql_error());
                $isSuccess = 0;
            } else {
                $db_selected = mysql_select_db('BALOLSAV', $con);
                if (!$db_selected) {
                    Print "db not present <br />";
                    Print "Creating Database <br />";
                    if (!mysql_query("CREATE DATABASE BALOLSAV")) {
                        die('Could not create database: ' . mysql_error());
                        $isSuccess = 0;
                    } else {
                        mysql_select_db("BALOLSAV", $con);
                        $isSuccess = 1;
                        Print "Creating tables<br />";
                        if (!mysql_query("create table school_master(
								school_id int(6) default null auto_increment ,
								school_name   char(100) not  NULL,
								s_address     char(100) default NULL,
								princ_name    char(60) default NULL,
								phone_number  int(12) default null,
								mail_id       char(60) default NULL,
								PRIMARY KEY (school_id)
								)")) {
                            $isSuccess = 0;
                            die('Could not create table school_master: ' . mysql_error());
                        } else {
                            Print " School Master created <br />";
                            $isSuccess = 1;
                            mysql_query("ALTER TABLE school_master AUTO_INCREMENT = 4000;");
                        }
                        if (!mysql_query("create table event_master(
								event_id     int(6) default null auto_increment,
								event_name   char(100) default NULL,
								event_type   int(1),
								event_year   int(4),
								PRIMARY KEY  (event_id) )")) {
                            die('Could not create table event_master: ' . mysql_error());
                            $isSuccess = 0;
                        } else {
                            mysql_query("ALTER TABLE event_master AUTO_INCREMENT = 6000;");
                            Print " Event Master created <br />";
                            $isSuccess = 1;
                        }
                        if (!mysql_query("create table participant_master(
								regn_number  int(6) default null auto_increment,
								student_name char(100) not  NULL,
								age          int(3) not null,
								dob          date   not null,
								sex          char(1) not null,
								school_id    int(4)  not null,
								parent_name     char(100) default NULL,
								st_adress       char(100) default NULL,
								pa_mail_id      char(60) default NULL,
								pa_phone_number int(12),
                                                                category        char(1) NOT NULL,
								PRIMARY KEY  (regn_number));")) {
                            die('Could not create table participant_master: ' . mysql_error());
                            $isSuccess = 0;
                        } else {
                            mysql_query("ALTER TABLE participant_master AUTO_INCREMENT = 200;");
                            Print " Participant Master created <br />";
                            $isSuccess = 1;
                        }
                        if (!mysql_query("create table event_trans (
								regn_number    int(4),
								event_id       int(4),
                                                                event_marks    int(4) default 0,
                                                                event_grade    char(1) default NULL,
								fee_paid       FLOAT,
								primary key (regn_number,event_id ));")) {
                            die('Could not create table event_trans: ' . mysql_error());
                            $isSuccess = 0;
                        } else {
                            Print " event trans created <br />";
                            $isSuccess = 1;
                        }
                        if (!mysql_query("create table event_result (
								regn_number    int(4) NOT NULL,
								event_id       int(4) NOT NULL,
								position       int(4) NOT NULL,
								primary key (regn_number,event_id,position ));")) {
                            die('Could not create table event_trans: ' . mysql_error());
                            $isSuccess = 0;
                        } else {
                            Print " event trans created <br />";
                            $isSuccess = 1;
                        }
                    }
                } else {
                    Print "Database exist init done moving on";
                    $isSuccess = 1;
                }
            }
            if ($isSuccess == 1) {
                mysql_close($con);
                ?>
                <p><a class="btn primary" href="home.php">Press Here to Continue &raquo;</a></p>
                <?php
            } else {
                Print "Cannot continue due to errors <br />";
            }
            ?>

        </div> <!-- /container -->

    </body>
</html>
