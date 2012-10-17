var eventsWithResult = null;

function geteventnames(element) {
    var phpFile;
    switch (element) {
        case 1:
            $('#part-items').empty();
            phpFile = "get_event_list.php";
            break;
        case 2:
        case 3:
            $('#report-eventName').empty();
            phpFile = "../get_event_list.php";
            break;
        case 4:
            $('#group_eventName').empty();
            phpFile = "get_event_list.php";
            break;
        default:
            $('#edit-part-items').empty();
            phpFile = "get_event_list.php";
            break;
    }
    $.get(phpFile, {
        term: "######getallstuff##########" //I am too lazy to do stuff properly
    },

    function (data) {
        var obj = jQuery.parseJSON(data);
        for (i = 0; i < obj.length; i++) {
            switch (element) {
                case 1:
                    $('#part-items').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                    break;
                case 2:
                    if (i === 0) {
                        $('#report-eventName').append("<option></option>");
                    }
                    $('#report-eventName').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                    break;
                case 3:
                    if (i === 0) {
                        $('#report-eventName').append("<option></option>");
                        $('#report-eventName').append("<option value='999999'>All Events</option>");
                    }
                    $('#report-eventName').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                    break;
                case 4:
                    if (i === 0) {
                        $('#group_eventName').append("<option></option>");
                    }
                    $('#group_eventName').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                    break;
                default:
                    var j = 0;
                    for (j = 0; j < element.length; j++) {
                        if (element[j] == obj[i].id) {
                            $('#edit-part-items').append(
                                $("<option selected></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                            break;
                        }
                    }
                    if (j == element.length) {
                        $('#edit-part-items').append(
                            $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                        break;
                    }
                    break;
            }
        }
        switch (element) {
            //call list updated to add the new data from ajax to the list
            case 1:
                $("#part-items").trigger("liszt:updated");
                break;
            case 2:
            case 3:
                $("#report-eventName").trigger("liszt:updated");
                break;
            case 4:
                $("#group_eventName").trigger("liszt:updated");
                break;
            default:
                $("#edit-part-items").trigger("liszt:updated");
                break;
        }
    });
}

function saveParticipant(print) {
    var participantName = $('#participantName').val();
    var DOB = $('#DOB').val();
    var SEX = $('#SEX').val();
    var partItems = $('#part-items').val();
    var partSId = $('#part-school-id').val();
    var partParentName = $('#part-parent-name').val();
    var partAddress = $('#part-address').val();
    var partMailid = $('#part-mailid').val();
    var partPhNum = $('#part-ph-num').val();
    var partFeePaid = $('#part-feePaid ').val();
    if (partSId <= 0 ||$('#edit-part-school-name').val() == null ) { //check for valid school
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please select a valid school from dropdown.");
        $('#participantAddResult').show();
    } else if (partItems == null) { //check for valid items
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please add events for the participant");
        $('#participantAddResult').show();
    } else if (DOB == "") { //check for valid DOB
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please input DOB for the participant");
        $('#participantAddResult').show();
    } else if (partFeePaid == "") { //check for valid feespaid
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please enter fee Paid.");
        $('#participantAddResult').show();
    } else {
        $.post("addParticipant.php", {
            type: "addParticipant",
            participantName: participantName,
            DOB: DOB,
            SEX: SEX,
            partItems: partItems,
            partSId: partSId,
            partParentName: partParentName,
            partAddress: partAddress,
            partMailid: partMailid,
            partPhNum: partPhNum,
            partFeePaid: partFeePaid
        },

        function (data) {
            //alert(data);
            var obj = jQuery.parseJSON(data);
            var result = obj.result;
            if (result == 1) {
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("success");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Participant added - Reg No."+obj.sid);
                $('#participantAddResult').show();
                $(".chzn-select").val('').trigger("liszt:updated");
                $('#partFields').find(':input').each(function () {
                    if (this.id != "part-school-name" && this.id != "part-school-id" && this.id != "part-items") $(this).val('');
                });
                if(print ==1){
                    alert("Remove auto fit to page option before printing");
                    var url = "report/regcardgenerate.php?sid="+obj.sid;
                    window.open(url);
                }
            } else if (result == -1) {
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("error");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Cannot register senior and junior events for same participant");
                $('#participantAddResult').show();
            }else if (result == 2) {
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("error");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Same participant already exists");
                $('#participantAddResult').show();
            } else {
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("error");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Could not add participant.");
                $('#participantAddResult').show();
            }
        });
    }
}

function getparticipantdetails() {
    eventsWithResult = null;
    $('#part-participantid').val("");
    $('#part-participantid').val($('#edit-participantid').val());
    $.post("addParticipant.php", {
        type: "getpartDetailsForEdit",
        pId: $('#edit-participantid').val()
    },

    function (data) {
        //alert(data);
        if (data == -1) {
            $('#edit-participantAddResult').removeClass('success');
            $('#edit-participantAddResult').addClass('error');
            $('#edit-participantAddResult').empty();
            $('#edit-participantAddResult').append("Not a valid registration id");
            $('#edit-participantAddResult').show();
            $('#edit-participantid').select();
            $('#edit-saveParticipantForm').hide();
            $('#edit-participantSave').hide();
            $('#edit-participantSavePrint').hide();
        } else {
            $('#edit-participantAddResult').removeClass('error');
            $('#edit-participantAddResult').addClass('success');
            $('#edit-participantAddResult').empty();
            $('#edit-participantAddResult').append("Details Retreived");
            var obj = jQuery.parseJSON(data);
            $('#edit-participantName').val(obj.student_name);
            var myarr = obj.dob.split("-");
            var mydate = myarr[2] + '/' + myarr[1] + '/' + myarr[0];
            $('#edit-DOB').val(mydate);
            $('#edit-SEX').val(obj.sex);
            geteventnames(obj.events);
            $('#edit-part-school-id').val(obj.school_id);
            $('#edit-part-school-name').val(obj.school_name);
            $('#edit-part-parent-name').val(obj.parent_name);
            $('#edit-part-address').val(obj.st_adress);
            $('#edit-part-mailid').val(obj.mail_id);
            $('#edit-part-ph-num').val(obj.phone_number);
            $('#edit-part-feePaid').val(obj.fee_paid);
            $('#edit-saveParticipantForm').show();
            $('#edit-participantAddResult').show();
            $('#edit-participantSave').show();
            $('#edit-participantSavePrint').show();
            $("#edit-DOB").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "d/mm/yy"
            });
        }
    });
}
$(document).ready(function () {
    $('#edit-part-items').chosen().change(function (evt) {
        //check if user is trying to delete any item from participants
        //registered events list for which result is declared
        //if user removes any such item, put it back and warn user.
        if (eventsWithResult == null) {
            //get all events whose results are entered for
            //which current participant has registered
            $.post("addParticipant.php", {
                type: "getEventsResultEntered",
                pId: $('#edit-participantid').val()
            }, function (data) {
                if (data != -1) {
                    var obj = jQuery.parseJSON(data);
                    eventsWithResult = new Array();
                    for (var i = 0; i < obj.evts.length; i++) {
                        eventsWithResult[i] = obj.evts[i];
                    }
                    //compare to events in current list to events for which
                    //results are announced.
                    comparePartEventList();
                }
            });
        } else {
            comparePartEventList();
        }
    });
});

function comparePartEventList() {
    //get selected events for the current participant
    var evList = $('#edit-part-items').val();
    //   check if all those elements are still selected or not
    if (evList == null) {
        alert("This item cannot be removed as result for this is already entered.");
        //add it back.
        $('#edit-part-items').find('option[value="'+ eventsWithResult[0] +'"]:not(:selected)').attr('selected','selected');
        $('.chzn-select').trigger("liszt:updated");
    } else {
        for (var i = 0; i < eventsWithResult.length; i++) {
            for (var j = 0; j < evList.length; j++) {
                if (eventsWithResult[i].localeCompare(evList[j]) == 0) break;
            }
            if (j == evList.length) {
                alert("This item cannot be removed as result for this is already entered.");
                $('#edit-part-items').find('option[value="'+ eventsWithResult[i] +'"]:not(:selected)').attr('selected','selected');
                $('.chzn-select').trigger("liszt:updated");
            }
        }
    }
}

function editParticipant(print) {
    var participantName = $('#edit-participantName').val();
    var DOB = $('#edit-DOB').val();
    var SEX = $('#edit-SEX').val();
    var partItems = $('#edit-part-items').val();
    var partSId = $('#edit-part-school-id').val();
    var partParentName = $('#edit-part-parent-name').val();
    var partAddress = $('#edit-part-address').val();
    var partMailid = $('#edit-part-mailid').val();
    var partPhNum = $('#edit-part-ph-num').val();
    var partFeePaid = $('#edit-part-feePaid ').val();
    if (partSId <= 0||$('#edit-part-school-name').val() == null) { //check for valid school
        $('#edit-participantAddResult').removeClass("success");
        $('#edit-participantAddResult').removeClass("error");
        $('#edit-participantAddResult').addClass("error");
        $('#edit-participantAddResult').empty();
        $('#edit-participantAddResult').append("Please select a valid school from dropdown.");
        $('#edit-participantAddResult').show();
    } else if (partItems == null) { //check for valid items
        $('#edit-participantAddResult').removeClass("success");
        $('#edit-participantAddResult').removeClass("error");
        $('#edit-participantAddResult').addClass("error");
        $('#edit-participantAddResult').empty();
        $('#edit-participantAddResult').append("Please add events for the participant");
        $('#edit-participantAddResult').show();
    } else if (DOB == "") { //check for valid DOB
        $('#edit-participantAddResult').removeClass("success");
        $('#edit-participantAddResult').removeClass("error");
        $('#edit-participantAddResult').addClass("error");
        $('#edit-participantAddResult').empty();
        $('#edit-participantAddResult').append("Please input DOB for the participant");
        $('#edit-participantAddResult').show();
    } else if (partFeePaid == "") { //check for valid feespaid
        $('#edit-participantAddResult').removeClass("success");
        $('#edit-participantAddResult').removeClass("error");
        $('#edit-participantAddResult').addClass("error");
        $('#edit-participantAddResult').empty();
        $('#edit-participantAddResult').append("Please enter fee Paid.");
        $('#edit-participantAddResult').show();
    } else {
        $.post("addParticipant.php", {
            type: "editParticipant",
            participantName: participantName,
            DOB: DOB,
            SEX: SEX,
            partItems: partItems,
            partSId: partSId,
            partParentName: partParentName,
            partAddress: partAddress,
            partMailid: partMailid,
            partPhNum: partPhNum,
            partFeePaid: partFeePaid,
            partId: $('#part-participantid').val()
        },

        function (data) {
            //alert(data);
            var obj = jQuery.parseJSON(data);
            var result = obj.result;
            //  alert(data+"result => "+result);
            if (result == 1) {
                $('#edit-participantAddResult').removeClass("success");
                $('#edit-participantAddResult').removeClass("error");
                $('#edit-participantAddResult').addClass("success");
                $('#edit-participantAddResult').empty();
                $('#edit-participantAddResult').append("Participant modified");
                $('#edit-participantAddResult').show();
                $(".chzn-select").val('').trigger("liszt:updated");
                //reset all fields
                $('#edit-partFields').find(':input').each(function () {
                    $(this).val('');
                });
                $('#part-participantid').val("");
                $('#edit-participantid').select();
                $('#edit-saveParticipantForm').hide();
                $('#edit-participantSave').hide();
                $('#edit-participantSavePrint').hide();
                // $('#edit-participantAddResult').hide();
                $('#part-participantid').val("");
                //check if reg card needs to be printed now.
                if(print ==1){
                    alert("Remove auto fit to page option before printing");
                    var url = "report/regcardgenerate.php?sid="+obj.sid;
                    window.open(url);
                }
            } else if (result == -1) {
                $('#edit-participantAddResult').removeClass("success");
                $('#edit-participantAddResult').removeClass("error");
                $('#edit-participantAddResult').addClass("error");
                $('#edit-participantAddResult').empty();
                $('#edit-participantAddResult').append("Cannot register senior and junior events for same participant");
                $('#edit-participantAddResult').show();
            }else if (result == 2) {
                $('#edit-participantAddResult').removeClass("success");
                $('#edit-participantAddResult').removeClass("error");
                $('#edit-participantAddResult').addClass("error");
                $('#edit-participantAddResult').empty();
                $('#edit-participantAddResult').append("Same participant already exists");
                $('#edit-participantAddResult').show();
            } else {
                //   alert(result);
                $('#edit-participantAddResult').removeClass("success");
                $('#edit-participantAddResult').removeClass("error");
                $('#edit-participantAddResult').addClass("error");
                $('#edit-participantAddResult').empty();
                $('#edit-participantAddResult').append("Could not edit participant.");
                $('#edit-participantAddResult').show();
            }
        });
    }
}

function geteventnames(element,schoolid,eventid) {
    var phpFile;
    switch (element) {
        case 1:
            $('#group_participants').empty();
            phpFile = "get_participant_list.php";
            break;
        default:
            break;
    }

    $.get(phpFile, {
        schoolid:schoolid,
        eventid:eventid
    },function (data) {
        var obj = jQuery.parseJSON(data);
        for (i = 0; i < obj.length; i++) {
            switch (element) {
                case 1:
                    if (i === 0) {
                        $('#group_participants').append("<option></option>");
                    }
                    $('#group_participants').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value + " - " + obj[i].label));
                    break;
            }
        }
        switch (element) {
            //call list updated to add the new data from ajax to the list
            case 1:
                $("#group_participants").trigger("liszt:updated");
                break;
        }

    });
}