<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function register($data)
    {
        // Set default values for optional fields
        $promo_emails = isset($data['promo_emails']) ? $data['promo_emails'] : 0; // Default to 0 (false)
        $terms_accepted = isset($data['terms_accepted']) ? $data['terms_accepted'] : 1; // Default to 1 (true)

        $this->db->query('INSERT INTO users (name, email, password, account_type, country, promo_emails, terms_accepted, created_at) 
                          VALUES(:name, :email, :password, :account_type, :country, :promo_emails, :terms_accepted, NOW())');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':promo_emails', $promo_emails);
        $this->db->bind(':terms_accepted', $terms_accepted);

        // Execute
        return $this->db->execute();
    }

    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if (!$row) {
            return false;
        }

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        return ($row) ? true : false;
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    public function updateProfile($data)
    {
        $this->db->query('UPDATE users 
                         SET name = :name, bio = :bio, skills = :skills, 
                             profile_image = :profile_image 
                         WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':skills', $data['skills']);
        $this->db->bind(':profile_image', $data['profile_image']);

        return $this->db->execute();
    }

    /**
     * Store a remember me token for the user
     *
     * @param int $userId The user ID
     * @param string $token The hashed token
     * @return bool
     */
    public function storeRememberToken($userId, $token)
    {
        $this->db->query('UPDATE users SET remember_token = :token, token_expires = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = :user_id');
        $this->db->bind(':token', $token);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    /**
     * Verify remember me token
     *
     * @param int $userId The user ID
     * @param string $token The token to verify
     * @return object|false User object or false
     */
    public function verifyRememberToken($userId, $token)
    {
        $this->db->query('SELECT * FROM users WHERE id = :user_id AND token_expires > NOW()');
        $this->db->bind(':user_id', $userId);

        $user = $this->db->single();

        if ($user && password_verify($token, $user->remember_token)) {
            return $user;
        }

        return false;
    }

    /**
     * Clear remember me token for the user
     *
     * @param int $userId The user ID
     * @return bool
     */
    public function clearRememberToken($userId)
    {
        $this->db->query('UPDATE users SET remember_token = NULL, token_expires = NULL WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    /**
     * Store a password reset token for the user
     *
     * @param string $email The user's email
     * @param string $token The reset token
     * @return bool
     */
    public function storeResetToken($email, $token)
    {
        $this->db->query('UPDATE users SET reset_token = :token, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE email = :email');
        $this->db->bind(':token', password_hash($token, PASSWORD_DEFAULT));
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    /**
     * Verify a password reset token
     *
     * @param string $email The user's email
     * @param string $token The token to verify
     * @return object|false User object or false
     */
    public function verifyResetToken($email, $token)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email AND reset_token_expires > NOW()');
        $this->db->bind(':email', $email);

        $user = $this->db->single();

        if ($user && password_verify($token, $user->reset_token)) {
            return $user;
        }

        return false;
    }

    /**
     * Clear reset token after password change
     *
     * @param int $userId The user ID
     * @return bool
     */
    public function clearResetToken($userId)
    {
        $this->db->query('UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }
}
