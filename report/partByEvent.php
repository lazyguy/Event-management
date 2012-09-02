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
                    <h5>Select Event name to get Participants</h5>
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
                        <div class="span6">
                            <a class="btn primary" id="printPartByEvent">Print</a>
                             <a class="btn primary" id="printPartBySchool">Print - Sort by school</a>
                        </div>
                    </div><!-- /clearfix -->
                </form>
                <div id="resultSet"  class="well" style="padding-top: 25px">
                    <div class ="span12" >
                        <table class="condensed-table bordered-table " id="partByEventTable">
                            <thead>
                                <tr>
                                    <th width="12%"><b>Reg No</b></th>
                                    <th width="25%"><b>Name</b></th>
                                    <th width="40%"><b>School Name</b></th>
                                    <th width="9%"><b>Sex</b></th>
                                    <th width="9%"><b>Age</b></th>
                                </tr>
                            </thead>
                            <tbody id="partByEventBody">
                            </tbody>
                        </table>
                        <table class="condensed-table bordered-table " id="allPartByEventTable">
                            <thead>
                                <tr>
                                    <th><b>Event Name</b></th>
                                    <th width="6%"><b>Reg No</b></th>
                                    <th><b>Name</b></th>
                                    <th><b>School Name</b></th>
                                    <th width="8%"><b>Sex</b></th>
                                    <th width="7%"><b>Age</b></th>
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
            $(document).ready(function(){
                geteventnames(3);
                $('#resultSet').hide();
                $('#allPartByEventTable').hide();
                $('#partByEventTable').hide();
                $('#printPartByEvent').hide();
                $('#printPartBySchool').hide();
                $('#printPartByEvent').on("click",function(){
                    var url = "printPartByEvent.php?eId="+$('#report-eventName').val();
                    window.open(url);
                });
                $('#printPartBySchool').on("click",function(){
                    var url = "printPartByEvent.php?eId="+$('#report-eventName').val()+"&sortby=1";
                    window.open(url);
                });
            });
            $(".chzn-select").chosen();
            $("#report-eventName").chosen().change(function(){
                $('#resultSet').show();
                $.post("getPartByType.php", {
                    type: "byEvent",
                    eId: $('#report-eventName').val()
                }, function(data){
                    /*
                       $('#errorSet').empty();
                        $('#errorSet').html(data);
                     */
                    var oTable1 = $('#allPartByEventTable').dataTable();
                    oTable1.fnDestroy();
                    var oTable2 = $('#partByEventTable').dataTable();
                    oTable2.fnDestroy();
                    $('#allPartByEventTable').hide();
                    $('#partByEventTable').hide();
                   
                    if($('#report-eventName').val() != 999999){
                        
                        $('#partByEventTable').show();
                        var obj = jQuery.parseJSON(data);
                        var oTable = $('#partByEventTable').dataTable();
                        oTable.fnClearTable();
                        if(obj){
                            for (var i = 0; i < obj.participants.length; i++) { 
                                $('#partByEventBody').append("<tr>"+"<td>"+obj.participants[i].rNum+
                                    "</td>"+"<td>"+obj.participants[i].name+"</td>"+"<td>"+obj.participants[i].school_name+"</td>"+"<td>"+obj.participants[i].sex+"</td>"+"<td>"+obj.participants[i].age+"</td>"+"</tr>");
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
                                "sEmptyTable": "No Participants Registered for this Event"
                            },
                            "bPaginate": false,
                            "bInfo":false,
                            "bDestroy": true,
                            "sScrollY": "170px"
                        });
                    }
                    else {
                        $('#allPartByEventTable').show();
                        var obj = jQuery.parseJSON(data);
                        var oTable = $('#allPartByEventTable').dataTable();
                        oTable.fnClearTable();
                        if(obj){
                            for (var i = 0; i < obj.participants.length; i++) { 
                                $('#allPartByEventBody').append("<tr>"+
                                    "<td>"+obj.participants[i].ename+"</td>"+
                                    "<td>"+obj.participants[i].rNum+"</td>"+
                                    "<td>"+obj.participants[i].name+"</td>"+
                                    "<td>"+obj.participants[i].school_name+"</td>"+
                                    "<td>"+obj.participants[i].sex+"</td>"+
                                    "<td>"+obj.participants[i].age+"</td>"+"</tr>");
                            }
                             $('#printPartByEvent').html('');
                            $('#printPartByEvent').html('Print - Sort By Event');
                            $('#printPartByEvent').show();
                            $('#printPartBySchool').show();
                        }
                        $('#allPartByEventTable').dataTable({
                            "bLengthChange": false,
                            "bFilter": false,
                            "oLanguage": {
                                "sEmptyTable": "No Participants found"
                            },
                            "bPaginate": false,
                            "bInfo":false,
                            "bDestroy": true,
                            "sScrollY": "250px"
                        });
                    }
                });
            });
            
            
        </script>
    </body>
</html>

