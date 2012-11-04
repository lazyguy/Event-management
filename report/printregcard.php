<!DOCTYPE html>
<?php include_once "../include.php"; ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Print Reg Card - Balolsav <?php echo getYear() ?></title>
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
                    <a class="brand" href="../index.php">Balolsav&nbsp;<?php echo getYear() ?></a>
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
                    <form id="resultentryform">
                        <fieldset id="resultSet"  class="well">
                            <H3>Print Report By Registration ID</H3>
                            <div class="row">
                                <div class="span6">
                                    <label for="report-firstRegId" style="padding-top: 15px;">Registration Id</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-firstRegId" placeholder="Enter registration Id and press enter" name="report-firstRegId" type="text" />
                                        <input id="firstHasCorrectValue" type="hidden" value="0"/>
                                    </div>
                                </div>
                                <div class="span7 offset1">
                                    <div  id="report-firstRegId-error" class="alert-message warning"></div>
                                </div>
                            </div>


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
                                    <a id="report-entry-save" class="btn primary">Print</a>
                                </div>
                                <div id="saveButtonMessage" class="span7 alert-message error"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>


                <div id="reportContentsrange" class="row" >
                    <form id="resultentryformrange">
                        <fieldset id="resultSetrange"  class="well">
                            <H3>Print Report By Registration ID Range</H3>

                            <div class="row" >
                                <div class="span6">
                                    <label for="report-rangestart" style="padding-top: 15px;">Starting Id</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-rangestart" name="report-rangestart" type="text"  />
                                    </div>
                                </div>
                                <div class="span6">
                                    <label for="report-rangeend" style="padding-top: 15px;">Finishing Id</label>
                                    <div class="input" style="padding-top: 5px;">
                                        <input class="large" id="report-rangeend" name="report-rangeend" type="text" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="clearfix" style="padding-top: 10px;">
                                    <input id="report-range-value" type="hidden" value="0"/>
                                    <label id="optionsRadio">Print All</label>
                                    <div class="input">
                                        <ul class="inputs-list">
                                            <li>
                                                <label>
                                                    <input type="radio" name="report-range" id="report-range-junior" value="1" />
                                                    <span>Juniors</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label>
                                                    <input type="radio" name="report-range" id="report-range-senior" value="2" />
                                                    <span>Seniors</span>
                                                </label>
                                            </li>

                                        </ul>
                                    </div>
                                </div><!-- /clearfix -->
                            </div>
                            <div class="row" id="resultSaveButtonsrange" style="padding-top: 25px;">
                                <div class="span3" >
                                    <a id="report-entry-cancelrange" class="btn error">Reset</a>
                                    <a id="report-entry-saverange" class="btn primary">Print</a>
                                </div>
                                <div id="saveButtonMessagerange" class="span7 alert-message error"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>




                <?php include_once "../footer.html" ?>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function(){
                geteventnames(2);
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
                            type:"getpartDetailsForEdit",
                            pId:$('#report-firstRegId').val()
                            //eid:$('#report-eventName').val()
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
                                //  alert(data);
                                var obj = jQuery.parseJSON(data);
                                $('#report-firstRegId-error').hide();
                                $('#report-firstPName').empty();
                                $('#report-firstPName').val(obj.student_name);
                                $('#report-firstSName').empty();
                                $('#report-firstSName').val(obj.school_name);
                                $('#firstHasCorrectValue').val('');
                                $('#firstHasCorrectValue').val(obj.part_id);
                            }
                        });
                    }
                });
                
                $('#saveButtonMessagerange').hide();
                $("input:radio[name=report-range]").click(function() {
                    $('#report-range-value').val($(this).val());
                });
            });

            $('#report-entry-cancel').on("click",function(){
                $('#resultentryform').find(':input').each(function(){ $(this).val('');});
                $('#report-firstRegId-error').hide();
                $('#saveButtonMessage').hide();
            });
            $('#report-entry-save').on("click",function(){
                alert("Remove auto fit to page option before printing");
                var url = "regcardgenerate.php?sid="+$('#firstHasCorrectValue').val();
                window.open(url);
            });
            $('#report-entry-cancelrange').on("click",function(){
                $('#resultentryformrange').find(':input').each(function(){ $(this).val('');});
                $('#saveButtonMessagerange').hide();
                $('#saveButtonMessagerange').hide();
            });
            $('#report-entry-saverange').on("click",function(){
                var printwhat =   $('#report-range-value').val();
                var startId = $('#report-rangestart').val();
                var endId = $('#report-rangeend').val();
                if(startId <=0 ||endId <=0||(endId<=startId) || startId==null || endId==null){
                    $('#saveButtonMessagerange').html("Please check values, starting id should be less than ending id");
                    $('#saveButtonMessagerange').show();
                }else if(printwhat<=0 ){
                    $('#saveButtonMessagerange').html("Please select junior/senior");
                    $('#saveButtonMessagerange').show();
                }else{
                    $('#saveButtonMessagerange').hide();
                    alert("Remove auto fit to page option before printing");
                      var url = "regcardgeneraterange.php?sid="+startId+"&eid="+endId+"&printwhat="+printwhat;
                      window.open(url);
                }
            });
        </script>
    </body>
</html>

