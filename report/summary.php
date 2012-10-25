<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Summary - Balolsav <?php echo getYear() ?></title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="../bootstrap.css" rel="stylesheet">
        <link href="../jquery-ui-1.8.22.custom.css" rel="stylesheet">
        <link href="../datatables/media/css/jquery.dataTables.css" rel="stylesheet">
        <link rel="stylesheet" href="../chosen.css" />
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap-modal.js"></script>
        <script src="../js/jquery-ui-1.8.22.custom.min.js"></script>
        <script src="../js/bootstrap-alerts.js"></script>
        <script src="../js/table-sorter.js"></script>
        <script src="../js/eventControl.js"></script>
        <script src="../js/schoolControl.js"></script>
        <script src="../js/participantControl.js"></script>
        <script src="../js/chosen.jquery.min.js"></script>
        <script src="../datatables/media/js/jquery.dataTables.min.js"></script>
        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>
        <link rel="shortcut icon" href="../images/logo.ico">
    </head>

    <body>
        <div class="topbar">
            <div class="topbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="../index.php">Balolsav <?php echo getYear() ?></a>
                    <ul class="nav">
                        <li class="active"><a href="../home.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <?php include_once "sidebar.html"; ?>
            <div class="content" style="padding-left: 20px">

                <div id="reportContents" class="row" >
                    <h3>Summary of Registration</h3>
                    <div class="well">
                        <div class="row">
                            <div class="span12">
                                <b>Number of Participants :<span style="padding-left: 128px;" id="totalParticipants"/></b>
                            </div>
                            <div id="totalPartCount">&nbsp;</div>
                            <ul class="offset1">
                                <li>Male Participants<span style="padding-left: 120px" id="maleCount"></span></li>
                                <li>Female Participants<span style="padding-left: 105px" id="femaleCount"></span></li>
                                <li>Seniors<span style="padding-left: 177px" id="seniorCount"></span></li>
                                <li>Juniors<span style="padding-left: 180px" id="juniorCount"></span></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="span5">
                                <b>Number of Schools :<span style="padding-left: 5px;" id="totalSchools"/></b>
                            </div>
                            <div id="totalSchoolCount">&nbsp;</div>
                            <div class=" span12 offset1">
                                <table class="condensed-table bordered-table" id="schoolSummaryTable">
                                    <thead>
                                        <tr>
                                            <th width="70%"><b>School Name</b></th>
                                            <th width="10%"><b>Seniors</b></th>
                                            <th width="10%"><b>Juniors</b></th>
                                            <th width="10%"><b>Fees</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="schoolSummaryBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 20px">
                            <div class="span5" >
                                <b>Number of Events :<span style="padding-left: 5px;" id="totalevents"/></b>
                            </div>
                            <div id="totalEventCount">&nbsp;</div>
                            <div class="row span12 offset1" >
                                <table class="condensed-table bordered-table" id="eventSummaryTable">
                                    <thead>
                                        <tr>
                                            <th width="55%"><b>Event Name</b></th>
                                            <th width="20%"><b>Category</b></th>
                                            <th width="25%"><b>Participants</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="eventSummaryBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 25px;padding-left: 375px">
                            <a class="btn primary" id="printSummaryReport" href="printSummary.php" target="_blank">Print Summary </a>
                        </div>
                    </div>
                </div>
                <div id ="test1"></div>
                <?php include_once "../footer.html" ?>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                $.post("reportManager.php", {
                    type: "getSummary"
                }, function(data){
                    
                    $('#test1').empty();
                  //  $('#test1').html(data);
                     
                    var obj = jQuery.parseJSON(data);
                    $('#maleCount').empty();
                    $('#femaleCount').empty();
                    $('#seniorCount').empty();
                    $('#juniorCount').empty();
                    $('#totalParticipants').empty();
                    $('#totalParticipants').append(parseInt(obj.participant.maleCount)+parseInt(obj.participant.femaleCount));
                    $('#maleCount').append(obj.participant.maleCount);
                    $('#femaleCount').append(obj.participant.femaleCount);
                    $('#seniorCount').append(obj.participant.seniorCount);
                    $('#juniorCount').append(obj.participant.juniorCount);
                    $('#totalevents').empty();
                    $('#totalSchools').empty();
                    $('#totalSchools').append(obj.counts.schoolCount);
                    $('#totalevents').append(obj.counts.eventsCount);
                    $('#schoolSummaryBody').empty();
                    if(obj.school){
                        for (var i = 0; i < obj.school.length; i++) {
                            $('#schoolSummaryBody').append("<tr>"+"<td>"+obj.school[i].sName+
                                "</td>"+"<td>"+obj.school[i].sCount+"</td>"+"<td>"+obj.school[i].jCount+"</td>"+"<td>"+obj.school[i].sSum+"</td>"+"</tr>");
                        }
                    }
                    $('#eventSummaryBody').empty();
                    if(obj.events){
                        for (var i = 0; i < obj.events.length; i++) {
                            $('#eventSummaryBody').append("<tr>"+"<td>"+obj.events[i].eName+
                                "</td>"+"<td>"+obj.events[i].category+"</td>"+"<td>"+obj.events[i].count+"</td>"+"</tr>");
                        }
                    }
                    $('#schoolSummaryTable').dataTable({
                        "bLengthChange": false,
                        //     "iDisplayLength": 6 ,
                        //      "bFilter": false,
                        "sScrollY": "170px",
                        "bPaginate": false,
                        "bInfo":false,
                        "bScrollCollapse": true
                    });

                    $('#eventSummaryTable').dataTable({
                        "bLengthChange": false,
                        //     "iDisplayLength": 6 ,
                        //           "bFilter": false,
                        "sScrollY": "170px",
                        "bPaginate": false,
                        "bInfo":false,
                        "bScrollCollapse": true
                    });
                });

            });


        </script>
    </body>
</html>

