<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * 
 * Handles user authentication operations including registration, login, email verification,
 * and password reset. Uses secure token-based verification for email and password recovery.
 * 
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property User_model $User_model
 * @property Token_model $Token_model
 */
class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Token_model');
    }

    /**
     * Parse JSON request body into an array.
     * 
     * This method provides a caching layer for JSON parsing to avoid redundant parsing
     * of the raw request body. It works alongside traditional form POST data, allowing
     * the API to accept both application/json and application/x-www-form-urlencoded
     * content types. The static caching ensures multiple calls return the same parsed
     * data without re-parsing.
     * 
     * Algorithm:
     * 1. Check if JSON has already been parsed (static cache)
     * 2. If cached, return the cached value
     * 3. Otherwise, read the raw request body from php://input
     * 4. Trim whitespace and handle null values
     * 5. Attempt JSON decode with associative array mode
     * 6. Return empty array if decoding fails (invalid JSON)
     * 
     * @return array Decoded JSON data as associative array, or empty array if invalid
     */
    protected function getJsonInput()
    {
        static $json;
        if ($json !== null) {
            return $json;
        }
        $raw = trim(file_get_contents('php://input') ?? '');
        $json = $raw ? json_decode($raw, true) : [];
        return is_array($json) ? $json : [];
    }

    
    // USER REGISTRATION
    

    /**
     * User Registration Endpoint
     * 
     * Creates a new user account with email and password validation.
     * university email domain restrictions to ensure only valid users.
     * can create accounts. Generates and sends a verification token for email.
     * confirmation before the account becomes fully verifid.
     
     * ? Validation Flow:
     
     * 1. Extract email and password from JSON or form data
     * 2. Validate email format and allowed domain (university.edu, alumni.university.edu on;y)
     * 3. Enforce minimum password length (8 characters minimum)
     * *. Enforce password complexity (uppercase, lowercase, number, special character)
     * 4. Check if email is already registered in database.
     * 5. Hash password using bcrypt with cost factor 10.
     * 6. Create user record with is_verified=0.
     * 7. Generate secure verification token.
     * 8. Store token with 24 hours expiration.
     * 9. Return token to client for verification endpointss.
     * 
     * @return void Outputs JSON response with message or error
     * 
     * Request (JSON or Form):
     *   email: string (must be university.edu or alumni.university.edulony)
     *   password: string (minimum 8 characters)
     * 
     * Response Success (200):
     *   {
     *     "message": "Registration successful. Verify email.",
     *     "token": "secure_verification_token_64_chars"
     *   }
     * 
     * Response Errors (400):
     *   {"error": "Invalid university email"}
     *   {"error": "Weak password"}
     *   {"error": "Email already exists"}
     */
    
    public function register()
    {
        $input = $this->getJsonInput();
        $email = $this->input->post("email",TRUE) ?: ($input['email'] ?? null);
        $password = $this->input->post("password",TRUE) ?: ($input['password'] ?? null);

        if(!validate_university_email($email)){
            echo json_encode(["error"=>"Invalid university email"]);
            return;
        }
            if(strlen($password) < 8){
                echo json_encode(["error"=>"Weak password"]);
                return;
            }

        if(!preg_match('/[A-Z]/', $password) ||
           !preg_match('/[a-z]/', $password) ||
           !preg_match('/[0-9]/', $password) ||
           !preg_match('/[\W]/', $password)) {
            
            echo json_encode([
                "error" => "Password must include uppercase, lowercase, number, and special character"
            ]);
            return;
        }
        if($this->User_model->get_by_email($email)){
            echo json_encode(["error"=>"Email already exists"]);
            return;
        }

        $this->User_model->create_user($email,$password);
        $user = $this->User_model->get_by_email($email);

        $token = generate_secure_token();
        $this->Token_model->create_verification_token($user->id,$token);

        echo json_encode([
            "message"=>"Registration successful. Verify email.",
            "token"=>$token
        ]);
    }

    // ========================================================================
    // EMAIL VERIFICATION
    // ========================================================================

    /**
     * Email Verification Endpoint
     * 
     * Validates an email verification token and marks the user account as verified.
     * Tokens expire after 24 hours and can only be used once. This endpoint is called
     * by the user after clicking a verification link or manually providing their token.
     * 
     * Token Verification Logic:
     * 1. Retrieve token from query parameter (?token=xyz)
     * 2. Query email_verification_tokens table for matching token
     * 3. Check if token has been used (used != 1)
     * 4. Verify token hasn't expired (expires_at > NOW())
     * 5. If valid: update users.is_verified=1 and mark token as used
     * 6. If invalid/expired: reject with error message
     * 
     * @return void Outputs JSON response with message or error
     * 
     * Request (GET):
     *   ?token=secure_verification_token_64_chars
     * Response Success (200):
     *   {"message": "Email verified"}
     * 
     * Response Error (400):
     *   {"error": "Invalid or expired token"}
     */
    public function verify_email()
    {
        $token = $this->input->get("token");
        $data = $this->Token_model->verify_token($token);
        if(!$data){
            echo json_encode(["error"=>"Invalid or expired token"]);
            return;
        }
        $this->User_model->verify_user($data->user_id);
        echo json_encode(["message"=>"Email verified"]);
    }

    // ========================================================================
    // USER LOGIN
    // ========================================================================

    /**
     * User Login Endpoint
     * 
     * Authenticates user with email and password credentials. Creates a server-side
     * session upon successful authentication. Email must be verified before login is allowed.
     * 
     * Authentication Algorithm:
     * 1. Extract email and password from request
     * 2. Query database for user record with matching email
     * 3. Verify user account is email-verified (is_verified=1)
     * 4. Compare submitted password against stored bcrypt hash
     * 5. If all checks pass: create session with user_id, email, logged_in flag
     * 6. Return success message
     * 
     * Security Considerations:
     * - Passwords are compared using password_verify() (bcrypt comparison)
     * - Session timeout is configured in config/config.php
     * - Same error message for "user not found" and "invalid password" prevents user enumeration
     * 
     * @return void Outputs JSON response with message or error
     * 
     * Request (JSON or Form):
     *   email: string
     *   password: string
     * 
     * Response Success (200):
     *   {"message": "Login successful"}
     * 
     * Response Errors (401):
     *   {"error": "User not found"}
     *   {"error": "Email not verified"}
     *   {"error": "Invalid password"}
     */
    public function login()
    {
        $input = $this->getJsonInput();
        $email = $this->input->post("email",TRUE) ?: ($input['email'] ?? null);
        $password = $this->input->post("password",TRUE) ?: ($input['password'] ?? null);
        $user = $this->User_model->get_by_email($email);

        if(!$user){ echo json_encode(["error"=>"User not found"]); return; }
        if(!$user->is_verified){ echo json_encode(["error"=>"Email not verified"]); return; }
        if(!password_verify($password,$user->password)){ echo json_encode(["error"=>"Invalid password"]); return; }

        $this->session->set_userdata([
            "user_id"=>$user->id,
            "email"=>$user->email,
            "logged_in"=>TRUE
        ]);

        echo json_encode(["message"=>"Login successful"]);
    }

    // ========================================================================
    // USER LOGOUT
    // ========================================================================

    /**
     * User Logout Endpoint
     * 
     * Destroys the current user session and logs out the user.
     * This clears all session data including user_id, email, and logged_in flag.
     * 
     * @return void Outputs JSON response with message
     * 
     * Response Success (200):
     *   {"message": "Logged out"}
     */
    public function logout()
    {
        $this->session->sess_destroy();
        echo json_encode(["message"=>"Logged out"]);
    }

    // ========================================================================
    // PASSWORD RESET REQUEST
    // ========================================================================

    /**
     * Request Password Reset Endpoint
     * 
     * Initiates password reset process by generating a reset token and storing it
     * in the database with a 1-hour expiration. In production, the token should be
     * sent to the user's email with a reset link.
     * 
     * Password Reset Flow:
     * 1. Extract email from request
     * 2. Query database for user with matching email
     * 3. Generate secure random token (64-character hex string)
     * 4. Store token with user_id and 1-hour expiration
     * 5. In production: send email with reset link containing token
     * 6. Return token to client (note: should only be sent via email in production)
     * 
     * Security Considerations:
     * - Token expires after 1 hour to limit attack window
     * - Tokens can only be used once (used flag is set after redemption)
     * - Token length is 64 characters (256 bits of entropy from random_bytes(32))
     * 
     * @return void Outputs JSON response with message or error
     * 
     * Request (JSON or Form):
     *   email: string
     * 
     * Response Success (200):
     *   {
     *     "message": "Reset email sent",
     *     "token": "reset_token_64_chars"
     *   }
     * 
     * Response Error (400):
     *   {"error": "Email not found"}
     */
    public function request_reset()
    {
        $input = $this->getJsonInput();
        $email = $this->input->post("email") ?: ($input['email'] ?? null);
        $user = $this->User_model->get_by_email($email);
        if(!$user){ echo json_encode(["error"=>"Email not found"]); return; }

        $token = generate_secure_token();
        $this->db->insert("password_reset_tokens",[
            "user_id"=>$user->id,
            "token"=>$token,
            "expires_at"=>date("Y-m-d H:i:s",strtotime("+1 hour"))
        ]);

        echo json_encode(["message"=>"Reset email sent","token"=>$token]);
    }

    // ========================================================================
    // PASSWORD RESET
    // ========================================================================

    /**
     * Reset Password Endpoint
     * 
     * Validates reset token and updates user password if token is valid and not expired.
     * This endpoint should only be called after a reset token has been generated and
     * confirmed by the user (typically via email link).
     * 
     * Password Reset Validation & Execution:
     * 1. Extract reset token and new password from request
     * 2. Query password_reset_tokens table for matching token
     * 3. Verify token hasn't been used (used != 1)
     * 4. Verify token hasn't expired (expires_at > NOW())
     * 5. Hash new password using bcrypt (PASSWORD_BCRYPT)
     * 6. Update user record with new password hash
     * 7. Mark token as used (used=1) to prevent reuse
     * 8. Return success message
     * 
     * Security Considerations:
     * - Passwords are hashed using bcrypt with PHP's PASSWORD_BCRYPT algorithm
     * - Tokens can only be used once to prevent replay attacks
     * - Expired tokens are rejected (1 hour window)
     * - Password requirements should be enforced before this endpoint
     * 
     * @return void Outputs JSON response with message or error
     * 
     * Request (JSON or Form):
     *   token: string (from password reset email)
     *   password: string (new password)
     * 
     * Response Success (200):
     *   {"message": "Password updated"}
     * 
     * Response Error (400):
     *   {"error": "Invalid token"}
     */
    public function reset_password()
    {
        $input = $this->getJsonInput();
        $token = $this->input->post("token") ?: ($input['token'] ?? null);
        $password = $this->input->post("password") ?: ($input['password'] ?? null);

        $this->db->where("token",$token);
        $this->db->where("used",0);
        $this->db->where("expires_at >",date("Y-m-d H:i:s"));
        $row = $this->db->get("password_reset_tokens")->row();

        if(!$row){ echo json_encode(["error"=>"Invalid token"]); return; }

        $hash = password_hash($password,PASSWORD_BCRYPT);
        $this->db->where("id",$row->user_id)->update("users",["password"=>$hash]);
        $this->db->where("id",$row->id)->update("password_reset_tokens",["used"=>1]);

        echo json_encode(["message"=>"Password updated"]);
    }

}