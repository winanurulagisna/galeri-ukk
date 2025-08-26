# Category API Documentation

This document describes the API endpoints for managing categories (kategori) in the system.

## Base URL
```
http://localhost:8000/api
```

## Database Table
The API works with the `kategori` table which has the following structure:
- `id` (int, auto-increment) - Primary key
- `name` (varchar(100)) - Category name
- `slug` (text) - URL-friendly version of the name (auto-generated)
- `created_at` (timestamp) - Creation timestamp
- `updated_at` (timestamp) - Last update timestamp

## API Endpoints

### 1. Get All Categories
**GET** `/categories`
- **Description**: Retrieve all categories
- **Response**: List of all categories with status and message

### 2. Create Category
**POST** `/categories`
- **Description**: Create a new category
- **Body**:
  ```json
  {
    "name": "Category Name"
  }
  ```
- **Notes**: 
  - `name` is required and must be unique
  - `slug` is automatically generated from the name
  - Maximum length for name is 100 characters

### 3. Get Category by ID
**GET** `/categories/{id}`
- **Description**: Retrieve a specific category by ID
- **Parameters**: `id` - Category ID
- **Response**: Category details or 404 if not found

### 4. Update Category
**PUT** `/categories/{id}`
- **Description**: Update an existing category
- **Parameters**: `id` - Category ID
- **Body**:
  ```json
  {
    "name": "Updated Category Name"
  }
  ```
- **Notes**: 
  - `name` is required and must be unique (excluding current category)
  - `slug` is automatically updated

### 5. Delete Category
**DELETE** `/categories/{id}`
- **Description**: Delete a category
- **Parameters**: `id` - Category ID
- **Response**: Success message or 404 if not found

### 6. Get Category by Slug
**GET** `/categories/slug/{slug}`
- **Description**: Retrieve a category by its slug
- **Parameters**: `slug` - Category slug
- **Response**: Category details or 404 if not found

### 7. Search Categories
**GET** `/categories/search/{keyword}`
- **Description**: Search categories by keyword
- **Parameters**: `keyword` - Search term
- **Search**: Searches in both name and slug fields
- **Response**: List of matching categories

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

## Setup Instructions

1. **Run Migration**: Create the database table
   ```bash
   php artisan migrate
   ```

2. **Import Postman Collection**: Use the provided `Category_API_Postman_Collection.json` file

3. **Set Base URL**: Update the `base_url` variable in Postman to match your local environment

## Example Usage

### Creating a Category
```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Content-Type: application/json" \
  -d '{"name": "Technology"}'
```

### Getting All Categories
```bash
curl http://localhost:8000/api/categories
```

### Updating a Category
```bash
curl -X PUT http://localhost:8000/api/categories/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Technology"}'
```

### Deleting a Category
```bash
curl -X DELETE http://localhost:8000/api/categories/1
```

## Notes

- The `slug` field is automatically generated from the `name` field using Laravel's `Str::slug()` helper
- All endpoints include proper error handling and validation
- The API follows RESTful conventions
- Timestamps are automatically managed by Laravel
