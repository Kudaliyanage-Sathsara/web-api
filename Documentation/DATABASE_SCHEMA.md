# Alumni Profile API - Database Schema Documentation

## Overview

The Alumni Profile API database is built on a relational model designed to manage user authentication and comprehensive profile information. The schema enforces data integrity through foreign key relationships and maintains temporal tracking with created_at and updated_at timestamps.

**Database Name**: `alumni_db`

**Database Type**: MySQL
---

## Table of Contents

1. [Core Authentication Tables](#core-authentication-tables)
2. [User Profile Tables](#user-profile-tables)
3. [Relationships & Constraints](#relationships--constraints)
4. [Indexes & Performance](#indexes--performance)
5. [Entity-Relationship Diagram](#entity-relationship-diagram)
6. [Data Integrity Rules](#data-integrity-rules)

---

## Core Authentication Tables

### 1. users Table

Stores core user account information and authentication credentials.

**Primary Key**: id

**Schema/query**:

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_verified INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

**Constraints**:
- PRIMARY KEY (id)
- UNIQUE KEY (email)
- CHECK: is_verified IN (0, 1)

**Indexes**:
- Primary key index on id (AUTO_INCREMENT)
- Unique index on email (speeds up lookups during login/registration)

---

### 2. email_verification_tokens Table

Stores email verification tokens for newly registered users.

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:


| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Token record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| token | VARCHAR(255) | NO | UNIQUE | Secure verification token (64-character hex string) |
| used | INT | YES | 0 | Token usage flag (0=unused, 1=used) |
| expires_at | DATETIME | NO | NULL | Token expiration timestamp (24 hours from creation) |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Token creation timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- UNIQUE KEY (token)
- CHECK: used IN (0, 1)

**Indexes**:
- Primary key on id
- Foreign key on user_id
- Unique index on token (fast lookup during verification)

**Token Generation Algorithm**:
- Source: random_bytes(32) - 256 bits of entropy
- Format: bin2hex() - 64-character hexadecimal string
- Expiration: NOW() + INTERVAL 24 HOURS
- One-time use: can only be used when used=0

**Cleanup Strategy**:
- Expired tokens can be archived or deleted via cron job
- Records auto-deleted when user_id deleted (CASCADE)

**Sample Data**:
```sql
INSERT INTO email_verification_tokens 
  (user_id, token, used, expires_at) VALUES
(3, 'sample_verification_token_1234567890abcdef', 0, DATE_ADD(NOW(), INTERVAL 24 HOUR));
```

---

### 3. `password_reset_tokens` Table

Stores password reset tokens for account recovery.

**Purpose**: Manages password reset workflow with expiring secure tokens

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Token record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| token | VARCHAR(255) | NO | UNIQUE | Secure reset token (64-character hex string) |
| used | INT | YES | 0 | Token usage flag (0=unused, 1=used) |
| expires_at | DATETIME | NO | NULL | Token expiration timestamp (1 hour from creation) |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Token creation timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- UNIQUE KEY (token)
- CHECK: used IN (0, 1)

**Indexes**:
- Primary key on id
- Foreign key on user_id
- Unique index on token (fast lookup during reset)

**Token Details**:
- Source: random_bytes(32) - 256 bits of entropy
- Format: bin2hex() - 64-character hexadecimal string
- Expiration: NOW() + INTERVAL 1 HOUR (shorter than email verification for security)
- One-time use: marked as used=1 after successful password reset

**Cleanup Strategy**:
- Expired tokens should be cleaned up daily to prevent table growth
- Records auto-deleted when user_id deleted (CASCADE)

**Sample Data**:
```sql
INSERT INTO password_reset_tokens 
  (user_id, token, used, expires_at) VALUES
(1, 'sample_reset_token_abcdef1234567890', 0, DATE_ADD(NOW(), INTERVAL 1 HOUR));
```

---

## User Profile Tables

### 4. `user_personal_infos` Table

Stores basic personal profile information for each user.

**Purpose**: Maintains personal details, biography, and profile image URL

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table (unique per user) |
| full_name | VARCHAR(255) | YES | NULL | User's full name |
| biography | TEXT | YES | NULL | User's biographical information |
| profile_image_url | VARCHAR(512) | YES | NULL | URL to profile image in uploads/ directory |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- UNIQUE KEY (user_id) - one personal info record per user

**Indexes**:
- Primary key on id
- Foreign key index on user_id (for lookups)
- Unique index on user_id (ensures 1:1 relationship)

**Profile Image Storage**:
- Images stored in: `/uploads/profile_images/`
- Filenames encrypted to prevent enumeration
- Max size: 2 MB
- Allowed types: gif, jpg, jpeg, png
- URL stored as absolute path for easy access

**Sample Data**:
```sql
INSERT INTO user_personal_infos 
  (user_id, full_name, biography, profile_image_url) VALUES
(1, 'John Michael Doe', 'Software engineer with 5+ years experience', 
 'http://localhost/alumni/uploads/profile_images/abc123def456.jpg');
```

---

### 5. `user_linkedin_profiles` Table

Stores multiple LinkedIn profile URLs for each user.

**Purpose**: Maintains social media links and professional networking profiles

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| url | VARCHAR(512) | NO | NULL | LinkedIn profile URL |
| label | VARCHAR(255) | YES | NULL | Label or title for this profile (e.g., "Professional", "Personal") |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: url (must provide LinkedIn URL)

**Indexes**:
- Primary key on id
- Foreign key index on user_id (for user's profiles lookups)

**URL Validation**:
- Validated with filter_var(FILTER_VALIDATE_URL)
- Must include http:// or https://
- Examples: https://linkedin.com/in/johndoe, https://linkedin.com/company/acme

**Multiplicity**:
- Users can have multiple LinkedIn profiles
- Each record is a separate LinkedIn URL with optional label

**Sample Data**:
```sql
INSERT INTO user_linkedin_profiles 
  (user_id, url, label) VALUES
(1, 'https://linkedin.com/in/johndoe', 'Personal LinkedIn'),
(1, 'https://linkedin.com/company/acme-corp', 'Company Page');
```

---

### 6. `user_degrees` Table

Stores educational degrees earned by users.

**Purpose**: Maintains academic credentials and degree verification information

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| institution | VARCHAR(255) | NO | NULL | Name of educational institution |
| degree | VARCHAR(255) | NO | NULL | Degree name (e.g., "Bachelor of Science") |
| field | VARCHAR(255) | YES | NULL | Field of study (e.g., "Computer Science") |
| degree_url | VARCHAR(512) | YES | NULL | URL to degree verification or diploma |
| completion_date | DATE | YES | NULL | Degree completion date (YYYY-MM-DD) |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: institution, degree

**Indexes**:
- Primary key on id
- Foreign key index on user_id (for user's degrees lookup)

**URL Validation**:
- degree_url validated with filter_var(FILTER_VALIDATE_URL)
- Must include http:// or https://

**Date Format**:
- ISO 8601 format: YYYY-MM-DD
- Examples: 2020-05-30, 2023-12-15

**Sample Data**:
```sql
INSERT INTO user_degrees 
  (user_id, institution, degree, field, degree_url, completion_date) VALUES
(1, 'University of Technology', 'Bachelor of Science', 'Computer Science', 
 'https://example.com/verify/degree123', '2020-05-30');
```

---

### 7. `user_certifications` Table

Stores professional certifications obtained by users.

**Purpose**: Maintains professional credentials and certification verification

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| title | VARCHAR(255) | NO | NULL | Certification title (e.g., "AWS Solutions Architect") |
| provider | VARCHAR(255) | YES | NULL | Certifying organization (e.g., "Amazon Web Services") |
| cert_url | VARCHAR(512) | YES | NULL | URL to certification verification or badge |
| completion_date | DATE | YES | NULL | Certification date (YYYY-MM-DD) |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: title

**Indexes**:
- Primary key on id
- Foreign key index on user_id

**Supported Certifications Examples**:
- AWS Certified Solutions Architect
- Google Cloud Professional Data Engineer
- Microsoft Azure Administrator
- Oracle Database Administrator
- Kubernetes (CKAD)

**Sample Data**:
```sql
INSERT INTO user_certifications 
  (user_id, title, provider, cert_url, completion_date) VALUES
(1, 'AWS Solutions Architect - Professional', 'Amazon Web Services', 
 'https://aws.amazon.com/verification/abc123', '2023-06-15');
```

---

### 8. `user_licenses` Table

Stores professional licenses held by users.

**Purpose**: Maintains professional licensing information and verification

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| title | VARCHAR(255) | NO | NULL | License name (e.g., "Professional Engineer") |
| issuer | VARCHAR(255) | YES | NULL | Licensing authority (e.g., "State Board of Engineering") |
| license_url | VARCHAR(512) | YES | NULL | URL to license verification or official record |
| completion_date | DATE | YES | NULL | License issuance or effective date |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: title

**Indexes**:
- Primary key on id
- Foreign key index on user_id

**Supported License Types**:
- Professional Engineer (PE)
- Licensed Architect (RA/AIA)
- Certified Public Accountant (CPA)
- Registered Nurse (RN)
- State-specific licenses

**Sample Data**:
```sql
INSERT INTO user_licenses 
  (user_id, title, issuer, license_url, completion_date) VALUES
(1, 'Professional Engineer - Software', 'State Board of Professional Engineers', 
 'https://verify.state.gov/license/PE123456', '2021-03-20');
```

---

### 9. `user_short_courses` Table

Stores short-term training courses and skill development programs.

**Purpose**: Maintains non-degree educational experiences and professional development

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| title | VARCHAR(255) | NO | NULL | Course title |
| provider | VARCHAR(255) | YES | NULL | Course provider (e.g., "Coursera", "Udacity", "LinkedIn Learning") |
| course_url | VARCHAR(512) | YES | NULL | URL to course or certificate |
| completion_date | DATE | YES | NULL | Course completion date |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: title

**Indexes**:
- Primary key on id
- Foreign key index on user_id

**Course Providers**:
- Coursera, Udacity, edX
- LinkedIn Learning, Pluralsight
- Datacamp, Codecademy
- Internal company training

**Sample Data**:
```sql
INSERT INTO user_short_courses 
  (user_id, title, provider, course_url, completion_date) VALUES
(1, 'Advanced Python Programming', 'Coursera', 
 'https://coursera.org/verify/abc123def456', '2023-09-10');
```

---

### 10. `user_employment_history` Table

Stores employment history and work experience records.

**Purpose**: Maintains career progression and professional experience timeline

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

**Schema**:

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| id | INT | NO | AUTO_INCREMENT | Record identifier |
| user_id | INT | NO | NULL | Reference to users table |
| company | VARCHAR(255) | NO | NULL | Employer name |
| role | VARCHAR(255) | NO | NULL | Job title or position |
| start_date | DATE | NO | NULL | Employment start date (YYYY-MM-DD) |
| end_date | DATE | YES | NULL | Employment end date (NULL for current employment) |
| description | TEXT | YES | NULL | Job description, responsibilities, achievements |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Last update timestamp |

**Constraints**:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- NOT NULL: company, role, start_date
- CHECK: end_date >= start_date OR end_date IS NULL (date logic validation)

**Indexes**:
- Primary key on id
- Foreign key index on user_id
- Composite index on (user_id, start_date DESC) for timeline queries

**Current Employment Indicator**:
- end_date = NULL indicates currently employed
- end_date populated with date indicates past employment

**Date Format**:
- ISO 8601 format: YYYY-MM-DD
- start_date must be <= end_date (if end_date provided)

**Sample Data**:
```sql
INSERT INTO user_employment_history 
  (user_id, company, role, start_date, end_date, description) VALUES
(1, 'Tech Company Inc', 'Senior Software Engineer', '2020-06-01', NULL, 
 'Led development of core platform features. Mentored 5 junior developers.'),
(1, 'Startup LLC', 'Full Stack Developer', '2018-01-15', '2020-05-31', 
 'Built and maintained web applications using React and Node.js');
```

---

## Relationships & Constraints

### Entity-Relationship Diagram

```
┌──────────────────┐
│     users        │
│  (1:N parent)    │
├──────────────────┤
│ id (PK)          │
│ email (UNIQUE)   │
│ password         │
│ is_verified      │
│ created_at       │
│ updated_at       │
└────────┬─────────┘
         │ 1
         │ ├─────────────────┐
         ├─N                 │
         │                   │
    ┌────┴──────────┐   ┌────┴──────────────────────┐
    │ email_verify  │   │ password_reset_tokens     │
    ├───────────────┤   ├──────────────────────────┤
    │ id (PK)       │   │ id (PK)                  │
    │ user_id (FK)  │   │ user_id (FK)             │
    │ token(UNIQUE) │   │ token (UNIQUE)           │
    │ used          │   │ used                     │
    │ expires_at    │   │ expires_at               │
    │ created_at    │   │ created_at               │
    └───────────────┘   └──────────────────────────┘

    ┌────────────────┐  ┌──────────────────┐  ┌─────────────────┐
    │ personal_info  │  │ linkedin_profile │  │ degrees        │
    ├────────────────┤  ├──────────────────┤  ├─────────────────┤
    │ id (PK)        │  │ id (PK)          │  │ id (PK)         │
    │ user_id (FK)   │  │ user_id (FK)     │  │ user_id (FK)    │
    │ full_name      │  │ url              │  │ institution     │
    │ biography      │  │ label            │  │ degree          │
    │ image_url      │  │ created_at       │  │ field           │
    │ created_at     │  │ updated_at       │  │ degree_url      │
    │ updated_at     │  └──────────────────┘  │ completion_date │
    └────────────────┘                        │ created_at      │
                                              │ updated_at      │
    ┌──────────────────┐  ┌─────────────┐    └─────────────────┘
    │ certifications   │  │ licenses    │
    ├──────────────────┤  ├─────────────┤     ┌──────────────────┐
    │ id (PK)          │  │ id (PK)     │     │ short_courses   │
    │ user_id (FK)     │  │ user_id(FK) │     ├──────────────────┤
    │ title            │  │ title       │     │ id (PK)          │
    │ provider         │  │ issuer      │     │ user_id (FK)     │
    │ cert_url         │  │ license_url │     │ title            │
    │ completion_date  │  │ completion_ │     │ provider         │
    │ created_at       │  │ date        │     │ course_url       │
    │ updated_at       │  │ created_at  │     │ completion_date  │
    └──────────────────┘  │ updated_at  │     │ created_at       │
                          └─────────────┘     │ updated_at       │
                                              └──────────────────┘

    ┌───────────────────────┐
    │ employment_history    │
    ├───────────────────────┤
    │ id (PK)               │
    │ user_id (FK)          │
    │ company               │
    │ role                  │
    │ start_date            │
    │ end_date              │
    │ description           │
    │ created_at            │
    │ updated_at            │
    └───────────────────────┘
```

### Foreign Key Relationships

All tables with user_id reference the users table with CASCADE DELETE:

```sql
CONSTRAINT fk_user_id 
  FOREIGN KEY (user_id) 
  REFERENCES users(id) 
  ON DELETE CASCADE
```

**Cascade Delete Behavior**:
- When user deleted from users table
- All related profile records automatically deleted
- Ensures referential integrity
- Prevents orphaned records

---

## Indexes & Performance

### Primary Indexes

| Table | Index | Type | Columns | Purpose |
|-------|-------|------|---------|---------|
| users | PRIMARY | BTREE | id | Primary key, auto-increment |
| users | UNIQUE | BTREE | email | Fast email lookup during auth |
| email_verification_tokens | PRIMARY | BTREE | id | Primary key |
| email_verification_tokens | FK | BTREE | user_id | Foreign key lookup |
| email_verification_tokens | UNIQUE | BTREE | token | Fast token lookup during verification |
| password_reset_tokens | PRIMARY | BTREE | id | Primary key |
| password_reset_tokens | FK | BTREE | user_id | Foreign key lookup |
| password_reset_tokens | UNIQUE | BTREE | token | Fast token lookup during reset |

### Profile Table Indexes

| Table | Index | Columns | Purpose |
|-------|-------|---------|---------|
| user_personal_infos | PRIMARY | id | Primary key |
| user_personal_infos | FK | user_id | Foreign key + ensure 1:1 uniqueness |
| user_linkedin_profiles | PRIMARY | id | Primary key |
| user_linkedin_profiles | FK | user_id | Fetch all LinkedIn profiles for user |
| user_degrees | PRIMARY | id | Primary key |
| user_degrees | FK | user_id | Fetch all degrees for user |
| user_certifications | PRIMARY | id | Primary key |
| user_certifications | FK | user_id | Fetch all certifications for user |
| user_licenses | PRIMARY | id | Primary key |
| user_licenses | FK | user_id | Fetch all licenses for user |
| user_short_courses | PRIMARY | id | Primary key |
| user_short_courses | FK | user_id | Fetch all courses for user |
| user_employment_history | PRIMARY | id | Primary key |
| user_employment_history | FK | user_id | Fetch employment history |
| user_employment_history | COMPOSITE | (user_id, start_date DESC) | Timeline chronological queries |

### Query Performance Considerations

**Slow Query Patterns to Avoid**:
- Full table scans on users (use email index)
- Scanning token tables without index (use token index)
- Joining profile tables inefficiently

**Optimized Query Patterns**:
```sql
-- Fast: Uses email UNIQUE index
SELECT * FROM users WHERE email = 'user@university.edu';

-- Fast: Uses token UNIQUE index
SELECT * FROM email_verification_tokens WHERE token = 'abc123...';

-- Fast: Uses foreign key index
SELECT * FROM user_degrees WHERE user_id = 1;

-- Slow: Avoids indexes
SELECT * FROM user_employment_history ORDER BY start_date LIMIT 10;
-- Better: Use user_id in WHERE clause first
SELECT * FROM user_employment_history 
WHERE user_id = 1 
ORDER BY start_date DESC;
```

---

## Data Integrity Rules

### Referential Integrity

1. **Foreign Key Constraints**: All profile tables reference users.id with ON DELETE CASCADE
2. **Unique Constraints**: Email uniqueness in users table prevents duplicate accounts
3. **Token Uniqueness**: Both token types have unique constraints to prevent token collisions

### Business Logic Constraints

| Rule | Enforcement | Validation |
|------|-------------|-----------|
| Email domain whitelist | Application layer | validate_university_email() helper |
| Password minimum length | Application layer | strlen() >= 8 check |
| Email verification before login | Application + is_verified flag | is_verified must = 1 |
| Token expiration | Application layer | expires_at > NOW() check |
| One-time token usage | Application layer + used flag | used must = 0 |
| URL format validation | Application layer | filter_var(FILTER_VALIDATE_URL) |
| Date format validation | Application layer | DateTime::createFromFormat() |
| One personal_info per user | Database unique constraint | UNIQUE(user_id) |

### Timestamp Management

All profile tables maintain temporal data:

```sql
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- Set once at record creation, never changes

updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- Set at creation, automatically updated on any record modification
```

**Usage**:
- Track profile change history
- Enable audit logging
- Display "Last updated" information to users
- Support data recovery/rollback procedures

---

## Migrations & Schema Initialization

### Initial Database Setup

```bash
# Create database
CREATE DATABASE alumni_db CHARACTER SET utf8 COLLATE utf8_general_ci;

# Run database_setup.sql
mysql -u root -p alumni_db < database_setup.sql

# Verify schema
USE alumni_db;
SHOW TABLES;
DESCRIBE users;
```

### Sample Data for Testing

```sql
-- Insert test users
INSERT INTO users (email, password, is_verified) VALUES
('test.user@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Insert personal info
INSERT INTO user_personal_infos (user_id, full_name) VALUES (1, 'Test User');

-- Insert credentials
INSERT INTO user_degrees 
  (user_id, institution, degree, field, completion_date) VALUES
(1, 'Test University', 'Bachelor', 'Computer Science', '2020-05-30');
```

---

## Maintenance & Best Practices

### Regular Maintenance Tasks

1. **Clean Up Expired Tokens**:
   ```sql
   DELETE FROM email_verification_tokens WHERE expires_at < NOW();
   DELETE FROM password_reset_tokens WHERE expires_at < NOW();
   ```

2. **Backup Database**:
   ```bash
   mysqldump -u root -p alumni_db > alumni_db_backup.sql
   ```

3. **Monitor Indexes**:
   ```sql
   ANALYZE TABLE users;
   OPTIMIZE TABLE users;
   ```

### Growth Projections

| Users | Degrees | Certs | Storage |
|-------|---------|-------|---------|
| 1,000 | 2,000 | 1,500 | ~5 MB |
| 10,000 | 20,000 | 15,000 | ~50 MB |
| 100,000 | 200,000 | 150,000 | ~500 MB |
| 1,000,000 | 2,000,000 | 1,500,000 | ~5 GB |

Consider:
- Archive old tokens monthly
- Implement data partitioning for large tables
- Archive inactive users annually

### Disaster Recovery

1. **Point-in-time recovery**: Enable binary logging
2. **Replicas**: Set up master-slave replication
3. **Backup strategy**: Daily full backups + hourly incremental
4. **Test restore**: Monthly restore drills

---

## Security Considerations

### Data Protection

- **Passwords**: Always use bcrypt hashing (PASSWORD_BCRYPT)
- **Tokens**: Generated with cryptographic randomness (random_bytes)
- **URLs**: Stored and validated for XSS prevention
- **PII**: Email and personal info encrypted at rest in production

### Access Control

- **Database User**: Limited privileges (SELECT, INSERT, UPDATE, DELETE only)
- **No Direct Access**: Application mediates all database interactions
- **Session-based**: User_id stored in session, not cookies

### Audit Trail

Consider implementing:
- Audit table for profile changes
- Log queries for sensitive operations
- Track password reset requests
- Monitor failed login attempts

---

## Troubleshooting

### Common Issues

**Issue**: Foreign key constraint error when deleting user
- **Cause**: Profile records still exist (shouldn't happen with CASCADE)
- **Solution**: Check CASCADE is properly set on all foreign keys

**Issue**: Token verification fails
- **Cause**: Token expired or already used
- **Solution**: Check expires_at > NOW() and used = 0

**Issue**: Slow profile queries
- **Cause**: Missing user_id index
- **Solution**: Verify indexes exist on all user_id columns

---

## Changelog

### Version 1.0
- Initial schema with user authentication
- 10 profile-related tables
- Complete cascade delete support
- Token expiration and single-use enforcement
- Temporal data tracking (created_at, updated_at)
