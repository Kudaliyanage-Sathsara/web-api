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


---

## Authentication Endpoints



***==========================================================***: 
### 1. User Registration
***==========================================================***: 


Create a new user account with email and password.

**Endpoint**: `POST /api/Auth/register`

**Test URL :** `POST - http://localhost/web_api/index.php/api/auth/register

**Request Body**:
```json
{
  "email": "thanuja.any_student_id@iit.ac.lk",
  "password": "Secure@pass123456"
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

---



***==========================================================***: 
### 3. User Login
***==========================================================***: 


**Endpoint**: `POST /api/Auth/login`

**Test URL :** ` http://localhost/web_api/index.php/api/auth/login

**Content-Type**: `application/json` or `application/x-www-form-urlencoded`

**Request Body**:
```json
{
  "email": "thanuja.any_student_id@iit.ac.lk",
  "password": "Secure@12345"
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
---


***==========================================================***: 
### 4. User Logout
***==========================================================***: 


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

**Request Body**:
```json
{
  "email": "thanuja.any_student_id@iit.ac.lk"
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
---



***==========================================================***: 
### 6. Reset Password
***==========================================================***: 



**Endpoint**: `POST /api/Auth/reset_password`

**Test URL :** `http://localhost/web_api/index.php/api/auth/reset_password

**Request Body**:
```json
{
  "token": "reset_token_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2",
  "password": "NewSecurepassv1235"
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

---

## Profile Endpoints


***==========================================================***: 
### 7. Get Complete User Profile
***==========================================================***: 


Retrieve all profile information for authenticated user.

**Endpoint**: `GET /api/Profile/get_profile`

**Test URL :** `http://localhost/web_api/index.php/api/profile/get_profile`

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

---


***==========================================================***: 
### 8. Update Personal Profile
***==========================================================***: 



Update user's personal information (name and biography).

**Endpoint**: `POST /api/Profile/update_profile`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_profile`

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

---


***==========================================================***: 
### 9. Upload Profile Image
***==========================================================***: 



**Endpoint**: `POST /api/Profile/upload_profile_image`

**Test URL :** `http://localhost/web_api/index.php/api/profile/upload_profile_image`


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
---

## Profile Sections


***==========================================================***: 
### 10. List Section Records
***==========================================================***: 



List all records in a profile section.

**Endpoint**: `GET /api/Profile/list_section/{section}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/list_section/{section}`

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

**Request Body**:
```json
{
  "degree": "Master of Science",
  "field": "Advanced Computer Science"
}
```
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

**Request Body**:
```json
{
  "institution": "University of Technology",
  "degree": "Master of Science",
  "field": "Advanced Computer Science",
  "degree_url": "https://example.com/degree-verification",
  "completion_date": "2022-05-30"
}


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

**Request Body**:
```json
{
  "title": "AWS Certified Solutions Architect",
  "provider": "Amazon Web Services",
  "cert_url": "https://aws.amazon.com/certification/cert-details",
  "completion_date": "2024-01-15"
}
```

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


**Endpoint**: `POST /api/Profile/update_section/certifications/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/certifications/{id}`

**Request Body**:
```json
{
  "title": "AWS Certified Solutions Architect - Associate",
  "provider": "Amazon Web Services",
  "cert_url": "https://aws.amazon.com/certification/cert-details",
  "completion_date": "2024-01-15"
}
```

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

**Request Body**:
```json
{
  "title": "Professional Engineer License",
  "issuer": "State Board of Professional Engineers",
  "license_url": "https://www.ncees.org/license-verification",
  "completion_date": "2023-06-20"
}
```


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


**Request Body**:
```json
{
  "title": "Professional Engineer License - Renewed",
  "issuer": "State Board of Professional Engineers",
  "license_url": "https://www.ncees.org/license-verification",
  "completion_date": "2023-06-20"
}
```


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


---


***==========================================================***: 
### 28. Update Employment History
***==========================================================***: 


Update existing employment record.

**Endpoint**: `POST /api/Profile/update_section/employment_history/{id}`

**Test URL :** `http://localhost/web_api/index.php/api/profile/update_section/employment_history/{id}`


**Request Body**:
```json
{
  "role": "Lead Software Engineer",
  "end_date": "2024-01-31",
  "description": "Led development of cloud-based microservices architecture, mentored junior developers, and drove architectural decisions"
}
```

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