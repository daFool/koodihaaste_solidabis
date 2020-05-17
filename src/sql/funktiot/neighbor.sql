drop function if exists neighbor(varchar(1), int);

/****f* Koodihaaste.database/neighbor
* NAME
*   neighbor    -- Hakee u:n naapurit v, joita ei ole vielä käsitelty.
* SYNOPSIS
*   record naapurit = neighbor( u varchar(1), id int)
* FUNCTION
*   Yhdistää getNodes() - tulokseen q-joukon ne rivit, joita ei ole käsitelty ja jotka eivät ole
*   etsittävä u. Palauttaa löytyneet naapurit tai tyhjää. 
* INPUTS
*   u   --  Vertex/node/pysäkki, jonka käsittelemättömiä naapureita etsitään.
*   id  --  Q-joukon/ajon tunniste, jossa naapureita etsitään
* RESULT
*   naapurit -- Käsittelemättömät naapurit, jos sellaisia oli, attribuuteilla: pysäkkitunnus (node), kaaren kustannus (cost), linjat (line) ja nykyisen arvion
* etäisyydestä lähtöpysäkille (cost).
* SEE ALSO
*   qset - taulu edges - taulu
* AUTHOR    
*   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
* COPYRIGHT 
*   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
*   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
*   Source: Löytyy Git Hubista [G1]
* |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
******
*/
create function neighbor(u varchar(1), id int) returns setof record as '
    DECLARE
        r   record;
    BEGIN
        FOR r IN SELECT
                node,
                e.cost,
                e.line,
                v.dist
            FROM
                (SELECT vertex, dist FROM qset WHERE run=id AND visited=false AND NOT vertex=u) AS v
            JOIN
                (select case when src=u then dst else src end as node, cost, line from edges where src=u or dst=u) AS e
            ON (e.node=v.vertex)
            GROUP BY node, cost, line, dist
        LOOP
            RETURN next r;
        END LOOP;
    END
' language plpgsql;

comment on function neighbor is 'Hakee u:n naapurit v, joita ei ole vielä käsitelty.';