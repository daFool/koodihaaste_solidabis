var cmap;
var nmap;

$(document).ready(function() {    
    $.get("http://localhost/koodihaaste/back/nodes", function (data) {
        nodes=new vis.DataSet(data);
        $.get("http://localhost/koodihaaste/back/edges", function (data) {
            edges=new vis.DataSet(data);
            reittikartta = {
                nodes : nodes,
                edges : edges
            }
            options = {};
            network = new vis.Network($("#reittikartta")[0],reittikartta, options);
            $("#nottiisi").hide("puff",500);
        });
    }
    );
    $('[name="reititys"]').submit(djikstra);
    nmap=new Array(26);
    for(i=0;i<26;i++) {
        nmap[i+1]=String.fromCharCode('A'.charCodeAt(0)+i);
    }
    cmap={"yellow" : "keltainen", "blue" : "sininen", "green" : "vihreä", "red" : "punainen" }
});