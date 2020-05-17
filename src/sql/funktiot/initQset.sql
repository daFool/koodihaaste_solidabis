drop function if exists initQset(varchar(1));

/****f* Koodihaaste.database/initQset
* NAME
*   Dijkstran algoritmin q-joukon alustus.
* SYNOPSIS
*   int runId = initQset( startBusStop varchar(1))
* FUNCTION
*   Haetaan sekvenssistä qruns q-joukolle tunniste.
*   Lisätään qset-tauluun rivi kutakin edges-taulusta löytyvää pysäkkiä kohden ( getNodes() ),
*   jos pysäkki on aloituspysäkki, on etäisyys aloituspysäkistä 0, muutoin null.
*   Haetaan sekvenssistä visits seuraava vapaa järjestysnumero ja asettaan se lähtöpysäkille,
*   muilla käsittelyjärjestysnumero asetaan nollaksi. Kaikille pysäkeille asettaan lisäksi
*   visited falseksi.
* RESULT
*   runId   -- Ajon tunniste / q- eli tulosjoukon tunniste
* SEE ALSO
*   qset - taulu
* AUTHOR    
*   Mauri "Fuula-setä" Sahlberg mailto:fuula@generalfailure.net
* COPYRIGHT 
*   (c) Copyright 2020 by Mauri Sahlberg, Helsinki
*   License: GPL-2.0 http://opensource.org/licenses/GPL-2.0
*   Source: Löytyy Git Hubista [G1]
* |html <a href="https://github.com/daFool/koodihaaste_solidabis">[G1]</a>
******
*/
create function initQset(startBusStop varchar(1)) returns INTEGER AS '
    DECLARE
        id int;
        n varchar(1);
        d int default null;
        s bigint default 0;
    BEGIN
        
        select nextval(''qruns'') into id;
        FOR n in select node from getNodes() group by node
        LOOP
            IF n=startBusStop THEN
                d:=0;
                s:=nextval(''visits'');
            else
                s:=0;
                d:=null;
            end IF;
            insert into qset (vertex, dist, prev, visited, run, jrnro)
                values (n, d, null, false, id, s);
        end loop;

        return id;
    end 
' language plpgsql;

comment on function initQset is 'Dijkstran algoritmin q-joukon alustus.';