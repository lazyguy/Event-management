<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Result Entry - Balolsav <?php echo getYear() ?></title>
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
                                    <input id="firstHasCorrectValue" type="hidden" value="0"/>
                                </div>
                            </div><!-- /clearfix -->
                        </fieldset>
                        <fieldset id="resultSet"  class="well">
                            <div class="row">
                                <div class="span6">
                                    <label for="report-firstRegId" style="padding-top: 15px;">Registration Id</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-firstRegId" placeholder="Enter registration Id and press enter" name="report-firstRegId" type="text" />
                                    </div>
                                </div>
                                <div class="span7 offset1">
                                    <div  id="report-firstRegId-error" class="alert-message warning"></div>
                                </div>
                            </div>
                            <div class="row" style="padding-top: 10px;">
                                <div class="span6">
                                    <label for="report-score" style="padding-top: 15px;">Score</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-score" placeholder="Enter score out of 100" name="report-score" type="text" />
                                    </div>
                                </div>
                                <div class="span6">
                                    <label for="report-grade" style="padding-top: 15px;">Grade</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-grade" readonly="readonly" name="report-grade" type="text" />
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix" style="padding-top: 10px;">
                                <input id="report-position-value" type="hidden" value="0"/>
                                <label id="optionsRadio">Position/Place</label>
                                <div class="input">
                                    <ul class="inputs-list">
                                        <li>
                                            <label>
                                                <input type="radio" name="report-position" id="report-first-position" value="1" />
                                                <span>First Position</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="report-position" id="report-second-position" value="2" />
                                                <span>Second Position</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="report-position" id="report-third-position" value="3" />
                                                <span>Third Position</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="report-position" id="report-no-position" value="0" checked="checked"/>
                                                <span>No Podium Position</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- /clearfix -->
                            <div class="row" >
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
                            <div class="row" id="resultSaveButtons" style="padding-top: 25px;">
                                <div class="span3" >
                                    <a id="report-entry-cancel" class="btn error">Reset</a>
                                    <a id="report-entry-save" class="btn primary">Save</a>
                                </div>
                                <div id="saveButtonMessage" class="span7 alert-message error"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <?php include_once "../footer.html"?>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                geteventnames(2);
                $('#resultSet').hide();
                $('#report-firstRegId-error').hide();
                $('#saveButtonMessage').hide();
                $('#report-firstRegId').on("change keypress paste textInput input" ,function(e){
                    $('#report-firstPName').val("");
                    $('#report-firstSName').val("");
                    $('#report-firstRegId-error').empty();
                    $('#report-firstRegId-error').append("Registration Id is changed, press enter in the field to validate");
                    $('#report-firstRegId-error').show();
                    $('#firstHasCorrectValue').val("0");
                });

                $('#report-firstRegId').on('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        $.post("../addParticipant.php",{
                            type:"getParticipant",
                            regId:$('#report-firstRegId').val(),
                            eid:$('#report-eventName').val()
                        },function(data){
                            // alert(data);
                            if(data==-1){
                                $('#report-firstRegId-error').empty();
                                $('#report-firstRegId-error').append("Registration Id is not correct");
                                $('#report-firstRegId-error').show();
                                $('#report-firstPName').val("");
                                $('#report-firstSName').val("");
                                $('#firstHasCorrectValue').val("0");
                                $('#report-firstRegId').select();
                            }else{
                                var obj = jQuery.parseJSON(data);
                                $('#report-firstRegId-error').hide();
                                $('#report-firstPName').empty();
                                $('#report-firstPName').val(obj[0].student_name);
                                $('#report-firstSName').empty();
                                $('#report-firstSName').val(obj[0].school_name);
                                $('#report-grade').val('');
                                $('#report-grade').val(obj[0].grade);
                                $('#report-score').val('');
                                $('#report-score').val(obj[0].score);
                                $('#report-position-value').val(obj[0].position);
                                switch( obj[0].position){
                                    case '1':
                                        $('#report-first-position').attr("checked","checked");
                                        break;
                                    case '2':
                                        $('#report-second-position').attr("checked","checked");
                                        break;
                                    case '3':
                                        $('#report-third-position').attr("checked","checked");
                                        break;
                                    default:
                                        $('#report-no-position').attr("checked","checked");
                                        break;
                                }
                                $('#firstHasCorrectValue').val("1");
                                $('#report-score').select();
                            }
                        });
                    }
                });
            });
            $(".chzn-select").chosen();
            $("#report-eventName").chosen().change(function(){
                $('#resultSet').show();
                resetform();
                $('#report-firstRegId').focus();
            });
            $('#report-entry-cancel').on("click",function(){
                resetform();
                $('#report-firstRegId-error').hide();
                $('#report-secondRegId-error').hide();
                $('#report-thirdRegId-error').hide();
                $('#saveButtonMessage').hide();
            });
            $('#report-entry-save').on("click",function(){
                var firstHasCorrectValue = $('#firstHasCorrectValue').val();
                if(firstHasCorrectValue <= 0){
                    $('#saveButtonMessage').removeClass('success');
                    $('#saveButtonMessage').addClass('error');
                    $('#saveButtonMessage').empty();
                    $('#saveButtonMessage').append("Please enter registration id before pressing save.");
                    $('#saveButtonMessage').show();
                }else if( ($('#report-score').val() <= 0 ||$('#report-score').val() == null) && $('#report-position-value').val() == 0 ){
                    $('#saveButtonMessage').removeClass('success');
                    $('#saveButtonMessage').addClass('error');
                    $('#saveButtonMessage').empty();
                    $('#saveButtonMessage').append("Please enter poition or score or both ");
                    $('#saveButtonMessage').show();
                }else{
                    $('#saveButtonMessage').hide();
                    var score = $('#report-score').val();
                    var grade = null;
                    if(score>=80) grade='A';
                    else if(score>=60&&score<80) grade='B';
                    else if(score<60) grade='C';
                    var calcScore = $('#report-position-value').val();
                    switch(calcScore){
                        case '1':
                            score = 25;
                            grade = 'A';
                            break;
                        case '2':
                            score = 20;
                            grade = 'B';
                            break;
                        case '3':
                            score = 15;
                            grade = 'C';
                            break;
                        case '0':
                        default:
                            switch(grade){
                                case 'A':
                                    score= 10;
                                    break;
                                case 'B':
                                    score= 5;
                                    break;
                                case 'C':
                                    score= 3;
                                    break;
                            }
                            break;
                    }
                    if ( $('#report-position-value').val() == null)
                        $('#report-position-value').val(0);
                    $.post("manageResult.php",{
                        type:"addResult2",
                        eid:$('#report-eventName').val(),
                        regId:$('#report-firstRegId').val(),
                        score:score,
                        grade:grade,
                        position:$('#report-position-value').val()

                    },function(data){
                        //alert(data);
                        if(data==1){
                            $('#saveButtonMessage').removeClass('error');
                            $('#saveButtonMessage').addClass('success');
                            $('#saveButtonMessage').empty();
                            $('#saveButtonMessage').append("Result for the event is successfully saved.");
                            $('#saveButtonMessage').show();
                            resetform();
                        }else if(data == -4){
                            $('#saveButtonMessage').removeClass('success');
                            $('#saveButtonMessage').addClass('error');
                            $('#saveButtonMessage').empty();
                            $('#saveButtonMessage').append("Participant is not registered for this event");
                            $('#saveButtonMessage').show();
                        }else{
                            $('#saveButtonMessage').removeClass('success');
                            $('#saveButtonMessage').addClass('error');
                            $('#saveButtonMessage').empty();
                            $('#saveButtonMessage').append("Result for the event coul not be saved.");
                            $('#saveButtonMessage').show();
                        }
                    });
                }
            });
            $("input:radio[name=report-position]").click(function() {
                $('#report-position-value').val($(this).val());
            });
            function resetform() {
                $('#resultentryform').find(':input').each(function(){
                    var objId = this.id;
                    if(objId.localeCompare ("report-eventName") !=0){
                        switch(this.type){
                            case 'checkbox':
                            case 'radio':
                                this.checked = false;
                                break;
                            default:
                                $(this).val('');
                                break;
                        }
                    }
                });
                $('#saveButtonMessage').hide();
            }
            $('#report-score').on("change keypress paste textInput input" ,function(e){
                var score = $('#report-score').val();
                var grade = null;
                if(score>=80) grade='A';
                else if(score>=60&&score<80) grade='B';
                else if(score<60) grade='C';
                $('#report-grade').val(grade);
            });
        </script>
    </body>
</html>

