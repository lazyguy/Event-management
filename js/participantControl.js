

function geteventnames() {
    $('#part-items').empty();
    $.get("get_event_list.php",{
        term:"######getallstuff##########"    //I am too lazy to do stuff properly
    },
    function(data){
        var obj = jQuery.parseJSON(data);
        for(i=0;i<obj.length;i++){
            $('#part-items').append(
                $("<option></option>").attr("value",obj[i].id).text(obj[i].value+" - "+obj[i].label)
                ); 
        }
        //call list updated to add the new data from ajax to the list
        $("#part-items").trigger("liszt:updated");
    });
}

function saveParticipant(print){
    var participantName = $('#participantName').val();
    var DOB = $('#DOB').val();
    var SEX = $('#SEX').val();
    var partItems = $('#part-items').val();
    var partSId = $('#part-school-id').val();
    var partParentName = $('#part-parent-nam').val();
    var partAddress = $('#part-address').val();
    var partMailid = $('#part-mailid').val();
    var partPhNum = $('#part-ph-num').val();
    var partFeePaid = $('#part-feePaid ').val();
    if(partSId <=0){        //check for valid school
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please select a valid school from dropdown.");
        $('#participantAddResult').show();
    }else if(partItems == null){ //check for valid items
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please add events for the participant");
        $('#participantAddResult').show();
    }else if(DOB == ""){ //check for valid DOB
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please input DOB for the participant");
        $('#participantAddResult').show();
    }else if(partFeePaid == ""){ //check for valid feespaid
        $('#participantAddResult').removeClass("success");
        $('#participantAddResult').removeClass("error");
        $('#participantAddResult').addClass("error");
        $('#participantAddResult').empty();
        $('#participantAddResult').append("Please enter fee Paid.");
        $('#participantAddResult').show();
    }else {
        $.post("addParticipant.php",{
            type:"addParticipant",
            participantName:participantName,
            DOB:DOB,
            SEX:SEX,
            partItems:partItems,
            partSId:partSId,
            partParentName:partParentName,
            partAddress:partAddress,
            partMailid:partMailid,
            partPhNum:partPhNum,
            partFeePaid:partFeePaid
        },
        function(data){
            
            });
    }
}