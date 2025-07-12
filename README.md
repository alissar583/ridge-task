# Laravel Modular API

A modular Laravel application with a clean architecture, built using Laravel 12 and organized into modules for better maintainability and scalability.

## Features

- **Modular Architecture**: Using nwidart/laravel-modules for code organization
- **API Documentation**: Integrated Swagger/OpenAPI via darkaonline/l5-swagger
- **Authentication**: Token-based authentication with Laravel Sanctum
- **Authorization**: Role-based authorization (Admin/Editor/User)
- **Exception Handling**: Custom exception handling in bootstrap/app.php
- **Design Patterns**:
  - **Repository Pattern** – Each entity has its own repository (e.g., `NotificationRepository`) that implements a corresponding interface (e.g., `NotificationRepositoryInterface`). These repositories handle all database operations and are injected into services via their interfaces.
  
  - **Service Layer Pattern** – Services (e.g., `NotificationService`) receive DTOs from controllers, process business logic, and interact with repositories to perform data operations. They also dispatch jobs and broadcast events when needed.
  
  - **Factory Pattern** – Each entity has a dedicated factory class (e.g., `NotificationFactory`) that creates model instances with predefined or random attributes, primarily used in database seeding and testing.
  
  - **Singleton Pattern** – The `NotificationService` is registered as a singleton in `NotificationServiceProvider` using Laravel's service container.
  
  - **DTO Pattern** – Controllers create DTOs (e.g., `CreateNotificationDto`) from validated request data. These DTOs are then passed to services and repositories to standardize data transfer between layers. DTOs are also used when dispatching jobs and broadcasting events.
  
- **Testing**:
  - Unit tests for Post service covering business logic
  - Feature tests for Post API including:
    - Unauthorized user access (401)
    - Role-based access control (403)
    - Successful post creation and database assertion
    - Validation error handling (422)
  - Feature tests for User API:
    - Paginated user list retrieval
    - JSON response structure verification
    - Pagination count check (15 per page)


## Requirements

- PHP 8.2 or higher
- Composer
- MySQL

## Installation

1. Clone the repository:
```bash
   git clone https://github.com/alissar583/ridge-task.git
   cd ridge-task
   ```

2. Install PHP dependencies:
```bash
   composer install
   ```

3. Create environment file and generate app key:
```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ridgetask
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Run migrations:
```bash
   php artisan module:migrate --all
   ```

6. Seed the database (optional):
```bash
   php artisan module:seed --all
   ```

7. Run queue for job:
```bash
   php artisan queue:work
   ```


## Development

Start the development server:
```bash
php artisan serve
```

## Modules

The application is organized into the following modules:

- **User**: User management and authentication
- **Post**: Blog post management
- **Notification**: System notifications

Each module follows a similar structure:
```
Modules/ModuleName/
├── app/
│   ├── DTOs/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   ├── Repositories/
│   │   ├── Contracts/
│   │   └── Eloquent/
│   ├── Services/
│   └── Transformers/
├── Config/
├── Console/
├── Database/
│   ├── Factories/
│   ├── Migrations/
│   └── Seeders/
├── Events/
├── Jobs/
├── Resources/
│   ├── assets/
│   └── views/
├── Routes/
└── Tests/
```

## API Documentation

API documentation is available at `/api/documentation`. The documentation is generated using Swagger/OpenAPI annotations in the controllers.

To regenerate the documentation:
```bash
php artisan l5-swagger:generate
```

## Testing

Run the test suite:
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=UserApiTest
```

### Test Structure

- **Unit Tests**: Located in `tests/Unit/Modules`
- **Feature Tests**: Located in `tests/Feature/Modules`

## Available Endpoints

### Authentication
- `POST /api/mock-login` - Login with mock credentials (for development)

### Users
- `GET /api/users` - List all users
- `GET /api/users/{id}` - Get a specific user

### Posts
- `GET /api/posts` - List all posts
- `POST /api/posts` - Create a new post (requires authentication)
- `DELETE /api/posts/{id}` - Delete a post (requires authentication)

### Notifications
- `GET /api/notifications` - List all notifications
- `POST /api/notifications` - Create a new notification (requires authentication)
- `DELETE /api/notifications/{id}` - Delete a notification (requires authentication)


## Implementation Notes

### Pagination
- All list endpoints implement pagination with a default value of 15 items per page
- Pagination can be customized via query parameters
- Paginated responses include metadata (current page, total pages, total items)

### Filtering
- User listing supports filtering by email and name
- Filters are implemented using query parameters
- The filtering logic uses Laravel's query builder

### Background Jobs
- Jobs are dispatched after certain actions (e.g., after notification creation)
- Queue worker must be started when initializing the application
- Jobs handle operations asynchronously

### Authentication
- Mock login functionality implemented for development purposes
- When a user provides email and role:
  - System checks if a user with the same email exists
  - If user exists, it verifies if the user has the provided role
  - If roles don't match, returns validation error that email exists with a different role
  - If roles match, returns authentication token
  - If user doesn't exist, creates a new user with provided credentials
- Authentication tokens are generated using Laravel Sanctum
- Role-based system with three predefined roles (Admin/Editor/User)
- Each user has a dedicated role field

### Real-time Simulation
- Events are broadcast after notification created
- Broadcasting uses Laravel's event system
- Notifications are broadcast on a public channel
- This simulates real-time updates for frontend applications

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Queue Configuration

### Configure Laravel to Use Redis
In your `.env` file:
```
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Run the Queue Worker
```bash
php artisan queue:work redis
```

## Docker Setup

This project uses Docker to simplify local development, including Redis and a queue worker.

### Prerequisites
- Docker installed and running
- Docker Desktop (or Docker CLI)
- Internet access outside restricted regions (e.g., not blocked by Docker Hub)

If you're in a restricted region, use a VPN to pull base images like php, redis, and nginx.

### Setup Steps
1. Clone the project
```bash
git clone https://github.com/alissar583/ridge-task.git
cd ridge-task
```

2. Copy .env
```bash
cp .env.example .env
```

3. Update these lines in .env:
```
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

QUEUE_CONNECTION=redis
REDIS_HOST=redis
```

4. If you're using Laravel modules, make sure module service providers are registered.

5. Build and start Docker containers
```bash
docker-compose up -d --build
```
This will build the app, run Nginx, Redis, and a queue worker.

6. Install Composer dependencies
```bash
docker-compose exec app composer install
```

7. Generate app key
```bash
docker-compose exec app php artisan key:generate
```

8. Run migrations 
```bash
docker-compose exec app php artisan module:make-migrate --all
```

### Access the App
Visit: http://localhost:8000

### Queue Worker
The queue worker runs automatically inside the queue container.
When you dispatch jobs, they will be processed in real-time.


