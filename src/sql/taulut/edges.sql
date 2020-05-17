/****s* Koodihaaste.database/edges
 * NAME
 *  edges   -- Dijkstran algoritmin "Graph" ja koodihaasteen pysäkkikartta.
 * ATTRIBUTES
 *  | Name | Type          | Purpose                                         |
 *  +------+---------------+-------------------------------------------------+
 *  | src  | varchar(1)    | Lähtöpysäkki/vertex/node/solmu                  |
 *  | dst  | varchar(1)    | Kohdepysäkki                                    |
 *  | cost | int           | Etäisyys pysäkkien välillä                      |
 *  | line | varchar(10)[] | Linjat joilla voi kulkea pysäkkien välillä      |
 * FUNCTION
 *  Dijkstran algoritmin [W1] "Graph"-lähde, kaikkien pysäkkien ja linjojen kartta.
 * AUTHOR    
 *   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
 * COPYRIGHT 
 *   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
 *   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
 *   Source: Löytyy Git Hubista [G1]
 * |html <a href="https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm">[W1]</a> <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
 ******
 */

drop table if exists edges;

create table edges (
    src  varchar(1) not null,
    dst  varchar(1) not null,
    cost    int,
    line    varchar(10)[],

    primary key (src, dst, cost),
    like Pohjat INCLUDING ALL
);

comment on table edges is 'Reittikartan suuntaamattomat kaaret';
comment on column edges.src is 'Pysäkki jolta bussi lähtee';
comment on column edges.dst is 'Pysäkki jolle bussi päätyy';
comment on column edges.cost is 'Matka-aika pysäkiltä toiselle';
comment on column edges.line is 'Linjat, jotka kulkevat pysäkkivälin';