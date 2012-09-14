<!DOCTYPE html>
<?php include_once "include.php"; ?>
<html lang="en">
    <head>
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

        <link href="jquery-ui-1.8.22.custom.css" rel="stylesheet">
        <link rel="stylesheet" href="chosen.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-modal.js"></script>
        <script src="js/jquery-ui-1.8.22.custom.min.js"></script>
        <script src="js/bootstrap-alerts.js"></script>
        <script src="js/bootstrap-dropdown.js"></script>
        <script src="js/table-sorter.js"></script>
        <script src="js/eventControl.js"></script>
        <script src="js/schoolControl.js"></script>
        <script src="js/participantControl.js"></script>
        <script src="js/chosen.jquery.min.js"></script>
        <!--
        <script src="js/bootstrap-twipsy.js"></script>
        <script src="js/bootstrap-popover.js"></script>
        <script src="js/bootstrap-dropdown.js"></script>
        <script src="js/bootstrap-scrollspy.js"></script>
        <script src="js/bootstrap-tabs.js"></script>
        <script src="js/bootstrap-buttons.js"></script>
        -->
        <style>
            .ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
        </style>
        <script type="text/javascript">
            //Global required for pagination
            var eventName;  //last event name that was searched
            var searchType; //last search type -> exact or match(%like%)
            var schoolName; //last school name that was searched
            $(document).ready(function(){
                //Detect if browser is IE. Many things do not work as expected in ie.
                //Not supporting IE seems like a better idea.
                if($.browser.msie && $.browser.version <9){
                    if (navigator.onLine){
                        alert("This Web App will not work with inter net ecplorer versions < 9;")
                        window.location.replace("http://abetterbrowser.org/");
                    }else{
                        window.location.replace("better.html");
                    }
                }
                $('#eventResult').hide();
                $('#eventCancel').click(function(){  $('#modal-add-event').modal('hide')});
                $('#eventSave').click(saveEvent);
                $('#saveEventForm').bind('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        saveEvent(e);
                    }
                });
                $('#eventNameEdit').bind('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        e.preventDefault();
                        $('#eventNameEdit').autocomplete("close");
                        getEvents(null);
                    }
                });
                $('#modal-add-event').bind('shown', function(){ $('#eventName').select(); $('#eventResult').hide();} );
                $('#eventEditResult').hide();
                $('#eventEditCancel').click(function(){  $('#modal-edit-event').modal('hide')});
                $('#modal-edit-event').bind('shown', function(){$('#eventNameEdit').select();$('#eventEditResult').hide();$('#eventsTable').hide();$('#eventEditPages').hide();});
                $("table#eventsTable").on("click", getAction);
                $('#eventNameEdit').autocomplete({
                    source: "get_event_list.php",
                    minLength: 2,
                    select: function( event, ui ){
                        ui.item ? getEvents(ui.item.value):false;
                    }
                });
                $("table#eventsTable").tablesorter({ headers:{ 2:{ sorter: false}, 3:{ sorter: false}}});
                $("#eventNextPage").on("click", getEventNextPage);
                $("#eventPrevPage").on("click", getEventPrevPage);
                $('#curPage').html("1");
                $('#modal-editSave-event').bind('shown', function(){$('#eventSaveName').select();$('#eventEditSaveResult').hide();});
                $('#eventSaveCancel').click(function(){  $('#modal-editSave-event').modal('hide')});
                $('#eventEditSave').click(editEventSave);
                //School add stuff
                $('#modal-add-school').bind('shown', function(){ $(':input','#saveSchoolForm').not(':button, :submit, :reset, :hidden').val(''); $('#schoolAddResult').hide();} );
                $('#schoolCancel').click(function(){  $('#modal-add-school').modal('hide')});
                $('#schoolSave').click(saveSchool);
                $('#modal-edit-school').bind('shown', function(){
                    $('#schoolNameEdit').select();
                    $('#schoolEditResult').hide();
                    $('#schoolsTable').hide();
                    $('#schoolEditPages').hide();
                });
                $('#schoolEditCancel').click(function(){  $('#modal-edit-school').modal('hide')});
                $('#schoolNameEdit').autocomplete({
                    source: "get_school_list.php",
                    minLength: 2,
                    select: function( event, ui ){
                        ui.item ? getSchools(ui.item.value):false;
                    }
                });
                $('#saveSchoolForm').bind('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        saveSchool(e);
                    }
                });
                $('#schoolNameEdit').bind('keypress', function(e){
                    if ( e.keyCode == 13 ){
                        e.preventDefault();
                        $('#schoolNameEdit').autocomplete("close");
                        getSchools(null);
                    }
                });
                $("table#schoolsTable").tablesorter({ headers:{ 2:{ sorter: false}, 3:{ sorter: false}}});
                $("#schoolNextPage").on("click", getSchoolNextPage);
                $("#schoolPrevPage").on("click", getSchoolPrevPage);
                $("table#schoolsTable").on("click", getSchoolAction);
                $('#modal-edit-save-school').bind('shown', function(){$('#schoolNameEdit').select();});
                $('#schoolEditsaveCancel').click(function(){  $('#modal-edit-save-school').modal('hide')});
                $('#schoolEditSave').click(function(){  editSchoolSave()});
                //participant add stuff.
                $('#modal-add-participant').bind('shown', function(){
                    //$(':input','#saveSchoolForm').not(':button, :submit, :reset, :hidden').val('');
                    $('#participantAddResult').hide();
                    geteventnames(1);
                });
                $('#participantCancel').click(function(){  $('#modal-add-participant').modal('hide')});

                $('#part-school-name').autocomplete({
                    source: "get_school_list.php",
                    minLength: 2,
                    select: function( event, ui ){
                        ui.item ? getSchools(ui.item.value):false;
                        $('#part-school-id').val(ui.item.id);
                    }
                });
                $(".chzn-select").chosen();
                $('#part-school-name').change(function() {
                    $('#part-school-id').val(0);
                });
                $("#DOB").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "c-20:c",
                    dateFormat: "d/mm/yy"
                });
                $("#participantSave").on("click",function(){ saveParticipant(0)});
                $("#participantSavePrint").on("click", function(){saveParticipant(1)});
                //stuff for particpant edit
                $('#edit-modal-add-participant').bind('shown', function(){
                    $('#edit-participantid').select();
                    $('#edit-saveParticipantForm').hide();
                    $('#edit-participantSave').hide();
                    $('#edit-participantSavePrint').hide();
                    $('#edit-participantAddResult').hide();
                    $('#part-participantid').val("");

                });
                $('#edit-participantid').on("keypress",function(e){
                    if ( e.keyCode == 13 ){
                        e.preventDefault();
                        getparticipantdetails();
                    }
                });
                $('#edit-part-school-name').autocomplete({
                    source: "get_school_list.php",
                    minLength: 2,
                    select: function( event, ui ){
                        ui.item ? getSchools(ui.item.value):false;
                        $('#edit-part-school-id').val(ui.item.id);
                    }
                });
                $('#edit-participantCancel').click(function(){  $('#edit-modal-add-participant').modal('hide')});
                $('#edit-participantSave').click(function(){ editParticipant(0);});
                $('#edit-participantSavePrint').click(function(){ editParticipant(1);});

                /*
                 * Reinitialize app -> this will delete all data and start fresh
                 * to delete all tables, and data and start fresh next year
                 * this will also backup current data if possible.
                 */
                $('#reinitallResult').hide();
                $('#modal-reinitall').bind('shown', function(){
                    $("#reinitPass").val('');
                    $("#reinitPass").focus();
                    $('#reinitallResult').hide();
                });
                $('#reinitCancel').on("click",function(){ $('#modal-reinitall').modal('hide');});
                $('#reinitConfirm').on("click",function(){
                    if (confirm('Are you sure you want to delete all data?')) {
                        $.post("reinit.php",
                        {
                            pass:$("#reinitPass").val()
                        },
                        function(data){
                            pass:$("#reinitPass").val('');
                            alert(data);
                            switch (data){
                                case '0': //wrong password
                                    $('#reinitallResult').html('Wrong password');
                                    $('#reinitallResult').show();
                                    break;
                                case '1': // success
                                    alert('All data deleted and app reset; Redirecting to home page');
                                    window.location.replace("index.php");
                                    break;
                                case '-2':
                                    $('#reinitallResult').html('No data to delete');
                                    $('#reinitallResult').show();
                                    window.location.replace("index.php");
                                    break;
                                default: // some error
                                    $('#reinitallResult').html('An error has occured and could not reset data.');
                                    $('#reinitallResult').show();
                                    window.location.replace("index.php");
                                    break;
                            }
                        });
                        //    $('#modal-reinitall').modal('hide');
                    } else {
                        // Do nothing!
                    }

                });
            });

        </script>
        <style type="text/css">
            body {
                padding-top: 60px;
            }
        </style>
        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="images/logo.ico">
    </head>

    <body>

        <div class="topbar" data-dropdown="dropdown">
            <div class="fill">
                <div class="container">
                    <a class="brand" href="index.php">Balolsav <?php echo getYear() ?></a>
                    <ul class="nav">
                        <li class="active">
                            <a href="index.php">Home</a>
                        </li>
                    </ul>
                    <ul class="nav secondary-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">Settings</a>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-controls-modal="modal-reinitall" data-backdrop="static" data-keyboard="true" >Reinitialize App</a></li>
                                <!--
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Another link</a></li>
                                -->
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1><img src="images/logo.png"/>RotaryBalolsav&nbsp;<?php echo getYear() ?></h1>
            </div>
            <div class="row">
                <div class="span-one-third">
                    <h2>Event/School</h2>

                    <?php include_once "eventView.html"; ?>
                    <?php include_once "schoolView.html"; ?>
                    <button data-controls-modal="modal-add-event" data-backdrop="static" data-keyboard="true" class="btn success">ADD
                        Event&nbsp;&nbsp;</button> <button data-controls-modal="modal-edit-event" data-backdrop="static" data-keyboard="true" class="btn primary">Edit
                        Event&nbsp;&nbsp;&nbsp;</button><br/>
                    <br/>
                    <button data-controls-modal="modal-add-school" data-backdrop="static" data-keyboard="true" class="btn success">ADD
                        School</button>
                    <button data-controls-modal="modal-edit-school" data-backdrop="static" data-keyboard="true" class="btn primary">Edit
                        School&nbsp;</button>
                    <p></p>
                    <p>
                        Use this page to add or remove events and schools
                    </p>
                </div>
                <div class="span-one-third">
                    <h2>Participant</h2>

                    <?php include_once "participantView.html"; ?>
                    <button data-controls-modal="modal-add-participant" data-backdrop="static" data-keyboard="true" class="btn success">ADD
                        Participant</button>
                    <button data-controls-modal="edit-modal-add-participant" data-backdrop="static" data-keyboard="true" class="btn primary">Edit
                        Participant</button>
                    <p></p>
                    <p>
                        Use this page to add or remove participants
                    </p>
                </div>
                <div class="span-one-third">
                    <h2>Results/Reports</h2>

                    <p>
                        <a class="btn success" href="reports.php">Reports/Results &raquo;</a>
                    </p>
                    <p>
                        Use this page to enter, view or print reports and results
                    </p>
                </div>
            </div>
            <div>
                <br /><br /><br /><br /><br /><br /><br /><br /> <!-- Add few blank lines Not a great solution!!! -->
            </div>
            <?php include_once "footer.html" ?>
        </div> <!-- /container -->


        <div id="modal-reinitall" class="modal hide fade">
            <div class="modal-header">
                <a href="#" class="close">&times;</a> <h3>Reinitialize Application</h3>
            </div>
            <div class="modal-body">
                <strong>
                    Reinitializing application will delete all data and reset tables in database to start fresh.
                    Use this function to clear all data before starting every year;
                </strong>

                <div class="row" style="padding-top: 15px;padding-bottom: 15px;">
                    <div class="span2">Password</div>
                    <div class="span4"> <input class="xlarge" id="reinitPass" name="reinitPass" size="30" type="password" /></div>
                </div>
                <a id="reinitallResult" class="alert-message error"></a>
            </div>
            <div class="modal-footer">
                <a id="reinitCancel" class="btn secondary">Cancel</a>
                <a id="reinitConfirm" class="btn danger">Reinit</a>
            </div>
        </div>


    </body>
</html>
