<?php declare(strict_types=1);

namespace App;

class Validator
{
    public static function passed(): bool
    {
        return empty($_SESSION['errors']);
    }

    public function register(string $email, string $password, string $passwordConfirmation): void
    {
        $this->registrationEmail($email);
        $this->passwordConfirmation($password, $passwordConfirmation);
        $this->passwordRequirements($password);
    }

    public function login(string $email, string $password)
    {
        $user = (new Database())->getConnection()->fetchAssociative('SELECT * FROM users WHERE email = ?', [$email]);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors']['login'] [] = 'Login failed. Please make sure that all fields are filled out correctly and try again!';
        }
    }

    private function registrationEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] [] = 'Email is not valid';
        }

        $emailCountInSystem = (new Database())->getConnection()->executeStatement('SELECT * FROM users WHERE email = ?', [$email]);
        if ($emailCountInSystem > 0) {
            $_SESSION['errors']['email'] [] = 'Registration failed. Please check that all fields are filled out correctly and try again!';
        }
    }

    private function passwordRequirements(string $password): void
    {
        preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/', $password, $matches);
        if (empty($matches[0])) {
            $_SESSION['errors']['password'] [] = 'Password must contain at least: 8 characters, uppercase letter, lowercase letter and number';
        }
    }

    private function passwordConfirmation(string $password, string $passwordConfirmation): void
    {
        if ($password !== $passwordConfirmation) {
            $_SESSION['errors']['password'] [] = 'Password confirmation does not match';
        }
    }
}