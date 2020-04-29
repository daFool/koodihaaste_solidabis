function djikstra(event) {
    from = $("#from").val();
    to = $("#to").val();
    if (from=="" || !from.match(/[A-P]{1}/)) {
        $("#nottiisi").html("Pysäkiltä: Pysäkki on yksi kirjain A:sta P:hen! ");
        $("#nottiisi").show("fold", 500);
        $("#nottiisi").hide("fold", 5000);
        return false;
    }
    if (to=="" || !from.match(/[A-P]{1}/)) {
        $("#nottiisi").html("Pysäkille: Pysäkki on yksi kirjain A:sta P:hen! ");
        $("#nottiisi").show("fold", 500);
        $("#nottiisi").hide("fold", 5000);
        return false;
    }
    $("#nottiisi").html("Ladataan...");
    $("#nottiisi").show("fold, 500");
    $.get("http://localhost/koodihaaste/back/djikstra",{from: from, to: to}, function (data) {
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
        options = {};
        network = new vis.Network($("#tuloskartta")[0],tuloskartta, options);
        $("#nottiisi").hide("fold",2000);
        $("#tuloskartta").focus();
        s="<table>\n";
        s+="  <tr><th>Linja</th><th>Pysäkiltä</th><th>Pysäkille</th><th>Kesto</th><th>Kesto yhteensä</th></tr>\n";
        dist=0
        i=0
        $.each(stepit, function(numero, data) {
            s+="  <tr><td>"+data["with"]+"</td>";
            s+="<td>"+data["from"]+"</td>";
            s+="<td>"+data["to"]+"</td>";
            s+="<td>"+data["for"]+"</td>";
            dist=data["traveled"];
            s+="<td>"+dist+"</td></tr>\n";
        });
        s+="</table>\n";
        $("#tulosreitti").html(s);
    }).fail(function (data) {
        alert("WTF?");
        console.log(data);
    });
    return false;
}