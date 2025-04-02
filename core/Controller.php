<?php

namespace Core;

abstract class Controller
{
    /**
     * Renders a view using the custom View engine.
     *
     * @param string $view The view name in dot notation (e.g. "home.index").
     * @param array $data Optional data to pass into the view.
     * @return string Rendered HTML content.
     *
     * Example:
     * return $this->view('auth.login', ['title' => 'Login']);
     */
    public function view(string $view, array $data = [])
    {
        return View::render($view, $data);
    }

    /**
     * Redirects the user to a different URL.
     *
     * @param string $url The target URL.
     *
     * Example:
     * return $this->redirect('/dashboard');
     */
    public function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Retrieves a value from POST or GET input.
     *
     * @param string $key The input field name.
     * @param mixed $default Default value if key is not found.
     * @return mixed The input value.
     *
     * Example:
     * $email = $this->input('email');
     */
    public function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Retrieves a session value, or all session data if no key is given.
     *
     * @param string|null $key Optional session key.
     * @return mixed The session value or the entire session array.
     *
     * Example (get one):
     * $userId = $this->session('user_id');
     *
     * Example (get all):
     * $session = $this->session();
     */
    public function session(?string $key = null)
    {
        session_start();

        if ($key !== null) {
            return $_SESSION[$key] ?? null;
        }

        return $_SESSION;
    }
}
