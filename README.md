Tasks : 
API 
USER Management
Repository Design Pattern
CRUD Generate
Auth System
API versions
Security
Image Upload
Searchable
Data Optimize


Matrix Data Generate


DEV
composer require laravel/breeze --dev 
php artisan breeze:install api
//Requirement
PHP 8.1 or above
BCMath PHP Extension
Ctype PHP Extension
Exif PHP Extension
JSON PHP Extension
Mbstring PHP Extension
OpenSSL PHP Extension
PDO PHP Extension
Tokenizer PHP Extension
XML PHP Extension
GD Library or ImageMagick
Composer

//Task
user management (roles and permission)
    Role : super-admin, admin, user
    
    Permission	                                Handle
    
    Access the Control Panel	                access cp
    Create, edit, and delete collections	    configure collections
    
    Access site	access {site} site
    View entries	                            view {collection} entries
    ↳ Edit entries	                            edit {collection} entries
    ↳ Create entries	                        create {collection} entries
    ↳ Delete entries	                        delete {collection} entries
    ↳ Publish entries	                        publish {collection} entries
    ↳ Reorder entries	                        reorder {collection} entries
    ↳ Edit other author's entries	            edit other authors {collection} entries
        ↳ Publish other author's entries	        publish other authors {collection} entries
        ↳ Delete other author's entries	            delete other authors {collection} entries
    
    Create, edit, and delete navs	configure navs
    ↳ View nav	                        view {nav} nav
    ↳ Edit nav	                        edit {nav} nav

    Edit global variables	            edit {global} globals
    
    View asset container	            view {container} assets
    ↳ Upload assets	                    upload {container} assets
    ↳ Edit assets	                    edit {container} assets
    ↳ Move assets	                    move {container} assets
    ↳ Rename assets	                    rename {container} assets
    ↳ Delete assets	                    delete {container} assets
    View available                      updates	view updates
    ↳ Perform updates	                perform updates

    View users	                        view users
    ↳ Edit users	                    edit users
    ↳ Create users	                    create users
    ↳ Delete users	                    delete users
    ↳ Change passwords	                change passwords
    ↳ Edit user groups	                edit user groups
    ↳ Edit roles	                    edit roles
    ↳ Impersonate users	                impersonate users

    Configure forms	configure forms
    View form submissions	        view {form} submissions
    ↳ Delete form submissions	    delete {form} submissions
