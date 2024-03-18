## Instalacja
Przed uruchomieniem aplikacji należy sprawdzić pliki konfiguracji w folderze `config`.
Należy skonfigurować serwer HTTP aby katalog `public` był katalogiem głównym,
następnie w przeglądarce trzeba wejść na `[ADRES_SERWERA][PRZEDROSTEK_URL]/install/`, aby
dokończyć proces instalacji. Stworzy to potrzebne tabele w bazie danych.

### Uwaga!
Ta aplikacja zakłada że adresy URL są zakończone przez '/' (jeżeli wskazują one na folder)

## Struktura
Folder `public` zawiera pliki które powinny być dostępne przez klienta

#### config
Folder zawierający konfigurację aplikacji
- config/app.php - Ogólna konfiguracja aplikacji
- config/database.php - Konfiguracja połączenia z bazą danych

#### controller
Folder zawiera klasy obsługujące zapytania API
- controller/admin_api.php - Metody API wymagające uprawnień administracyjnych
- controller/public_api.php - Metody API dostępne dla wszystkich uźytkowników

#### database
Folder zawierający klasy pomocnicze dostępu do bazy danych
- database/base.php - Klasa pomocnicza dostępu do bazy danych
- database/user.php - Klasa pomagająca w zarządzaniu użytkownikami

#### public
Folder widziany publicznie przez klientów aplikacji

#### utility
- utility/config.php - Plik importujący konfigurację aplikacji
- utility/endpoint.php - Tworzy adres dostępu do API
- utility/head.php - Plik zawierający większość zawartości sekcji `<head>`
- utility/include.php - Plik importujący aplikację
- utility/nav.php - Plik tworzący pasek nawigacji
- utility/response.php - Klasa pomocnicza do generowania odpowiedzi HTTP

