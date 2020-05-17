drop function if exists reverse(varchar(1), int);

/****f* Koodihaaste.database/reverse
* NAME
*   reverse -- Palauttaa Dijkstran SPF-haun tulosreitin
* SYNOPSIS
*   table rset = reverse( endBusStop varchar(1), id int)
* INPUTS
*   endBusStop  -- Pysäkki, jolta "etsitään" reitti tulosjoukossa takaisin lähtöpysäkille.
*   id          -- Ajon tunniste, jonka tulosjoukosta haetaan.
* FUNCTION
*   Käy SPF:n tuottaman hakupuun lävitse kohdesolmusta juureen ja palauttaa tulosjoukon (qset) vertexit/pysäkit/solmut.
*   Rekursiivisen CTE:n sisällä luodaan järjestysnumerollinen joukko, missä kohdesolmu on yksi ja siitä eteenpäin
*   kohden lähtösolmua kaikki solmut saavat kasvavan järjestysnumeron. CTE:n tulos palautetaan laskevassa järjestyksessä ilman
*   "väliaikaista" järjestysnumeroa.
* RESULT
*   rset -- Joukko solmuja/vertexejä/pysäkkejä, attribuuteilla vertex (pysäkin tunnus), dist (etäisyys lähtöpysäkistä),
*   line (linjat jolla pysäkille voi kulkea edelliseltä pysäkiltä), prev (pysäkki jolta tälle pysäkille tultiin) ja run (ajon tunniste).
* SEE ALSO
*   qset - taulu
* AUTHOR    
*   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
* COPYRIGHT 
*   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
*   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
*   Source: Löytyy Git Hubista [G1]
* |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
******
*/
create function reverse(endBusStop varchar(1), id int) 
returns TABLE (
    vertex      varchar(1), 
    dist        int, 
    line        varchar(10)[],
    prev        varchar(1), 
    run         int
    ) 
AS
'
with recursive foo as (
                    select vertex,dist, line, prev, run, 1 as i from qset where run=id and visited=true and vertex=endBusStop
                union
                    select q.vertex, q.dist, q.line, q.prev, q.run, i+1 as i from qset q inner join foo f  on (f.run=q.run and f.prev=q.vertex)
            )
            select vertex, dist, line, prev, run from foo order by i desc;
'
language 'sql';

comment on function reverse(varchar(1), int) is 'Palauttaa Dijkstran SPF-haun tulosreitin';