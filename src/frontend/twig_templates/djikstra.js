<script>
function djikstra(event) {
    from = $("#from").val();
    to = $("#to").val();
    if (from=="" || !from.match({{ cjsPattern }})) {
        $("#nottiisi").html("{{ tPysakkiohje }}");
        $("#nottiisi").show("fold", 500);
        $("#nottiisi").hide("fold", 5000);
        return false;
    }
    if (to=="" || !from.match({{ cjsPattern }})) {
        $("#nottiisi").html("{{ tPysakkiohje }}");
        $("#nottiisi").show("fold", 500);
        $("#nottiisi").hide("fold", 5000);
        return false;
    }
    $("#nottiisi").html("{{ tLadataan }}");
    $("#nottiisi").show("fold, 500");
    $.get("{{ backendUrl }}/djikstra",{from: from, to: to}, function (data) {
        tulos = data[0];
        pysakit = data[1];
        kaaret = data[2];
        stepit = data[3];
        edges=new vis.DataSet(kaaret);
        nodet=new vis.DataSet(pysakit);
        tuloskartta = {
            nodes : nodet,
            edges : edges
        }
        options = {
            physics: {
                enabled : false
            },
            layout: {
                hierarchical : {
                    enabled : true, 
                    direction : '{{ tDirection }}',
                    levelSeparation : {{ tLevelSeparation }},
                    nodeSpacing : {{ tNodeSpacing }}
                }
            },
            edges : {
                arrows : {
                    to : {
                        enabled : true,
                        type : "arrow"
                    }
                }
            }
        }
        network = new vis.Network($("#tuloskartta")[0],tuloskartta, options);
        $("#nottiisi").hide("fold",2000);
        $("#tuloskartta").focus();
        s='<table id="reittiohje">\n';
        s+="  <tr><th>{{ tLinja }}</th><th>{{ tPysakilta }}</th><th>{{ tPysakille }}</th><th>{{ tKesto }}</th><th>{{ tKestoY }}</th></tr>\n";
        disty=0
        i=0
        $.each(stepit, function(numero, data) {
            s+="  <tr><td>"+data["with"]+"</td>";
            s+="<td>"+data["from"]+"</td>";
            s+="<td>"+data["to"]+"</td>";
            s+="<td>"+data["for"]+"</td>";
            dist=data["traveled"];
            disty=dist;
            s+="<td>"+dist+"</td></tr>\n";
        });
        s+="</table>\n";
        $("#tulosreitti").html(s);
        $("#tulosreittif").html("{{ tKestoY }}:"+disty)
    }).fail(function (data) {
        alert("WTF?");
        console.log(data);
    });
    return false;
}
</script>