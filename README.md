web - Chmura + Strona WWW
===
## Użyte technologie:
- Baza danych - MySQL
- Silnik - PHP + JavaScript (Framework jQuery)
- Widoki - HTML + CSS
- Przechowywanie statycznych danych - XML

## Struktura plików:
- common/ - katalog ze wszystkim co potrzebne
	- css/ - katalog zawierający pliki css - arkusze stylów
	- data/ - katalog tymczasowy - pomocniczy (nieistotny)
	- js/ - katalog ze skryptami JavaScripy (jQuery)
		- adapters/, modules/, themes/ - katalogi od biblioteki highcharts
		- highcharts.js, highcharts.src.js, highcharts-all.js, highcharts-more.js, highcharts-more.src.js - biblioteka highcharts
		- analis.js - analiza sygnał
		- jquery.min.js - framework jQuery
		- script.js - silnik - obsługa zdarzeń ze strony
	- php/ - katalog ze skryptami PHP
		- analiz.php - analiza sygnału
		- content.php - ładowanie zawartości strony - tekstów statyczne
		- database.php - obsługa bazy danych
		- read.php - wczytywanie danych z plików
		- write.php - zapis danych do plików
	- pics/ - katalog z obrazami
	- xml/ - katalog z plikami XML
		- connect.xml - dane do połączenia z bazą danych
		- content.xml - dane (teksty) statyczne
- data/ - katalog z sygnałami użytkowników
	- uXXXXXX/ - katalog z danymi użytkownika o id XXXXXX
- index.html - strona główna
