# VERO Digital Task

A web-based task management system that displays tasks in both DataTable and simple table views and implements modal popups for image selection. 


## Features

- Two table views:
   - DataTable with built-in sorting, searching, and pagination
   - Simple table with custom search and sorting functionality
   - Real-time color coding of tasks
   - Automatic data refresh every 60 minutes
   - Last update timestamp display
- Modal popups for image selection
- 
## Requirements

- PHP 8.2 or higher
- Web server (Apache/Nginx)
- Modern web browser
- Composer for dependency management

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
```
2. Install dependencies:
```bash
composer install
```
3. Set up your web server to point to the `public` directory of the cloned repository.
4. Create a `.env` file in the root directory.
   - Copy the file to : `.env.example``.env`
```bash
cp .env.example .env
```
5. Update the `.env` file with your configuration:
```env
# API Configuration
API_BASE_URL=https://api.baubuddy.de
API_USERNAME=
API_PASSWORD=
API_BASIC_AUTH=
```

## Testing

### Running Tests

1. Run all tests:
```bash
    ./vendor/bin/phpunit tests/Service/TaskCollectorTest.php
```