drop function if exists reverse(varchar(1), int);
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