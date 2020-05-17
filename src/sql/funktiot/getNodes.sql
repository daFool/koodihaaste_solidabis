drop function if exists getNodes();

/****f* Koodihaaste.database/getNodes
* NAME
*   getNodes    -- Hakee kaikki vertexit/nodet/pysäkit edges-taulusta.
* SYNOPSIS
*   table nodes -- getNodes( )
* RESULT
*   nodes   -- Pysäkit node (pysäkki), cost (kaaren pituus) ja line (linjat)
* SEE ALSO
*   edges - taulu
* AUTHOR    
*   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
* COPYRIGHT 
*   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
*   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
*   Source: Löytyy Git Hubista [G1]
* |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
******
*/
create function getNodes() returns table (node varchar(1), cost int, line varchar(10)[]) as '
    select node, cost, line from (
                select src as node, cost, line from edges group by src, cost, line
            union
                select dst as node, cost, line from edges group by dst, cost, line) f
' language 'sql';

comment on function getNodes is 'Hakee kaikki vertexit/nodet/pysäkit edges-taulusta.'