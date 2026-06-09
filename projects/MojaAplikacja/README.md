# MojaAplikacja

Profesjonalna aplikacja Symfony 7 służąca jako przykład przejścia z własnego mini-frameworka (`semestrApp`) na pełny framework.

## Aktualny stan projektu

- Routing oparty na atrybutach `#[Route]`
- `BlogController` z dwoma akcjami (`index` + `show`)
- Wyszukiwarka wpisów (formularz GET)
- System komentarzy (zapisane w sesji – bez bazy danych)
- Walidacja formularzy z komunikatami po polsku
- Twig + proste style inline w `base.html.twig`

**Uwaga:** Wpisy są na razie wpisane na sztywno w kontrolerze (tablica `POSTS`). To prosty i czytelny sposób na początek. Komentarze przechowywane są w sesji użytkownika.

## Struktura

```
src/
├── Controller/
│   └── BlogController.php
├── Form/
│   ├── PostSearchType.php
│   └── PostCommentType.php
├── Service/
│   └── PostService.php          # tymczasowe źródło danych
└── Kernel.php

templates/
├── base.html.twig
└── blog/
    ├── index.html.twig
    └── show.html.twig
```

## Uruchamianie lokalnie (bez Dockera)

```bash
cd projects/MojaAplikacja

composer install

# Wygeneruj sekret jeśli pusty w .env
php bin/console secrets:generate-keys

php -S 127.0.0.1:8092 -t public public/index.php
```

Adresy:
- http://127.0.0.1:8092/
- http://127.0.0.1:8092/post/pierwszy-wpis

## Uruchamianie przez Docker (Symfony style)

W katalogu projektu znajduje się `compose.yaml` (PostgreSQL). Aby go użyć:

```bash
docker compose up -d

# Wejdź do kontenera PHP
docker compose exec php bash

composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

**Uwaga na rozbieżność bazy danych:**

- `compose.yaml` + `compose.override.yaml` → PostgreSQL
- `.env` → MariaDB (`DATABASE_URL=mysql://...`)

W obecnym etapie projektu (dane in-memory) nie ma to znaczenia. Gdy przejdziesz na Doctrine, wybierz jedną spójną konfigurację.

## Przydatne komendy

```bash
php bin/console debug:router
php bin/console debug:container
php bin/console cache:clear
php bin/console make:entity          # gdy będziesz gotowy na Doctrine
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Plan rozwoju (prosty, studencki)

1. [ ] Stworzenie encji `Post` + migracji (gdy będzie baza)
2. [ ] Przeniesienie komentarzy do bazy danych
3. [ ] Dodanie paginacji
4. [ ] Dodanie testów
5. [ ] Wyciągnięcie CSS do osobnego pliku (opcjonalnie)

Na razie wszystko jest w jednym kontrolerze – prosto i czytelnie.

## Różnice względem semestrApp (proste porównanie)

| Cecha              | semestrApp (własny mini-framework) | MojaAplikacja (Symfony)       |
|--------------------|------------------------------------|-------------------------------|
| Routing            | Własna klasa Router                | Atrybuty `#[Route]`           |
| Kontrolery         | Invokable + własny interfejs       | AbstractController            |
| Formularze         | Brak                               | Pełny system Form + walidacja |
| Szablony           | Zwykłe pliki PHP                   | Twig                          |
| Baza danych        | Brak                               | Doctrine gotowe               |

Na razie w MojaAplikacji wszystko jest bardzo prosto – dane w tablicy w kontrolerze.

## Licencja

Prywatny projekt edukacyjny.
# phpSemestrApp
