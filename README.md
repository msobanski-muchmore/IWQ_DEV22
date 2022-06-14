Aby uruchomić projet w dockerze należy przejść do katalogu .docker_iwq
i wykonać polecenie docker-compose up.
Następnie należy w bazie danych wykonać import sql'ki załączonej do projektu, 
która znajduje się w katalogu sql. 
(
 host: localhost, port: 3306, user: root, hasło pass, baza iwq
)
Następnie w terminalu dockera należy wejść do konsoli i zainstalować vendory 
poleceniem composer install (z poziomu katalogu projektu)

Środowisko w docker to php w wesji 8.0 i taka jest zalecana do uruchomienia tego projektu.

Przygotowane zostały testy jednostkowe sprawdzające poprawność działania klas 
odpowiadającuych za walidację i parsowanie numeru pesel.
Aby uruchomić test należy wywołać polecenie w terminalu 
var/bin/phpunit tests/PeselTest.php (z poziomu katalogu projektu)

W projekcie zainstalowano ORM Doctrine z encją opisywaną po przez adnotacje.

Jeśli poprawnie uruchomiono dockera to aplikację można uruchomić w przeglądarce pod  
adresem http://localhost

