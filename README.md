<!-- Artisan Command -->
# Laravel Reguler Uses Artisan Command

```bash
    php artisan make:model Products -mcs or -a

    //After View Folder Name the .make blade Name
    php artisan make:view DashboardContain.dashboard
    Only Migration-----
    php artisan make:migration TableName->php artisan migrate
    php artisan make:Model ModelName
    php artisan make:Controller UserConroller

    Php artisan Ser---
    Composer Run Dev
    php artisan make:model ______Model Name -a
    make all type of  model,controlle,seeder,faker,migration
    
    only Migration --
    $ php artisan make:migration create_supplier_transaction
    
    model,migration,controller,seeder -all are create at a command
    php artisan make:model ______Model Name -mcrs
    example - php artisan make:model Srahman -mrcsf
    
   

  make:view                 Create a new view
  make:controller           Create a new controller class
  make:seeder               Create a new seeder class
  make:request              Create a new form request class
  make:migration            Create a new migration file
  make:model                Create a new Eloquent model class
  make:factory              Create a new model factory
  make:resource             Create a new resource
  make:middleware           Create a new HTTP middleware class

```

## Database Artisan command

```bash
 delete-Datase->
    php artisan db:wipe
    
    and Same
    php artisan migrate:rollback

    Migration rollback
    php artisan migrate:rollback

    php artisan migrate:refresh --seed or 
    php artisan migrate:fresh --seed

    php artisan db:seed

```

### API Arrtisan Command

```bash
php artisan install:api 
API check in PostMan
url/api/route_name/
```

# php artisan Command

```bash
  make:cache-table           [cache:table]
 Create a migration for the cache database table
  make:cast                 Create a new custom Eloquent cast class
  make:channel              Create a new channel class
  make:class                Create a new class
  make:command              Create a new Artisan command
  make:component            Create a new view component class
  make:config               [config:make] Create a new configuration file
  make:enum                 Create a new enum
  make:event                Create a new event class
  make:exception            Create a new custom exception class
  make:interface            Create a new interface
  make:job                  Create a new job class
  make:job-middleware       Create a new job middleware class
  make:listener             Create a new event listener class
  make:mail                 Create a new email class
  make:notification         Create a new notification class
  make:notifications-table  [notifications:table] Create a migration for the notifications table
  make:observer             Create a new observer class
  make:policy               Create a new policy class
  make:provider             Create a new service provider class
  make:queue-batches-table  [queue:batches-table] Create a migration for the batches database table
  make:queue-failed-table   [queue:failed-table] Create a migration for the failed queue jobs database table
  make:queue-table          [queue:table] Create a migration for the queue jobs database table
  make:rule                 Create a new validation rule
  make:scope                Create a new scope class
  make:session-table        [session:table] Create a migration for the session database table
  make:test                 Create a new test class
  make:trait                Create a new trait
```
