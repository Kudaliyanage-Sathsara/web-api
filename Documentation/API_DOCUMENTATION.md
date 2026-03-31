# Alumni Profile API - Complete Documentation

## Overview

The Alumni Profile API is a RESTful web service built with CodeIgniter 3 that manages user authentication and comprehensive profile information for alumni profilws. The API enforces university email domain restrictions, implements secure authentication with email verification, and provides complete profile management capabilities.

**Base URL**: `http://localhost/alumni/api/`

---

## Table of Contents

1. [Authentication Endpoints](#authentication-endpoints)
2. [Profile Endpoints](#profile-endpoints)
3. [Profile Sections](#profile-sections)
4. [LinkedIn Profile Management](#linkedin-profile-management)
5. [Degrees Management](#degrees-management)
6. [Certifications Management](#certifications-management)
7. [Licenses Management](#licenses-management)
8. [Employment History Management](#employment-history-management)
9. [Response Formats](#response-formats)
10. [Error Handling](#error-handling)
11. [Rate Limiting](#rate-limiting)

---

## Authentication Endpoints



***==========================================================***: 
### 1. User Registration
***==========================================================***: 


Create a new user account with email and password.

**Endpoint**: `POST /api/Auth/register`

**Test URL :** `POST - http://localhost/web_api/index.php/api/auth/register

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "email": "sathsara.vx@university.edu",
  "password": "Secure@Pass123"
}
```
**Response**:
```json
{
  "message": "Registration successful. Verify email.",
  "token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2"
}
```

**Error Responses (400 Bad Request)**:
```json
// Invalid email domain
{
  "error": "Invalid university email"
}

// Password too weak
{
  "error": "Weak password or Password must include uppercase, lowercase, number, and special character"
}

// Email already registered
{
  "error": "Email already exists"
}
```

**Validation Rules**:
- Email must match pattern: `*@university.edu` or `*@alumni.university.edu`
- Password must be at least 8 characters long
- Email must be unique in the database

**Security Notes**:
- Passwords are hashed using bcrypt (PASSWORD_BCRYPT)
- Verification token is 64-character hex string (256 bits of entropy)
- Token expires after 24 hours
- Tokens can only be used once

---




***==========================================================***: 
### 2. Email Verification
***==========================================================***: 


Verify user email address using verification token sent during registration.

**Endpoint**: `GET /api/Auth/verify_email?token=TOKEN`

**Test URL :** `http://localhost/web_api/index.php/api/auth/verify_email?token=token-from-register-response


**Response (200 OK)**:
```json
{
  "message": "Email verified"
}
```

**Error Response (400 Bad Request)**:
```json
{
  "error": "Invalid or expired token"
}
```

**Token Validation**:
- Token must exist in email_verification_tokens table
- Token must have used=0 (not yet used)
- Can only verify once per token

---



***==========================================================***: 
### 3. User Login
***==========================================================***: 


Authenticate user with email and password, creates server session.

**Endpoint**: `POST /api/Auth/login`

**Test URL :** ` http://localhost/web_api/index.php/api/auth/login

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "email": "sathsara.vx@university.edu",
  "password": "Secure@Pass123"
}
```

**Response (200 OK)**:
```json
{
  "message": "Login successful"
}
```

**Error Responses (401 Unauthorized)**:
```json
// User not found
{
  "error": "User not found"
}

// Email not verified
{
  "error": "Email not verified"
}

// Invalid password
{
  "error": "Invalid password"
}
```

**Authentication Requirements**:
- User account must exist in database
- User must have is_verified=1
- Password must match stored bcrypt hash using password_verify()

**Session Creation**:
- Sets session userdata: user_id, email, logged_in=true
- Session timeout: 1800 seconds (30 minutes) configurable in config.php

---


***==========================================================***: 
### 4. User Logout
***==========================================================***: 


Destroy user session and log out.

**Endpoint**: `POST /api/Auth/logout`

**Test URL :** ` http://localhost/web_api/index.php/api/auth/logout

**Authentication**: Session required

**Request Body**: Empty

**Response (200 OK)**:
```json
{
  "message": "Logged out"
}
```

---


***==========================================================***: 
### 5. Request Password Reset
***==========================================================***: 


Initiate password reset process for user account.

**Endpoint**: `POST /api/Auth/request_reset`

**Test URL :** `http://localhost/web_api/index.php/api/auth/request_reset

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "email": "sathsara.vx@university.edu"
}
```

**Response (200 OK)**:
```json
{
  "message": "Reset email sent",
  "token": "reset_token_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2"
}
```

**Error Response (400 Bad Request)**:
```json
{
  "error": "Email not found"
}
```

**Token Details**:
- Generated using generate_secure_token() (64-char hex string)
- Stored in password_reset_tokens table
- Expires after 1 hour (3600 seconds)
- Can only be used once (used flag)

---



***==========================================================***: 
### 6. Reset Password
***==========================================================***: 




Update user password using reset token.

**Endpoint**: `POST /api/Auth/reset_password`

**Test URL :** `http://localhost/web_api/index.php/api/auth/reset_password

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "token": "reset_token_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2",
  "password": "NewSecurePass456"
}
```

**Response (200 OK)**:
```json
{
  "message": "Password updated"
}
```

**Error Response (400 Bad Request)**:
```json
{
  "error": "Invalid token"
}
```

**Token Validation**:
- Token must exist in password_reset_tokens table
- Token must have used=0 (not yet used)
- Token must not be expired (expires_at > NOW())

**Password Update Process**:
- New password is hashed using bcrypt (PASSWORD_BCRYPT)
- Updates users.password with new hash
- Marks token as used (used=1) to prevent reuse
- User can immediately log in with new password

---

## Profile Endpoints


***==========================================================***: 
### 7. Get Complete User Profile
***==========================================================***: 


Retrieve all profile information for authenticated user.

**Endpoint**: `GET /api/Profile/get_profile`

**Test URL :** `http://localhost/web_api/index.php/api/profile/get_profile`

**Authentication**: Session required (must be logged in)

**Request Body**: Empty

**Response (200 OK)**:
```json
{
  "personal": {
    "id": 1,
    "user_id": 1,
    "full_name": "John Doe",
    "biography": "Passionate about technology and innovation",
    "profile_image_url": "http://localhost/alumni/uploads/profile_images/abc123def456.jpg",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00"
  },
  "linkedin": [
    {
      "id": 1,
      "user_id": 1,
      "url": "https://linkedin.com/in/johndoe",
      "label": "LinkedIn Profile",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ],
  "degrees": [
    {
      "id": 1,
      "user_id": 1,
      "institution": "University of Technology",
      "degree": "Bachelor of Science",
      "field": "Computer Science",
      "degree_url": "https://example.com/degree-verification",
      "completion_date": "2020-05-30",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00"
    }
  ],
  "certifications": [],
  "licenses": [],
  "short_courses": [],
  "employment_history": []
}
```

**Error Response (401 Unauthorized)**:
```json
{
  "error": "Authentication required"
}
```

**Data Structure**:
- Returns complete profile with all sections populated
- Returns empty arrays for sections with no records
- All timestamps in ISO 8601 format (YYYY-MM-DD HH:MM:SS)

---


***==========================================================***: 
### 8. Update Personal Profile
***==========================================================***: 



Update user's personal information (name and biography).

**Endpoint**: `POST /api/Profile/update_profile`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_profile`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "full_name": "John Michael Doe",
  "biography": "Software Engineer with 5+ years of experience"
}
```

**Response (200 OK)**:
```json
{
  "message": "Profile updated"
}
```

**Error Response (401 Unauthorized)**:
```json
{
  "error": "Authentication required"
}
```

**Behavior**:
- Creates user_personal_infos record if doesn't exist
- Updates existing record if already exists
- Preserves profile_image_url (doesn't overwrite)
- Sets updated_at timestamp automatically

---


***==========================================================***: 
### 9. Upload Profile Image
***==========================================================***: 



Upload and store user profile picture.

**Endpoint**: `POST /api/Profile/upload_profile_image`

**Test URL :** `http://localhost/web_api/index.php/api/profile/upload_profile_image`

**Authentication**: Session required

**Content-Type**: `multipart/form-data`

**Request Body**:
```
FILE: profile_image (multipart file upload)
```

**Request Parameters**:
- `profile_image` (file, required): Image file to upload

**Upload Constraints**:
- Maximum file size: 2 MB
- Allowed types: gif, jpg, jpeg, png
- Filenames encrypted to prevent collisions
- Stored in: `/uploads/profile_images/`

**Response (200 OK)**:
```json
{
  "message": "Image uploaded",
  "profile_image_url": "http://localhost/alumni/uploads/profile_images/abc123def456.jpg"
}
```

**Error Responses**:
```json
// No file provided
{
  "error": "No file uploaded"
}

// Invalid file type or size
{
  "error": "The filetype you are attempting to upload is not allowed.<br />"
}
```

**File Upload Process**:
1. Validates file exists in $_FILES
2. Creates upload directory if missing (mode 0755)
3. Configures CodeIgniter Upload library
4. Encrypts filename to prevent name collisions
5. Stores file on disk
6. Generates public URL using base_url()
7. Updates or creates user_personal_infos record
8. Returns public accessible URL
---

## Profile Sections

The following endpoints handle profile sections (degrees, certifications, etc). Each section uses the same CRUD patterns.

### Section Names and Table Mappings

| Section | Database Table | Fields |
|---------|----------------|--------|
| degrees | user_degrees | institution, degree, field, degree_url, completion_date |
| certifications | user_certifications | title, provider, cert_url, completion_date |
| licenses | user_licenses | title, issuer, license_url, completion_date |
| short_courses | user_short_courses | title, provider, course_url, completion_date |
| employment_history | user_employment_history | company, role, start_date, end_date, description |



***==========================================================***: 
### 10. List Section Records
***==========================================================***: 



List all records in a profile section.

**Endpoint**: `GET /api/Profile/list_section/{section}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/list_section/{section}`

**Authentication**: Session required

**URL Parameters**:
- `section` (string, required): Section name (degrees, certifications, licenses, short_courses, employment_history)

**Response (200 OK)**:
```json
[
  {
    "id": 1,
    "user_id": 1,
    "institution": "University of Technology",
    "degree": "Bachelor of Science",
    "field": "Computer Science",
    "degree_url": "https://example.com/verify",
    "completion_date": "2020-05-30",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00"
  }
]
```

**Error Responses**:
```json
// Invalid section
{
  "error": "Unknown section"
}

// Not authenticated
{
  "error": "Authentication required"
}
```

---


***==========================================================***: 
### 11. Add Section Record
***==========================================================***: 


Create new record in a profile section.

**Endpoint**: `POST /api/Profile/add_section/{section}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_section/{section}`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Example: Add Degree**:
```json
{
  "institution": "University of Technology",
  "degree": "Bachelor of Science",
  "field": "Computer Science",
  "degree_url": "https://example.com/verify",
  "completion_date": "2020-05-30"
}
```

**Example: Add Employment**:
```json
{
  "company": "Tech Company Inc",
  "role": "Senior Software Engineer",
  "start_date": "2020-06-01",
  "end_date": null,
  "description": "Led development of core platform systems"
}
```

**Field Validation**:

| Section | Required Fields | Optional Fields |
|---------|-----------------|-----------------|
| degrees | institution, degree | field, degree_url, completion_date |
| certifications | title | provider, cert_url, completion_date |
| licenses | title | issuer, license_url, completion_date |
| short_courses | title | provider, course_url, completion_date |
| employment_history | company, role, start_date | end_date, description |

**URL Validation**:
- All URL fields validated using filter_var(FILTER_VALIDATE_URL)
- Must include http:// or https:// protocol
- Examples: https://example.com, https://example.com/path

**Date Validation**:
- Format: YYYY-MM-DD (ISO 8601 standard)
- Optional unless specified as required
- Examples: 2020-05-30, 2024-01-15

**Response (200 OK)**:
```json
{
  "message": "Degrees item created"
}
```

**Error Responses (400 Bad Request)**:
```json
// Missing required field
{
  "error": "Institution and degree are required"
}

// Invalid URL
{
  "error": "Invalid degree URL"
}

// Invalid date format
{
  "error": "Invalid completion date, use YYYY-MM-DD"
}

// Unknown section
{
  "error": "Unknown section"
}
```

---


***==========================================================***: 
### 12. Update Section Record
***==========================================================***: 


Update existing record in a profile section.

**Endpoint**: `POST /api/Profile/update_section/{section}/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/{section}/{id}`

**Authentication**: Session required

**URL Parameters**:
- `section` (string, required): Section name
- `id` (integer, required): Record ID to update

**Request Body**:
```json
{
  "degree": "Master of Science",
  "field": "Advanced Computer Science"
}
```

**Update Behavior**:
- All fields are optional
- Provided fields overwrite existing values
- Omitted fields retain their current values
- Cannot modify: id, user_id, created_at
- Cannot modify: updated_at (auto-updated)

**Response (200 OK)**:
```json
{
  "message": "Degrees item updated"
}
```

**Error Responses (400/404)**:
```json
// Record not found or doesn't belong to user
{
  "error": "Item not found"
}

// Validation error
{
  "error": "Invalid degree URL"
}
```

---


***==========================================================***: 
### 13. Delete Section Record
***==========================================================***: 


Remove record from a profile section.

**Endpoint**: `POST /api/Profile/delete_section/{section}/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_section/{section}/{id}`

**Authentication**: Session required

**URL Parameters**:
- `section` (string, required): Section name
- `id` (integer, required): Record ID to delete

**Response (200 OK)**:
```json
{
  "message": "Degrees item deleted"
}
```

**Error Response (404 Not Found)**:
```json
{
  "error": "Item not found"
}
```

---

## LinkedIn Profile Management


***==========================================================***: 
### 14. Add LinkedIn Profile
***==========================================================***: 


Add LinkedIn profile URL and label.

**Endpoint**: `POST /api/Profile/add_linkedin`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_linkedin`

**Authentication**: Session required

**Request Body**:
```json
{
  "url": "https://linkedin.com/in/johndoe",
  "label": "LinkedIn Profile"
}
```

**Request Parameters**:
- `url` (string, required): LinkedIn profile URL
- `label` (string, optional): Label or title for the profile

**Response (200 OK)**:
```json
{
  "message": "LinkedIn profile added"
}
```

**Error Response (400 Bad Request)**:
```json
{
  "error": "Valid LinkedIn URL is required"
}
```

**Notes**:
- Users can have multiple LinkedIn profiles
- URL must be valid using filter_var(FILTER_VALIDATE_URL)

---


***==========================================================***: 
### 15. List LinkedIn Profiles
***==========================================================***: 


Retrieve all LinkedIn profiles for user.

**Endpoint**: `GET /api/Profile/list_linkedin`

**Test URL :** `http://localhost/web_api/index.php/api/profile/list_linkedin`

**Authentication**: Session required

**Response (200 OK)**:
```json
[
  {
    "id": 1,
    "user_id": 1,
    "url": "https://linkedin.com/in/johndoe",
    "label": "LinkedIn Profile",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00"
  }
]
```

---


***==========================================================***: 
### 16. Update LinkedIn Profile
***==========================================================***: 



Update existing LinkedIn profile entry.

**Endpoint**: `POST /api/Profile/update_linkedin/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_linkedin/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): LinkedIn profile record ID

**Request Body**:
```json
{
  "url": "https://linkedin.com/in/johndoe2",
  "label": "Professional Profile"
}
```

**Response (200 OK)**:
```json
{
  "message": "LinkedIn profile updated"
}
```

**Error Response**:
```json
{
  "error": "LinkedIn entry not found"
}
```

---


***==========================================================***: 
### 17. Delete LinkedIn Profile
***==========================================================***: 


Remove LinkedIn profile entry.

**Endpoint**: `POST /api/Profile/delete_linkedin/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_linkedin/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): LinkedIn profile record ID

**Response (200 OK)**:
```json
{
  "message": "LinkedIn profile removed"
}
```

**Error Response (404)**:
```json
{
  "error": "LinkedIn entry not found"
}
```

---

## Degrees Management


***==========================================================***: 
### 18. Add Degree
***==========================================================***: 


Add a new degree to user profile.

**Endpoint**: `POST /api/Profile/add_section/degrees`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_section/degrees`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "institution": "University of Technology",
  "degree": "Bachelor of Science",
  "field": "Computer Science",
  "degree_url": "https://example.com/degree-verification",
  "completion_date": "2020-05-30"
}
```

**Request Parameters**:
- `institution` (string, required): University or institution name
- `degree` (string, required): Degree type (e.g., Bachelor of Science, Master of Arts)
- `field` (string, optional): Field of study or major
- `degree_url` (string, optional): URL to degree verification
- `completion_date` (string, optional): Graduation date in YYYY-MM-DD format

**Response (200 OK)**:
```json
{
  "message": "Degrees item created"
}
```

**Error Responses (400 Bad Request)**:
```json
// Missing required field
{
  "error": "Institution and degree are required"
}

// Invalid URL
{
  "error": "Invalid degree URL"
}

// Invalid date format
{
  "error": "Invalid completion date, use YYYY-MM-DD"
}
```

---


***==========================================================***: 
### 19. Update Degree
***==========================================================***: 


Update existing degree record.

**Endpoint**: `POST /api/Profile/update_section/degrees/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/degrees/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Degree record ID

**Request Body**:
```json
{
  "institution": "University of Technology",
  "degree": "Master of Science",
  "field": "Advanced Computer Science",
  "degree_url": "https://example.com/degree-verification",
  "completion_date": "2022-05-30"
}
```

**Update Behavior**:
- All fields are optional
- Provided fields overwrite existing values
- Omitted fields retain their current values
- Cannot modify: id, user_id, created_at
- Cannot modify: updated_at (auto-updated)

**Response (200 OK)**:
```json
{
  "message": "Degrees item updated"
}
```

**Error Responses (400/404)**:
```json
// Record not found or doesn't belong to user
{
  "error": "Item not found"
}

// Validation error
{
  "error": "Invalid degree URL"
}
```

---


***==========================================================***: 
### 20. Delete Degree
***==========================================================***: 


Remove degree from user profile.

**Endpoint**: `POST /api/Profile/delete_section/degrees/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_section/degrees/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Degree record ID to delete

**Response (200 OK)**:
```json
{
  "message": "Degrees item deleted"
}
```

**Error Response (404 Not Found)**:
```json
{
  "error": "Item not found"
}
```

---

## Certifications Management


***==========================================================***: 
### 21. Add Certification
***==========================================================***: 


Add a new certification to user profile.

**Endpoint**: `POST /api/Profile/add_section/certifications`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_section/certifications`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "title": "AWS Certified Solutions Architect",
  "provider": "Amazon Web Services",
  "cert_url": "https://aws.amazon.com/certification/cert-details",
  "completion_date": "2024-01-15"
}
```

**Request Parameters**:
- `title` (string, required): Certification title
- `provider` (string, optional): Certification provider
- `cert_url` (string, optional): URL to certification details
- `completion_date` (string, optional): Completion date in YYYY-MM-DD format

**Response (200 OK)**:
```json
{
  "message": "Certifications item created"
}
```

**Error Responses (400 Bad Request)**:
```json
// Missing required field
{
  "error": "Title is required"
}

// Invalid URL
{
  "error": "Invalid cert URL"
}

// Invalid date format
{
  "error": "Invalid completion date, use YYYY-MM-DD"
}
```

---


***==========================================================***: 
### 22. Update Certification
***==========================================================***: 


Update existing certification record.

**Endpoint**: `POST /api/Profile/update_section/certifications/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/certifications/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Certification record ID

**Request Body**:
```json
{
  "title": "AWS Certified Solutions Architect - Associate",
  "provider": "Amazon Web Services",
  "cert_url": "https://aws.amazon.com/certification/cert-details",
  "completion_date": "2024-01-15"
}
```

**Update Behavior**:
- All fields are optional
- Provided fields overwrite existing values
- Omitted fields retain their current values
- Cannot modify: id, user_id, created_at
- Cannot modify: updated_at (auto-updated)

**Response (200 OK)**:
```json
{
  "message": "Certifications item updated"
}
```

**Error Responses (400/404)**:
```json
// Record not found or doesn't belong to user
{
  "error": "Item not found"
}

// Validation error
{
  "error": "Invalid cert URL"
}
```

---


***==========================================================***: 
### 23. Delete Certification
***==========================================================***: 


Remove certification from user profile.

**Endpoint**: `POST /api/Profile/delete_section/certifications/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_section/certifications/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Certification record ID to delete

**Response (200 OK)**:
```json
{
  "message": "Certifications item deleted"
}
```

**Error Response (404 Not Found)**:
```json
{
  "error": "Item not found"
}
```

---

## Licenses Management


***==========================================================***: 
### 24. Add License
***==========================================================***: 


Add a new professional license to user profile.

**Endpoint**: `POST /api/Profile/add_section/licenses`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_section/licenses`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "title": "Professional Engineer License",
  "issuer": "State Board of Professional Engineers",
  "license_url": "https://www.ncees.org/license-verification",
  "completion_date": "2023-06-20"
}
```

**Request Parameters**:
- `title` (string, required): License title
- `issuer` (string, optional): License issuing authority
- `license_url` (string, optional): URL to license verification
- `completion_date` (string, optional): Issue or completion date in YYYY-MM-DD format

**Response (200 OK)**:
```json
{
  "message": "Licenses item created"
}
```

**Error Responses (400 Bad Request)**:
```json
// Missing required field
{
  "error": "Title is required"
}

// Invalid URL
{
  "error": "Invalid license URL"
}

// Invalid date format
{
  "error": "Invalid completion date, use YYYY-MM-DD"
}
```

---


***==========================================================***: 
### 25. Update License
***==========================================================***: 


Update existing professional license record.

**Endpoint**: `POST /api/Profile/update_section/licenses/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/licenses/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): License record ID

**Request Body**:
```json
{
  "title": "Professional Engineer License - Renewed",
  "issuer": "State Board of Professional Engineers",
  "license_url": "https://www.ncees.org/license-verification",
  "completion_date": "2023-06-20"
}
```

**Update Behavior**:
- All fields are optional
- Provided fields overwrite existing values
- Omitted fields retain their current values
- Cannot modify: id, user_id, created_at
- Cannot modify: updated_at (auto-updated)

**Response (200 OK)**:
```json
{
  "message": "Licenses item updated"
}
```

**Error Responses (400/404)**:
```json
// Record not found or doesn't belong to user
{
  "error": "Item not found"
}

// Validation error
{
  "error": "Invalid license URL"
}
```

---


***==========================================================***: 
### 26. Delete License
***==========================================================***: 


Remove professional license from user profile.

**Endpoint**: `POST /api/Profile/delete_section/licenses/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_section/licenses/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): License record ID to delete

**Response (200 OK)**:
```json
{
  "message": "Licenses item deleted"
}
```

**Error Response (404 Not Found)**:
```json
{
  "error": "Item not found"
}
```

---

## Employment History Management


***==========================================================***: 
### 27. Add Employment History
***==========================================================***: 


Add a new employment record to user profile.

**Endpoint**: `POST /api/Profile/add_section/employment_history`

**Test URL :** `http://localhost/web_api/index.php/api/profile/add_section/employment_history`

**Authentication**: Session required

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "company": "Tech Innovation Corp",
  "role": "Senior Software Engineer",
  "start_date": "2021-03-15",
  "end_date": null,
  "description": "Led development of cloud-based microservices architecture and mentored junior developers"
}
```

**Request Parameters**:
- `company` (string, required): Company name
- `role` (string, required): Job title/position
- `start_date` (string, required): Employment start date in YYYY-MM-DD format
- `end_date` (string, optional): Employment end date in YYYY-MM-DD format (null for current employment)
- `description` (string, optional): Job description or responsibilities

**Response (200 OK)**:
```json
{
  "message": "Employment history item created"
}
```

**Error Responses (400 Bad Request)**:
```json
// Missing required field
{
  "error": "Company and role are required"
}

// Invalid date format
{
  "error": "Invalid start_date, use YYYY-MM-DD"
}

// Invalid date logic
{
  "error": "End date cannot be before start date"
}
```

**Notes**:
- `end_date` can be null for current/ongoing employment
- `start_date` is mandatory
- Date format must be YYYY-MM-DD (ISO 8601)

---


***==========================================================***: 
### 28. Update Employment History
***==========================================================***: 


Update existing employment record.

**Endpoint**: `POST /api/Profile/update_section/employment_history/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/employment_history/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Employment history record ID

**Request Body**:
```json
{
  "role": "Lead Software Engineer",
  "end_date": "2024-01-31",
  "description": "Led development of cloud-based microservices architecture, mentored junior developers, and drove architectural decisions"
}
```

**Update Behavior**:
- All fields are optional
- Provided fields overwrite existing values
- Omitted fields retain their current values
- Cannot modify: id, user_id, created_at
- Cannot modify: updated_at (auto-updated)

**Response (200 OK)**:
```json
{
  "message": "Employment history item updated"
}
```

**Error Responses (400/404)**:
```json
// Record not found or doesn't belong to user
{
  "error": "Item not found"
}

// Validation error
{
  "error": "Invalid end_date, use YYYY-MM-DD"
}
```

---


***==========================================================***: 
### 29. Delete Employment History
***==========================================================***: 


Remove employment record from user profile.

**Endpoint**: `POST /api/Profile/delete_section/employment_history/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/delete_section/employment_history/{id}`

**Authentication**: Session required

**URL Parameters**:
- `id` (integer, required): Employment history record ID to delete

**Response (200 OK)**:
```json
{
  "message": "Employment history item deleted"
}
```

**Error Response (404 Not Found)**:
```json
{
  "error": "Item not found"
}
```

---

## Security Considerations

### Authentication
- Email verification required before login
- Sessions stored server-side in PHP sessions
- Session timeout: 30 minutes (configurable)
- Tokens use 256-bit cryptographic randomness

### Password Security
- Passwords hashed with bcrypt (PASSWORD_BCRYPT)
- Password verification using password_verify()
- Password reset tokens expire after 1 hour
- Tokens can only be used once

### Data Protection
- All URLs validated (filter_var with FILTER_VALIDATE_URL)
- File uploads restricted to image types only (gif, jpg, jpeg, png)
- Profile images stored outside web root when possible
- User data isolated by user_id (no cross-user access)

### Input Validation
- All inputs XSS-escaped via CodeIgniter $this->input->post('field', TRUE)
- Email domain whitelist enforcement
- URL format validation
- Date format validation (YYYY-MM-DD)

## Support & Feedback

For issues, bugs, or feature requests, please contact the development team or submit an issue to the project repository.
