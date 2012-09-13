<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Winners - Balolsav <?php echo getYear() ?></title>
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
                <div class ="row">
                    <h3>Champions</h3>
                </div>
                <!--
                KalaThilakom -> Female with max points
                KalaPrathibha -> Male with max points
                -->
                <div class ="row" id="thilakomDiv">
                    <h4>KalaThilakom</h4>
                    <div class ="well">
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Name :</div><div class="span6" id="thilakomName"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Points :</div><div class="span6" id="thilakomPoints"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Reg No :</div><div class="span6" id="thilakomReg"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">School :</div><div class="span6" id="thilakomSchool"></div></div>
                    </div>
                </div>

                <div class ="row" id="prathibhaDiv">
                    <h4>KalaPrathibha</h4>
                    <div class ="well">
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Name :</div><div class="span6"id="prathibhaName"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Points :</div><div class="span6" id="prathibhaPoints"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">Reg No :</div><div class="span6" id="prathibhaReg"></div></div>
                        <div class="row"><div style="padding-left: 25px;" class ="span2">School :</div><div class="span6" id="prathibhaSchool"></div></div>
                    </div>
                </div>
                <div class ="row" id="pointsTableDiv">
                    <h4>Points Table</h4>
                    <div id="resultSet"  class="well" style="padding-top: 25px">
                        <div class ="span12" >
                            <table class="condensed-table bordered-table " id="pointsTable">
                                <thead>
                                    <tr>
                                        <th width="12%"><b>Reg No</b></th>
                                        <th width="25%"><b>Name</b></th>
                                        <th width="32%"b>School Name</b></th>
                                        <th width="26%"><b>Points</b></th>
                                    </tr>
                                </thead>
                                <tbody id="pointsTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--div id="errorSet"  class="well" style="padding-top: 25px"></div-->

                <?php include_once "../footer.html" ?>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#thilakomDiv').hide();
                $('#prathibhaDiv').hide();
                $('#pointsTableDiv').hide();
                $.post("getwinners.php",{
                    type:"getAll"
                },function(data){
                   // $('#errorSet').html(data);
                    if(data != null){
                        var obj = jQuery.parseJSON(data);
                        if(!obj)
                            return;
                        $('#thilakomName').html('<b>'+obj.thilakom.Name+'</b>');
                        $('#thilakomPoints').html('<b>'+obj.thilakom.points+'</b>');
                        $('#thilakomReg').html('<b>'+obj.thilakom.regn_number+'</b>');
                        $('#thilakomSchool').html('<b>'+obj.thilakom.school_name+'</b>');

                        $('#prathibhaName').html('<b>'+obj.prathibha.Name+'</b>');
                        $('#prathibhaPoints').html('<b>'+obj.prathibha.points+'</b>');
                        $('#prathibhaReg').html('<b>'+obj.prathibha.regn_number+'</b>');
                        $('#prathibhaSchool').html('<b>'+obj.prathibha.school_name+'</b>');

                        $('#thilakomDiv').show();
                        $('#prathibhaDiv').show();
                        $('#pointsTableDiv').show();
                        var oTable = $('#pointsTable').dataTable();
                        oTable.fnClearTable();
                        for (var i = 0; i < obj.participants.length; i++) {
                            $('#pointsTableBody').append("<tr>"+
                                "<td>"+obj.participants[i].regn_number+"</td>"+
                                "<td>"+obj.participants[i].Name+"</td>"+
                                "<td>"+obj.participants[i].school_name+"</td>"+
                                "<td>"+obj.participants[i].points+"</td>"+"</tr>");
                        }
                        $('#pointsTable').dataTable({
                            "bLengthChange": false,
                            "bFilter": false,
                            "oLanguage": {
                                "sEmptyTable": "No Data"
                            },
                            "bPaginate": false,
                            "bInfo":false,
                            "bDestroy": true,
                            "sScrollY": "170px",
                            "bScrollCollapse": true,
                            "aaSorting": [[3,'desc']]
                        });

                    }else{
                        alert("Could not retreive data");
                    }
                });
            });
        </script>
    </body>
</html>

