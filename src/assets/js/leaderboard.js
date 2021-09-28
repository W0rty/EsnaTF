leaderboardGeneral = []
leaderboardPython = []
leaderboardJava = []
leaderboardC = []

var endpoint = "/api/leaderboard"

$.ajax({
    type: "GET",
    url: endpoint,
    success: function(data)
    {
        leaderboardGeneral = data
    }
})

$.ajax({
    type: "GET",
    url: endpoint+"?lang=python",
    success: function(data)
    {
        leaderboardPython = data
    }
})

$.ajax({
    type: "GET",
    url: endpoint+"?lang=java",
    success:function(data)
    {
        leaderboardJava = data;
    }
})

$.ajax({
    type: "GET",
    url: endpoint+"?lang=c",
    success:function(data)
    {
        leaderboardC = data
    }
})

function changeScoreboard(type)
{
    var table_content = '<thead class="thead-dark"><tr><th scope="col">Place</th><th scope="col">Pseudo</th><th>Score</th></tr></thead>'
    if(type == "general")
    {
        $("#table").empty()
        for(var i=0; i<leaderboardGeneral.length; i++)
        {
            table_content += "<tr><th scope='row'>"+(i+1)+"</th><td>"+leaderboardGeneral[i]["username"]+"</td><td>"+leaderboardGeneral[i]["total"]+"</td></tr>"
        }
        $("#table").append(table_content)
        $("#scoreboard").html("Scoreboard Général")
        return;
    }
    if(type == "python")
    {
        $("#table").empty()
        for(var i=0; i<leaderboardPython.length; i++)
        {
            table_content += "<tr><th scope='row'>"+(i+1)+"</th><td>"+leaderboardPython[i]["username"]+"</td><td>"+leaderboardPython[i]["python"]+"</td></tr>"
        }
        $("#table").append(table_content)
        $("#scoreboard").html("Scoreboard en python")
        return;
    }
    if(type === "java")
    {
        $("#table").empty()
        for(var i=0; i<leaderboardJava.length; i++)
        {
            table_content += "<tr><th scope='row'>"+(i+1)+"</th><td>"+leaderboardJava[i]["username"]+"</td><td>"+leaderboardJava[i]["java"]+"</td></tr>"
        }
        $("#table").append(table_content)
        $("#scoreboard").html("Scoreboard en java")
        return;
    }
    if(type === "c")
    {
        $("#table").empty()
        for(var i=0; i<leaderboardC.length; i++)
        {
            table_content += "<tr><th scope='row'>"+(i+1)+"</th><td>"+leaderboardC[i]["username"]+"</td><td>"+leaderboardC[i]["c"]+"</td></tr>"
        }
        $("#table").append(table_content)
        $("#scoreboard").html("Scoreboard en c")
        return;
    }
}

setTimeout(function(){changeScoreboard("general")},200);