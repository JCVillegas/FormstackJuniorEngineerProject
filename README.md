# FormstackJuniorEngineerProject

## Introduction
Framework application assignment that  creates, reads, updates, downloads and exports csv created documents.

## Application files 
-__index.php__ <br />
Works as a router, recieves a GET operation, if it exists it calls the controller and sends the received operation, 
<br />if none is received it calls the __readDocuments()__ method to display a list of documents.<br>index.php requires the following classes / files :

1.__DatabaseConfig__ <br />
Database configuration file.


2.__DatabaseConnection__ <br />
 Contains the database PDO objetcts. 


3.__ControllerDocument__ <br /> 
Controls all the models and views for the operations.
Available controller methods:<br />
  *confirmDeleteDocument()<br />
  *createDocument()<br />
  *deleteDocument()<br />
  *exportDocument()<br />
  *exportDocumentDropBox()<br />
  *getToken()<br />
  *readDocuments()<br />
  *saveDocument()<br />
  *updateDocument()<br />

4.__ModelDocument__ <br /> 
Manages the application logic.
Available model methods:<br />
  *deleteDocument()<br />
  *getAllDocuments()<br />
  *getDocument()<br />
  *saveDocument()<br />
  *saveDocumentUrl()<br />
  *updateExportDocument()<br />  
  
5.__ViewDocumentDelete__<br />
One method that shows a delete confirmation in html.

6.__ViewDocumentEdit__<br />
One method that shows a create or update form in html.

7.__ViewDocumentFooter__<br />
One method that shows an html footer.

8.__ViewDocumentHeader__<br />
One method that shows an html header.

9.__ViewDocumentList__<br />
One method that shows a list of documents if they exist in html.

10.__ViewDocumentMessage__<br />
One method that shows a custom message in html.

11.__ViewDocumentExport__<br />
Methods that show options to download and export a created csv.<br /> 
Available methods:<br />
 *exportDocument()<br />
 *exportDocumentDropBox()<br />
 *showToken()<br />
 *showUrl()<br />
 *uploadExportFile()<br />
 *getDropBoxUrl()<br />


##How to use
With a web server running open a web browser and
and make use of the CRUD operations:

*__CREATE__   - http://localhost/?operation=createDocument<br />
*__READ__     - http://localhost/?operation=readDocuments<br />
*__UPDATE__   - http://localhost/?operation=updateDocument&id=[documentId]<br />
*__DELETE__   - http://localhost/?operation=confirmDeleteDocument&id=[documentIdId]<br />
*__DOWNLOAD__ - http://localhost/?operation=exportDocument&id=[documentIdId]<br />
*__EXPORT__   - http://localhost/?operation=exportDocumentDropBox&id=[documentIdId]<br />


## Notes
Developed in XAMPP VM envrionment using PHP 7.2.7 and MariaDB 10.1. <br />
1 The web application allows to store a set of key/value pairs and a document name in the db.<br />
2 It also stores metadata (date of document created, exported, updated)<br />
3 Lists all files with metadata fields.<br />
4 Updates key/ value pairs for existing document.<br />
5 When updating the document it updates last modification date.<br />
6 Allows to delete document.<br />
7 Allows to export stored data as a csv comma separated file.<br />

BONUS<br />
2 Exports stored data (csv file) to Dropbox<br />
3 Retrieves public URL from Dropbox.<br />

To test the bonus part I decided to share a temporary API key to my Dropbox account.<br />
But any key could be used.<br />





