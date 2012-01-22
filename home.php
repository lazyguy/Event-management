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
                $('#schoolCancel').click(function(){  $('#modal-add-school').modal('hide')});
                $('#modal-add-school').bind('shown', function(){$('#schoolName').select();});
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
                    <div id="modal-add-event" class="modal hide fade">
                        <div class="modal-header">
                            <a href="#" class="close">&times;</a> <h3>Add Event</h3>
                        </div>
                        <div class="modal-body">
                            <form id="saveEventForm">
                                <fieldset>
                                    <div class="clearfix">
                                        <label for="eventName">
                                            Event Name
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="eventName" name="eventName" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->
                                    <div class="clearfix">
                                        <label for="eventType">
                                            Event Type
                                        </label>
                                        <div class="input">
                                            <select class="xlarge" name="eventType" id="eventType">
                                                <option>Junior</option> <option>Senior</option> <option>Junior and Senior</option>
                                            </select>
                                        </div>
                                    </div><!-- /clearfix -->
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div  id="eventResult" class="alert-message error">
                            </div>
                            <a id="eventCancel" class="btn secondary">Cancel</a> <a id="eventSave" class="btn primary">Save</a>
                        </div>

                    </div>
                    <div id="modal-edit-event" class="modal hide fade">
                        <div class="modal-header">
                            <a href="#" class="close">&times;</a> <h3>Edit Event</h3>
                        </div>
                        <div class="modal-body">
                            <form id="editEventForm">
                                <fieldset>
                                    <div class="clearfix">
                                        <label for="eventNameEdit">
                                            Event Name
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="eventNameEdit" name="eventNameEdit" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->
                                </fieldset>
                            </form>

                            <table id="eventsTable" class="bordered-table condensed-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            <div class="pagination" id="eventEditPages">
                                <ul>
                                    <li id="eventPrevPage" class="prev">
                                        <a>&larr; Previous&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                    </li>
                                    <li class="disabled">
                                        <a> <span id="curPage"></span>/<span id ="totalPages"></span></a>
                                    </li>
                                    <li id="eventNextPage" class="next">
                                        <a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Next 
                                            &rarr;</a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <div  id="eventEditResult" class="alert-message error">
                            </div>
                            <a id="eventEditCancel" class="btn secondary">Cancel</a>
                            <!--                            <a id="eventEditSave" class="btn primary">Save&nbsp;&nbsp;</a>
                                                                                    <a id="eventEditDelete" class="btn danger">Delete</a>                            -->
                        </div>

                    </div>
                    <div id="modal-add-school" class="modal hide fade">
                        <div class="modal-header">
                            <a href="#" class="close">&times;</a> <h3>Add School</h3>
                        </div>
                        <div class="modal-body">
                            <form id="saveSchoolForm">
                                <fieldset>
                                    <div class="clearfix">
                                        <label for="schoolName">
                                            School Name
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="schoolName" name="schoolName" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->

                                    <div class="clearfix">
                                        <label for="schoolAddress">
                                            Address
                                        </label>
                                        <div class="input">
                                            <textarea class="xlarge" id="schoolAddress" name="schoolAddress" rows="4" ></textarea>
                                        </div>
                                    </div><!-- /clearfix -->

                                    <div class="clearfix">
                                        <label for="principalName">
                                            Name of Principal
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="principalName" name="principalName" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->

                                    <div class="clearfix">
                                        <label for="phoneNumber">
                                            Phone Number
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="phoneNumber" name="phoneNumber" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->

                                    <div class="clearfix">
                                        <label for="emailId">
                                            Email ID
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="emailId" name="emailId" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <a id="schoolCancel" class="btn secondary">Cancel</a> <a id="schoolSave" class="btn primary">Save</a>
                        </div>
                    </div>
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
                    <div id="modal-add-participant" class="modal hide fade">
                        <div class="modal-header">
                            <a href="#" class="close">&times;</a> <h3>Add participant</h3>
                        </div>
                        <div class="modal-body">
                            <form id="saveParticipantForm">
                                <fieldset>
                                    <div class="clearfix">
                                        <label for="participantName">
                                            Name
                                        </label>
                                        <div class="input">
                                            <input class="xlarge" id="participantName" name="participantName" size="30" type="text" />
                                        </div>
                                    </div><!-- /clearfix -->
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <a id="participantCancel" class="btn secondary">Cancel</a> <a id="participantSave" class="btn primary">Save</a>

                        </div>
                    </div>
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
        <div id="modal-editSave-event" class="modal hide fade">
            <div class="modal-header">
                <a href="#" class="close">&times;</a> <h3>Edit Event</h3>
            </div>
            <div class="modal-body">
                <form id="eidtEventSaveForm">
                    <fieldset>
                        <div class="clearfix">
                            <label for="eventSaveName">
                                Event Name
                            </label>
                            <div class="input">
                                <input class="xlarge" id="eventSaveName" name="eventSaveName" size="30" type="text" />
                            </div>
                        </div><!-- /clearfix -->
                        <div class="clearfix">
                            <label for="eventSaveType">
                                Event Type
                            </label>
                            <div class="input">
                                <select class="xlarge" name="eventSaveType" id="eventSaveType">
                                    <option value="1">Junior</option> <option value="2">Senior</option> <option value="3">Junior and Senior</option>
                                </select>
                            </div>
                        </div><!-- /clearfix -->
                        <input type="hidden" id="eventIdForEdit" value="NULL">                        
                    </fieldset>

                </form>
            </div>
            <div class="modal-footer">
                <div  id="eventEditSaveResult" class="alert-message success">
                </div>
                <a id="eventSaveCancel" class="btn secondary">Cancel</a> <a id="eventEditSave" class="btn primary">Save</a>
            </div>

        </div>
    </body>
</html>
