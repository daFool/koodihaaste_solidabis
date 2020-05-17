drop function if exists dijkstra(varchar(1), varchar(1));

/****f* Koodihaaste.database/dijkstra
* NAME
*   dijkstra -- Toteuttaa Dijkstran SPF-hakualgoritmin
* SYNOPSIS
*   int runId = dijkstra( startBusStop int, endBusStop int)
* INPUTS
*   startBusStop    -- Vertexi/pysäkki, josta haku aloitetaan.
*   endBusStop      -- Vertexi/pysäkki, jolle etsitään reittiä.
* FUNCTION
*   Dijkstran hakualgoritmin kuvaus löytyy wikipediasta [W1].
*   Q-joukko (taulu) alustetaan funktiolla initQset() ja käsittelyssä olevan vertexin (pysäkin), jolle etäisyyttä lasketaan
*   naapurit etsitään funktiolla neighbor().
* RESULT 
*   runId -- Tulosjoukon tunniste
* SEE ALSO 
*   initQset() neighbor() qset edges visits qruns
* AUTHOR    
*   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
* COPYRIGHT 
*   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
*   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
*   Source: Löytyy Git Hubista [G1]
* |html <a href="https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm">[W1]</a> <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
******
*/
create function dijkstra(startBusStop varchar(1), endBusStop varchar(1)) returns integer as '
    DECLARE
        id int;
        u   qset%rowtype;
        v   record;
        alt int;
        linja   varchar(10)[];

    BEGIN
        select initQset(startBusStop) into id;
        select * into u from qset where run=id and vertex=startBusStop;
        WHILE FOUND LOOP
            update qset set visited=true, muokattu=now() where vertex=u.vertex and run=id;
            IF u.vertex=endBusStop THEN
                return id;
            END IF;
            FOR v in select * from neighbor(u.vertex, id) as f(node varchar(1), cost int, line varchar(10)[], dist int) LOOP
                alt:=u.dist + v.cost;
                IF v.dist is null or v.dist>alt then
                    linja:=v.line;
                    update qset set dist=alt, prev=u.vertex, line=linja, muokattu=now(), jrnro=nextval(''visits'') where run=id and vertex=v.node;
                END IF;
            END LOOP;
            select * into u from qset where run=id and dist is not null and visited=false order by dist asc limit 1;
        END LOOP;
        return id;
    END
' language plpgsql;

comment on function dijkstra is 'Toteuttaa Dijkstran SPF-hakualgoritmin';