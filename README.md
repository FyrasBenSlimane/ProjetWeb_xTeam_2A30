# LenSi - Creative Services Marketplace

A platform for creative professionals to offer services, collaborate on projects, and engage with the community.

## Setup Instructions

### Environment Configuration

1. Copy the `.env.example` file to a new file named `.env`:
   ```
   cp .env.example .env
   ```

2. Update the `.env` file with your actual configuration values:
   ```
   # Database Configuration
   DB_HOST=your_database_host
   DB_USER=your_database_username
   DB_PASS=your_database_password
   DB_NAME=your_database_name

   # OAuth Credentials
   GITHUB_CLIENT_ID=your_github_client_id
   GITHUB_CLIENT_SECRET=your_github_client_secret
   
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   ```

3. Make sure the `.env` file is excluded from your Git repository for security (it should be in the `.gitignore` file).

### Database Setup

1. Create a new database using the name you specified in the `.env` file.
2. Import the SQL schema from `app/sql/setup.sql`:
   ```
   mysql -u your_username -p your_database_name < app/sql/setup.sql
   ```

### Web Server Configuration

1. Configure your web server (Apache/Nginx) to point to the project's root directory.
2. Ensure that the web server has read/write permissions for the required directories:
   - `public/uploads/` (for user uploads)
   - Any directories that need to be writable by the application

### Running the Application

1. Navigate to the URL you configured in the `.env` file (URL_ROOT).
2. The application should now be running and accessible.

## Features

- User authentication with traditional login or OAuth (GitHub, Google)
- Marketplace for creative services
- Project collaboration tools
- Community resources and forums
- Support ticket system
- Admin dashboard for site management

## Development

### Directory Structure

- `/app`: Core application code
  - `/config`: Configuration files
  - `/controllers`: MVC controllers
  - `/models`: Data models
  - `/views`: View templates
  - `/helpers`: Helper functions
  - `/core`: Core framework classes
- `/public`: Publicly accessible files
  - `/css`: Stylesheets
  - `/js`: JavaScript files
  - `/images`: Static images
  - `/uploads`: User uploads

## License

[Your license information here]
