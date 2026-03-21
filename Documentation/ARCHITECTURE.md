# Alumni Profile API - Architecture Design Documentation

## Overview

The Alumni Profile API is built on a deliberate, layered architecture that emphasizes clear component separation, maintainability, and scalability. The system follows established software architecture principles including Model-View-Controller (MVC), separation of concerns, and dependency injection patterns.

**Architecture Pattern**: Layered Architecture with MVC + Helpers

**Framework**: CodeIgniter 3 (PHP)

---

## Table of Contents

1. [System Architecture Overview](#system-architecture-overview)
2. [Layered Architecture](#layered-architecture)
3. [Component Responsibilities](#component-responsibilities)
4. [Data Flow & Interactions](#data-flow--interactions)
5. [Design Patterns Used](#design-patterns-used)
6. [Separation of Concerns](#separation-of-concerns)
7. [Dependency Injection](#dependency-injection)
8. [Communication Patterns](#communication-patterns)
9. [Error Handling Architecture](#error-handling-architecture)
10. [Security Architecture](#security-architecture)

---

## System Architecture Overview

### High-Level System Diagram


```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
│  (Web Browser, Mobile App, Third-party Integration)              │
└────────────────────────┬────────────────────────────────────────┘
                         │ HTTP Requests/Responses
                         │ JSON over REST
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           API Endpoints (Controllers)                    │   │
│  │  ┌────────────────┐  ┌────────────────┐                 │   │
│  │  │  Auth          │  │  Profile       │                 │   │
│  │  │  Controller    │  │  Controller    │                 │   │
│  │  │                │  │                │                 │   │
│  │  │ - register()   │  │ - get_profile()│                 │   │
│  │  │ - login()      │  │ - update_prof()│                 │   │
│  │  │ - logout()     │  │ - add_section()│                 │   │
│  │  │ - verify_email │  │ - delete_etc() │                 │   │
│  │  └────────────────┘  └────────────────┘                 │   │
│  └──────────────────────────────────────────────────────────┘   │
└────────────────────────┬────────────────────────────────────────┘
                         │ Method Calls
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                  BUSINESS LOGIC LAYER                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           Models (Data Access & Business Logic)          │   │
│  │  ┌────────────────┐  ┌────────────────┐                 │   │
│  │  │  User_model    │  │  Profile_model │                 │   │
│  │  │                │  │                │                 │   │
│  │  │ - create_user()│  │ - save_info()  │                 │   │
│  │  │ - verify_user()│  │ - list_records │                 │   │
│  │  │ - get_by_email │  │ - get_record() │                 │   │
│  │  └────────────────┘  └────────────────┘                 │   │
│  │  ┌────────────────┐                                      │   │
│  │  │  Token_model   │                                      │   │
│  │  │                │                                      │   │
│  │  │ - verify_token │                                      │   │
│  │  │ - create_token │                                      │   │
│  │  └────────────────┘                                      │   │
│  └──────────────────────────────────────────────────────────┘   │
└────────────────────────┬────────────────────────────────────────┘
                         │ Database Queries
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                  HELPER LAYER                                    │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │        Utility Functions & Helpers                       │   │
│  │  ┌────────────────────┐  ┌─────────────────────┐        │   │
│  │  │ security_helper.php │  │ form_helper.php     │        │   │
│  │  │                     │  │                     │        │   │
│  │  │ - generate_token()  │  │ - form_open()      │        │   │
│  │  │ - validate_email()  │  │ - form_input()     │        │   │
│  │  └────────────────────┘  └─────────────────────┘        │   │
│  └──────────────────────────────────────────────────────────┘   │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓
┌─────────────────────────────────────────────────────────────────┐
│                      DATA LAYER                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           Database (MySQL/MariaDB)                       │   │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐     │   │
│  │  │ users        │ │ degrees      │ │ employment   │ ... │   │
│  │  ├──────────────┤ ├──────────────┤ ├──────────────┤     │   │
│  │  │ id (PK)      │ │ id (PK)      │ │ id (PK)      │     │   │
│  │  │ email        │ │ user_id (FK) │ │ user_id (FK) │     │   │
│  │  │ password     │ │ institution  │ │ company      │     │   │
│  │  │ is_verified  │ │ degree       │ │ role         │     │   │
│  │  └──────────────┘ └──────────────┘ └──────────────┘     │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Layered Architecture

The application follows a 4-layer architecture pattern:

### Layer 1: Presentation Layer (Controllers)

**Location**: `application/controllers/Api/`

**Responsibility**: 
- Handle HTTP requests and responses
- Parse and validate input data
- Route requests to appropriate business logic
- Format and return JSON responses
- Manage authentication state via sessions

**Components**:
- `Auth.php` - Authentication endpoints
- `Profile.php` - Profile management endpoints

**Key Characteristics**:
- Stateless request handlers
- Input validation and sanitization
- HTTP status code management
- JSON serialization/deserialization
- No direct database access (delegates to models)

**Request Flow**:
```
HTTP Request → Router → Controller Method → Validate → Business Logic → Response
```

---

### Layer 2: Business Logic Layer (Models)

**Location**: `application/models/`

**Responsibility**:
- Implement business rules and logic
- Manage data operations and persistence
- Enforce data integrity constraints
- Handle domain-specific calculations and validations
- Provide data access abstraction

**Components**:
- `User_model.php` - User account management
- `Profile_model.php` - Profile data management  
- `Token_model.php` - Authentication token handling

**Key Characteristics**:
- Encapsulate database interactions
- Implement business rules
- Reusable across multiple controllers
- Database query abstraction
- Transaction management

**Model Responsibilities by Table**:

| Model | Responsibility | Methods |
|-------|-----------------|---------|
| User_model | User accounts, authentication | create_user(), get_by_email(), verify_user() |
| Token_model | Token lifecycle management | create_verification_token(), verify_token() |
| Profile_model | Profile data CRUD | get_personal_info(), list_records(), create_record(), update_record(), delete_record() |

---

### Layer 3: Helper Layer (Utilities)

**Location**: `application/helpers/`

**Responsibility**:
- Provide reusable utility functions
- Cross-cutting concerns (security, validation)
- Domain-specific helper functions
- Stateless, pure functions

**Components**:
- `security_helper.php` - Cryptographic and validation utilities
- Framework helpers (form_helper, url_helper, etc.)

**Key Characteristics**:
- No dependencies on models or controllers
- Pure functions (no side effects)
- Reusable across application
- Security-focused implementations

**Security Helper Functions**:
```
generate_secure_token()        → Cryptographically secure token generation
validate_university_email()    → Email domain whitelist validation
```

---

### Layer 4: Data Layer (Database)

**Location**: MySQL/MariaDB Database

**Responsibility**:
- Persistent data storage
- ACID transaction support
- Query execution and result handling
- Foreign key constraint enforcement
- Data integrity through indexes

**Components**:
- 10 tables with defined relationships
- Indexes for performance
- Cascading deletes for referential integrity

---

## Component Responsibilities

### Controllers (Presentation Layer)

#### Auth Controller

**File**: `application/controllers/Api/Auth.php`

**Single Responsibility**: Handle all authentication-related HTTP endpoints

**Public Methods**:

| Method | Responsibility | Returns |
|--------|-----------------|---------|
| `register()` | Create new user account | JSON: message + token |
| `verify_email()` | Activate account via email token | JSON: message |
| `login()` | Authenticate user and create session | JSON: message |
| `logout()` | Terminate user session | JSON: message |
| `request_reset()` | Initiate password reset | JSON: message + token |
| `reset_password()` | Update password using reset token | JSON: message |

**Protected Methods**:

| Method | Responsibility |
|--------|-----------------|
| `getJsonInput()` | Parse JSON request body with caching |

**Dependencies**:
- User_model (user operations)
- Token_model (token operations)
- security_helper (token generation, email validation)
- CodeIgniter Session library

---

#### Profile Controller

**File**: `application/controllers/Api/Profile.php`

**Single Responsibility**: Handle all profile management HTTP endpoints

**Data Mapping Property**:
```php
private $sectionTables = [
    'degrees' => 'user_degrees',
    'certifications' => 'user_certifications',
    'licenses' => 'user_licenses',
    'short_courses' => 'user_short_courses',
    'employment_history' => 'user_employment_history',
];
```

**Public Methods**:

| Method | Responsibility |
|--------|-----------------|
| `get_profile()` | Retrieve complete user profile with all sections |
| `update_profile()` | Update personal info (name, biography) |
| `upload_profile_image()` | Handle profile image file upload |
| `list_section($section)` | List all records in a profile section |
| `add_section($section)` | Create new record in profile section |
| `update_section($section, $id)` | Update existing section record |
| `delete_section($section, $id)` | Delete section record |
| `add_linkedin()` | Add LinkedIn profile URL |
| `list_linkedin()` | List all LinkedIn profiles |
| `update_linkedin($id)` | Update LinkedIn profile |
| `delete_linkedin($id)` | Delete LinkedIn profile |

**Protected Methods**:

| Method | Responsibility |
|--------|-----------------|
| `getJsonInput()` | Parse JSON request body |
| `require_auth()` | Check authentication and return user_id |
| `is_valid_url()` | Validate URL format |
| `is_valid_date()` | Validate date format (YYYY-MM-DD) |

**Dependencies**:
- Profile_model (profile operations)
- CodeIgniter Session library
- CodeIgniter Upload library
- CodeIgniter Helpers (url, file)

---

### Models (Business Logic Layer)

#### User Model

**File**: `application/models/User_model.php`

**Single Responsibility**: Manage user account operations

**Public Methods**:

```php
create_user($email, $password)
  → Creates new user with hashed password
  → Used by: Auth::register()

get_by_email($email)
  → Retrieves user by email
  → Used by: Auth::login(), Auth::register()

verify_user($id)
  → Marks user email as verified
  → Used by: Auth::verify_email()
```

**Data Access Pattern**:
```php
// INSERT
$data = ["email" => $email, "password" => hash, "is_verified" => 0];
$this->db->insert("users", $data);

// SELECT
$this->db->get_where("users", ["email" => $email])->row();

// UPDATE
$this->db->where("id", $id);
$this->db->update("users", ["is_verified" => 1]);
```

---

#### Token Model

**File**: `application/models/Token_model.php`

**Single Responsibility**: Manage verification and reset tokens

**Public Methods**:

```php
create_verification_token($user_id, $token)
  → Creates email verification token with 24h expiry

verify_token($token)
  → Validates and retrieves token if not expired/used

create_reset_token($user_id, $token)
  → Creates password reset token with 1h expiry
```

**Token Lifecycle**:
```
Generated → Stored → Sent to User → User clicks link → Validated → Used → Marked
  (64 chars)  (DB)    (via email)   (in URL param)  (checked)  (yes)  (used=1)
```

---

#### Profile Model

**File**: `application/models/Profile_model.php`

**Single Responsibility**: Manage all user profile data operations

**Public Methods**:

| Method | Responsibility |
|--------|-----------------|
| `get_personal_info($user_id)` | Retrieve personal details |
| `save_personal_info($user_id, $data)` | Create or update personal info |
| `get_linkedin($user_id)` | Get all LinkedIn profiles |
| `save_linkedin($user_id, $data)` | Create LinkedIn entry |
| `update_linkedin($id, $user_id, $data)` | Update LinkedIn entry |
| `delete_linkedin($id, $user_id)` | Delete LinkedIn entry |
| `create_record($table, $user_id, $data)` | Generic record creation |
| `list_records($table, $user_id)` | Generic record listing |
| `get_record($table, $id, $user_id)` | Generic single record retrieval |
| `update_record($table, $id, $user_id, $data)` | Generic record update |
| `delete_record($table, $id, $user_id)` | Generic record deletion |

**Generic CRUD Pattern**:
```php
// Create
public function create_record($table, $user_id, $data) {
    $data['user_id'] = $user_id;
    return $this->db->insert($table, $data);
}

// Read
public function list_records($table, $user_id) {
    return $this->db->get_where($table, ['user_id' => $user_id])->result();
}

// Update
public function update_record($table, $id, $user_id, $data) {
    $this->db->where('id', $id);
    $this->db->where('user_id', $user_id);
    return $this->db->update($table, $data);
}

// Delete
public function delete_record($table, $id, $user_id) {
    $this->db->where('id', $id);
    $this->db->where('user_id', $user_id);
    return $this->db->delete($table);
}
```

**Benefits**:
- Single implementation for multiple tables
- Consistent behavior across sections
- Reduced code duplication
- Easy to test
- Extensible for new sections

---

### Helpers (Utility Layer)

#### Security Helper

**File**: `application/helpers/security_helper.php`

**Single Responsibility**: Provide cryptographic and validation utilities

**Functions**:

```php
generate_secure_token()
  Purpose: Generate secure random token for email verification/password reset
  Implementation: random_bytes(32) → bin2hex()
  Returns: 64-character hexadecimal string (256 bits entropy)
  Usage: $token = generate_secure_token();

validate_university_email($email)
  Purpose: Validate email is from allowed university domain
  Implementation: Extract domain → Check against whitelist
  Returns: boolean (true if valid, false otherwise)
  Usage: if (!validate_university_email($email)) { error; }
```

**Security Considerations**:
- Tokens use cryptographically secure random source (random_bytes)
- Email validation prevents unauthorized domain registrations
- No sensitive data logging

---

## Data Flow & Interactions

### User Registration Flow

```
1. CLIENT REQUEST
   POST /api/Auth/register
   {
     "email": "user@university.edu",
     "password": "SecurePass123"
   }

2. CONTROLLER (Auth::register())
   ├─ Call getJsonInput() → Parse request body
   ├─ Extract email, password parameters
   ├─ Call validate_university_email() → Validate domain
   ├─ Check password length >= 8
   ├─ Call User_model::get_by_email() → Check duplicate
   ├─ Call User_model::create_user() → Create user
   │  └─ Model hashes password with bcrypt
   │  └─ Stores in database with is_verified=0
   ├─ Call generate_secure_token() → Generate token
   ├─ Call Token_model::create_verification_token()
   │  └─ Stores token with user_id and 24h expiry
   └─ Return JSON response with token

3. DATABASE OPERATIONS (Model)
   ├─ INSERT INTO users (email, password, is_verified)
   └─ INSERT INTO email_verification_tokens (user_id, token, expires_at)

4. RESPONSE
   {
     "message": "Registration successful. Verify email.",
     "token": "a1b2c3d4e5f6..."
   }
```

### Authentication Flow

```
1. CLIENT REQUEST: POST /api/Auth/login
   {"email": "user@university.edu", "password": "SecurePass123"}

2. CONTROLLER (Auth::login())
   ├─ Parse JSON input
   ├─ Call User_model::get_by_email() → Retrieve user
   ├─ Verify is_verified == 1
   ├─ Call password_verify() → Compare passwords
   ├─ Create session: set_userdata(['user_id', 'email', 'logged_in'])
   └─ Return success message

3. SESSION STORAGE
   ├─ Server-side session created
   ├─ Session ID in client cookie
   └─ Session timeout: 1800 seconds

4. RESPONSE
   {"message": "Login successful"}

5. SUBSEQUENT REQUESTS
   ├─ Client sends session cookie
   ├─ Controller calls require_auth()
   ├─ require_auth() retrieves user_id from session
   ├─ Validates user_id exists
   └─ Returns user_id or exits with 401
```

### Profile Data Flow

```
1. CLIENT REQUEST: GET /api/Profile/get_profile

2. CONTROLLER (Profile::get_profile())
   ├─ Call require_auth() → Get user_id from session
   ├─ Call Profile_model::get_personal_info($user_id)
   ├─ Call Profile_model::get_linkedin($user_id)
   ├─ Call Profile_model::list_records('user_degrees', $user_id)
   ├─ Call Profile_model::list_records() for each section
   ├─ Aggregate results into associative array
   └─ JSON encode and return

3. DATABASE QUERIES (Models)
   ├─ SELECT * FROM user_personal_infos WHERE user_id = X
   ├─ SELECT * FROM user_linkedin_profiles WHERE user_id = X
   ├─ SELECT * FROM user_degrees WHERE user_id = X
   ├─ SELECT * FROM user_certifications WHERE user_id = X
   └─ ... (repeat for each section)

4. RESPONSE
   {
     "personal": {...},
     "linkedin": [{...}],
     "degrees": [{...}],
     "certifications": [],
     ...
   }
```

---

## Design Patterns Used

### 1. Model-View-Controller (MVC)

**Implementation**:
- **View**: JSON responses (no HTML templates)
- **Controller**: Auth.php, Profile.php (request handlers)
- **Model**: User_model, Profile_model, Token_model (data layer)

**Benefits**:
- Separation of concerns
- Easy to test each component
- Clear responsibility boundaries

---

### 2. Repository Pattern

**Implementation**: Models act as repositories for database access

**Example**:
```php
// Instead of direct DB calls in controller:
$user = $this->User_model->get_by_email($email);

// Model encapsulates query:
public function get_by_email($email) {
    return $this->db->get_where("users", ["email" => $email])->row();
}
```

**Benefits**:
- Abstraction of persistence mechanism
- Easy to swap database implementations
- Testable via mocks

---

### 3. Active Record Pattern

**Implementation**: CodeIgniter's Query Builder provides Active Record interface

**Example**:
```php
// Insert
$this->db->insert("users", $data);

// Select
$this->db->get_where("users", ["email" => $email]);

// Update
$this->db->where("id", $id)->update("users", $data);

// Delete
$this->db->where("id", $id)->delete("users");
```

**Benefits**:
- SQL injection prevention (parameterized queries)
- Database abstraction
- Chainable query building

---

### 4. Generic CRUD Operations

**Implementation**: Profile_model provides generic methods for all profile sections

**Example**:
```php
// Instead of separate methods for each table:
public function create_record($table, $user_id, $data) {
    $data['user_id'] = $user_id;
    return $this->db->insert($table, $data);
}

// Use single method for all sections:
$this->Profile_model->create_record('user_degrees', $user_id, $data);
$this->Profile_model->create_record('user_certifications', $user_id, $data);
```

**Benefits**:
- Reduces code duplication
- Easier to maintain
- Consistent behavior across sections

---

### 5. Static Helper Functions

**Implementation**: Utility functions available globally

**Example**:
```php
// Helper function (stateless, pure)
function generate_secure_token() {
    return bin2hex(random_bytes(32));
}

// Usage in any controller/model
$token = generate_secure_token();
```

**Benefits**:
- Reusable across application
- No object instantiation needed
- Simple, focused functions

---

### 6. Dependency Injection (Implicit)

**Implementation**: CodeIgniter loads dependencies via load patterns

**Example**:
```php
// In controller constructor
public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->model('Token_model');
}

// Usage
$user = $this->User_model->get_by_email($email);
```

**Benefits**:
- Controllers don't create their own dependencies
- Easy to mock for testing
- Loose coupling

---

### 7. Template Method Pattern

**Implementation**: Controller methods follow consistent template

**Example**:
```php
// All controller methods follow:
public function someEndpoint() {
    1. Validate authentication (require_auth)
    2. Parse/validate input
    3. Call business logic (models)
    4. Handle errors
    5. Return JSON response
}
```

**Benefits**:
- Consistent request handling
- Easy to understand flow
- Reduces bugs from inconsistent patterns

---

## Separation of Concerns

### By Layer

```
PRESENTATION LAYER (Controllers)
├─ Responsibility: HTTP handling, input parsing, response formatting
├─ Should NOT: Access database directly, implement business logic
└─ Complexity: Low-Medium

BUSINESS LOGIC LAYER (Models)
├─ Responsibility: Business rules, data operations, integrity
├─ Should NOT: Format HTTP responses, handle sessions
└─ Complexity: Medium-High

HELPER LAYER (Utilities)
├─ Responsibility: Utility functions, pure calculations
├─ Should NOT: Depend on models/controllers, have side effects
└─ Complexity: Low

DATA LAYER (Database)
├─ Responsibility: Persistent storage, transactions
├─ Should NOT: Implement business logic
└─ Complexity: Medium
```

### By Concern Type

| Concern | Responsibility | Location |
|---------|-----------------|----------|
| HTTP Handling | Parse requests, format responses | Controllers |
| Authentication | Validate credentials, manage sessions | Models + Controllers |
| Validation | Check data format and constraints | Models + Helpers |
| Authorization | Verify user owns data | Models (user_id checks) |
| Data Persistence | Database operations | Models |
| Cryptography | Token generation, hashing | Helpers + Models |
| Email Management | Send/receive emails | (Separate service) |
| File Upload | Process file uploads | Controllers |

---

## Dependency Injection

### Current Implementation (Implicit)

**Pattern**: CodeIgniter's loader-based injection

```php
public function __construct() {
    parent::__construct();
    // Dependencies injected via load()
    $this->load->model('User_model');
    $this->load->helper('security_helper');
    $this->load->library('session');
}
```

**Advantages**:
- Simple and clear
- CodeIgniter standard
- Minimal boilerplate

**Disadvantages**:
- Hard to unit test without CodeIgniter
- Global state via $this

---

### Recommended Enhancement (Explicit)

**Future Pattern**: Constructor-based DI

```php
public function __construct(UserModel $userModel, TokenModel $tokenModel) {
    parent::__construct();
    $this->userModel = $userModel;
    $this->tokenModel = $tokenModel;
}

// Usage
$user = $this->userModel->get_by_email($email);
```

**Advantages**:
- Easier to test with mocks
- Clear dependencies
- No magic loader calls

---

## Communication Patterns

### Controller → Model Communication

**Pattern**: Method calls with parameters

```php
// Controller calls model method
$user = $this->User_model->get_by_email($email);

// Returns model instance
return (object) ["id" => 1, "email" => "...", "password" => "..."];
```

**Flow**:
```
Controller Method
    ↓ (call with params)
Model Method
    ↓ (execute query)
Database
    ↓ (return results)
Model (format result)
    ↓ (return object/array)
Controller (process result)
```

---

### Controller → Helper Communication

**Pattern**: Function calls

```php
// Controller calls helper function
$token = generate_secure_token();

// Function executes and returns
return "a1b2c3d4e5f6...";
```

**Benefits**:
- Simple, direct communication
- No state
- Easy to test

---

### Controller → View Communication

**Pattern**: JSON serialization (REST API specific)

```php
// Controller prepares data
$result = [
    "message" => "Success",
    "data" => $user
];

// Encodes and outputs
echo json_encode($result);
```

---

### Model ↔ Database Communication

**Pattern**: Query Builder (Active Record)

```php
// Model builds query
$this->db->where("user_id", $user_id);
$this->db->where("used", 0);
$this->db->where("expires_at >", date("Y-m-d H:i:s"));

// Executes query
$result = $this->db->get("table_name");

// Returns stdObject or array
return $result->row();
```

---

## Error Handling Architecture

### Error Hierarchy

```
┌─────────────────────────────────┐
│     Client Error (4xx)          │
├─────────────────────────────────┤
│ 400 Bad Request                 │
│  ├─ Invalid input               │
│  ├─ Validation failure          │
│  └─ Malformed request           │
│                                 │
│ 401 Unauthorized                │
│  ├─ Not authenticated           │
│  ├─ Session expired             │
│  └─ Invalid credentials         │
│                                 │
│ 404 Not Found                   │
│  ├─ Resource doesn't exist      │
│  ├─ Unknown endpoint            │
│  └─ Record not found            │
└─────────────────────────────────┘

┌─────────────────────────────────┐
│     Server Error (5xx)          │
├─────────────────────────────────┤
│ 500 Internal Server Error       │
│  ├─ Database error              │
│  ├─ Unhandled exception         │
│  └─ Server misconfiguration     │
└─────────────────────────────────┘
```

### Error Response Format

```json
{
  "error": "Description of what went wrong"
}
```

### Error Handling in Controllers

```php
// Input validation → 400 error
if (!$email) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Email required']);
    return;
}

// Authentication check → 401 error
if (!$user_id) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

// Record not found → 404 error
if (!$record) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Item not found']);
    return;
}
```

---

## Security Architecture

### Authentication Architecture

```
USER REGISTRATION
├─ Email domain validation (whitelist)
├─ Password hashing (bcrypt)
├─ Email verification (token-based)
└─ Account activation (is_verified flag)

USER LOGIN
├─ Email lookup
├─ Verification check
├─ Password comparison (bcrypt verify)
├─ Session creation
└─ Session timeout (30 min)

SESSION MANAGEMENT
├─ Server-side sessions (PHP)
├─ Session cookies (HTTP only)
├─ User data in session (user_id, email, logged_in)
└─ Session destruction on logout
```

---

### Data Access Control

```
USER ISOLATION
├─ All queries filtered by user_id
├─ Models verify ownership
├─ Controllers call require_auth()
└─ Prevent cross-user access

EXAMPLE (Profile update):
├─ require_auth() → Get user_id from session
├─ Model: WHERE user_id = ? AND id = ?
├─ Ensures user can only modify their own data
└─ Database enforces foreign key integrity
```

---

### Input Validation

```
VALIDATION LAYERS

Layer 1: Controller
├─ Required field checks
├─ Type validation
└─ Format checking

Layer 2: Helper Functions
├─ Email domain validation
├─ URL format validation
├─ Date format validation
└─ Cryptographic token generation

Layer 3: Model
├─ Database constraints
├─ Foreign key validation
└─ Unique constraint enforcement
```

---

## Extensibility & Scalability

### Adding New Profile Sections

**Current Design**: Generic CRUD methods support unlimited sections

**Steps to add new section**:

1. Create database table with schema
2. Add entry to $sectionTables mapping
3. Existing endpoints automatically support new section

**Example**:
```php
// Database
CREATE TABLE user_new_section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    field1 VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

// Controller mapping
private $sectionTables = [
    'new_section' => 'user_new_section',  // ← Add this line
];

// Endpoints automatically available
POST /api/Profile/add_section/new_section
GET /api/Profile/list_section/new_section
POST /api/Profile/update_section/new_section/{id}
POST /api/Profile/delete_section/new_section/{id}
```

---

### Adding New Endpoints

**Structure for new endpoint**:

1. Add public method to Controller
2. Implement business logic in Model
3. Return JSON response

**Example**:
```php
// In Controller
public function new_endpoint() {
    $user_id = $this->require_auth();
    $input = $this->getJsonInput();
    
    $result = $this->SomeModel->perform_operation($user_id, $input);
    
    echo json_encode(['message' => 'Success', 'data' => $result]);
}

// In Model
public function perform_operation($user_id, $data) {
    // Implement business logic
    return $result;
}

// Endpoint available
POST /api/SomeController/new_endpoint
```

---

### Performance Optimization Points

**Database Level**:
- Indexes on user_id (fast lookups)
- Indexes on email (authentication)
- Indexes on token fields (verification)

**Application Level**:
- Generic CRUD reduces database calls
- Query caching via static variables (getJsonInput)
- Session caching prevents repeated user lookups

**Recommended Improvements**:
- Add database query result caching (Redis)
- Implement API rate limiting
- Add request/response compression
- Optimize image upload processing

---

## Component Interaction Diagram

```
API REQUEST
    ↓
┌─────────────────────┐
│   Router            │ (Route matching)
│ (config/routes.php) │
└──────────┬──────────┘
           ↓
┌─────────────────────────────────┐
│   Controller                    │
│ ├─ receive HTTP request        │
│ ├─ parse input                 │
│ ├─ call require_auth()         │
│ └─ call helper/model functions │
└──────────┬──────────────────────┘
           ↓
    ┌──────┴──────────┬──────────────┐
    ↓                 ↓              ↓
┌─────────────┐ ┌──────────┐ ┌─────────────┐
│Helper       │ │Model     │ │Library      │
│Functions    │ │Business  │ │Session,     │
│             │ │Logic     │ │Upload, etc  │
│- generate   │ │          │ │             │
│  token()    │ │- CRUD    │ └─────────────┘
│- validate   │ │- get_    │
│  email()    │ │  by_id() │
└─────────────┘ └────┬─────┘
                     ↓
              ┌─────────────────┐
              │  Database       │
              │ (Query Builder) │
              │  execute SQL    │
              └─────────────────┘
                     ↓
              ┌─────────────────┐
              │  MySQL/MariaDB  │
              │  Data Storage   │
              └─────────────────┘
                     ↓
    ┌────────────────┴────────────────┐
    ↓                                 ↓
Result Object/Array            ← Return to Model
    ↓
Format for Response
    ↓
JSON Encode
    ↓
HTTP Response with Status Code
    ↓
CLIENT
```

---

## Architecture Principles Applied

### SOLID Principles

| Principle | Application |
|-----------|------------|
| **S**ingle Responsibility | Each model/controller handles one concern |
| **O**pen/Closed | Generic CRUD methods extensible without modification |
| **L**iskov Substitution | Models interchangeable (same interface) |
| **I**nterface Segregation | Helpers provide focused functions |
| **D**ependency Inversion | Controllers depend on abstractions (models) |

### DRY (Don't Repeat Yourself)

- Generic CRUD methods in Profile_model
- Reusable helper functions
- Common validation patterns

### KISS (Keep It Simple, Stupid)

- Straightforward controller-model-helper structure
- Clear separation of concerns
- Minimal abstraction layers

---

## Conclusion

The Alumni Profile API architecture demonstrates:

✓ **Clear Separation of Concerns** - Each layer has distinct responsibility
✓ **Scalability** - Generic patterns support unlimited extensions
✓ **Maintainability** - Code is organized, documented, and easy to navigate
✓ **Security** - Input validation, authentication, authorization at multiple levels
✓ **Testability** - Components can be tested independently
✓ **Performance** - Optimized queries and caching strategies
✓ **Extensibility** - New features require minimal code changes

This deliberate architectural design ensures long-term maintainability and supports future growth of the platform.
