# Alumni Profile API - Database Schema Documentation

## Overview

The Alumni Profile API database is built on a relational model designed to manage user authentication and comprehensive profile information. The schema enforces data integrity through foreign key relationships and maintains temporal tracking with created_at and updated_at timestamps.

**Database Name**: `alumni_db`

**Database Type**: MySQL.
---

## Table of Contents

1. [Core Authentication Tables](#core-authentication-tables)
2. [User Profile Tables](#user-profile-tables)
3. [Relationships & Constraints](#relationships--constraints)
4. [Entity-Relationship Diagram](#entity-relationship-diagram)

---

## Core Authentication Tables

### 1. users Table

Stores core user account information and authentication credentials.

**Primary Key**: id

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

**Foreign Key**: `user_id` = users.id (ON DELETE CASCADE)

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

---

### 3. `password_reset_tokens` Table

Stores password reset tokens for account recovery.

**Primary Key**: `id`

**Foreign Key**: `user_id` = users.id (ON DELETE CASCADE)

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


---

## User Profile Tables

### 4. `user_personal_infos` Table

Stores basic personal profile information for each user.

**Primary Key**: `id`

**Foreign Key**: `user_id` = users.id (ON DELETE CASCADE)

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

---

### 5. `user_linkedin_profiles` Table

Stores multiple LinkedIn profile URLs for each user.

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

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
- Examples: https://linkedin.com/in/thanuja, https://linkedin.com/company/acme

---

### 6. `user_degrees` Table

Stores educational degrees earned by users.

**Primary Key**: `id`

**Foreign Key**: `user_id` = users.id (ON DELETE CASCADE)

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

---

### 7. `user_certifications` Table

Stores professional certifications obtained by users.

**Primary Key**: `id`

**Foreign Key**: `user_id` = users.id (ON DELETE CASCADE)

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

---

### 8. `user_licenses` Table

Stores professional licenses held by users.

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

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

---

### 9. `user_short_courses` Table

Stores short-term training courses and skill development programs.

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

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

---

### 10. `user_employment_history` Table

Stores employment history and work experience records.

**Primary Key**: `id`

**Foreign Key**: `user_id` → users.id (ON DELETE CASCADE)

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

---

## Relationships & Constraints

# Entity-Relationship Diagram


![App Screenshot](images/Relationship_diagram.png)


path = (C:\xampp\htdocs\web_api\Documentation\images\Relationship_diagram.png) 


### Foreign Key Relationships

All tables with user_id reference the users table with CASCADE DELETE:

```sql
CONSTRAINT fk_user_id 
  FOREIGN KEY (user_id) 
  REFERENCES users(id) 
  ON DELETE CASCADE
```

---




