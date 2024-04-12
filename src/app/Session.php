<?php

declare(strict_types=1);

namespace App;

use App\DTOs\SessionConfig;
use App\Exceptions\SessionException;
use App\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    public function __construct(private readonly SessionConfig $options)
    {
    }

    public function start(): void
    {
        if ($this->isActive()) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($filename, $line)) {
            throw new SessionException('header has already been sent at ' . $filename . ':' . $line);
        }

        session_set_cookie_params([
            'secure' => $this->options->secure,
            'httponly' => $this->options->httpOnly,
            'samesite' => $this->options->sameSite->value
        ]);

        if (! empty($this->options->name)) {
            session_name($this->options->name);
        }

        // Not ideal. Proper solution will be created later.
        // date_default_timezone_set('Africa/Lagos');

        if (! session_start()) {
            throw new SessionException('Unable to start sesssion');
        }
    }

    public function save(): void
    {
        session_write_close();
    }

    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    
    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? [];
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function regenerate(): void
    {
        session_regenerate_id();
    }

    public function flash(string $key, mixed $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    public function getFlash(string $key): mixed
    {
        $value = $_SESSION['flash'][$key] ?? [];

        unset($_SESSION['flash'][$key]);

        return $value;
    }
}