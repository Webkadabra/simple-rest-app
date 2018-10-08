# Requirements

* PHP 7.2+
* MSQL

# Setup

* Create database `testapp` with access for username `root` without a password (you can change this in `config.php` file)
* Build database structure with MYSQL dump: `database.sql`
* Point your apache server to `/web/` directory

# USAGE

To be able to send requests, you must provide `authToken` parameter in URL

# API endpoints

Example links include `authToken`:

* Get all books: <http://example.test/book/list?authToken=3qPjJB2YdTNyENhwCLmSnVyHFrsncxDzTKUkEEffMX56k2zERZQOg6zDkDZJI8Y8&from=0&limit=10>
    Supports pagination parameters: $_GET['from'] and $_GET['limit']
* One Book: <http://example.test/book/read?id=15&authToken=3qPjJB2YdTNyENhwCLmSnVyHFrsncxDzTKUkEEffMX56k2zERZQOg6zDkDZJI8Y8>
* Create book: <http://example.test/book/create?authToken=3qPjJB2YdTNyENhwCLmSnVyHFrsncxDzTKUkEEffMX56k2zERZQOg6zDkDZJI8Y8>

    Example JSON request BODY:
    
    ```
    {
        "title": "New Book",
        "authors": [
            {
                "id": 1
            },
            {
                "name": "Sam"
            }
        ]
    }
    ```
    
    This request will create a new book, assigning one existing and one new author to the book

* Update book: <http://example.test/book/update?id=15&authToken=3qPjJB2YdTNyENhwCLmSnVyHFrsncxDzTKUkEEffMX56k2zERZQOg6zDkDZJI8Y8>

    Example JSON request body:
    
    ```
    {
        "title": "Renamed Book",
        "description": "description of a book has changed",
        "authors": [
            {
                "id": 2
            },
            {
                "name": "New Author"
            }
        ]
    }
    ```

* DELETE book: <http://example.test/book/delete?id=15&authToken=3qPjJB2YdTNyENhwCLmSnVyHFrsncxDzTKUkEEffMX56k2zERZQOg6zDkDZJI8Y8>
  
  **Must be sent** as `DELETE` request type

# Would-be TODO:

* improve REST list action with correct REST link headers for pagination 
* improve access validation and move it to controller level, so app could support publicly available REST API endpoints