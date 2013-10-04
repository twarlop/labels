etiketten
=========

Etiketten module van shoponsite

##dependencies:

jquery: this was developped using

jquery.1.8.3

jquery-ui.1.9.2 custom library from handelaars2 admin. 

__important__ Do not forget to include the images and ui.css file that belongs to this custom library

##installation instructions:

edit: 4 oktober 2013
FOR SOME REASON FILES ARE NOT AUTO INCLUDED FROM VENDOR DIR...

run composer install/update

at the moment, the css and js files are included from the vendor dir, so no need to copy. Except for the ajax and sos_tools file

copy the css dir to the appropriate css dir

copy the js dir to the appropriate js dir

copy the ajax dir to the appropriate ajax dir

copy the sos_tools file to the appropriate dir

make sure you have a wrapping page (read: controller-file) and include presentation/index.php in it between the html-top and html-bottom

use the index.php in the root of the plugin to test it. this file uses a custom header and bottom
