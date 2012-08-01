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
        <link href="jquery.ui.autocomplete.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-modal.js"></script>
        <script src="js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="js/bootstrap-alerts.js"></script>
        <script src="js/table-sorter.js"></script>
        <script src="js/eventControl.js"></script>        
        <script src="js/schoolControl.js"></script>
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
                $('#modal-edit-school').bind('shown', function(){$('#schoolNameEdit').select();$('#schoolEditResult').hide();$('#schoolsTable').hide();$('#schoolEditPages').hide();});
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
                $('#modal-add-participant').bind('shown', function(){ $(':input','#saveSchoolForm').not(':button, :submit, :reset, :hidden').val(''); $('#participantAddResult').hide();} );
                $('#participantCancel').click(function(){  $('#modal-add-participant').modal('hide')});
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

        <div class="topbar">
            <div class="fill">
                <div class="container">
                    <a class="brand" href="index.php">Balolsav 2012</a>
                    <ul class="nav">
                        <li class="active">
                            <a href="index.php">Home</a>
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
                    <p>
                        Use this page to add or remove events and schools
                    </p>
                    <?php include_once "eventView.html"; ?>
                    <?php include_once "schoolView.html"; ?>
                    <button data-controls-modal="modal-add-event" data-backdrop="static" data-keyboard="true" class="btn success">ADD 
                        Event&nbsp;&nbsp;</button> <button data-controls-modal="modal-edit-event" data-backdrop="static" data-keyboard="true" class="btn primary">Edit 
                        Event&nbsp;&nbsp;&nbsp;</button><br/>
                    <br/>
                    <button data-controls-modal="modal-add-school" data-backdrop="static" data-keyboard="true" class="btn success">ADD 
                        School</button> <button data-controls-modal="modal-edit-school" data-backdrop="static" data-keyboard="true" class="btn primary">Edit 
                        School&nbsp;</button>
                </div>
                <div class="span-one-third">
                    <h2>Participant</h2>
                    <p>
                        Use this page to add or remove participants
                    </p>
                    <?php include_once "participantView.html"; ?>
                    <button data-controls-modal="modal-add-participant" data-backdrop="static" data-keyboard="true" class="btn success">ADD 
                        Participant</button> <button data-controls-modal="modal-edit-participant" data-backdrop="static" data-keyboard="true" class="btn primary">Edit 
                        Participant</button>
                </div>
                <div class="span-one-third">
                    <h2>Results/Reports</h2>
                    <p>
                        Use this page to view or print reports and results
                    </p>
                    <p>
                        <a class="btn success" href="#">View Reports &raquo;</a>
                    </p>
                </div>
            </div>
            <div>
                <br /><br /><br /><br /><br /><br /><br /><br /> <!-- Add few blank lines Not a great solution!!! -->
            </div>
            <footer>
                <p>
                    &copy; Rotary Club of Cherthala
                </p>
            </footer>
        </div> <!-- /container -->

    </body>
</html>
