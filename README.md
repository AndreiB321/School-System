Proba backend - Laravel

Pentru rulare, in terminal se apeleaza:
php artisan serve
php artisan migrate
Pentru testare se apeleaza endpoint-urile 

Pentru fiecare entitate am creat un tabel si un controller.
Fiecare endpoint este implementat in controller, fiind apelata metoda din
api.php. In cazul in care pentru unele endpoiteri este necesara autentificarea,
metodele sunt apelate dintr-un middleware.
In fiecare metoda se verifica body-ul requestului/id-ul user-ului autentificat/
rolul user-ului, iar in cazul in care nu exista erori, se executa cererea.

