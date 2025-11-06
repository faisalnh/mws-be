# MWS Backend

This is the backend for the MWS application, a comprehensive platform for managing student data, assessments, and educational activities.

## About The Project

The MWS Backend is a Laravel-based application that provides a robust set of APIs for the MWS platform. It handles everything from student and teacher management to emotional check-ins, assessments, and reporting.

### Key Features:

*   **User Management:** Handles different user roles like students, parents, and teachers.
*   **Student Information:** Manages student profiles, grades, classes, and schedules.
*   **Assessments:** Allows for the creation and management of assessments, questions, and responses.
*   **Emotional Check-ins:** Provides a feature for students to log their emotional state.
*   **Reporting:** Generates various reports based on student data and assessments.
*   **API Authentication:** Uses Laravel Sanctum for API token authentication.

## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & npm
*   A database (e.g., MySQL, PostgreSQL)

### Installation

1.  Clone the repo
    ```sh
    git clone https://github.com/faisalnh/mws-be.git
    ```
2.  Install PHP dependencies
    ```sh
    composer install
    ```
3.  Install NPM packages
    ```sh
    npm install
    ```
4.  Create a copy of your .env file
    ```sh
    cp .env.example .env
    ```
5.  Generate an app encryption key
    ```sh
    php artisan key:generate
    ```
6.  Run the database migrations
    ```sh
    php artisan migrate
    ```
7.  Start the development server
    ```sh
    php artisan serve
    ```

## API Endpoints

The API endpoints are defined in the `routes/api.php` file. Please refer to this file for a complete list of available endpoints.

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.