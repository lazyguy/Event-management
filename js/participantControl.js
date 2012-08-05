

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