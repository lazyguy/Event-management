<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Print Certificate - Balolsav <?php echo getYear() ?></title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="../bootstrap.css" rel="stylesheet">
        <link href="../jquery-ui-1.8.22.custom.css" rel="stylesheet">
        <link rel="stylesheet" href="../chosen.css" />
        <link href="../datatables/media/css/jquery.dataTables.css" rel="stylesheet">

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
                <div>
                    <h5>Select Event name to Print Certificates</h5>
                </div>
                <form>
                    <div class="row">
                        <label for="report-eventName">Event Name</label>
                        <div class="input span5">
                            <select id="report-eventName" data-placeholder="Choose an event" class="chzn-select" style="width:275px;">
                                <option value=""></option>
                            </select>
                            <input id="firstHasCorrectValue" type="hidden" value="0"/>
                        </div>

                    </div><!-- /clearfix -->
                </form>
                <div id="resultSet"  class="well" style="padding-top: 5px">
                    <h4>Winners &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn primary" id="printWinnerCert">Print All Winner Certificates</a>
                    </h4>
                    <div class ="span12" style="padding-top: 10px;">
                        <table class="condensed-table bordered-table " id="partByEventTable">
                            <thead>
                                <tr>
                                    <th width="9%"><b>Rank</b></th>
                                    <th width="12%"><b>Reg No</b></th>
                                    <th width="25%"><b>Name</b></th>
                                    <th width="40%"><b>School Name</b></th>
                                    <th width="9%"><b>Sex</b></th>
                                </tr>
                            </thead>
                            <tbody id="partByEventBody">
                            </tbody>
                        </table>

                    </div>
                </div>
                <div id="resultSet1"  class="well" style="padding-top: 5px">
                    <h4>Participants &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn primary" id="printParticipationCert">Print All Participation Certificates</a>
                    </h4>
                    <em><b>Data for participants whose points are not entered will not be shown</b></em>
                    <div class ="span12" style="padding-top: 10px;">
                        <table class="condensed-table bordered-table " id="allPartByEventTable">
                            <thead>
                                <tr>
                                    <th width="12%"><b>Reg No</b></th>
                                    <th width="25%"><b>Name</b></th>
                                    <th width="40%"><b>School Name</b></th>
                                    <th width="9%"><b>Sex</b></th>
                                    <th width="9%"><b>Grade/Points</b></th>
                                </tr>
                            </thead>
                            <tbody id="allPartByEventBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="errorSet"  class="well" style="padding-top: 25px"></div>

                <?php include_once "../footer.html" ?>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                $('#errorSet').hide();
                geteventnames(2);
                $('#resultSet').hide();
                $('#resultSet1').hide();
                $('#allPartByEventTable').hide();
                $('#partByEventTable').hide();
                $('#printWinnerCert').hide();
                $('#printParticipationCert').hide();
                $('#printWinnerCert').on("click",function(){
                    alert("Remove auto fit to page option before printing");
                    var url = "printcertbyevent.php?eId="+$('#report-eventName').val()+"&winner=1";
                    window.open(url);
                });
                $('#printParticipationCert').on("click",function(){
                    alert("Remove auto fit to page option before printing");
                    var url = "printcertbyevent.php?eId="+$('#report-eventName').val()+"&winner=0";
                    window.open(url);
                });
            });
            $(".chzn-select").chosen();
            $("#report-eventName").chosen().change(function(){
                $('#resultSet').show();
                $('#resultSet1').show();
                $.post("getPartByType.php", {
                    type: "byEventWinner",
                    eId: $('#report-eventName').val()
                }, function(data){

                    $('#errorSet').empty();
                    $('#errorSet').html(data);
                    //$('#errorSet').show();
                    $('#printWinnerCert').hide();
                    $('#printParticipationCert').hide();
                    var oTable1 = $('#allPartByEventTable').dataTable();
                    oTable1.fnDestroy();
                    var oTable2 = $('#partByEventTable').dataTable();
                    oTable2.fnDestroy();
                    $('#allPartByEventTable').hide();
                    $('#partByEventTable').hide();
                    $('#partByEventTable').show();
                    $('#allPartByEventTable').show();
                    var obj = jQuery.parseJSON(data);
                    var oTable = $('#partByEventTable').dataTable();
                    oTable.fnClearTable();
                    var oTable1 = $('#allPartByEventTable').dataTable();
                    oTable1.fnClearTable();

                    if(obj){
                        for (var i = 0; i < obj.winners.length; i++) {
                            $('#partByEventBody').append("<tr>"+
                                "<td>"+obj.winners[i].position+"</td>"+
                                "<td>"+obj.winners[i].rNum+"</td>"+
                                "<td>"+obj.winners[i].name+"</td>"+
                                "<td>"+obj.winners[i].school_name+"</td>"+
                                "<td>"+obj.winners[i].sex+"</td>"+
                                "</tr>");
                        }
                        for (var i = 0; i < obj.participants.length; i++) {
                            $('#allPartByEventBody').append("<tr>"+
                                "<td>"+obj.participants[i].rNum+"</td>"+
                                "<td>"+obj.participants[i].name+"</td>"+
                                "<td>"+obj.participants[i].school_name+"</td>"+
                                "<td>"+obj.participants[i].sex+"</td>"+
                                "<td>"+obj.participants[i].grade+"</td>"+
                                "</tr>");
                        }
                    }

                    $('#partByEventTable').dataTable({
                        "bLengthChange": false,
                        //     "bFilter": false,
                        "oLanguage": {
                            "sEmptyTable": "No Winners Entered"
                        },
                        "bPaginate": false,
                        "bInfo":false,
                        "bDestroy": true,
                        "sScrollY": "80px",
                        "bScrollCollapse": true
                    });
                    if(obj.participants)
                        var scrollLength = 27*obj.participants.length;
                    else
                        scrollLength = 30;
                    $('#allPartByEventTable').dataTable({
                        "bLengthChange": false,
                        //      "bFilter": false,
                        "oLanguage": {
                            "sEmptyTable": "No Participants with grade/point found"
                        },
                        "bPaginate": false,
                        "bInfo":false,
                        "bDestroy": true,
                        "sScrollY": scrollLength+"px",
                        "bScrollCollapse": true
                    });
                    if(obj.participants.length > 0)
                        $('#printParticipationCert').show();
                    if(obj.winners.length > 0)
                        $('#printWinnerCert').show();
                });
            });


        </script>
    </body>
</html>

