<script>
var cmap;
var nmap;

$(document).ready(function() {    
    $.get("{{ backendUrl }}/nodes", function (data) {
        nodes=new vis.DataSet(data);
        $.get("{{ backendUrl }}/edges", function (data) {
            edges=new vis.DataSet(data);
            reittikartta = {
                nodes : nodes,
                edges : edges
            }
            options = {
                height : "{{ ysize }}",
                //width : "{{ xsize }}",
                width : "100%",
                physics: {
                    enabled : false
                },
                layout: {
                    hierarchical : {
                        enabled : true, 
                        direction : "{{ direction }}",
                        levelSeparation : {{ levelSeparation }},
                        nodeSpacing : {{ nodeSpacing }}
                    }
                }                
            }
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
</script> 