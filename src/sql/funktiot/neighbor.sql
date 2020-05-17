drop function if exists neighbor(varchar(1), int);
create function neighbor(u varchar(1), id int) returns setof record as '
    DECLARE
        r   record;
    BEGIN
        FOR r IN 
            select case 
                    when e.src=u then e.dst
                    else e.src
                end as node,
                e.cost,
                e.line,
                v.dist
            from
                (select vertex, dist from qset where run=id and visited=false and not vertex=u) as v
            join
                (select * from edges where src=u or dst=u) as e
            on (e.src=v.vertex or e.dst=v.vertex)
            group by node, cost, line, dist
        LOOP
            return next r;
        END LOOP;
    END
' language plpgsql;