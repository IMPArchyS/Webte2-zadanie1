# WEBTE2 Zadanie č.1 LS 2023/2024
## 1. Všeobecné pokyny 
- Zadania by mali byť optimalizované pre posledné verzie Google Chrome a Firefox
- Zadania sa odovzdávajú vždy do polnoci dňa, ktorý predchádza cvičeniu.
- Neskoré odovzdanie zadania sa trestá znížením počtu bodov.
- Pre každé zadanie si vytvorte novú databázu (t.j. nemiešajte spolu databázy z rôznych úloh, pokiaľ to v úlohe nebude vyslovene uvedené).
- Je potrebné odovzdať:<br>
  (1) zazipované zadanie aj s nadradeným adresárom (t.j. nie 7zip, RAR, ani žiadny iný formát). Názov ZIP archívu musí byť v tvare idStudenta_priezvisko_z3.zip. Úvodný skript nazvite index.php. Konfiguračný súbor nazvite config.php a umiestnite ho do rovnakého adresára, v ktorom máte umiestnený index.php. Do poznámky priložte linku na funkčné zadanie umiestnené na vašom pridelenom serveri 147.175.105.XX (nodeXX.webte.fei.stuba.sk).<br>
Príklad štruktúry odovzdaného zadania:<br>
12345_mrkvicka_z2.zip:<br>
12345_mrkvicka_z2/<br>
index.php<br>
config.php<br>
12345_mrkvicka_z2.sql (v prípade práce s databázou)<br>
12345_mrkvicka_z2.doc (len v prípade odovzdanej technickej správy)<br>
(2) súbor docker compose<br>
(3) technickú správu<br>
(4) adresu umiestnenia na školskom serveri (uvedte do poznámky v MS Teams).<br>
- V prípade zistenia plagiátorstva je treba počítať s následkami.
## 2. Zadanie cvičenia:
Vytvorte webovú stránku, ktorá bude poskytovať prehľad o nositeľoch Nobelových cien v slovenčine (bude sa kontrolovať správne zobrazená diakritika). Stránka bude mať svoju verejnú aj privátnu zónu, do ktorej sa bude treba prihlásiť.

1. Na základe poskytnutého súboru si navrhnite databázu s minimálne troma tabuľkami, ktoré navzájom prepojíte pomocou cudzích kľúčov a nastavíte správne obmedzenia pre modifikáciu údajov a mazanie záznamov. Dbajte na správne kódovanie, aby sa vám zobrazila adekvátna diakritika. Vypracovanie zadania len na základe jednej tabuľky bude mať za následok strhnutie bodov, nakoľko to nezodpovedá správnemu návrhu databázy a na predmete sa snažíme budovať správne návyky.
2. Na webovej stránke zobrazte tabuľku nositeľov Nobelových cien spolu s:
   - rokom, kedy ocenenie získali,
   - uvedením krajiny, ktorú reprezentovali,
   - kategóriou, za ktorú ocenenie získali.
Nad tabuľkou zobrazte dve rozbaľovacie položky (comboboxy), ktoré umožnia filtrovať položky v tabuľke podľa roku a kategórie. V prípade, že bude nastavený filter na niektorú z týchto položiek, daný stĺpec (rok alebo/a kategória) nebude v tabuľke zobrazený. Záhlavia stĺpcov "Priezvisko", "Rok", "Kategória" budú "klikateľné" (ak budú v tabuľke zobrazené). Po kliknutí na ne, sa stĺpec zoradí podľa veľkosti alebo podľa abecedy.
3. V prípade, že sa v zobrazenej tabuľke klikne na meno niektorého nositeľa Nobelovej ceny, otvorí sa nová stránka so zobrazením detailu danej osoby so všetkými jej údajmi, ktoré boli poskytnuté v priloženom súbore. Z tejto stránky bude umožnené vrátiť sa naspäť na zoznam nositeľov Nobelových cien. Doporučujeme stránku preto urobiť s vlastným menu, aby bola navigácia jednoduchá. Taktiež je potrebné urobiť stránkovanie, napr. 10 alebo 20 záznamov na stránku a myslite aj na možnosť zobraziť všetky záznamy (funkcionalitu je možné vytvoriť napr. pomocou knižnice DataTables, Tabulator, Handsontable, ...).
   <br><strong>BONUS</strong> - V prípade, že urobíte stránkovanie v tabuľkach tak, že bude fungovať na back-ende, a nie iba pomocou DataTables (inej knižnice, frameworku) a zároveň bude fungovať aj triedenie naprieč celou tabuľkou (t.j. nie iba na aktuálne zobrazenej stránke), môžete získať bonusový bod.

4. Do privátnej zóny sa užívateľ bude môcť prihlásiť podľa svojho výberu jednou z dvoch možností:
   - pomocou vlastnej registrácie (kvôli tomuto bodu je potrebné sprístupniť užívateľovi registračný formulár, pri ktorom si zadá meno, priezvisko, email (login) a heslo, pričom tieto údaje sa budú ukladať do databázy).
   - pomocou konta na Google. V prípade registrácie pomocou Google e-mailu presmerujte používateľa na Google prihlásenie.

5. Pri vlastnej registrácii použite 2FA a heslo do databázy ukladajte zašifrované adekvátnym šifrovacím algoritmom. Zároveň vhodne zvoľte pre používateľa jedinečný identifikátor napr. e-mail. Keďže ukladáte osobné informácie používateľa, je potrebné pri prvej návšteve stránky informovať používateľa o ukladaní cookies.

6. Po prihlásení sa do aplikácie zobrazte užívateľovi informáciu, kto je prihlásený a vhodnú uvítaciu správu. Informácia o prihlásenom užívateľovi musí zostať stále zobrazená. Nezabudnite zabezpečiť aj odhlásenie užívateľa z aplikácie. Neprihlásený používateľ nesmie mať prístup na podstránky pre prihláseného používateľa/administrátora. V prípade, že sa sem pokúsi dostať a nemá oprávnenia, presmerujte ho na stránku s prihlásením.

7. Prihlásenému užívateľovi umožnite:
   - pridávať nového nositeľa Nobelovej ceny aj spolu so všetkými dostupnými informáciami. Ošetrite, aby nebolo možné pridať osobu, ktorá už v databáze existuje (jedna osoba môže dostať aj viacero ocenení). To, či tieto údaje budete robiť pomocou jedného alebo viacerých formulárov je na vás.
   - modifikovať ľubovoľné údaje o osobe v databáze (pri modifikácii údajov položky formulára predvyplňte hodnotami z databázy),
   - vymazávať osoby z databázy (spolu s vymazaním osoby sa vymažú všetky informácie, ktoré sa viažu k tejto osobe).
8. Pri registrácii, prihlasovaní, vkladaní a úprave záznamov nezabudnite na validáciu vstupov. Potrebná je front-end aj back-end validácia.

9. Po úprave záznamu nezabudnite informovať používateľa o tom, či sa to podarilo, vhodným typom upozornenia (ideálne formou nejakého toastu, ...). <strong>Nepoužívajte JS metódu alert()</strong>, jej použitie bude penalizované.

10. Vzhľad aplikácie naprogramujte použitím kaskádnych štýlov, urobte ju responzívnu a snažte sa dbať aj na jej vonkajší dojem.
Pre vizuál stránky odporúčame použiť CSS (napr. Bootstrap) a JavaScript knižnice (napr. Datatables) alebo grafické šablóny, pričom dodržte licencie na použitie. Povolené je tiež použitie JS frameworku Vue 3 <strong>(nepoužívajte Vue 2)</strong>, pričom je vhodné použiť aj nejaké nadstavby (napr. Vuetify).
