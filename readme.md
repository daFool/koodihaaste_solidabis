# Solidabis Koodihaaste

## Käytetyt teknologiat
* Postgresql-tietokanta, todennäköisesti mikä tahansa versio 9.5+ käy, kunhan siinä on plpgsql-tuki, kehityskoneessa on versio 11.0 (9.5 on ensimmäinen josta löytyy to_json()-funktio)
* Jokin linux-kone, kehitysalustana oli Fedora release 31 (Thirty One)
* 7-sarjan PHP, kehityskoneen versio: 7.3.17 (php:n pakettiluettelo löytyy tiedostosta php_rpms.txt)
* Composer php-riippuvuuksien ylläpitoon, composerilla haettu Fat Free Core
* Jokin web-palvelin, kehitysalustassa on käytetty Apachea 2.4.43
* Käyttöliittymä on toteutettu ecmascriptillä, jqueryllä, jquery-ui:llä, bootstrapilla ja vis.js:llä. Kaikki ladataan lennossa CDN:stä
* Ratkaisussa käytetään toista php-projektiani, joka löytyy githubista https://github.com/daFool/mosBase

## Ratkaisun arkkitehtuuri
Ratkaisu perustuu Djikstran hakualgoritmiin, algoritmi on kuvattu wikipediassa: https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm#Pseudocode .
Varsinaisen laskenta tapahtuu tietokannassa funktioilla aputauluja käyttäen. Itse web-ratkaisu on hajautettu kahteen palaseen: backendiin ja käyttöliittymään. Backend on löyhästi määritelty REST-määritelmän
täyttävä API, jota käyttöliittymän javascript-kutsuu ajaxilla. Toteutuksessa on sovellettu MVC-arkkitehtuuria. Teoriassa kaikki kolme palikkaa voidaan asentaa omiin kontteihinsa ja kutakin konttia ajaa useampia rinnakkain. Backend kutsuu kantaa aina yhtenä atomisena kutsuna, joten ei ole väliksi vaikka eri kutsujen välissä vastaisi toinen instanssi kantaa. Frontend:in ja Backendin välinen kommunikaatio on myös tilatonta, joten ei ole väliä mitä backendiä mikäkin frontend kutsuisi.

## Asentaminen
Asennuksessa oletetaan, että asentajalla on jokin linux-ympäristö käytettävissään, josta löytyvät asennettuna php, postgresql ja apache. Asennusohje on testattu koneessa, jossa on: CentOS Linux release 7.6.1810 (Core), PHP 7.1.27 (cli) (built: Mar 10 2019 16:23:55) ( NTS ), psql (PostgreSQL) 12.2 ja Apache 2.4.6. Asennusohjeissa ei opasteta web-palvelimen, tai postgresql:n konfigurointia, puhumattakaan palomuuri- tai selinux-konfigurointia. 

Asennus kannattaa aloittaa hakemalla koko repository johonkin paikkaan kohdekoneella, jossa web-palvelimelle voidaan antaa kulku- ja lukuoikeus hierarkiaan. Käytännössä kaikkiin muihin hakemistoihin kuin sql-alihakemistoihin on tarve päästä. Tämä siis tarkoittaa web-palvelimen käyttäjän luku- ja pääsyoikeuksia. Itse web:in kautta pääsyn voi rajata hakemistoihin: src/frontend ja src/backend. 

git clone https://github.com/daFool/koodihaaste_solidabis.git

cd koodihaaste_solidabis

git clone https://github.com/daFool/mosBase.git

Luodaan ratkaisun tarvitsema kanta:
./createDatabase.sh

Luodaan kantaan taulut:
./loadsql.sh

Asetetaan ympäristömuuttujat php-skriptejä varten:
source local.sh
cd src
php initializeDatabase.php ../haaste/reittiopas.json

Näiden temppujen jälkeen komentorivityökalun pitäisi toimia ja reitittää oikein. Esimerkiksi:
[mos@coredump src]$ ./routeIt.php A O
A->C with vihreä for 1/1 
C->E with vihreä for 2/3 
E->M with sininen for 10/13 
M->N with sininen for 2/15 
N->O with sininen for 2/17 

### Web-backend
Apachelle pitää kertoa mistä front- ja backendit löytyvät. Riippuen tietysti apachen versiosta tai siitä käyttääkö ollenkaan apachea, niin jotakin tämmöistä voisi päätyä /etc/httpd/conf.d - hakemiston sopivaan tiedostoon:
Alias /koodihaaste/ui   /web/koodihaaste_solidabis/src/frontend
Alias /koodihaaste/back /web/koodihaaste_solidabis/src/backend

<Directory "/web/koodihaaste_solidabis/src/frontend">
        AllowOverride all
        Require all granted
</Directory>

<Directory "/web/koodihaaste_solidabis/src/backend">
        AllowOverride all
        Require all granted
</Directory>

Backend käyttää Fat Free Corea, joka pitää hakea composerilla:
[mos@coredump src]$ composer update
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Installing bcosca/fatfree-core (3.7.1): Downloading (100%)  

Tämän jälkeen pitää vielä korjata .htaccess-tiedosto hakemistossa src/backend ja src/front tiedostojen polut. Tämä käy vaikka sed-komennolla, jotenkin tähän malliin:
for i in backend/.htaccess frontend/.htaccess; do sed -s -i 's/\/home\/mos\/Projektit\/Koodihaaste/\/web\/koodihaaste_solidabis/g' $i;done

Komennossa \/web-alkava osuus on polku josta projekti löytyy.

Jos kaikki meni hyvin, niin surffaamalla asennusosoitteessa backendiin, vaikka seuraavasti:
https://www.generalfailure.net/koodihaaste/back/nodes

pitäisi vastauksen olla json:
[{"id":1,"label":"A"},{"id":2,"label":"B"},{"id":3,"label":"C"},{"id":4,"label":"D"},{"id":5,"label":"E"},{"id":6,"label":"F"},{"id":7,"label":"G"},{"id":8,"label":"H"},{"id":9,"label":"I"},{"id":10,"label":"J"},{"id":11,"label":"K"},{"id":12,"label":"L"},{"id":13,"label":"M"},{"id":14,"label":"N"},{"id":15,"label":"O"},{"id":16,"label":"P"},{"id":17,"label":"Q"},{"id":18,"label":"R"}]

osoitteesta https://www.generalfailure.net/koodihaaste/back/edges löytyvät "kaaret" ja reititystä voi kysellä vaikka:
https://www.generalfailure.net/koodihaaste/back/djikstra?from=E&to=M


## Frontti
Frontti vaatii yhden on-linerin lisää hakeistossa front:
find . -type f -exec sed -i -s 's/http:\/\/localhost\/koodihaaste\/back/https:\/\/generalfailure.net\/koodihaaste\/back/g' {} \;
jälleen sed:in jälkimmäinen osa on se mitä pitää muuttaa. Osaan tulee web-palvelimen osoite, johon sovellus on asennettu.
Tämän jälkeen käyttöliittymän pitäisi löytyä osoitteesta:
https://generalfailure.net/koodihaaste/ui/


