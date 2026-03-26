# Alumni Profile API - Complete Documentation

## Overview

The Alumni Profile API is a RESTful web service built with CodeIgniter 3 that manages user authentication and comprehensive profile information for alumni profilws. The API enforces university email domain restrictions, implements secure authentication with email verification, and provides complete profile management capabilities.

**Base URL**: `http://localhost/alumni/api/`

---

## Table of Contents

1. [Authentication Endpoints](#authentication-endpoints)
2. [Profile Endpoints](#profile-endpoints)
3. [Profile Sections](#profile-sections)
4. [Response Formats](#response-formats)
5. [Error Handling](#error-handling)
6. [Rate Limiting](#rate-limiting)

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

**Request Parameters**:
- `full_name` (string, optional): User's full name
- `biography` (string, optional): User's biographical information

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

## Response Formats

### Success Response (200, 201)

```json
{
  "message": "Operation successful",
  "data": {}  // Optional, included for data-returning endpoints
}
```

### Error Response (400, 401, 404)

```json
{
  "error": "Description of what went wrong"
}
```

### List Response (200)

```json
[
  { /* record 1 */ },
  { /* record 2 */ }
]
```

Returns empty array `[]` if no records found.

---

## Error Handling

### HTTP Status Codes

| Code | Meaning | When Used |
|------|---------|-----------|
| 200 | OK | Successful GET, POST returning data |
| 201 | Created | Successful POST creating resource |
| 400 | Bad Request | Validation error, missing required fields |
| 401 | Unauthorized | Not authenticated or session expired |
| 404 | Not Found | Resource doesn't exist or unknown endpoint |
| 500 | Internal Server Error | Database or server error |

### Common Error Messages

| Error | Cause | Resolution |
|-------|-------|-----------|
| "Authentication required" | No active session or session expired | Log in again via /api/Auth/login |
| "Invalid university email" | Email domain not authorized | Use @university.edu or @alumni.university.edu |
| "Weak password" | Password less than 8 characters | Use stronger password (8+ chars) |
| "Email already exists" | Email registered to another account | Use different email or reset password |
| "Invalid or expired token" | Token invalid, used, or expired (24h) | Request new registration or reset |
| "Item not found" | Record doesn't exist or belongs to different user | Verify record ID |
| "Invalid [field] URL" | URL format invalid | Ensure URL includes http:// or https:// |
| "Invalid date format" | Date not YYYY-MM-DD | Format dates as YYYY-MM-DD |

---

## Rate Limiting

- **Limit**: 60 requests per minute per IP address
- **Window**: 1 minute (60 seconds)
- **Headers**: Rate limit info in response headers (when implemented)

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

---

## API Usage Examples

### Complete Registration Flow

```bash
# 1. Register user
curl -X POST http://localhost/alumni/api/Auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john.doe@university.edu",
    "password": "SecurePass123"
  }'

# Response
{
  "message": "Registration successful. Verify email.",
  "token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2"
}

# 2. Verify email
curl -X GET "http://localhost/alumni/api/Auth/verify_email?token=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2"

# Response
{
  "message": "Email verified"
}

# 3. Login
curl -X POST http://localhost/alumni/api/Auth/login \
  -H "Content-Type: application/json" \
  -c cookies.txt \
  -d '{
    "email": "john.doe@university.edu",
    "password": "SecurePass123"
  }'

# Response
{
  "message": "Login successful"
}

# 4. Get profile
curl -X GET http://localhost/alumni/api/Profile/get_profile \
  -b cookies.txt

# Response
{
  "personal": { ... },
  "linkedin": [],
  "degrees": [],
  ...
}
```

### Add and Update Profile Sections

```bash
# Add degree
curl -X POST http://localhost/alumni/api/Profile/add_section/degrees \
  -H "Content-Type: application/json" \
  -b cookies.txt \
  -d '{
    "institution": "University of Technology",
    "degree": "Bachelor of Science",
    "field": "Computer Science",
    "completion_date": "2020-05-30"
  }'

# Upload profile image
curl -X POST http://localhost/alumni/api/Profile/upload_profile_image \
  -F "profile_image=@/path/to/image.jpg" \
  -b cookies.txt

# Update degree
curl -X POST http://localhost/alumni/api/Profile/update_section/degrees/1 \
  -H "Content-Type: application/json" \
  -b cookies.txt \
  -d '{
    "degree": "Master of Science"
  }'
```

---

## Changelog

### Version 1.0 (Current)
- Authentication endpoints (register, verify, login, logout, password reset)
- Profile management (personal info, image upload)
- Profile sections (degrees, certifications, licenses, courses, employment)
- LinkedIn profile integration
- Complete validation and error handling

---

## Support & Feedback

For issues, bugs, or feature requests, please contact the development team or submit an issue to the project repository.
