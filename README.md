# Emission Connector

This project is a Connector to calculate emissions for flights, hotels, and trains. Currently, it only connects to Squake. The application is built using Laravel 12.

## Getting Started

### Running the Project

To run this project, use the following command:

```sh
docker compose up -d
```

This command will start the application in detached mode.

### Environment Variables

Before running the project, ensure you have the following environment variables set in your `.env` file:

```env
SQUAKE_BASE_URL=your_squake_base_url
SQUAKE_TOKEN=your_squake_token
```

### Running Tests

To run the tests, use the following command:

```sh
php artisan test
```

This command will execute the test suite using Pest.

### Checking Code Style

To check the code style, use the following command:

```sh
./vendor/bin/pint
```

This command will check and fix the code style using Pint.

### Usage

Once the application is running, you can access it at `http://localhost:8001`.

### Features

- Calculate emissions for flights
- Calculate emissions for hotels
- Calculate emissions for trains

### License

This project is licensed under the MIT License.
