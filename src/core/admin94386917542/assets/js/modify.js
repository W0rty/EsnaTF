allUsers = new Array();
exosC = new Array();
exosJava = new Array();
exosPython = new Array();

function storeUsers(users)
{
    for(var i=0; i<users.length; i++) allUsers.push(users[i]);
}

function updateInfos()
{
    var user = $(":selected").val() 
    var found = false;
    for(var i=0; i<allUsers.length; i++)
    {
        if(user == allUsers[i][0])
        {
            $("#id").val(allUsers[i][0]);
            $("#username").val(allUsers[i][1]);
            found = true;
            break;
        }
    }
    if(found) $("#userInformations").show();
}

function storeExosC(exos)
{
    for(var i=0; i<exos.length; i++) exosC.push(exos[i])
}

function storeExosPython(exos)
{
    for(var i=0; i<exos.length; i++) exosPython.push(exos[i])
}

function storeExosJava(exos)
{
    for(var i=0; i<exos.length; i++) exosJava.push(exos[i])
}

function downloadExos()
{
    $("#msg").remove();
    $("#msgconfirm").remove();
    $("#msgerror").remove();
    var exo = $(":selected").val()
    var lang = ""
    var found = false;
    if(exo.toLowerCase().includes("c"))
    {
        for(var i=0; i<exosC.length; i++)
        {
            if(exo == exosC[i][4])
            {
                var id = exosC[i][0]
                found = true;
                lang = "exercice_c";
                break
            }
        }
    }else if(exo.toLowerCase().includes("python"))
    {
        for(var i=0; i<exosPython.length; i++)
        {
            if(exo == exosPython[i][4])
            {
                var id = exosPython[i][0]
                found = true;
                lang = "exercice_python";
                break
            }
        }
    }else if(exo.toLowerCase().includes("java"))
    {
        for(var i=0; i<exosJava.length; i++)
        {
            if(exo == exosJava[i][4])
            {
                var id = exosJava[i][0]
                found = true;
                lang = "exercice_java";
                break
            }
        }
    }
    if(found){
        $("#exosInformations").show();
        $.ajax({
            type : "GET",
            url : "/admin/exercices/templating_exos",
            data: {"id":id,"lang":lang},
            success: function(data)
            {
                if(data["ok"]) $("#form").before('<p id="msg" class="lead" style="color: green;">Le fichier représentant l\'exercice est accessible <a style="color: blue;" download="template_for_exercice.json" href="/auxfile95172851967286/'+data["ok"]+'">ici.</a><br>Attention à ne pas modifier l\'id, le numéro ainsi que le langage associé à l\'exercice.</p>')
                if(data["error"]) $("#form").before('<p id="msg" class="lead" style="color: red;">'+data["error"]+'</p>')
            }
        })
    }else{
        $("#selectUser").before('<script id="msg">alert("GnEGNe c maran de jouer aveque le caude sourse")</script>')
    }
}