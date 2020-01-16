# PHP Url Finder

With this Application, a URL of your choice will be searched for all links on the page.
This links will be splittet in internal and external links.

With the listed links it is possible to do the whole search again by clicking on them.

The results are being saved in a database, so if a URL, that has already been searched for, is entered again, the results will be read from the database instead of searching the site again.

## Functionallity

As for now I have programmed this only on my local machine so there is no live-demo.
If you want to test it out, you have to download the project and put your database-credentials in the file "const.php" in the Folder php->constants.

#### Backend:

I've used a DAO-Pattern to manage the database-stuff.
