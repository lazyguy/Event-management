var resultPerPage = 5;

function saveSchool(e) {
    e.preventDefault();
    var ename = $('#schoolName').val();
    var schoolAddress = $('#schoolAddress').val();
    var emailId = $('#emailId').val();
    var phoneNumber = $('#phoneNumber').val();
    var principalName = $('#principalName').val();
    if (ename.length <=0) {
        $('#schoolAddResult').empty();
        $('#schoolAddResult').removeClass('success').addClass('error');
        $('#schoolAddResult').append("School Name cannot be empty");
        $('#schoolAddResult').show();
        $('#schoolName').html("");
        $('#schoolName').select();
    }
    else {
        $.post("addSchool.php",{
            type:"addSchool",
            eName:ename,
            schoolAddress:schoolAddress,
            emailId:emailId,
            phoneNumber:phoneNumber,
            principalName:principalName
        },
        function(data){
            $('#schoolAddResult').empty();
            if(data == 1){  //return 1 = school added succesfully
                $('#schoolAddResult').append(ename +" Added");
                $('#schoolNameEdit').empty();
                $('#schoolAddResult').removeClass('error').addClass('success');
                $('#schoolAddResult').show();
                $('#schoolName').select();
            }else if(data == 2){    //return 2 = school exists
                $('#schoolAddResult').append("School already exists");
                $('#schoolAddResult').removeClass('success').addClass('error');
                $('#schoolAddResult').show();
                $('#schoolName').text(null);
                $('#schoolName').select();
            }else{
                $('#schoolAddResult').append("ERROR:Cannot add school");
                $('#schoolAddResult').removeClass('success').addClass('error');
                $('#schoolAddResult').show();
                $('#schoolName').text(null);
                $('#schoolName').select();
            }
        });
    }
}
function getSchoolAction(e) {
    var event_element=e.target? e.target : e.srcElement;
    if (e && event_element.className == "deleteSchoolimage deleteimageptr")
        deleteSchool(e);
    else if (e && event_element.className == "editSchoolimage editimageptr")
        editSchool(e);
    else
        return;
}
function deleteSchool(e) {
    var event_element=e.target? e.target : e.srcElement;
    var eid = event_element.name;
    var del= confirm("Do you really want to delete the school?");
    if (del != true) {
        return;
    }
    $.post("addSchool.php",{
        type:"deleteSchool",
        eid:eid
    },
    function(data){
        $('#schoolEditResult').empty();
        if(data == 1){  //return 1 = school added succesfully
            $('#schoolEditResult').append("School Deleted");
            $('#schoolEditResult').removeClass('warning error').addClass('success');
            $('#schoolEditResult').show();
            $('#schoolsTable').hide();
            $('#schoolEditPages').hide();
            $('#schoolNameEdit').select();
        }else if(data == 2){
            $('#schoolEditResult').append("School Not Present");
            $('#schoolEditResult').removeClass('error success').addClass('warning');
            $('#schoolEditResult').show();
            $('#schoolNameEdit').html("");
            $('#schoolNameEdit').select();
        }else{
            alert(data);
            $('#schoolEditResult').append("ERROR:Cannot delete school");
            $('#schoolEditResult').removeClass('warning success').addClass('error');
            $('#schoolEditResult').show();
            $('#schoolNameEdit').html("");
            $('#schoolNameEdit').select();
        }
    });
}
function getSchools(e) {
    if (e == null) {
        schoolName = $('#schoolNameEdit').val();
        searchType = "match";
    } else {
        schoolName = e;
        searchType = "exact";
    }
    if (schoolName.length >0) {
        $.post("addSchool.php",{
            type:"getSchool",
            eName:schoolName,
            searchType:searchType
        },
        function(data){
            $('#schoolsTable > tbody:last').empty();
            var obj = jQuery.parseJSON(data);
            var count = obj[0].totalcount;
            var numofPages = Math.ceil(count/resultPerPage);
            $('#totalSchoolPages').html(numofPages);
            $('#schoolEditResult').hide();
            $('#curSchoolPage').html('');
            $('#curSchoolPage').html('1');
            setpageSchoolEnableDisable(1,numofPages);
            if(count >=1){
                $('#schoolsTable').show();
                if(parseInt(numofPages)>1){
                    $('#schoolEditPages').show();
                    $('#schoolNextPage').addClass('editimageptr');
                    $('#schoolPrevPage').addClass('disabled');
                    $('#schoolPrevPage').removeClass('editimageptr');
                }
            }
            else{
                $('#schoolEditResult').empty();
                $('#schoolsTable').hide();
                $('#schoolEditResult').append("No results for search term");
                $('#schoolEditResult').removeClass('error success').addClass('warning');
                $('#schoolEditResult').show();
                $('#schoolNameEdit').html("");
                $('#schoolNameEdit').select();
            }
            $.each(obj, function(index){
                var item = obj[index+1];
                $('#schoolsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                    '</td><td>'+item.event_type+'</td><td><img class="editSchoolimage editimageptr" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                    '&nbsp;&nbsp;&nbsp;<img class="deleteSchoolimage deleteimageptr" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
                $("table#schoolsTable").trigger("update");
            });
        });
    }
}
function getSchoolPrevPage(e){
    getSchoolPage("Prev");
}
function getSchoolNextPage(e){
    getSchoolPage("Next");
}

function getSchoolPage(e){
    var type;
    var currentPage;
    var totalCount = $('#totalSchoolPages').html();
    if(e=="Next")
        type = "NextSchoolPage";
    else if (e=="Prev")
        type = "PrevSchoolPage"
    currentPage = $('#curSchoolPage').html();
    if(parseInt(currentPage)== 1 && e=="Prev")
        return;
    if(parseInt(currentPage)== parseInt(totalCount) && e=="Next")
        return;
    $.post("addSchool.php",{
        type:type,
        eName:schoolName,
        searchType:searchType,
        currentPage:currentPage
    },
    function(data){

        $('#schoolsTable > tbody:last').empty();
        var obj = jQuery.parseJSON(data);
        var count = obj[0].totalcount;
        var numofPages = Math.ceil(count/resultPerPage);
        //                            $('#totalPages').html(numofPages);
        $('#schoolEditResult').hide();
        if(e=="Next"){
            currentPage = parseInt(currentPage) + 1;

        }
        else if (e=="Prev"){
            currentPage = parseInt(currentPage) -1;
        }
        $('#curSchoolPage').html('');
        $('#curSchoolPage').html(currentPage);
        //Enable disable prev/next according to current page
        setpageSchoolEnableDisable(currentPage,numofPages);
        if(count >=1){
            $('#schoolsTable').show();
        }
        else{
            $('#schoolEditResult').empty();
            $('#schoolsTable').hide();
            $('#schoolEditResult').append("No results for search term");
            $('#schoolEditResult').removeClass('error success').addClass('warning');
            $('#schoolEditResult').show();
            $('#schoolNameEdit').html("");
            $('#schoolNameEdit').select();
        }
        $.each(obj, function(index){
            var item = obj[index+1];
            $('#schoolsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                '</td><td>'+item.event_type+'</td><td><img class="editSchoolimage editimageptr" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                '&nbsp;&nbsp;&nbsp;<img class="deleteSchoolimage deleteimageptr" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
            $("table#schoolsTable").trigger("update");
        });
    });
}

function setpageSchoolEnableDisable(currentPage,numofPages){
    if(numofPages == parseInt(currentPage)){
        $('#schoolNextPage').addClass('disabled');
        $('#schoolNextPage').removeClass('editimageptr');
    }
    else {
        $('#schoolNextPage').removeClass('disabled');
        $('#schoolNextPage').addClass('editimageptr');
    }
    if(parseInt(currentPage) <= 1){
        $('#schoolPrevPage').addClass('disabled');
        $('#schoolPrevPage').removeClass('editimageptr');
    }
    else{
        $('#schoolPrevPage').removeClass('disabled');
        $('#schoolPrevPage').addClass('editimageptr');
    }
}

function editSchool(e) {
    var school_element=e.target? e.target : e.srcElement;
    var eid =school_element.name;
    $.post("addSchool.php",{
        type:"getSchoolbyId",
        eid:eid
    },
    function(data){
        var obj = jQuery.parseJSON(data);
        var eName = obj[0].value;
        var sAddress = obj[0].address;
        var pName = obj[0].pname;
        var pNum = obj[0].pnum;
        var pMail = obj[0].mailid;
        $('#schoolNameEditsave').val(eName);
        $('#schoolIdForEdit').val(eid);
        $('#schoolAddressEdit').val(sAddress);
        $('#principalNameEdit').val(pName);
        $('#phoneNumberEdit').val(pNum);
        $('#emailIdEdit').val(pMail);

        $('#modal-edit-save-school').modal({
            backdrop: false,
            keyboard:true
        });

        $('#modal-edit-save-school').modal('show');
    });
}

function editSchoolSave(e) {
    var eid =  $('#schoolIdForEdit').val();
    var eName = $('#schoolNameEditsave').val();
    var eAddress = $('#schoolAddressEdit').val();
    var ePrincipal = $('#principalNameEdit').val();
    var ePhone = $('#phoneNumberEdit').val();
    var eEmail = $('#emailIdEdit').val();
    $.post("addSchool.php",{
        type:"schoolModify",
        eid:eid,
        eName:eName,
        eAddress:eAddress,
        ePrincipal:ePrincipal,
        ePhone:ePhone,
        eEmail:eEmail
    },
    function(data){
        // alert(data);
        if(data == 1){  //return 1 = school added succesfully
            $('#schoolEditResult').append("School Modified Succesfully");
            $('#schoolEditResult').removeClass('warning error').addClass('success');
            $('#schoolEditResult').show();
            $('#schoolsTable').hide();
            $('#schoolEditPages').hide();
            $('#schoolNameEdit').select();
            $('#modal-edit-save-school').modal('hide')
        }else if(data == 2){
            $('#schoolEditSaveResult').empty();
            $('#schoolEditSaveResult').append("Same school already exists");
            $('#schoolEditSaveResult').removeClass('error success').addClass('warning');
            $('#schoolEditSaveResult').show();
        }else{
            $('#schoolEditSaveResult').empty();
            $('#schoolEditSaveResult').append("Could not edit school");
            $('#schoolEditSaveResult').removeClass('error success').addClass('warning');
            $('#schoolEditSaveResult').show();
        }
    });
}

function getschoolnames(element) {

    var phpFile;
    switch (element) {
        case 0:
            $('#report-schoolName').empty();
            phpFile = "../get_school_list.php";
            break;
        case 1:
            $('#group_schoolName').empty();
            phpFile = "get_school_list.php";
            break;
        default:
            break;
    }

    $.get(phpFile, {
        term: "######getallstuff##########" //I am too lazy to do stuff properly
    },function (data) {
        var obj = jQuery.parseJSON(data);
        for (i = 0; i < obj.length; i++) {
            switch (element) {
                case 0:
                    if (i == 0) {
                        $('#report-schoolName').append("<option></option>");
                        $('#report-schoolName').append("<option value='999999'>All Schools</option>");
                    }
                    $('#report-schoolName').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value));
                    break;
                case 1:
                    if (i == 0) {
                        $('#group_schoolName').append("<option></option>");
                    }
                    $('#group_schoolName').append(
                        $("<option></option>").attr("value", obj[i].id).text(obj[i].value));
                    break;
                default:

                    break;
            }
        }
        switch (element) {
            //call list updated to add the new data from ajax to the list
            case 0:
                $("#report-schoolName").trigger("liszt:updated");
                break;
            case 1:
                $("#group_schoolName").trigger("liszt:updated");
                break;
            default:
                break;
        }
    });

}