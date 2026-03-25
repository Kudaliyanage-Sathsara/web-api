# API Test URLs for Postman

## 1. Auth

### 1.1 Register (optional)
POST http://localhost/web_api/index.php/api/auth/register
Body (JSON):
{
  "email":"john.doe@university.edu",
  "password":"password123"
}

Response:
{
  "message":"Registration successful. Verify email.",
  "token":"..."
}

### 1.2 Login
POST http://localhost/web_api/index.php/api/auth/login
Body (JSON):
{
  "email":"john.doe@university.edu",
  "password":"password123"
}

Response:
{
  "message":"Login successful"
}

### 1.3 Verify email
GET http://localhost/web_api/index.php/api/auth/verify_email?token={token-from-register-response}

Response:
{
  "message":"Email verified"
}

### 1.4 Request password reset
POST http://localhost/web_api/index.php/api/auth/request_reset
Body (JSON):
{
  "email":"john.doe@university.edu"
}

Response:
{
  "message":"Reset email sent",
  "token":"sample_reset_token_abcdef1234567890"
}

### 1.5 Reset password
POST http://localhost/web_api/index.php/api/auth/reset_password
Body (JSON):
{
  "token":"sample_reset_token_abcdef1234567890",
  "password":"newPassword123"
}

Response:
{
  "message":"Password updated"
}

### 1.6 Logout
POST http://localhost/web_api/index.php/api/auth/logout

Response:
{
  "message":"Logged out"
}

## 2. Profile

> Ensure login cookie/session is active in Postman.

### 2.1 Get profile (all sections)
GET http://localhost/web_api/index.php/api/profile/get_profile

Response:
{
  "personal": {"id":1,"user_id":1,"full_name":"John Doe","biography":"...","profile_image_url":"...","created_at":"...","updated_at":"..."},
  "linkedin": [ ... ],
  "degrees": [ ... ],
  "certifications": [ ... ],
  "licenses": [ ... ],
  "short_courses": [ ... ],
  "employment_history": [ ... ]
}

### 2.2 Update profile (personal fields)
POST http://localhost/web_api/index.php/api/profile/update_profile
Body (JSON):
{
  "full_name":"John Doe",
  "biography":"Engineer"
}

Response:
{
  "message":"Profile updated"
}

### 2.3 Upload profile image
POST http://localhost/web_api/index.php/api/profile/upload_profile_image
Body: form-data, key: `profile_image` (Type=file)

Response:
{
  "message":"Image uploaded",
  "profile_image_url":"http://localhost/web_api/uploads/profile_images/xxxx.jpg"
}

## 3. LinkedIn entries

### 3.1 Add LinkedIn
POST http://localhost/web_api/index.php/api/profile/add_linkedin
Body (JSON):
{
  "url":"https://linkedin.com/in/john",
  "label":"Personal"
}

Response:
{
  "message":"LinkedIn profile added"
}

### 3.2 List LinkedIn
GET http://localhost/web_api/index.php/api/profile/list_linkedin

Response:
[ {"id":1,"user_id":1,"url":"https://linkedin.com/in/john","label":"Personal","created_at":"...","updated_at":"..."} ]

### 3.3 Update LinkedIn
POST http://localhost/web_api/index.php/api/profile/update_linkedin
Body (JSON):
{
  "id": 1,
  "url":"https://linkedin.com/in/john-doe",
  "label":"Work"
}

Response:
{
  "message":"LinkedIn profile updated"
}

### 3.4 Delete LinkedIn
POST http://localhost/web_api/index.php/api/profile/delete_linkedin
Body (JSON):
{
  "id": 1
}

Response:
{
  "message":"LinkedIn profile removed"
}

## 4. Multi-entry sections (degrees, certifications, licenses, short_courses, employment_history)

### 4.1 Add Degree:
POST http://localhost/web_api/index.php/api/profile/add_section/degrees
Body (JSON):
{
  "institution":"University X",
  "degree":"BSc Computer Science",
  "field":"Computer Science",
  "degree_url":"https://universityx.edu/degree",
  "completion_date":"2020-05-30"
}

Response:
{
  "message":"Degrees item created"
}

### 4.2 List Degrees:
GET http://localhost/web_api/index.php/api/profile/list_section/degrees

Response:
[ {"id":1,"user_id":1,"institution":"University X","degree":"BSc Computer Science","field":"Computer Science","degree_url":"https://universityx.edu/degree","completion_date":"2020-05-30","created_at":"...","updated_at":"..."} ]

### 4.3 Update Degree:
POST http://localhost/web_api/index.php/api/profile/update_section/degrees/1
Body (JSON):
{
  "field":"Software Engineering"
}

Response:
{
  "message":"Degrees item updated"
}

### 4.4 Delete Degree:
POST http://localhost/web_api/index.php/api/profile/delete_section/degrees/1

Response:
{
  "message":"Degrees item deleted"
}

### 4.5 Add Certification:
POST http://localhost/web_api/index.php/api/profile/add_section/certifications
Body (JSON):
{
  "title":"AWS Solutions Architect",
  "provider":"Amazon Web Services",
  "cert_url":"https://aws.amazon.com/certification",
  "completion_date":"2021-03-15"
}

Response:
{
  "message":"Certifications item created"
}

### 4.6 List Certifications:
GET http://localhost/web_api/index.php/api/profile/list_section/certifications

Response:
[ {"id":1,"user_id":1,"title":"AWS Solutions Architect","provider":"Amazon Web Services","cert_url":"https://aws.amazon.com/certification","completion_date":"2021-03-15","created_at":"...","updated_at":"..."} ]

### 4.7 Update Certification:
POST http://localhost/web_api/index.php/api/profile/update_section/certifications/1
Body (JSON):
{
  "provider":"AWS Training Partners"
}

Response:
{
  "message":"Certifications item updated"
}

### 4.8 Delete Certification:
POST http://localhost/web_api/index.php/api/profile/delete_section/certifications/1

Response:
{
  "message":"Certifications item deleted"
}

### 4.9 Add License:
POST http://localhost/web_api/index.php/api/profile/add_section/licenses
Body (JSON):
{
  "title":"Professional Engineer License",
  "issuer":"Professional Engineering Board",
  "license_url":"https://peb.org/verify",
  "completion_date":"2019-06-20"
}

Response:
{
  "message":"Licenses item created"
}

### 4.10 List Licenses:
GET http://localhost/web_api/index.php/api/profile/list_section/licenses

Response:
[ {"id":1,"user_id":1,"title":"Professional Engineer License","issuer":"Professional Engineering Board","license_url":"https://peb.org/verify","completion_date":"2019-06-20","created_at":"...","updated_at":"..."} ]

### 4.11 Update License:
POST http://localhost/web_api/index.php/api/profile/update_section/licenses/1
Body (JSON):
{
  "issuer":"State Professional Engineering Board"
}

Response:
{
  "message":"Licenses item updated"
}

### 4.12 Delete License:
POST http://localhost/web_api/index.php/api/profile/delete_section/licenses/1

Response:
{
  "message":"Licenses item deleted"
}

### 4.13 Add Short Course:
POST http://localhost/web_api/index.php/api/profile/add_section/short_courses
Body (JSON):
{
  "title":"Advanced JavaScript",
  "provider":"Udemy",
  "course_url":"https://udemy.com/advanced-js",
  "completion_date":"2022-08-10"
}

Response:
{
  "message":"Short courses item created"
}

### 4.14 List Short Courses:
GET http://localhost/web_api/index.php/api/profile/list_section/short_courses

Response:
[ {"id":1,"user_id":1,"title":"Advanced JavaScript","provider":"Udemy","course_url":"https://udemy.com/advanced-js","completion_date":"2022-08-10","created_at":"...","updated_at":"..."} ]

### 4.15 Update Short Course:
POST http://localhost/web_api/index.php/api/profile/update_section/short_courses/1
Body (JSON):
{
  "provider":"Coursera"
}

Response:
{
  "message":"Short courses item updated"
}

### 4.16 Delete Short Course:
POST http://localhost/web_api/index.php/api/profile/delete_section/short_courses/1

Response:
{
  "message":"Short courses item deleted"
}

### 4.17 Add Employment History:
POST http://localhost/web_api/index.php/api/profile/add_section/employment_history
Body (JSON):
{
  "company":"Tech Corp Inc",
  "role":"Senior Software Engineer",
  "start_date":"2019-01-15",
  "end_date":"2023-12-31",
  "description":"Led development of microservices platform"
}

Response:
{
  "message":"Employment history item created"
}

### 4.18 List Employment History:
GET http://localhost/web_api/index.php/api/profile/list_section/employment_history

Response:
[ {"id":1,"user_id":1,"company":"Tech Corp Inc","role":"Senior Software Engineer","start_date":"2019-01-15","end_date":"2023-12-31","description":"Led development of microservices platform","created_at":"...","updated_at":"..."} ]

### 4.19 Update Employment History:
POST http://localhost/web_api/index.php/api/profile/update_section/employment_history/1
Body (JSON):
{
  "role":"Principal Software Engineer",
  "end_date":null
}

Response:
{
  "message":"Employment history item updated"
}

### 4.20 Delete Employment History:
POST http://localhost/web_api/index.php/api/profile/delete_section/employment_history/1

Response:
{
  "message":"Employment history item deleted"
}

## 5. Error behaviors

- 401 if no session: {"error":"Authentication required"}
- 404 for bad section or bad item id: {"error":"Unknown section"} / {"error":"Item not found"}
- invalid URL field: {"error":"Invalid ... URL"}
- invalid date field: {"error":"Invalid date format, use YYYY-MM-DD"}
- missing required field: {"error":"... is required"}

## 6. SQL Queries to retrieve uploaded data

### 6.1 Get all users (usernames and emails)
```sql
SELECT id, email, is_verified, created_at FROM users;
```

### 6.2 Get user profile info
```sql
SELECT * FROM user_personal_infos WHERE user_id = 1;
```

### 6.3 Get user degrees
```sql
SELECT * FROM user_degrees WHERE user_id = 1;
```

### 6.4 Get user certifications
```sql
SELECT * FROM user_certifications WHERE user_id = 1;
```

### 6.5 Get user licenses
```sql
SELECT * FROM user_licenses WHERE user_id = 1;
```

### 6.6 Get user short courses
```sql
SELECT * FROM user_short_courses WHERE user_id = 1;
```

### 6.7 Get user employment history
```sql
SELECT * FROM user_employment_history WHERE user_id = 1;
```

### 6.8 Get user LinkedIn profiles
```sql
SELECT * FROM user_linkedin_profiles WHERE user_id = 1;
```

### 6.9 Complete user profile (JOIN all data)
```sql
SELECT u.id, u.email, u.is_verified,
       p.full_name, p.biography, p.profile_image_url,
       COUNT(DISTINCT d.id) AS degree_count,
       COUNT(DISTINCT c.id) AS cert_count,
       COUNT(DISTINCT l.id) AS license_count,
       COUNT(DISTINCT sc.id) AS course_count,
       COUNT(DISTINCT eh.id) AS employment_count,
       COUNT(DISTINCT lp.id) AS linkedin_count
FROM users u
LEFT JOIN user_personal_infos p ON p.user_id = u.id
LEFT JOIN user_degrees d ON d.user_id = u.id
LEFT JOIN user_certifications c ON c.user_id = u.id
LEFT JOIN user_licenses l ON l.user_id = u.id
LEFT JOIN user_short_courses sc ON sc.user_id = u.id
LEFT JOIN user_employment_history eh ON eh.user_id = u.id
LEFT JOIN user_linkedin_profiles lp ON lp.user_id = u.id
WHERE u.id = 1
GROUP BY u.id;
```

### 6.10 Check password reset tokens
```sql
SELECT user_id, token, used, expires_at FROM password_reset_tokens;
```

### 6.11 Check email verification tokens
```sql
SELECT user_id, token, used, expires_at FROM email_verification_tokens;
```
