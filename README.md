# multitenant-yii2-application
Just an example of implementation of multi-tenant application that connect shared database.
This example shows how to use one yii2 codebase to deliver many websites with shared database, where each multi-tenant table has a 'tenant' column that defines application that inserted it.

Layout solution:
In this example I am using Yii2 themes and special web-hostname based pattern to build a correct routing to them.
As you can see, in folder ```\frontend``` there are two folders called tenant1 and tenant2 - these are the folders themes are using layout and views from.
In ```\config\hosts.php``` global constant CURRENT_TENANT is set there. It is used as the part of laoyout routes.

DB solution:
You can find all the logic in the folder ```\components```. Basically, ```MultiActiveQuery``` and ```MultiActiveRecord``` classes are responsible for modification sql queries produced by application, by applying correct tenant field.

