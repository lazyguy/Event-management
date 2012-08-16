

function geteventnames(element) {
    var phpFile;
    switch(element){
        case 1:
            $('#part-items').empty();
            phpFile="get_event_list.php";
            break;
        case 2:
            $('#report-eventName').empty();
            phpFile="../get_event_list.php";
            break;
    }
    $.get(phpFile,{
        term:"######getallstuff##########"    //I am too lazy to do stuff properly
    },
    function(data){
        var obj = jQuery.parseJSON(data);
        for(i=0;i<obj.length;i++){
            switch(element){
                case 1:
                    $('#part-items').append(
                        $("<option></option>").attr("value",obj[i].id).text(obj[i].value+" - "+obj[i].label)
                        );
                    break;
                case 2:
                    if(i===0){
                        $('#report-eventName').append("<option></option>");
                    }
                    $('#report-eventName').append(
                        $("<option></option>").attr("value",obj[i].id).text(obj[i].value+" - "+obj[i].label)
                        );
                    break;
            }
        }
        switch(element){
            //call list updated to add the new data from ajax to the list
            case 1:
                $("#part-items").trigger("liszt:updated");
                break;
            case 2:
                $("#report-eventName").trigger("liszt:updated");
                break;
        }
    });
}

function saveParticipant(print){
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
            // alert(data);
            if(data==1){
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("success");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Participant added");
                $('#participantAddResult').show();
                $(".chzn-select").val('').trigger("liszt:updated");
                $('#partFields').find(':input').each(function() {
                    if(this.id != "part-school-name" && this.id != "part-school-id" && this.id !="part-items")
                        $(this).val('');
                });
            }else if(data==2){
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("error");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("Same participant already exists");
                $('#participantAddResult').show();
            }else{
                $('#participantAddResult').removeClass("success");
                $('#participantAddResult').removeClass("error");
                $('#participantAddResult').addClass("error");
                $('#participantAddResult').empty();
                $('#participantAddResult').append("An Error occured while adding participant.");
                $('#participantAddResult').show();
            }
        });
    }
}