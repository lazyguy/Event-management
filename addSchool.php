<?php

include_once "include.php";
$insertType = $_POST["type"];
$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        if (strcmp($insertType, "addSchool") == 0) {

            $eName = $_POST["eName"];
            $eName = mysql_real_escape_string($eName);
            $schoolAddress = $_POST["schoolAddress"];
            $schoolAddress = mysql_real_escape_string($schoolAddress);
            $emailId = $_POST["emailId"];
            $emailId = mysql_real_escape_string($emailId);
            $phoneNumber = $_POST["phoneNumber"];
            $phoneNumber = mysql_real_escape_string($phoneNumber);
            $principalName = $_POST["principalName"];
            $principalName = mysql_real_escape_string($principalName);
            // $year = getYear();

            $count = mysql_query("SELECT COUNT(*) FROM school_master WHERE school_name='$eName'");
            $count = mysql_fetch_array($count);
            if ($count[0] < 1) {
                if (!mysql_query("INSERT INTO school_master (school_name, s_address, princ_name,phone_number,mail_id)
				VALUES ('$eName', '$schoolAddress', '$principalName', '$phoneNumber', '$emailId')")) {
                    $error = mysql_error();
                    Print $error;
                    echo "0";
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        } else if (strcmp($insertType, "getSchool") == 0 || strcmp($insertType, "NextSchoolPage") == 0 || strcmp($insertType, "PrevSchoolPage") == 0) {
            $ename = $_POST["eName"];
            $ename = mysql_real_escape_string($ename);
            $result = array();
            // $year = getYear();
            $sType = $_POST["searchType"];

            if (strcmp($insertType, "NextSchoolPage") == 0) {
                $current_page = $_POST["currentPage"];
                $current_page = $current_page + 1;
            } else if (strcmp($insertType, "PrevSchoolPage") == 0) {
                $current_page = $_POST["currentPage"];
                $current_page = $current_page - 1;
            }
            else
                $current_page = 1;
            $offset = (($current_page - 1) * $resultPerPage);

            if (strcmp($sType, "exact") == 0) {
                $count = mysql_query("SELECT COUNT(*) FROM school_master WHERE school_name='$ename'");
                $count = mysql_fetch_array($count);
                $rsd = mysql_query("SELECT school_id,school_name,princ_name FROM school_master where school_name='$ename' order by school_name limit $offset,$resultPerPage");
                $erroror = mysql_error();
            } else {
                $count = mysql_query("SELECT COUNT(*) FROM school_master WHERE school_name LIKE '%$ename%'");
                $count = mysql_fetch_array($count);
                $rsd = mysql_query("SELECT school_id,school_name,princ_name FROM school_master where school_name LIKE '%$ename%' order by school_name limit $offset,$resultPerPage");
            }
            array_push($result, array("totalcount" => $count[0]));
            while ($rs = mysql_fetch_array($rsd)) {
                array_push($result, array("id" => $rs['school_id'], "value" => $rs['school_name'], "event_type" => $rs['princ_name']));
            }
            echo array_to_json($result);
            mysql_close($con);
        } else if (strcmp($insertType, "deleteSchool") == 0) {
            $eid = $_POST["eid"];
            $year = getYear();
            $count = mysql_query("SELECT COUNT(*) FROM school_master WHERE school_id='$eid'");
            $count = mysql_fetch_array($count);
            if ($count[0] >= 1) {
                if (!mysql_query("DELETE FROM school_master WHERE school_id='$eid'")) {
                    echo "0";
                    Print mysql_error();
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        } else if (strcmp($insertType, "getSchoolbyId") == 0) {
            $result = array();
            $eid = $_POST["eid"];
            $year = getYear();
            $rsd = mysql_query("SELECT * FROM school_master WHERE school_id='$eid'");
            $erroror = mysql_error();
            if (!$rsd) {
                echo "0";
                break;
            }
            while ($rs = mysql_fetch_array($rsd)) {
                array_push($result, array("id" => $rs['school_id'], "value" => $rs['school_name'],
                    "address" => $rs['s_address'], "pname" => $rs['princ_name'], "pnum" => $rs['phone_number'], "mailid" => $rs['mail_id']));
            }
            echo array_to_json($result);
            mysql_close($con);
        } else if (strcmp($insertType, "schoolModify") == 0) {
            $result = array();

            $eid = $_POST["eid"];
            $eName = $_POST["eName"];
            $eAddress = $_POST["eAddress"];
            $ePrincipal = $_POST["ePrincipal"];
            $ePhone = $_POST["ePhone"];
            $eEmail = $_POST["eEmail"];
            $year = getYear();


            $count = mysql_query("SELECT COUNT(*) FROM school_master WHERE school_id='$eid'");
            $erroror = mysql_error();
            $count = mysql_fetch_array($count);
            if ($count[0] >= 1) {
                if (!mysql_query("UPDATE school_master SET school_name='$eName',s_address='$eAddress'
                        ,princ_name='$ePrincipal',phone_number='$ePhone',mail_id='$eEmail' WHERE school_id='$eid'")) {
                    echo "0";
                } else {
                    $erroror = mysql_error();
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        }
    }
}
?>