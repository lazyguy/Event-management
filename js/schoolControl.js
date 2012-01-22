var resultPerPage = 5;

function saveSchool(e) {
    e.preventDefault();
    var ename = $('#schoolName').val();
    var etype = $('#schoolType').val();
    if (ename.length <=0) {
        $('#schoolResult').empty();
        $('#schoolResult').removeClass('success').addClass('error');
        $('#schoolResult').append("School Name cannot be empty");
        $('#schoolResult').show();
        $('#schoolName').html("");
        $('#schoolName').select();
    } else {
        $.post("insertdata.php",{
            type:"addSchool",
            eName:ename,
            etype:etype
        },
        function(data){
            $('#schoolResult').empty();
            if(data == 1){  //return 1 = school added succesfully
                $('#schoolResult').append(ename +" Added");
                $('#schoolNameEdit').empty();
                $('#schoolResult').removeClass('error').addClass('success');
                $('#schoolResult').show();
                $('#schoolName').select();
            }else if(data == 2){    //return 2 = school exists
                $('#schoolResult').append("School already exists");
                $('#schoolResult').removeClass('success').addClass('error');
                $('#schoolResult').show();
                $('#schoolName').text(null);
                $('#schoolName').select();
            }else{
                $('#schoolResult').append("ERROR:Cannot add school");
                $('#schoolResult').removeClass('success').addClass('error');
                $('#schoolResult').show();
                $('#schoolName').text(null);
                $('#schoolName').select();
            }
        });
    }
}
function getAction(e) {
    var event_element=e.target? e.target : e.srcElement;
    if (e && event_element.className == "deleteimage")
        deleteSchool(e);
    else if (e && event_element.className == "editimage")
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
    $.post("insertdata.php",{
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
        $.post("insertdata.php",{
            type:"getSchool",
            eName:schoolName,
            searchType:searchType
        },
        function(data){
            $('#schoolsTable > tbody:last').empty();
            var obj = jQuery.parseJSON(data);
            var count = obj[0].totalcount;
            var numofPages = Math.ceil(count/resultPerPage);
            $('#totalPages').html(numofPages);
            $('#schoolEditResult').hide();
            $('#curSchoolPage').html('');
            $('#curSchoolPage').html('1');
            setpageEnableDisable(1,numofPages);
            if(count >=1){
                $('#schoolsTable').show();
                if(parseInt(numofPages)>1){
                    $('#schoolEditPages').show();                 
                    $('#schoolNextPage').addClass('editimage');
                    $('#schoolPrevPage').addClass('disabled');
                    $('#schoolPrevPage').removeClass('editimage');
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
                var eType;
                switch(item.school_type){
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
                $('#schoolsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                    '</td><td>'+eType+'</td><td><img class="editimage" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                    '&nbsp;&nbsp;&nbsp;<img class="deleteimage" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
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
    var totalCount = $('#totalPages').html();
    if(e=="Next")
        type = "NextSchoolPage";
    else if (e=="Prev")
        type = "PrevSchoolPage"
    currentPage = $('#curSchoolPage').html();
    if(parseInt(currentPage)== 1 && e=="Prev")
        return;
    if(parseInt(currentPage)== parseInt(totalCount) && e=="Next")
        return;
    $.post("insertdata.php",{
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
        setpageEnableDisable(currentPage,numofPages);
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
            var eType;
            switch(item.school_type){
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
            $('#schoolsTable > tbody:last').append('<tr id="'+item.id+'"><td>'+item.id+'</td><td>'+item.value+
                '</td><td>'+eType+'</td><td><img class="editimage" name="'+item.id+'" src="images/edit.png" title="Edit" alt="Edit"></img>'+
                '&nbsp;&nbsp;&nbsp;<img class="deleteimage" name="'+item.id+'"src="images/delete.png" title="Delete" alt="Del"></img></td></tr>');
            $("table#schoolsTable").trigger("update");
        });
    });
}
                            
function setpageEnableDisable(currentPage,numofPages){
    if(numofPages == parseInt(currentPage)){
        $('#schoolNextPage').addClass('disabled');
        $('#schoolNextPage').removeClass('editimage');
    }
    else {
        $('#schoolNextPage').removeClass('disabled');
        $('#schoolNextPage').addClass('editimage');
    }
    if(parseInt(currentPage) <= 1){
        $('#schoolPrevPage').addClass('disabled');
        $('#schoolPrevPage').removeClass('editimage');                                    
    }
    else{
        $('#schoolPrevPage').removeClass('disabled');
        $('#schoolPrevPage').addClass('editimage');
    }
}

function editSchool(e) {
    var school_element=e.target? e.target : e.srcElement;
    var eid =school_element.name;
    $.post("insertdata.php",{
        type:"getSchoolbyId",
        eid:eid
    },
    function(data){
        var obj = jQuery.parseJSON(data);
        var eName = obj[0].value;
        var eType = obj[0].school_type;
        //  $('#schoolSaveName').html('');
        //  $('#schoolSaveName').html(eName);
        $('#schoolSaveName').val(eName);
        $('#schoolSaveType').val(eType);
        $('#schoolIdForEdit').val(eid);
        
        $('modal-editSave-school').modal({
            backdrop: true, 
            keyboard:true
        });
        
        $('#modal-editSave-school').modal('show');
    });
}

function editSchoolSave(e) {
    var eid =  $('#schoolIdForEdit').val();
    var eType = $('#schoolSaveType').val();
    var eName = $('#schoolSaveName').val();
    $.post("insertdata.php",{
        type:"schoolModify",
        eid:eid,
        eType:eType,
        eName:eName
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
            $('#modal-editSave-school').modal('hide');
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