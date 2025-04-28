# Laravel 12 API Starter Kit

This starter kit provides a pre-configured and feature-rich platform designed to accelerate the development of Laravel 12 APIs. It includes essential packages and configurations, allowing you to focus on building your core business logic right away without repetitive setup tasks.

## Purpose

The main goal of this starter kit is to streamline the initial setup process for new Laravel API projects by providing:

*   Essential authentication and authorization.
*   Commonly used API development tools.
*   CI/CD workflow enhancements.
*   Sensible defaults and configurations.

## Tech Stack

This starter kit is built upon the following technologies and packages:

*   **Framework:** [Laravel 12.x](https://laravel.com/docs/12.x)
*   **PHP:** ^8.2
*   **Authentication:** [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum) for API token authentication.
*   **Authorization:** [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction) for role-based access control.
*   **API Querying:** [Spatie Laravel Query Builder](https://spatie.be/docs/laravel-query-builder/v6/introduction) for easy filtering, sorting, and includes.
*   **Enums:** [BenSampo Laravel Enum](https://github.com/BenSampo/laravel-enum) for robust enum support.
*   **OPcache:** [Appstract Laravel OPcache](https://github.com/appstract/laravel-opcache) for optimizing PHP OPcache.
*   **Development Tools:**
    *   [Laravel Pint](https://laravel.com/docs/12.x/pint) for code style fixing.
    *   [Larastan](https://github.com/larastan/larastan) for static analysis.
    *   [Laravel Lang](https://github.com/Laravel-Lang/lang) for multi-language support (includes English and French).

## Installation Guide

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/Abdallah01win/StarterApi.git your-project-name
    cd your-project-name
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Generate the application key:
        ```bash
        php artisan key:generate
        ```
    *   Configure your database connection, application URL (`APP_URL`), and other necessary settings in the `.env` file.

4.  **Database Migration:**
    *   Run the database migrations:
        ```bash
        php artisan migrate --seed
        ```

5.  **Serve the Application:**
    *   Use the built-in development server:
        ```bash
        php artisan serve
        ```
    *   Or use any other tool or server you wish.

6.  **(Optional) OPcache Setup:**
    *   The `appstract/laravel-opcache` package is included. It provides routes to manage PHP OPcache.
    *   To enable and configure OPcache follow [this guid.](https://medium.com/appstract/make-your-laravel-app-fly-with-php-opcache-9948db2a5f93#.bjrpj4h1c)

## Usage Guide

This starter kit provides a foundation for your API. Here's how to get started building your specific features:

1.  **Authentication:**
    *   API routes requiring authentication should be placed within the `auth:sanctum` middleware group.
    *   Endpoints for login (`/api/login`), logout (`/api/logout`), and fetching the authenticated user (`/api/init_user`) are pre-configured.

2.  **Authorization:**
    *   Use `spatie/laravel-permission` to define roles and permissions.
    *   Assign roles/permissions to users. The `User` model is already set up with the `HasRoles` trait.

3.  **Building Your API:**
    *   **Data Structures:** Start by creating your Model, Request, Migration, and Response as you normally would.
    *   **Create Controllers:** Generate API controllers (`php artisan make:controller YourController`). Then extend the `BaseController` and provide the required field for a full CRUD controller with minimal effort. Note: You can always override existing or add new controller methods.
    *   **Define Routes:** Add your API routes to `routes/api.php` within the `auth:sanctum` group if they require authentication.
    *   **Use Enums:** Utilize `bensampo/laravel-enum` for defining enums (e.g., user roles, statuses). The `User` model includes an example `role` attribute cast to an Enum.
    *   **Helpers:** Add global helper functions to `helpers/helpers.php`.

4.  **Development Workflow:**
    *   **Formatting:** Keep your code style consistent using Pint:
        ```bash
        composer run format
        ```
    *   **Static Analysis:** Check your code for potential errors with Larastan:
        ```bash
        composer run analyse
        ```
    *   **Logging:** Tail logs easily using Pail:
        ```bash
        php artisan pail
        ```
    *   **Multi-language:** Add translation strings to the `lang` directory. Use the `lang:update` composer script after updating the `laravel-lang/lang` package.

## Contributing

If you wish to contribute to this starter kit, please follow standard procedures like forking the repository, creating a feature branch, and submitting a pull request.

## License

This Laravel starter kit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).