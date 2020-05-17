# Solidabis koodihaaste
Ratkaisua kuvataan tarkemmin video-sarjassani Fuula-sedän IT-Sirkus, Solidabis - koodihaaste. Haasteen ensimmäinen jakso on
nyt katsottavissa YouTubessa: https://www.youtube.com/watch?v=2dcJS_M0PFY . Sarjan edetessä myös tämän repositoryn sisältö muuttuu ja suurella todennäköisyydellä jatkaa sarjan valmistumisen jälkeen muuttumista. Projektille on todennäköisesti käyttöä tulevien IT-Sirkuksen jaksojen tekemisessä. 

Samoin osoitteesta: https://generalfailure.net/koodihaaste/ui/ löytyvä malliratkaisu muuttuu sarjan edetessä.
## Ratkaisua selittävät videot
1. Tehtäväksianto: https://www.youtube.com/watch?v=2dcJS_M0PFY 
2. Tietokanta: https://www.youtube.com/watch?v=nN9K3Q_-d8g
3. Tietokantasuunnittelu: https://www.youtube.com/watch?v=-cAGeVjK37k&feature=youtu.be
4. Dijkstran algoritmi: https://www.youtube.com/watch?v=Y-yMupnW4cc&feature=youtu.be

## Käytetyt teknologiat
* Postgresql-tietokanta, todennäköisesti mikä tahansa versio, 9.5 version jälkeen käy, kunhan siinä on plpgsql-tuki. 9.5 on ensimmäinen Postgresql-versio, josta löytyy to_json()-funktio. Ratkaisu on kehitetty koneessa, jossa on asennettuna Postgresql versio 11.0. 
* Jokin linux-kone, kehitys tapahtui Fedora release 31 (Thirty One) työasemalla
* Tuettu 7-sarjan PHP, kehityskoneen versio: 7.3.17 (kehityskoneen php:n pakettiluettelo löytyy tiedostosta php_rpms.txt)
* Composer, php-riippuvuuksien ylläpitoon, composerilla on haettu Fat Free Core ja Twig-templating engine
* Jokin web-palvelin, kehitysalustassa on käytetty Apachea versiolla 2.4.43
* Käyttöliittymä on toteutettu ecma-scriptillä, jqueryllä, jquery-ui:llä, bootstrapilla ja vis.js:llä. Kaikki ulkoiset javascript-kirjastot ladataan lennossa CDN:stä. 
* Bootstrap-templatena on käytetty ilmaista Start Bootstrapin Small Business-templatea.
* Kehitys on tehty Chrome-selaimen versiolla: Versio 81.0.4044.122 (Virallinen koontiversio) (64-bittinen)
* Ratkaisussa käytetään osaa toisesta php-projektistani, joka löytyy GitHubista: https://github.com/daFool/mosBase
* Ant - java-kavereiden lahja Makefileä pelkääville ja xml-sulkeisista tykkääville
* robodoc - tietokannan dokumentoinnin generoimiseen

## Ratkaisun arkkitehtuuri
Ratkaisu perustuu Dijkstran hakualgoritmiin. Hakualgoritmi on kuvattu wikipediassa: https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm#Pseudocode . Varsinainen laskenta tapahtuu tietokannassa funktioilla aputauluja käyttäen. Itse web-ratkaisu on hajautettu kahteen osaan: backendiin ja käyttöliittymään. Backend on löyhästi REST-määritelmän toteuttava API, jota käyttöliittymän javascript-kutsuu ajaxilla. Toteutuksessa on sovellettu MVC-arkkitehtuuria. Teoriassa kaikki kolme ohjelmisto-osaa voidaan asentaa vaikka omiin kontteihinsa ja kutakin konttia ajaa useampia rinnakkain. Backend kutsuu kantaa aina yhtenä atomisena kutsuna, joten ei ole väliksi vaikka eri kutsujen välissä vastaisi toinen instanssi kantaa. Frontend:in ja Backendin välinen kommunikaatio on myös tilatonta, joten ei ole väliä mitä backendiä mikäkin frontend kutsuu.

## Asentaminen
Asennuksessa oletetaan, että asentajalla on jokin linux-ympäristö käytettävissään, josta löytyvät asennettuina php, postgresql ja apache. Asennusohje on testattu koneessa, jossa on: CentOS Linux release 7.6.1810 (Core), PHP 7.2.27 (cli) (built: Mar 10 2019 16:23:55) ( NTS ), psql (PostgreSQL) 12.2 ja Apache 2.4.6. Asennusohjeissa ei opasteta web-palvelimen, tai postgresql:n konfigurointia, puhumattakaan palomuurin- tai selinuxin-konfiguroinnista.

Asennus kannattaa aloittaa hakemalla koko repository johonkin paikkaan kohdekoneella, johon web-palvelimelle voidaan antaa hakemistohierarkiaan kulku- ja lukuoikeus. Käytännössä kaikkiin muihin hakemistoihin kuin sql-alihakemistoihin on tarve päästä. Tämä siis tarkoittaa web-palvelimen käyttäjän luku- ja pääsyoikeuksia hakemistoihin. Webin kautta pääsyn voi rajata hakemistoihin: src/frontend ja src/backend. 

    git clone https://github.com/daFool/koodihaaste_solidabis.git
    cd koodihaaste_solidabis
    git clone https://github.com/daFool/mosBase.git

Luodaan ratkaisun tarvitsema kanta:

`ant database`

Tämän jälkeen ratkaisun komentorivityökalun pitäisi jo toimia ja sillä voi kysellä reittejä. Esimerkiksi:

[mos@coredump src]$ `./routeIt.php A O`

    A->C with vihreä for 1/1 
    C->E with vihreä for 2/3 
    E->M with sininen for 10/13 
    M->N with sininen for 2/15 
    N->O with sininen for 2/17 

### Web-backend
Apachelle pitää kertoa mistä front- ja backendit löytyvät. Riippuen tietysti apachen versiosta tai siitä käyttääkö ollenkaan apachea, niin jotakin tämmöistä voisi päätyä /etc/httpd/conf.d - hakemistossa sopivaan tiedostoon:

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

Backend käyttää Fat Free Corea ja frontend Twigiä, jotka pitää hakea composerilla:

    mos@coredump src]$ `composer update`
    Loading composer repositories with package information
    Updating dependencies (including require-dev)
    Package operations: 1 install, 0 updates, 0 removals
    - Installing bcosca/fatfree-core (3.7.1): Downloading (100%)...  

Tämän jälkeen pitää vielä korjata .htaccess-tiedosto hakemistossa src/backend ja src/front tiedostojen polut. Tämän voi tehdä projektin päätason komennolla

`./fixhtaccess.sh`

Jos kaikki meni hyvin, niin surffaamalla asennusosoitteessa backendiin, vaikka seuraavasti:
    https://generalfailure.net/koodihaaste/back/nodes

pitäisi vastauksen olla json:

[{"id":1,"label":"A"},{"id":2,"label":"B"},{"id":3,"label":"C"},{"id":4,"label":"D"},{"id":5,"label":"E"},{"id":6,"label":"F"},{"id":7,"label":"G"},{"id":8,"label":"H"},{"id":9,"label":"I"},{"id":10,"label":"J"},{"id":11,"label":"K"},{"id":12,"label":"L"},{"id":13,"label":"M"},{"id":14,"label":"N"},{"id":15,"label":"O"},{"id":16,"label":"P"},{"id":17,"label":"Q"},{"id":18,"label":"R"}]

osoitteesta https://generalfailure.net/koodihaaste/back/edges löytyvät "kaaret" ja reititystä voi kysellä vaikka:

https://generalfailure.net/koodihaaste/back/djikstra?from=E&to=M


## Frontti
Fronttia varten pitää kertoa mistä backend-löytyy. Tämä tehdään muuttamalla src-hakemistosta löytyvää koodihaaste.ini-tiedostoa:
   
    [General]
    TZ = "Europe/Helsinki"
    vendor= ${koodihaaste}"/src/vendor/autoload.php"
    basePath = ${koodihaaste}"/src"
    baseUrl = "https://generalfailure.net/koodihaaste/ui"
    backEndUrl = "https://generalfailure.net/koodihaaste/back"

*baseUrl*:iin tulee käyttöliittymän web-osoite ja *backEndUrl*:iin backendin web-osoite.

Frontti käyttää valmiin bootstrap-templaten tyylitiedostoa, alkuperäinen ilmaisprojekti pitää hakea githubista src-hakemistoon:

`git clone https://github.com/BlackrockDigital/startbootstrap-small-business.git`

Tämän jälkeen käyttöliittymän pitäisi löytyä osoitteesta:

https://generalfailure.net/koodihaaste/ui/


## Saako minulle tarjota töitä tai minua ostaa?
Jos olet _massikeisari_ tai joku muu _queen of fuc*ing everything_ ja haluat minut kokoelmiisi, niin tee toki tarjous, josta
en voi kieltäytyä. Olen kokopäiväisesti töissä itc-alalla, mutta mikään [Ronaldo](https://www.is.fi/eurosarjat/art-2000005260020.html) tai sankarikoodari en ole. Fuula-sedän tavoitat mm osoitteesta fuula@generalfailure.net

Minua voi myös ostaa kappale- tai kilotavarana. Rohkeasti vaan sähköpostia, niin siirrän sinut juttelemaan nykyisen työnantajani myynnin kanssa, joka lopulta päättää paljonko Fuula-setää saa sillä määrällä kultaa, mikä sinulla on käytettävissäsi.

