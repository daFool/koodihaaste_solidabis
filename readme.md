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

 

