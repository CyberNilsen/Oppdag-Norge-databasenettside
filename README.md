Hei! Her har jeg laget en nettside med php, html, css og javascript. Jeg har også brukt en env fil i dette tilfelle og hvis du har lyst å få nettside her til å kjøre så må du lage en env fil med api key fra sendgrid. Du må også legge env filen utenfor htmlogphp mappen eller i mappen Databasenettside.  Her er oppsettet du må bruke hvis du vil prøve nettsiden og bruke env fil sånn som meg: 

SENDGRID_API_KEY=(Din api nøkkel)
SMTP_HOST=smtp.sendgrid.net
SMTP_PORT=587
SMTP_FROM_EMAIL=(eposten som ble brukt til sendgrid)
SMTP_FROM_NAME=Oppdag-Norge

DB_SERVER=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_NAME=oppdagnorge

Deretter må du legge hele mappen Oppdag_Norge_databasenettside i htdocs i XAMPP. Så må du kjøre MySQL og Apache server deretter lage en ny tabell i MySQL med innholdet her:

	1	id Primary	int(6)		UNSIGNED	No	None		AUTO_INCREMENT		
	2	email	varchar(100)	utf8mb4_general_ci		No	None				
	3	password	varchar(255)	utf8mb4_general_ci		No	None			
	4	two_fa_enabled	tinyint(1)			Yes	1			
	5	two_fa_code	varchar(100)	utf8mb4_general_ci		Yes	NULL			
	6	reg_date	timestamp			No	current_timestamp()		ON UPDATE CURRENT_TIMESTAMP()	
	7	name	varchar(255)	utf8mb4_general_ci		No	None			
	8	two_fa_method	varchar(50)	utf8mb4_general_ci		Yes	email			
	9	two_fa_expiry	bigint(20)			Yes	NULL			

 Så skriver du inn urlen: http://localhost/Oppdag-Norge-databasenettside/Oppdag_Norge_databasenettside/index.php så skal nettsiden være oppe og kjøre. Hvis du mangler noen libraries kan det hende at du må installere det selv og sette det opp.
