allUsers = new Array();
function storeUsers(users)
{
    for(var i=0; i<users.length; i++) allUsers.push(users[i]);
}
function updateInfos()
{
    $("#msgerror").remove();
    var user = $(":selected").val() 
    var found = false;
    $("#debandiv").hide()
    $("#reasondiv").hide()
    $("#bandiv").show()
    for(var i=0; i<allUsers.length; i++)
    {
        if(user == allUsers[i][0])
        {
            $("#id").val(allUsers[i][0])
            $("#username").val(allUsers[i][1])
            if(allUsers[i][2] == "1"){
                $("#debandiv").show()
                $("#bandiv").hide()   
                $("#deban").attr("required",true)
                $("#ban").attr("required",false)
                $("#reason").attr("required",false)
            }else{
                $("#reasondiv").show()
                $("#ban").attr("required",true)
                $("#reason").attr("required",true)
                $("#deban").attr("required",false)
            }
            found = true;
            break;
        }
    }
    if(found) $("#userInformations").show();
}