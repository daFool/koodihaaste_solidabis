drop table if exists qset;

drop sequence if exists qruns;

/****s* Koodihaaste.database/qruns
 * NAME
 *   qruns   -- Ajojoukkojen keinotekoinen avain
 * AUTHOR    
 *   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
 * COPYRIGHT 
 *   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
 *   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
 *   Source: Löytyy Git Hubista [G1]
 * |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
 ******
 */
create sequence qruns start 1;

drop sequence if exists visits;

/****s* Koodihaaste.database/visits
 * NAME
 *   visits   -- Ajojoukkojen vierailu/käsittelyjärjestys
 * AUTHOR    
 *   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
 * COPYRIGHT 
 *   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
 *   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
 *   Source: Löytyy Git Hubista [G1]
 * |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
 ******
 */
create sequence visits start 1;

/****s* Koodihaaste.database/qset
 * NAME
 *  qset    -- Dijkstran algoritmin Q-joukko, johon kootaa käsiteltävät vertexit - koodihaasteen pysäkit.
 * ATTRIBUTES
 *  | Name   | Type          | Purpose                                             |
 *  +---------+---------------+----------------------------------------------------+
 *  | vertx   | varchar(1)    | Lähtöpysäkki/vertex/node/solmu                     |
 *  | dist    | int           | Kohdepysäkki                                       |
 *  | prev    | int           | Etäisyys pysäkkien välillä                         |
 *  | visited | boolean       | Onko vertexi loppuunkäsitelty algoritmin toimesta? |
 *  | line    | varchar(10)[] | Linjat joilla kuljettiin tähän vertexiin           |
 *  | run     | int           | Ajon tunniste, jolle tämä qset kuuluu              |
 *  | jrnro   | int           | Järjestysnumero                                    |
 * FUNCTION
 *  Dijkstran algoritmin [W1] tulospuuta ja käsittelyjoukkoa QSET vastaava tietokantataulu.
 *  run otetaan qruns sekvenssistä ja jrnro visits sekvenssistä.
 * SEE ALSO
 *  qruns visits
 * AUTHOR    
 *   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
 * COPYRIGHT 
 *   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
 *   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
 *   Source: Löytyy Git Hubista [G1]
 * |html <a href="https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm">[W1]</a> <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
 ******
 */
create table qset (
    vertex  varchar(1),
    dist    INT default null,
    prev    varchar(1),
    visited boolean,
    line    varchar(10)[],
    run     int,
    jrnro   bigint,

    primary key (vertex, run),
    like Pohjat INCLUDING ALL
);

comment on sequence qruns is 'Hakujoukon tunniste.';

comment on sequence visits is 'Hakujoukon käsittelyjärjestys';

comment on table qset is 'Dijkstran algoritmin q-joukko nodeja';
comment on column qset.vertex is 'Noden tunniste (pysäkki)';
comment on column qset.dist is 'Etäisyys pysäkille lähtöpysäkiltä';
comment on column qset.prev is 'Noden edeltäjä etsittävällä polulla, Dijkstran prev';
comment on column qset.visited is 'Onko node käsitelty loppuun? Vastaa algoritmin "poistoa" - true == poistettu';
comment on column qset.line is 'Linjat, joilla päädyttiin tälle pysäkille';
comment on column qset.run is 'Haun tunniste, jolle tämä qset-kuuluu';
comment on column qset.jrnro is 'Missä vaiheessa tätä pysäkkiä on käsitelty?';

