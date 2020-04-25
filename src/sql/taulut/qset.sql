drop table if exists qset;
drop sequence if exists qruns;
create sequence qruns start 1;

create table qset (
    vertex  varchar(1),
    dist    INT default null,
    prev    varchar(1),
    visited boolean,
    line    varchar(10)[],
    run     int,

    primary key (vertex, run),
    like Pohjat INCLUDING ALL
);

comment on table qset is 'Djikstran algoritmin q-joukko nodeja';
comment on column qset.vertex is 'Noden tunniste (pysäkki)';
comment on column qset.dist is 'Etäisyys etsityllä reitillä';
comment on column qset.prev is 'Noden edeltäjä etsittävällä polulla, Djikstran prev';
comment on column qset.visited is 'Onko node käsitelty loppuun?';
comment on column qset.line is 'Linjat, joilla tänne tultiin';
comment on column qset.run is 'Ajo, jolle tämä qset-kuuluu';

