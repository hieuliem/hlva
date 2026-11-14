Instructions to run the Demonstration of a hand coded comment and reply system.

For an instant demonstration see: http://www.jgdprojects.co.uk/democomments.htm (though due to the spam that inevitably ends up being sent to this pages input form this may be discontinued).

Required: A webserver running MySQL with a database to which you have a username and password and can create a new read/write table in.

This webserver also needs PHP enabled. If you want to add a comment and reply section to pages ending in .htm or .html you will also need to enable the PHP processor to run on files of that type. If this is not the case, you can change the extension of the supplied file: dynamiccontents.htm to .php . However not enabling the PHP processor for .htm or .html does mean any future web pages you use the comment and reply system on must use the .php extension.

Stage 1: Setup the MySQL database

This demonstration uses the main webserver directory for all files apart from one, containing passwords, which (so it can be kept hidden) requires a subdirectory.

Create a sub directory of your main webserver directory called: '/democomments

Open the supplied file called dbconnect.php in an editor. In this dbconnect.php file add the MySQL 'hostname', 'username', 'password' and 'databasename' for your database so that it looks similar to that shown below.

<?php
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'username');
    define('DB_PSWD', 'password');
    define('DB_NAME', 'databasename');
        
?>

Save your modified dbconnect.php file and upload it the newly created '/democomments sub-directory on your webserver.

Stage 2: Create the required table

Upload democommentssetup.php to the main directory on the webserver.

Call demmocomments.php from your browser by opening the page in your browser, e.g. Replace <yoursite> with your site name and open:

 http://www.<yoursite>/democommentssetup.php 

This page will use the dbconnect.php file in the democomments sub-directory to access your database and create the MySQL table needed.
On opening democomment.php the browser should create the table and show text as follows:

	Creation of database table used for retrieving comments and replies
	
	This file creates the database table used to store comments and 	replies, but will not delete an existing table. 
	Existing table not found. 
	Table ready for use.
	

Stage 3: Upload files

If your webserver does not support PHP processing on .htm files change the extension of democomment.htm (if is does it can be left as it is). Upload the rest of the supplied files:

		democomments_add.php
		democomments_class_comment.php
		demodisplaycomments.php
		democommentstyle.css
		democomments.htm
		democommentsform.htm
		jgdhandlers.js


Open the page democomment.htm page (or democomment2.php) in your browser and click the 'NewComment' link. This will display the hidden form which can be completed and the 'Submit' button clicked to send the comment to the database.

Once the comment is sent, the form is hidden again and that comment is returned from the database call and displayed below the 'NewComment' link. This comment will include a 'Reply' link which operates in the same manner as just described except that when a reply includes an extra data field which contains the Id of the comment it's a reply to.

		
Democomments.htm uses the files already uploaded and shows how only a unuque identifier and a few 'include' lines arequired to add the comment and reply system to a web page of your own. Though remember to give it a new ID number or the same comments will show on any  pages with that ID.
		

A fuller description of the coding and operation, and any updates, can be found at:

http://www.jgdprojects.co.uk/codingcomments.htm
http://www.jgdprojects.co.uk/usercomments.htm


Regards, Jonathan
		
