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
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap-modal.js"></script>
        <script src="../js/jquery-ui-1.8.22.custom.min.js"></script>
        <script src="../js/bootstrap-alerts.js"></script>
        <script src="../js/table-sorter.js"></script>
        <script src="../js/eventControl.js"></script>
        <script src="../js/schoolControl.js"></script>
        <script src="../js/participantControl.js"></script>
        <script src="../js/chosen.jquery.min.js"></script>
        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>
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
                    <h5>Select Event name to enter result</h5>
                </div>
                <div id="reportContents" class="row" >
                    <form id="resultentryform">
                        <fieldset>
                            <div class="clearfix">
                                <label for="report-eventName">Event Name</label>
                                <div class="input">
                                    <select id="report-eventName" data-placeholder="Choose an event" class="chzn-select" style="width:275px;">
                                        <option value=""></option>
                                    </select>
                                    <input id="firstHasCorrectValue" type="hidden" value="-1"/>
                                    <input id="secondHasCorrectValue" type="hidden" value="-1"/>
                                    <input id="thirdHasCorrectValue" type="hidden" value="-1"/>
                                </div>
                            </div><!-- /clearfix -->
                        </fieldset>
                        <fieldset id="resultSet">
                            <fieldset id="firstResultSet"  class="well" >
                                <h5>First Position</h5>
                                <div class="row">
                                    <div class="span6">
                                        <label for="report-firstRegId" style="padding-top: 15px;">Registration Id</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-firstRegId" placeholder="Enter registration Id and press enter" name="report-firstRegId" type="text" />
                                        </div>
                                    </div>
                                    <div class="span7 offset1">
                                        <div  id="report-firstRegId-error" class="alert-message error"></div>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px;">
                                    <div class="span6">
                                        <label for="report-firstPName" style="padding-top: 15px;">Participant Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-firstPName" name="report-firstPName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <label for="report-firstSName" style="padding-top: 15px;">School Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-firstSName" name="report-firstSName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="secondResultSet"  class="well" >
                                <h5>Second Position</h5>
                                <div class="row">
                                    <div class="span6">
                                        <label for="report-secondRegId" style="padding-top: 15px;">Registration Id</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-secondRegId" placeholder="Enter registration Id and press enter" name="report-secondRegId" type="text" />
                                        </div>
                                    </div>
                                    <div class="span7 offset1">
                                        <div  id="report-secondRegId-error" class="alert-message error"></div>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px;">
                                    <div class="span6">
                                        <label for="report-secondPName" style="padding-top: 15px;">Participant Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-secondPName" name="report-secondPName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <label for="report-secondSName" style="padding-top: 15px;">School Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-secondSName" name="report-secondSName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset id="thirdResultSet"  class="well" >
                                <h5>Third Position</h5>
                                <div class="row">
                                    <div class="span6">
                                        <label for="report-thirdRegId" style="padding-top: 15px;">Registration Id</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-thirdRegId" placeholder="Enter registration Id and press enter" name="report-thirdRegId" type="text" />
                                        </div>
                                    </div>
                                    <div class="span7 offset1">
                                        <div  id="report-thirdRegId-error" class="alert-message error"></div>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px;">
                                    <div class="span6">
                                        <label for="report-thirdPName" style="padding-top: 15px;">Participant Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-thirdPName" name="report-thirdPName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <label for="report-thirdSName" style="padding-top: 15px;">School Name</label>
                                        <div class="input" style="padding-top: 5px;">
                                            <input class="large" id="report-thirdSName" name="report-thirdSName" type="text" readonly="readonly" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="span3" >
                                    <a id="report-entry-cancel" class="btn error">Reset</a>
                                    <a id="report-entry-save" class="btn primary">Save</a>
                                </div>
                                <div id="saveButtonMessage" class="span7 alert-message error"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <footer>
                    <p>&copy; Rotary Club of Cherthala</p>
                </footer>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                geteventnames(2);
                //  $('#resultSet').hide();
                $('#report-firstRegId-error').hide();
                $('#report-secondRegId-error').hide();
                $('#report-thirdRegId-error').hide();
                $('#saveButtonMessage').hide();                
                $('#report-firstRegId').change( function(e){
                    $('#report-firstPName').val("");
                    $('#report-firstSName').val("");
                    $('#report-firstRegId-error').empty();
                    $('#report-firstRegId-error').append("Registration Id is changed, press enter in the field to validate");
                    $('#report-firstRegId-error').show();
                    $('#firstHasCorrectValue').val("-1");
                });
                $('#report-secondRegId').change( function(e){
                    $('#report-secondPName').val("");
                    $('#report-secondSName').val("");
                    $('#report-secondRegId-error').empty();
                    $('#report-secondRegId-error').append("Registration Id is changed, press enter in the field to validate");
                    $('#report-secondRegId-error').show();
                    $('#secondHasCorrectValue').val("-1");
                });
                $('#report-thirdRegId').change( function(e){
                    $('#report-thirdPName').val("");
                    $('#report-thirdSName').val("");
                    $('#report-thirdRegId-error').empty();
                    $('#report-thirdRegId-error').append("Registration Id is changed, press enter in the field to validate");
                    $('#report-thirdRegId-error').show();
                    $('#thirdHasCorrectValue').val("-1");
                });
                $('#report-firstRegId').on('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        $.post("../addParticipant.php",{
                            type:"getParticipant",
                            regId:$('#report-firstRegId').val()
                        },function(data){
                            //alert(data);
                            if(data==-1){
                                $('#report-firstRegId-error').empty();
                                $('#report-firstRegId-error').append("Registration Id is not correct");
                                $('#report-firstRegId-error').show();
                                $('#report-firstPName').val("");
                                $('#report-firstSName').val("");
                                $('#firstHasCorrectValue').val("-1");
                            }else{
                                var obj = jQuery.parseJSON(data);
                                $('#report-firstRegId-error').hide();
                                $('#report-firstPName').empty();
                                $('#report-firstPName').val(obj[0].student_name);
                                $('#report-firstSName').empty();
                                $('#report-firstSName').val(obj[0].school_name);
                                $('#firstHasCorrectValue').val("1");
                            }
                        });
                    }
                });
                $('#report-secondRegId').on('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        $.post("../addParticipant.php",{
                            type:"getParticipant",
                            regId:$('#report-secondRegId').val()
                        },function(data){
                            // alert(data);
                            if(data==-1){
                                $('#report-secondRegId-error').empty();
                                $('#report-secondRegId-error').append("Registration Id is not correct");
                                $('#report-secondRegId-error').show();
                                $('#report-secondPName').val("");
                                $('#report-secondSName').val("");
                                $('#secondHasCorrectValue').val("-1");
                            }else{
                                var obj = jQuery.parseJSON(data);
                                $('#report-secondRegId-error').hide();
                                $('#report-secondPName').empty();
                                $('#report-secondPName').val(obj[0].student_name);
                                $('#report-secondSName').empty();
                                $('#report-secondSName').val(obj[0].school_name);
                                $('#secondHasCorrectValue').val("1");
                            }
                        });
                    }
                });
                $('#report-thirdRegId').on('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        $.post("../addParticipant.php",{
                            type:"getParticipant",
                            regId:$('#report-thirdRegId').val()
                        },function(data){
                            // alert(data);
                            if(data==-1){
                                $('#report-thirdRegId-error').empty();
                                $('#report-thirdRegId-error').append("Registration Id is not correct");
                                $('#report-thirdRegId-error').show();
                                $('#report-thirdPName').val("");
                                $('#report-thirdSName').val("");
                                $('#thirdHasCorrectValue').val("-1");
                            }else{
                                var obj = jQuery.parseJSON(data);
                                $('#report-thirdRegId-error').hide();
                                $('#report-thirdPName').empty();
                                $('#report-thirdPName').val(obj[0].student_name);
                                $('#report-thirdSName').empty();
                                $('#report-thirdSName').val(obj[0].school_name);
                                $('#thirdHasCorrectValue').val("1");
                            }
                        });
                    }
                });
            });
            $(".chzn-select").chosen();
            $("#report-eventName").chosen().change(function(){
                // alert("Asd");
                $('#resultSet').show();
            });
            $('#report-entry-cancel').on("click",function(){
                alert("dsa");
            });
            $('#report-entry-save').on("click",function(){
                var firstHasCorrectValue = $('#firstHasCorrectValue').val();
                var secondHasCorrectValue = $('#secondHasCorrectValue').val();
                var thirdHasCorrectValue = $('#thirdHasCorrectValue').val();
                if(firstHasCorrectValue <= -1||secondHasCorrectValue == -1||thirdHasCorrectValue == -1){
                    $('#saveButtonMessage').empty();
                    $('#saveButtonMessage').append("Please enter result for all three places before pressing save.");
                    $('#saveButtonMessage').show();
                }else{
                    $('#saveButtonMessage').hide();
                    $.post("../resultentry.php",{
                        type:"addResult",
                        eid:$('#report-eventName').val(),
                        firstregId:$('#report-firstRegId').val(),
                        secondregId:$('#report-secondRegId').val(),
                        thirdregId:$('#report-thirdRegId').val()
                    },function(data){
                        alert("insert done"+data);
                    });
                }
            });
        </script>
    </body>
</html>

