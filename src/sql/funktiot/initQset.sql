drop function if exists initQset(varchar(1));
create function initQset(startBusStop varchar(1)) returns INTEGER AS '
    DECLARE
        id int;
        n varchar(1);
        d int default null;
    BEGIN
        select nextval(''qruns'') into id;
        FOR n in select node from (
                select src as node from edges group by src
            union
                select dst as node from edges group by dst) f
        LOOP
            IF n=startBusStop THEN
                d:=0;
            else
                d:=null;
            end IF;
            insert into qset (vertex, dist, prev, visited, run)
                values (n, d, null, false, id);
        end loop;

        return id;
    end 
' language plpgsql;

