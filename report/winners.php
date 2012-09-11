<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Balolsav <?php echo getYear() ?></title>
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
                    <h5>Select School name to get Winners</h5>
                </div>
                <form>
                    <div class="row">
                        <label for="report-schoolName">School Name</label>
                        <div class="input span5">
                            <select id="report-schoolName" data-placeholder="Choose a School" class="chzn-select" style="width:275px;">
                                <option value=""></option>
                            </select>
                            <input id="firstHasCorrectValue" type="hidden" value="0"/>
                        </div>
                    </div><!-- /clearfix -->
                </form>
                <div id="resultSet"  class="well" style="padding-top: 25px">
                    <div class ="span12" >
                        <div  class="row">
                            <div class="span3">
                                <h4>Points by School</h4>
                            </div>
                            <div class="span3">
                                <a class="btn primary" id="printPointsBySchool">Print</a>
                            </div>
                        </div>
                        <table class="condensed-table bordered-table " id="partByEventTable">
                            <thead>
                                <tr>
                                    <th width="15%"><b>School Id</b></th>
                                    <th width="70%"><b>School Name</b></th>
                                    <th width="15%"><b>Total Score</b></th>
                                </tr>
                            </thead>
                            <tbody id="partByEventBody">
                            </tbody>
                        </table>
                        <br /><br />
                        <div class ="row">
                            <div class="span3">
                                <h4>Winners by School</h4></div>
                            <div class="span4">
                                <a class="btn primary" id="printWinnerBySchool">Print</a>
                            </div>
                        </div>
                        <table class="condensed-table bordered-table " id="allPartByEventTable">
                            <thead>
                                <tr>
                                    <th width="12%"><b>Reg No</b></th>
                                    <th width="20%"><b>Name</b></th>
                                    <th width="32%"b>School Name</b></th>
                                    <th width="22%"><b>Event Name</b></th>
                                    <th width="9%"><b>Position</b></th>
                                </tr>
                            </thead>
                            <tbody id="allPartByEventBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--div id="errorSet"  class="well" style="padding-top: 25px"></div-->

                <footer>
                    <p>&copy; Rotary Club of Cherthala</p>
                </footer>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                getschoolnames(0);
                $('#resultSet').hide();
                $('#allPartByEventTable').hide();
                $('#partByEventTable').hide();
                $('#printWinnerBySchool').hide();
                $('#printPointsBySchool').hide();
                $('#printWinnerBySchool').on("click", function () {
                    var url = "printwinnerbyschool.php?eId=" + $('#report-schoolName').val();
                    window.open(url);
                });
                $('#printPointsBySchool').on("click", function () {
                    var url = "printwinnerbyschool.php?eId=999999&printall=1";
                    window.open(url);
                });
            });
            $(".chzn-select").chosen();
            $("#report-schoolName").chosen().change(function () {
                $('#resultSet').show();
                $.post("winnerbyschool.php", {
                    type: "bySchool",
                    eId: $('#report-schoolName').val()
                }, function (data) {
                    /*
                    $('#errorSet').empty();
                    $('#errorSet').html(data);
                     */
                    var oTable1 = $('#allPartByEventTable').dataTable();
                    oTable1.fnDestroy();
                    var oTable2 = $('#partByEventTable').dataTable();
                    oTable2.fnDestroy();
                    $('#partByEventTable').show();
                    var obj = jQuery.parseJSON(data);
                    var oTable = $('#partByEventTable').dataTable();
                    oTable.fnClearTable();
                    if(obj){
                        for (var i = 0; i < obj.sum.length; i++) {
                            $('#partByEventBody').append("<tr>"+
                                "<td>"+obj.sum[i].school_id+"</td>"+
                                "<td>"+obj.sum[i].school_name+"</td>"+
                                "<td>"+obj.sum[i].marks_sum+"</td>"+
                                "</tr>");
                        }
                        $('#printPartByEvent').html('');
                        $('#printPartByEvent').html('Print');
                        $('#printPartByEvent').show();
                        $('#printPartBySchool').hide();
                    }
                    $('#partByEventTable').dataTable({
                        "bLengthChange": false,
                        "bFilter": false,
                        "oLanguage": {
                            "sEmptyTable": "No Data found"
                        },
                        "bPaginate": false,
                        "bInfo":false,
                        "bDestroy": true,
                        "sScrollY": "170px",
                        "bScrollCollapse": true,
                        "aaSorting": [[2,'desc']]
                    });
                    $('#allPartByEventTable').show();
                    // var obj1 = jQuery.parseJSON(data);
                    var oTable1 = $('#allPartByEventTable').dataTable();
                    oTable1.fnClearTable();
                    if (obj.participants) {
                        for (var i = 0; i < obj.participants.length; i++) {
                            $('#allPartByEventBody').append("<tr>" +
                                "<td>" + obj.participants[i].regn_number + "</td>" +
                                "<td>" + obj.participants[i].name + "</td>" +
                                "<td>" + obj.participants[i].school_name + "</td>" +
                                "<td>" + obj.participants[i].event_name + "</td>" +
                                "<td>" + obj.participants[i].position + "</td>" +
                                "</tr>");
                        }
                        if(obj.participants && obj.participants.length > 0)
                            $('#printWinnerBySchool').show();
                        else
                            $('#printWinnerBySchool').hide();
                        //  $('#printPointsBySchool').show();
                    }
                    $('#allPartByEventTable').dataTable({
                        "bLengthChange": false,
                        "bFilter": false,
                        "oLanguage": {
                            "sEmptyTable": "No Data found"
                        },
                        "bPaginate": false,
                        "bInfo": false,
                        "bDestroy": true,
                        "sScrollY": "170px",
                        "bScrollCollapse": true,
                        "aaSorting": [[2,'asc']]
                    });
                    if(obj.participants && obj.participants.length > 0)
                        $('#printWinnerBySchool').show();
                    else
                        $('#printWinnerBySchool').hide();
                    if(obj.sum && obj.sum.length > 0)
                        $('#printPointsBySchool').show();
                    else
                        $('#printPointsBySchool').hide();
                });
            });
        </script>
    </body>
</html>

