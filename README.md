# cwPHP

Repozytorium zawiera:

- aktualne projekty robione na zajeciach
- archiwalne materialy z kolejnych terminow w katalogu `PlikiZajecia`
- przyklady Docker + PHP
- nowy przyklad aplikacji Symfony

## Struktura repo

### `projects/semestrApp`

Wlasny mini-framework / mini-aplikacja MVC pisana w czystym PHP.

Sa tu m.in.:

- front controller w `public/index.php`
- `Application`, `Router`, `Request`, `Layout`
- prosty `ServiceContainer`
- kontrolery
- `Response` i `JsonResponse`

To jest rozwinięcie cwiczen z zajec z marca i kwietnia.

### `projects/inf-backend`

Bardzo prosty przyklad PHP OOP w jednym pliku.
Projekt demonstracyjny, bez rozbudowanego routingu i bez widokow.

### `projects/MojaAplikacja`

Profesjonalna aplikacja Symfony 7 (aktualnie rozwijany projekt).

Przykład przejścia z własnego mini-frameworka (`semestrApp`) na pełny ekosystem Symfony.

Obecnie zawiera:

- routing przez atrybuty `#[Route]`
- `BlogController` z wyszukiwarką i systemem komentarzy (sesja)
- Formularze + walidacja (polskie komunikaty)
- `PostService` (in-memory) – przygotowanie pod Doctrine
- Pełna struktura Symfony 7.4 (Twig, Form, Validator, Doctrine gotowe)

Szczegóły i instrukcje uruchomienia: [projects/MojaAplikacja/README.md](projects/MojaAplikacja/README.md)

### `PlikiZajecia`

Archiwum materialow z zajec.

Najwazniejsze etapy:

- `17.03.2026 - App Intro` - poczatkowa aplikacja z prostym wyborem strony
- `24.03.2026` - pierwsze podejscie do routingu po URL
- `31.03.2026` - dodanie `ServiceContainer`
- `14.04.2026` - wejscie w `Request`, kontrolery i odpowiedzi
- `21.04.2026` - bardziej kompletna wersja mini-MVC z `PageController`, `JsonResponse` i obsluga bledow
- `Cw_JS_Wolny` - osobne cwiczenia z JavaScriptu: DOM, fetch, Promise, async/await, moduly ES6, mini-aplikacje
- `docker-php-*` - materialy do prostego PHP w Dockerze
- `docker-symfony-*` - wersja dockerowa bardziej przygotowana pod Symfony

## Uruchamianie

### 1. `semestrApp` lokalnie

```bash
cd /home/bartek/Dokumenty/cwPHP-main/projects/semestrApp
composer install
php -S 127.0.0.1:8090 -t public public/index.php
```

Adresy:

- <http://127.0.0.1:8090/>
- <http://127.0.0.1:8090/about>
- <http://127.0.0.1:8090/articles/6>
- <http://127.0.0.1:8090/dashboard>

### 2. `inf-backend` lokalnie

```bash
cd /home/bartek/Dokumenty/cwPHP-main/projects/inf-backend
php -S 127.0.0.1:8091 -t public public/index.php
```

Adres:

- <http://127.0.0.1:8091/>

### 3. `symfonyApp` lokalnie

```bash
cd /home/bartek/Dokumenty/cwPHP-main/projects/symfonyApp
php -S 127.0.0.1:8092 -t public public/index.php
```

Adresy:

- <http://127.0.0.1:8092/>
- <http://127.0.0.1:8092/api/demo/Bartek>

Przydatne komendy:

```bash
php bin/console about
php bin/console debug:router
```

### 4. Glowny Docker z repo

Z katalogu glownego:

```bash
cd /home/bartek/Dokumenty/cwPHP-main
docker compose up --build
```

Ta konfiguracja wystawia:

- `inf-backend`
- `semestrApp`

Konfiguracja Apache uzywa hostow:

- `inf.local`
- `semestr.local`

W razie potrzeby dodaj do `/etc/hosts`:

```text
127.0.0.1 inf.local semestr.local
```

Przykladowe adresy:

- <http://inf.local:8095>
- <http://semestr.local:8095>

Uwaga:
glowny Docker z repo nie jest jeszcze podlaczony pod `projects/symfonyApp`.

## Symfony - uwaga o srodowisku

Na tej maszynie lokalne PHP 8.3 nie ma rozszerzenia XML/DOM.
Z tego powodu instalacja Symfony wymagala obejscia Composera podczas przygotowania projektu.

Zeby miec czystsze lokalne srodowisko dla Symfony, doinstaluj:

```bash
sudo apt install php8.3-xml
```

Potem w katalogu `projects/symfonyApp` warto wykonac:

```bash
composer install
```

## Co jest aktualne, a co nie

- `projects/semestrApp` jest zgodny z kierunkiem rozwoju z materialow z zajec
- `projects/symfonyApp` jest nowym dodatkiem do nauki Symfony
- glowny `docker-compose.yml` dziala, ale jest starszy niz materialy `docker-symfony-*`
- `README.md` opisuje teraz zarowno aktualne projekty, jak i materialy z zajec
