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
comment on column edges.line is 'Linja jolle kaari kuuluu';