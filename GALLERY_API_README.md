# Gallery API Documentation

This document describes the API endpoints for managing galleries (images) in the system.

## Base URL
```
http://localhost:8000/api
```

## Database Table
The API works with the `galleries` table which has the following structure:
- `id` (int, auto-increment) - Primary key
- `title` (varchar(150)) - Gallery title (required)
- `caption` (text) - Image caption (optional)
- `file_path` (varchar(255)) - File path to the image (required)
- `post_id` (int) - Foreign key to posts table (optional)
- `created_at` (datetime) - Creation timestamp
- `updated_at` (datetime) - Last update timestamp

## Relationships
- **Post**: Each gallery can belong to a post (optional relationship)
- **Foreign Key**: `post_id` references `posts.id`

## API Endpoints

### 1. Get All Galleries
**GET** `/galleries`
- **Description**: Retrieve all galleries with post information
- **Response**: List of all galleries with their associated posts
- **Features**: Automatically loads post relationship

### 2. Create Gallery
**POST** `/galleries`
- **Description**: Create a new gallery entry
- **Body**:
  ```json
  {
    "title": "Gallery Title",
    "caption": "Image caption here...",
    "file_path": "/storage/galleries/image.jpg",
    "post_id": 1
  }
  ```
- **Notes**: 
  - `title` and `file_path` are required
  - `caption` and `post_id` are optional
  - `post_id` must reference an existing post

### 3. Upload Gallery File
**POST** `/galleries/upload`
- **Description**: Upload a file and create a gallery entry
- **Body**: Form-data with file upload
- **Fields**:
  - `title` (required): Gallery title
  - `caption` (optional): Image caption
  - `file` (required): Image file (jpeg, png, jpg, gif, webp, max 2MB)
  - `post_id` (optional): Associated post ID
- **Features**: Automatic file storage and path generation

### 4. Get Gallery by ID
**GET** `/galleries/{id}`
- **Description**: Retrieve a specific gallery by ID
- **Parameters**: `id` - Gallery ID
- **Response**: Gallery details with post information
- **Features**: Automatically loads post relationship

### 5. Update Gallery
**PUT** `/galleries/{id}`
- **Description**: Update an existing gallery
- **Parameters**: `id` - Gallery ID
- **Body**: Same structure as POST
- **Notes**: 
  - All fields are validated
  - `post_id` must reference an existing post

### 6. Delete Gallery
**DELETE** `/galleries/{id}`
- **Description**: Delete a gallery
- **Parameters**: `id` - Gallery ID
- **Response**: Success message or 404 if not found
- **Note**: File deletion from storage is commented out by default

### 7. Get Galleries by Post
**GET** `/galleries/post/{postId}`
- **Description**: Retrieve all galleries from a specific post
- **Parameters**: `postId` - Post ID
- **Response**: List of galleries in the specified post

### 8. Get Standalone Galleries
**GET** `/galleries/standalone`
- **Description**: Retrieve all galleries that are not associated with any post
- **Response**: List of standalone galleries
- **Use Case**: For general image galleries not tied to specific posts

### 9. Search Galleries
**GET** `/galleries/search/{keyword}`
- **Description**: Search galleries by keyword
- **Parameters**: `keyword` - Search term
- **Search**: Searches in both title and caption fields
- **Response**: List of matching galleries

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
- `400` - Bad Request (for file upload issues)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Validation Rules

### Create/Update Gallery
- `title`: Required, string, max 150 characters
- `caption`: Optional, string
- `file_path`: Required, string, max 255 characters
- `post_id`: Optional, integer, must exist in posts table

### File Upload
- `title`: Required, string, max 150 characters
- `caption`: Optional, string
- `file`: Required, file, mimes: jpeg,png,jpg,gif,webp, max 2MB
- `post_id`: Optional, integer, must exist in posts table

## File Storage

- Files are stored in the `storage/app/public/galleries/` directory
- File paths are automatically generated with timestamp prefix
- Supported formats: JPEG, PNG, JPG, GIF, WebP
- Maximum file size: 2MB

## Setup Instructions

1. **Run Migration**: Create the database table
   ```bash
   php artisan migrate
   ```

2. **Create Storage Link**: Link storage to public directory
   ```bash
   php artisan storage:link
   ```

3. **Import Postman Collection**: Use the provided `Gallery_API_Postman_Collection.json` file

4. **Set Base URL**: Update the `base_url` variable in Postman to match your local environment

5. **Prerequisites**: Ensure the `posts` table exists if you want to associate galleries with posts

## Example Usage

### Creating a Gallery Entry
```bash
curl -X POST http://localhost:8000/api/galleries \
  -H "Content-Type: application/json" \
  -d '{
    "title": "My Gallery Image",
    "caption": "This is a beautiful image",
    "file_path": "/storage/galleries/my-image.jpg",
    "post_id": 1
  }'
```

### Uploading a File
```bash
curl -X POST http://localhost:8000/api/galleries/upload \
  -F "title=Uploaded Image" \
  -F "caption=This was uploaded via API" \
  -F "file=@/path/to/image.jpg" \
  -F "post_id=1"
```

### Getting All Galleries
```bash
curl http://localhost:8000/api/galleries
```

### Updating a Gallery
```bash
curl -X PUT http://localhost:8000/api/galleries/1 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Gallery Title",
    "caption": "Updated caption",
    "file_path": "/storage/galleries/updated-image.jpg",
    "post_id": 2
  }'
```

### Deleting a Gallery
```bash
curl -X DELETE http://localhost:8000/api/galleries/1
```

### Getting Galleries by Post
```bash
curl http://localhost:8000/api/galleries/post/1
```

### Getting Standalone Galleries
```bash
curl http://localhost:8000/api/galleries/standalone
```

### Searching Galleries
```bash
curl http://localhost:8000/api/galleries/search/beautiful
```

## Features

- **File Upload Support**: Direct file upload with automatic storage
- **Post Integration**: Can associate images with specific posts
- **Standalone Images**: Support for general gallery images not tied to posts
- **Automatic Relationships**: Galleries automatically load their associated post information
- **Flexible Fields**: Most fields are optional except title and file_path
- **Search Functionality**: Full-text search in title and caption
- **Comprehensive Validation**: Input validation with detailed error messages
- **Error Handling**: Proper error handling for all operations

## Notes

- The `created_at` and `updated_at` fields are automatically managed by Laravel
- Foreign key constraints ensure data integrity with the post system
- File deletion from storage is commented out by default for safety
- All endpoints include proper error handling and validation
- The API follows RESTful conventions
- File upload endpoint uses multipart/form-data
- Images are stored with timestamp prefixes to avoid naming conflicts
- The `post_id` field is optional, allowing standalone gallery images

## Security Considerations

- File upload validation prevents malicious file uploads
- File size limits prevent abuse
- File type restrictions ensure only images are uploaded
- Foreign key constraints maintain data integrity
