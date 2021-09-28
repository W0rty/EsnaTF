exosC = new Array();
exosJava = new Array();
exosPython = new Array();

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


function delExo()
{
    $("#msgconfirm").remove();
    $("#msgerror").remove();
    $("#deleteButton").show();
    var exo = $(":selected").val()
    if(exo.toLowerCase().includes("c"))
    {
        for(var i=0; i<exosC.length; i++)
        {
            if(exo == exosC[i][4])
            {
                var id = exosC[i][0]
                $("#id").val(id)
                found = true;
                $("#lang").val("exercice_c");
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
                $("#id").val(id)
                found = true;
                $("#lang").val("exercice_python");
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
                $("#id").val(id)
                found = true;
                $("#lang").val("exercice_java");
                break
            }
        }
    }
    $("#enonce").val()
    $("#exosInformations").show();
}