# ThreeIre Assignment | PHP Laravel| Docker
<hr>

##  Prerequisites

- Laravel: Version 12.x
- Livewire 3.x
- MySQL: Version 8.1
- Node: Version 24.9.0

## Installation
1. ### Clone the repository:
    ```bash
    git clone https://github.com/ayushmohanan/three-ireland.git
    ```
2. ### Application structure

    ```
    .
    ├── backend             (Laravel 12.x)
    │   ├── app
    |   |   ├── Events
    |   |   |
    │   │   ├── Http
    |   |   |      └── Controllers    
    |   |   |                └── Api
    |   |   |                     └── v1
    |   |   ├── Jobs
    │   │   └── Models
    |   |   └── Livewire
    |   |   |      └── Admin    
    |   |   |      └── Auth
    |   |   |                     
    |   |   ├── Models
    |   |   |── Providers
    |   |   
    |   ├── Database
    |   |
    |   ├── Resources
    |
    │   ├── database
    │   │   └── migrations
    |   ├── resources
    │   ├── routes
    │   ├── tests
    │   └── storage
    |        └── logs
    |
   
    ```

3. ### In the backend folder rename env.example to .env
   
    ```bash
    
       mv .env.example .env
    
    ```  

4. ### Install the necessary dependencies by running

    ```bash
    
       cd laravel-app
       composer install
    
    ```

   Install Npm modules 
   
    ```bash
    
      npm install
    
   ```
 
4. ### Build the project by running:
    ```bash
      docker-compose up -d --build

5. ### Migrate DB using the following code
     ```bash

     docker-compose exec app php artisan migrate
     docker-compose exec app php artisan db:seed
     
     ```
6. ### Create Super-Admin User to login 
     ```bash

    docker-compose exec app php artisan app:create-super-admin
     
     ```

7. ### Run the project

    1. - the project is by open at  [http://localhost:9000/]

8. ### API curl Collections

    laravel-app/postman_collection.json

9. ### Setup local Environment Backend

   ```bash
   
       .env update mysql details
   
       php artisan migrate #Migrate the database
       php artisan db:seed #seed the dummy values to db
       php artisan serve #run the backend    
    ---
10. ### Testing Rate Throttling in API

    ```bash
       
    curl -i -H "Authorization: Bearer <your_token>" http://localhost:9000/api/v1/products

    ```





