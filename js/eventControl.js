var resultPerPage = 5;

function saveEvent(e) {
    e.preventDefault();
    var ename = $('#eventName').val();
    var etype = $('#eventType').val();
    if (ename.length <=0) {
        $('#eventResult').empty();
        $('#eventResult').removeClass('success').addClass('error');
        $('#eventResult').append("Event Name cannot be empty");
        $('#eventResult').show();
        $('#eventName').html("");
        $('#eventName').select();
    } else {
        $.post("insertdata.php",{
            type:"addEvent",
            eName:ename,
            etype:etype
        },
        function(data){
            $('#eventResult').empty();
            if(data == 1){  //return 1 = event added succesfully
                $('#eventResult').append(ename +" Added");
                $('#eventNameEdit').empty();
                $('#eventResult').removeClass('error').addClass('success');
                $('#eventResult').show();
                $('#eventName').select();
            }else if(data == 2){    //return 2 = event exists
                $('#eventResult').append("Event already exists");
                $('#eventResult').removeClass('success').addClass('error');
                $('#eventResult').show();
                $('#eventName').text(null);
                $('#eventName').select();
            }else{
                $('#eventResult').append("ERROR:Cannot add event");
                $('#eventResult').removeClass('success').addClass('error');
                $('#eventResult').show();
                $('#eventName').text(null);
                $('#eventName').select();
            }
        });
    }
}
function getAction(e) {
    var event_element=e.target? e.target : e.srcElement;
    if (e && event_element.className == "deleteimage")
        deleteEvent(e);
    else if (e && event_element.className == "editimage")
        editEvent(e);
    else
        return;
}
function deleteEvent(e) {
    var event_element=e.target? e.target : e.srcElement;
    var eid = event_element.name;
    var del= confirm("Do you really want to delete the event?");
    if (del != true) {
        return;
    }
    $.post("insertdata.php",{
        type:"deleteEvent",
        eid:eid
    },
    function(data){
        $('#eventEditResult').empty();
        if(data == 1){  //return 1 = event added succesfully
            $('#eventEditResult').append("Event Deleted");
            $('#eventEditResult').removeClass('warning error').addClass('success');
            $('#eventEditResult').show();
            $('#eventsTable').hide();
            $('#eventEditPages').hide();
            $('#eventNameEdit').select();
        }else if(data == 2){
            $('#eventEditResult').append("Event Not Present");
            $('#eventEditResult').removeClass('error success').addClass('warning');
            $('#eventEditResult').show();
            $('#eventNameEdit').html("");
            $('#eventNameEdit').select();
        }else{
            alert(data);
            $('#eventEditResult').append("ERROR:Cannot delete event");
            $('#eventEditResult').removeClass('warning success').addClass('error');
            $('#eventEditResult').show();
            $('#eventNameEdit').html("");
            $('#eventNameEdit').select();
        }
    });
}
function getEvents(e) {
    if (e == null) {
        eventName = $('#eventNameEdit').val();
        searchType = "match";
    } else {
        eventName = e;
        searchType = "exact";
    }
    if (eventName.length >0) {
        $.post("insertdata.php",{
            type:"getEvent",
            eName:eventName,
            searchType:searchType
        },
        function(data){
            $('#eventsTable > tbody:last').empty();
            var obj = jQuery.parseJSON(data);
            var count = obj[0].totalcount;
            var numofPages = Math.ceil(count/resultPerPage);
            $('#totalPages').html(numofPages);
            $('#eventEditResult').hide();
            $('#curPage').html('');
            $('#curPage').html('1');
            setpageEnableDisable(1,numofPages);
            if(count >=1){
                $('#eventsTable').show();
                if(parseInt(numofPages)>1){
                    $('#eventEditPages').show();                 
                    $('#eventNextPage').addClass('editimage');
                    $('#eventPrevPage').addClass('disabled');
                    $('#eventPrevPage').removeClass('editimage');
                }
            }
            else{
                $('#eventEditResult').empty();
                $('#eventsTable').hide();
                $('#eventEditResult').append("No results for search term");
                $('#eventEditResult').removeClass('error success').addClass('warning');
                $('#eventEditResult').show();
                $('#eventNameEdit').html("");
                $('#eventNameEdit').select();
            }                            
            $.each(obj, function(index){
                var item = obj[index+1];
                var eType;
                switch(item.event_type){
                    case "1":
                        eType = "Junior";
                        break;
                    case "2":
                        eType = "Senior";
                        break;
                    case "3":
                        eType = "Junior/Senior";
                        break;
                }
                $('#eventsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                    '</td><td>'+eType+'</td><td><img class="editimage" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                    '&nbsp;&nbsp;&nbsp;<img class="deleteimage" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
                $("table#eventsTable").trigger("update");
            });
        });
    }
}
function getEventPrevPage(e){
    getEventPage("Prev");
}
function getEventNextPage(e){
    getEventPage("Next");
}
                    
function getEventPage(e){
    var type;
    var currentPage;
    var totalCount = $('#totalPages').html();
    if(e=="Next")
        type = "NextEventPage";
    else if (e=="Prev")
        type = "PrevEventPage"
    currentPage = $('#curPage').html();
    if(parseInt(currentPage)== 1 && e=="Prev")
        return;
    if(parseInt(currentPage)== parseInt(totalCount) && e=="Next")
        return;
    $.post("insertdata.php",{
        type:type,
        eName:eventName,
        searchType:searchType,
        currentPage:currentPage
    },
    function(data){
                            
        $('#eventsTable > tbody:last').empty();
        var obj = jQuery.parseJSON(data);
        var count = obj[0].totalcount;
        var numofPages = Math.ceil(count/resultPerPage);
        //                            $('#totalPages').html(numofPages);
        $('#eventEditResult').hide();
        if(e=="Next"){
            currentPage = parseInt(currentPage) + 1;
                        
        }
        else if (e=="Prev"){
            currentPage = parseInt(currentPage) -1;
        }
        $('#curPage').html('');
        $('#curPage').html(currentPage);
        //Enable disable prev/next according to current page
        setpageEnableDisable(currentPage,numofPages);
        if(count >=1){
            $('#eventsTable').show();
        }
        else{
            $('#eventEditResult').empty();
            $('#eventsTable').hide();
            $('#eventEditResult').append("No results for search term");
            $('#eventEditResult').removeClass('error success').addClass('warning');
            $('#eventEditResult').show();
            $('#eventNameEdit').html("");
            $('#eventNameEdit').select();
        }                            
        $.each(obj, function(index){
            var item = obj[index+1];
            var eType;
            switch(item.event_type){
                case "1":
                    eType = "Junior";
                    break;
                case "2":
                    eType = "Senior";
                    break;
                case "3":
                    eType = "Junior/Senior";
                    break;
            }
            $('#eventsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                '</td><td>'+eType+'</td><td><img class="editimage" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                '&nbsp;&nbsp;&nbsp;<img class="deleteimage" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
            $("table#eventsTable").trigger("update");
        });
    });
}
                            
function setpageEnableDisable(currentPage,numofPages){
    if(numofPages == parseInt(currentPage)){
        $('#eventNextPage').addClass('disabled');
        $('#eventNextPage').removeClass('editimage');
    }
    else {
        $('#eventNextPage').removeClass('disabled');
        $('#eventNextPage').addClass('editimage');
    }
    if(parseInt(currentPage) <= 1){
        $('#eventPrevPage').addClass('disabled');
        $('#eventPrevPage').removeClass('editimage');                                    
    }
    else{
        $('#eventPrevPage').removeClass('disabled');
        $('#eventPrevPage').addClass('editimage');
    }
}

function editEvent(e) {
    var event_element=e.target? e.target : e.srcElement;
    var eid =event_element.name;
    $.post("insertdata.php",{
        type:"getEventbyId",
        eid:eid
    },
    function(data){
        var obj = jQuery.parseJSON(data);
        var eName = obj[0].value;
        var eType = obj[0].event_type;
        //  $('#eventSaveName').html('');
        //  $('#eventSaveName').html(eName);
        $('#eventSaveName').val(eName);
        $('#eventSaveType').val(eType);
        $('#eventIdForEdit').val(eid);
        
        $('modal-editSave-event').modal({
            backdrop: true, 
            keyboard:true
        });
        
        $('#modal-editSave-event').modal('show');
    });
}

function editEventSave(e) {
    var eid =  $('#eventIdForEdit').val();
    var eType = $('#eventSaveType').val();
    var eName = $('#eventSaveName').val();
    $.post("insertdata.php",{
        type:"eventModify",
        eid:eid,
        eType:eType,
        eName:eName
    },
    function(data){
        // alert(data);
        if(data == 1){  //return 1 = event added succesfully
            $('#eventEditResult').append("Event Modified Succesfully");
            $('#eventEditResult').removeClass('warning error').addClass('success');
            $('#eventEditResult').show();
            $('#eventsTable').hide();
            $('#eventEditPages').hide();
            $('#eventNameEdit').select();
            $('#modal-editSave-event').modal('hide');
        }else if(data == 2){
            $('#eventEditSaveResult').empty();
            $('#eventEditSaveResult').append("Same event already exists");
            $('#eventEditSaveResult').removeClass('error success').addClass('warning');
            $('#eventEditSaveResult').show();
        }else{
            $('#eventEditSaveResult').empty();
            $('#eventEditSaveResult').append("Could not edit event");
            $('#eventEditSaveResult').removeClass('error success').addClass('warning');
            $('#eventEditSaveResult').show();
        }
    });
}