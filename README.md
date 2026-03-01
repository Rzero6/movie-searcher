## 🚀 Movie Searcher

A Laravel-based web application for searching movies using the OMDb API. Provides user authentication, favorite movies management, caching, localization, and a modern UI built with Livewire.

## 📸 Screenshot

> - ![Home page](app%20screenshot/home%20page.png)
> - ![Search result page](app%20screenshot/search%20result%20page.png)
> - ![Favorites page](app%20screenshot/favorites%20page.png)
> - ![Login page](app%20screenshot/login%20page.png)
> - ![Detail page](app%20screenshot/detail%20page.png)

## ✅ Features

- **OMDb API Integration**: Search for movie information using the OMDb API.
- **User Authentication**: Register, login, and manage user sessions via Laravel's built-in auth.
- **Favorites**: Logged-in users can add movies to their favorites list.
- **Cache Implementation**: Responses from OMDb are cached to reduce API calls and improve performance.
- **Livewire-Powered UI**: Interactive components for searching, adding favorites, and infinite scrolling.
- **Infinite Scroll**: Load more results seamlessly as you scroll down the page.
- **Multilingual Support**: English and Indonesian languages are available out-of-the-box.

## 🛠️ Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm (for frontend assets)
- MySQL or another supported database
- OMDb API key (get one at [http://www.omdbapi.com/](http://www.omdbapi.com/))

## 📁 Installation

1. Install PHP dependencies:

    ```bash
    composer install
    ```

2. Copy the example environment file and set your configuration:

    ```bash
    cp .env.example .env
    ```

    - Set your database credentials
    - Add your OMDb API key:

        ```
        OMDB_API_KEY=your_api_key_here
        ```

3. Generate application key:

    ```bash
    php artisan key:generate
    ```

## 🗄️ Database Setup

Run the migrations to create necessary tables:

```bash
php artisan migrate
```

Seed the database with username/password (aldmic/123abc123):

```bash
php artisan db:seed
```

## 🎯 Usage

1. Start the development server:

    ```

    php artisan serve

    ```

2. Visit `http://localhost:8000` in your browser.

3. Log in with existing credentials (aldmic/123abc123).

4. Use the search bar to find movies. Scroll down to load more results automatically.

5. Click the favorite button to add/remove movies from your favorites list.

## 🌍 Localization

The application supports multiple languages. To switch languages, update the locale in `config/app.php` or use the language selector in the UI if available. English (`en`) and Indonesian (`id`) translations are provided under `resources/lang/`.

## 🛡️ Cache

The OMDb search results are cached using Laravel's cache system. You can configure your preferred cache driver in `.env` (e.g., `file`, `redis`, `memcached`).

## 📦 Additional Commands

- **Clear cache**: `php artisan cache:clear`
- **Clear config cache**: `php artisan config:clear`
- **Rebuild assets**: `npm run build`

## 🧩 Extending the Project

- Add more languages by creating new directories under `resources/lang/` and adding translation files.
- Implement pagination or filtering features with Livewire components.
- Replace OMDb with another movie API by updating the `OmdbService`.

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

---
