# Document Management System (DMS) - Laravel

A full-featured Document Management System built with Laravel 11.

## Features

- **User Authentication**: Login, Register, Logout using Laravel Breeze
- **Role-Based Access Control**: Admin, Staff, Viewer roles with different permissions
- **Document Upload**: Support for PDF, DOC, DOCX, PNG, JPG, JPEG, GIF files (max 20MB)
- **Document Metadata**: Title, description, category, and tags
- **Document Versioning**: Multiple versions per document with version history
- **File Preview & Download**: Download current version or specific versions
- **Search & Filtering**: Search by title, category, date range, user
- **Admin Panel**: Manage users and categories
- **RESTful API**: API endpoints for mobile integration

## Requirements

- PHP 8.2+
- Composer
- SQLite (included) or MySQL/PostgreSQL

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd DMSLaravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## Default Users

After seeding, you can log in with these accounts:

| Role   | Email                | Password  |
|--------|----------------------|-----------|
| Admin  | admin@example.com    | password  |
| Staff  | staff@example.com    | password  |
| Viewer | viewer@example.com   | password  |

## Roles & Permissions

| Feature                  | Admin | Staff | Viewer |
|--------------------------|-------|-------|--------|
| View Documents           | Yes   | Yes   | Yes    |
| Download Documents       | Yes   | Yes   | Yes    |
| Upload Documents         | Yes   | Yes   | No     |
| Edit Own Documents       | Yes   | Yes   | No     |
| Delete Own Documents     | Yes   | Yes   | No     |
| Edit Any Documents       | Yes   | No    | No     |
| Delete Any Documents     | Yes   | No    | No     |
| Manage Users             | Yes   | No    | No     |
| Manage Categories        | Yes   | No    | No     |

## Routes

### Authentication
- `GET /login` - Login page
- `POST /login` - Login action
- `GET /register` - Register page
- `POST /register` - Register action
- `POST /logout` - Logout action

### Dashboard
- `GET /dashboard` - Dashboard overview

### Documents
- `GET /documents` - List all documents
- `GET /documents/create` - Upload document form
- `POST /documents` - Store new document
- `GET /documents/{id}` - View document details
- `GET /documents/{id}/edit` - Edit document form
- `PUT /documents/{id}` - Update document
- `DELETE /documents/{id}` - Delete document
- `GET /documents/{id}/download` - Download document

### Admin (Admin only)
- `GET /admin/users` - List users
- `GET /admin/users/create` - Create user form
- `POST /admin/users` - Store new user
- `GET /admin/users/{id}/edit` - Edit user form
- `PUT /admin/users/{id}` - Update user
- `DELETE /admin/users/{id}` - Delete user
- `GET /admin/categories` - Manage categories

### API Routes
- `GET /api/documents` - List documents (authenticated)
- `POST /api/documents` - Create document (authenticated)
- `GET /api/documents/{id}` - Get document details (authenticated)
- `GET /api/documents/{id}/download` - Download document (authenticated)

## Database Schema

### Users
- id, name, email, password, role, timestamps

### Categories
- id, name, timestamps

### Documents
- id, title, description, file_path, user_id, category_id, tags, timestamps

### Versions
- id, document_id, file_path, version_number, notes, user_id, timestamps

## File Storage

Files are stored in `storage/app/public/documents/` and served via the `public/storage` symlink.

## Development

### Running Tests
```bash
php artisan test
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## License

MIT License
