# 🎓 ABU CMS - Campus Complaint Management System Backend

## 📋 **Project Overview**

**ABU CMS** (Ahmadu Bello University Campus Complaint Management System) is a comprehensive web and mobile application designed to streamline complaint management within educational institutions. This repository contains the **Laravel Backend API** that powers the entire system.

## 🏗️ **System Architecture**

- **Backend**: Laravel 11 RESTful API
- **Database**: MySQL 8.0 with complete schema
- **Authentication**: Laravel Sanctum (JWT Tokens)
- **Frontend**: Admin Dashboard + Department Staff Interface
- **Mobile App**: Android application (separate repository)

## ✨ **Features**

### **🔐 Authentication System**
- User registration and login
- Role-based access control (Student, Staff, Admin)
- JWT token authentication
- Secure session management

### **📝 Complaint Management**
- Submit, view, and track complaints
- Category-based classification
- Priority levels (Low, Medium, High, Urgent)
- Status tracking (Pending, Assigned, In Progress, Resolved)
- Department assignment system

### **👨‍💼 Admin Dashboard**
- Comprehensive complaint overview
- User management system
- Department management
- Staff assignment and management
- Real-time statistics and analytics

### **🏢 Department Staff Interface**
- Department-specific complaint views
- Status update capabilities
- Resolution note management
- Performance tracking

### **📱 RESTful API**
- Complete CRUD operations
- JSON responses
- Proper HTTP status codes
- Comprehensive error handling

## 🛠️ **Technology Stack**

- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Validation**: Laravel Form Requests
- **ORM**: Eloquent ORM
- **API**: RESTful design
- **Frontend**: Bootstrap 5 + Material Design

## 📁 **Project Structure**

```
backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   ├── Api/             # API controllers
│   │   └── Department/      # Department staff controllers
│   ├── Models/              # Eloquent models
│   └── Http/Middleware/     # Custom middleware
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/            # Sample data
├── resources/views/         # Blade templates
│   ├── admin/              # Admin interface
│   └── department/         # Department interface
└── routes/
    ├── api.php             # API routes
    └── web.php             # Web routes
```

## 🚀 **Quick Start**

### **Prerequisites**
- PHP 8.2+
- Composer
- MySQL 8.0+
- Git

### **Installation**

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/abu-cms-backend.git
   cd abu-cms-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   ```bash
   # Edit .env file with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=abu_cms
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## 🔗 **Access URLs**

- **Main Application**: http://127.0.0.1:8000
- **Admin Login**: http://127.0.0.1:8000/admin/login
- **Department Login**: http://127.0.0.1:8000/department/login
- **API Base**: http://127.0.0.1:8000/api/

## 🔐 **Default Credentials**

### **Admin User**
- **Email**: `admin@admin.com`
- **Password**: `admin123`

### **Sample Student**
- **Email**: `test@student.com`
- **Password**: `password123`

### **Sample Staff**
- **Email**: `it.manager@abu.edu.ng`
- **Password**: `password`

## 📚 **API Documentation**

### **Authentication Endpoints**
- `POST /api/auth/register` - Student registration
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/user` - Get user profile

### **Complaint Endpoints**
- `GET /api/complaints` - Get user's complaints
- `POST /api/complaints` - Submit new complaint
- `GET /api/complaints/{id}` - Get specific complaint
- `PUT /api/complaints/{id}` - Update complaint
- `DELETE /api/complaints/{id}` - Delete complaint

### **Public Endpoints**
- `GET /api/complaints/categories` - Get complaint categories
- `GET /api/complaints/statuses` - Get complaint statuses

## 🧪 **Testing**

### **API Testing with cURL**
```bash
# Test categories endpoint
curl http://127.0.0.1:8000/api/complaints/categories

# Test login
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@student.com","password":"password123"}'
```

### **Database Testing**
```bash
# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed

# Clear cache
php artisan cache:clear
```

## 🚀 **Deployment**

### **Production Setup**
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Generate production key
php artisan key:generate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### **Recommended Hosting**
- **Render** (Free tier available)
- **Railway** (Free $5 credit monthly)
- **Heroku** (Professional hosting)
- **DigitalOcean** (Full control)

## 📊 **Database Schema**

### **Core Tables**
- `users` - User accounts and authentication
- `roles` - User role definitions
- `complaints` - Main complaint records
- `complaint_categories` - Complaint classification
- `complaint_statuses` - Status tracking
- `departments` - Department information
- `staff` - Staff member records

### **Relationships**
- Users belong to roles
- Complaints belong to users and categories
- Staff belong to departments
- Complaints can be assigned to departments

## 🔧 **Development Commands**

```bash
# Start development server
php artisan serve --host=0.0.0.0 --port=8000

# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration create_table_name

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# View routes
php artisan route:list
```

## 🐛 **Troubleshooting**

### **Common Issues**
1. **Port 8000 already in use**
   ```bash
   lsof -i :8000
   kill -9 PID_NUMBER
   ```

2. **Database connection issues**
   ```bash
   sudo systemctl status mysql
   sudo systemctl restart mysql
   ```

3. **Permission issues**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 📝 **Contributing**

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 **License**

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 **Authors**

- **Developer**: [Your Name]
- **Project**: ABU Campus Complaint Management System
- **Institution**: Ahmadu Bello University

## 🙏 **Acknowledgments**

- Laravel framework and community
- Bootstrap for UI components
- Material Design principles
- Educational institution feedback

---

**🎯 ABU CMS Backend - Streamlining campus complaint management through modern web technology!**

For questions or support, please open an issue in this repository.
