drop function if exists getNodes();
create function getNodes() returns table (node varchar(1)) as '
    select node from (
                select src as node from edges group by src
            union
                select dst as node from edges group by dst) f
' language 'sql';