# multitenant-yii2-application
##How this applies to your application?
Just an example of implementation of multi-tenant application that connect shared database.
This example shows how to use one yii2 codebase to deliver many websites with shared database, where each multi-tenant table has a 'tenant' column that defines application that inserted it.
You can use this solution in cases where you need a network of small websites that has to store their data in one shared
database in order to allow application-1 to access data from application-2 and application-3 very quick 
and you don't want or can't develop separate data access layer (not enough time or budget).
#####Example: 
You are making a network of landing pages for candy shop that is going be opened shortly. You want to use different 
layouts to try out best web-marketing campaign. On each landing page you are gathering your visitors emails in order to
send newsletters after your shop is opened.
On site1 you are highlighting gummy bears as your unique product with the best quality ever. In newsletters from that 
site1 you will be using same marketing campaign.
On site2 your main point is chocolate, so you want to adjust newsletter layout accordingly.

But what happens if the same user has subscribed on both websites? This is how rows in DB would look like:
```
tbl newsletter_targets:
--------------------------------------------
ID    email           tenant_id   created_at
1     john@doe.com    1           05-08-2017 
2     john@doe.com    2           06-08-2017
3     jim@back.com    1           06-08-2017
--------------------------------------------
```
You don't want to send a newsletter to john@doe.com twice, right? Moreover, you want to send a fancy newsletter to him,
the one that includes both, 'gummy bears' and 'chocolate' blocks.

##Layout solution:
In this example I am using Yii2 themes and special web-hostname based pattern to build a correct routing to them.
As you can see, in folder `\frontend` there are two folders called `\frontend\tenant1` and `\frontend\tenant2` - these 
are the folders themes are using layout and views from. In `\config\hosts.php` global constant CURRENT_TENANT is set
there. It is used as the part of layout routes.

##DB solution explained:
You can find all the logic in the folder `\components`. Basically, `\components\MultiActiveQuery` and
`\components\MultiActiveRecord` classes are responsible for modification sql queries produced by application, by applying correct tenant field.

``
MultiActiveQuery->prepare($builder) 
``

This method is responsible for all the magic going on. It is mixing in `AndWhere(tenant_id = CURRENT_TENANT_ID)` to 
any query that is built for class that extends `MultiActiveRecord`.
You can find other small tricks in those classes that assure all `ActiveRecord` native methods are now using `using tenant_id`
column to operate with data. This makes your coding safe and you don't have to manually manage any tenant identity field in actual application code.

``
MultiActiveQuery->acrossTenants($tenantIds)
``
This method allows making certain request looking against a couple of tenants at the same time. Code snipper for candy 
shop example above(assuming model is called NewsletterTargets and it extends MultiActiveRecord class):
``NewsletterTargets::find()->where('ID > 50')->acrossTenants(1, 2)->all()``

Selects all models from tenants 1 and 2 with ID greater than 50.