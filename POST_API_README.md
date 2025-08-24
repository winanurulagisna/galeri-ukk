# Post API Documentation

This document describes the API endpoints for managing posts in the system.

## Base URL
```
http://localhost:8000/api
```

## Database Table
The API works with the `posts` table which has the following structure:
- `id` (int, auto-increment) - Primary key
- `title` (varchar(150)) - Post title (required)
- `body` (text) - Post content (optional)
- `date` (date) - Post date (optional)
- `category_id` (int) - Foreign key to kategori table (optional)
- `created_at` (datetime) - Creation timestamp
- `updated_at` (datetime) - Last update timestamp

## Relationships
- **Category**: Each post can belong to a category (optional relationship)
- **Foreign Key**: `category_id` references `kategori.id`

## API Endpoints

### 1. Get All Posts
**GET** `/posts`
- **Description**: Retrieve all posts with category information
- **Response**: List of all posts with their associated categories
- **Features**: Automatically loads category relationship

### 2. Create Post
**POST** `/posts`
- **Description**: Create a new post
- **Body**:
  ```json
  {
    "title": "Post Title",
    "body": "Post content here...",
    "date": "2025-08-23",
    "category_id": 1
  }
  ```
- **Notes**: 
  - `title` is required and maximum 150 characters
  - `body`, `date`, and `category_id` are optional
  - `category_id` must reference an existing category

### 3. Get Post by ID
**GET** `/posts/{id}`
- **Description**: Retrieve a specific post by ID
- **Parameters**: `id` - Post ID
- **Response**: Post details with category information
- **Features**: Automatically loads category relationship

### 4. Update Post
**PUT** `/posts/{id}`
- **Description**: Update an existing post
- **Parameters**: `id` - Post ID
- **Body**: Same structure as POST
- **Notes**: 
  - All fields are validated
  - `category_id` must reference an existing category

### 5. Delete Post
**DELETE** `/posts/{id}`
- **Description**: Delete a post
- **Parameters**: `id` - Post ID
- **Response**: Success message or 404 if not found

### 6. Get Posts by Category
**GET** `/posts/category/{categoryId}`
- **Description**: Retrieve all posts from a specific category
- **Parameters**: `categoryId` - Category ID
- **Response**: List of posts in the specified category

### 7. Get Posts by Date
**GET** `/posts/date/{date}`
- **Description**: Retrieve all posts from a specific date
- **Parameters**: `date` - Date in YYYY-MM-DD format
- **Response**: List of posts from the specified date

### 8. Search Posts
**GET** `/posts/search/{keyword}`
- **Description**: Search posts by keyword
- **Parameters**: `keyword` - Search term
- **Search**: Searches in both title and body fields
- **Response**: List of matching posts

## Response Format

All API responses follow this structure:

### Success Response
```json
{
  "status": "success",
  "message": "Operation completed successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description",
  "errors": { ... } // For validation errors
}
```

## HTTP Status Codes

- `200` - Success
- `201` - Created (for POST requests)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Validation Rules

### Create/Update Post
- `title`: Required, string, max 150 characters
- `body`: Optional, string
- `date`: Optional, valid date format
- `category_id`: Optional, integer, must exist in kategori table

## Setup Instructions

1. **Run Migration**: Create the database table
   ```bash
   php artisan migrate
   ```

2. **Import Postman Collection**: Use the provided `Post_API_Postman_Collection.json` file

3. **Set Base URL**: Update the `base_url` variable in Postman to match your local environment

4. **Prerequisites**: Ensure the `kategori` table exists and has some categories

## Example Usage

### Creating a Post
```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My First Post",
    "body": "This is the content of my first post.",
    "date": "2025-08-23",
    "category_id": 1
  }'
```

### Getting All Posts
```bash
curl http://localhost:8000/api/posts
```

### Updating a Post
```bash
curl -X PUT http://localhost:8000/api/posts/1 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Post Title",
    "body": "Updated content here.",
    "date": "2025-08-24",
    "category_id": 2
  }'
```

### Deleting a Post
```bash
curl -X DELETE http://localhost:8000/api/posts/1
```

### Getting Posts by Category
```bash
curl http://localhost:8000/api/posts/category/1
```

### Getting Posts by Date
```bash
curl http://localhost:8000/api/posts/date/2025-08-23
```

### Searching Posts
```bash
curl http://localhost:8000/api/posts/search/technology
```

## Features

- **Automatic Relationships**: Posts automatically load their associated category information
- **Flexible Fields**: Most fields are optional except title
- **Date Handling**: Proper date validation and formatting
- **Category Integration**: Seamless integration with the existing category system
- **Search Functionality**: Full-text search in title and body
- **Comprehensive Validation**: Input validation with detailed error messages
- **Error Handling**: Proper error handling for all operations

## Notes

- The `created_at` and `updated_at` fields are automatically managed by Laravel
- Foreign key constraints ensure data integrity with the category system
- All endpoints include proper error handling and validation
- The API follows RESTful conventions
- Date format should be YYYY-MM-DD (ISO 8601)
- Category ID must reference an existing category in the `kategori` table

