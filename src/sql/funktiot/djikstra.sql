drop function if exists djikstra(varchar(1), varchar(1));
create function djikstra(startBusStop varchar(1), endBusStop varchar(1)) returns integer as '
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
            update qset set visited=true where vertex=u.vertex and run=id;
            IF u.vertex=endBusStop THEN
                return id;
            END IF;
            FOR v in select * from neighbor(u.vertex, id) as f(node varchar(1), cost int, line varchar(10)[], dist int) LOOP
                alt:=u.dist + v.cost;
                IF v.dist is null or v.dist>alt then
                    linja:=v.line;
                    update qset set dist=alt, prev=u.vertex, line=linja where run=id and vertex=v.node;
                END IF;
            END LOOP;
            select * into u from qset where run=id and dist is not null and visited=false order by dist asc limit 1;
        END LOOP;
        return id;
    END
' language plpgsql;