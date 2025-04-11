<?php

namespace Core;

class View
{
    protected static ?string $layout = null;
    protected static array $sections = [];
    protected static array $sectionStack = [];
    protected static array $shared = [];

    /**
     * Stores a shared key-value pair that can be accessed from any view or layout.
     *
     * Example:
     * View::set('title', 'Dashboard');
     * In layout: View::get('title');
     */
    public static function set(string $key, $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Retrieves a shared variable.
     *
     * @param string $key The key to retrieve.
     * @param mixed $default Default value if key is not found.
     */
    public static function get(string $key, $default = null)
    {
        return self::$shared[$key] ?? $default;
    }

    /**
     * Sets the layout file to be used for the current view.
     *
     * Example:
     * View::extend('app'); // looks for layouts/app.pluma.php
     */
    public static function extend(string $layout): void
    {
        self::$layout = $layout;
    }

    /**
     * Starts capturing a section of content to be injected into the layout.
     *
     * Example in view:
     * View::startSection('content');
     */
    public static function startSection(string $name): void
    {
        self::$sectionStack[] = $name;
        ob_start();
    }

    /**
     * Ends the last started section and stores the captured content.
     *
     * Example in view:
     * View::endSection();
     */
    public static function endSection(): void
    {
        $content = ob_get_clean();
        $name = array_pop(self::$sectionStack);
        self::$sections[$name] = $content;
    }

    /**
     * Outputs the content of a section from the layout.
     *
     * Example in layout:
     * View::section('content');
     */
    public static function section(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    /**
     * Renders a view with optional data and wraps it in a layout if defined.
     * Supports .pluma.php templates and auto-escapes {{ }} output using e().
     * 
     * @param string $view Dot-separated path to the view (e.g. "auth.login")
     * @param array $data Variables to extract into the view
     * @return string The rendered HTML
     */
    public static function render(string $view, array $data = []): string
    {
        $basePath = dirname(__DIR__) . "/public/views/";
        $dotPath = str_replace('.', '/', $view);
        $viewPath = $basePath . $dotPath . '.pluma.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("View '{$view}' not found at: {$viewPath}");
        }

        // Read the view content
        $rawContent = file_get_contents($viewPath);

        // Replace {{ ... }} with escaped PHP output using helper `e()`.
        // Automatically adds "$" to variable names that appear at the beginning
        // of the expression, like: `message ?? 'Default'` → `$message ?? 'Default'`

        /**
         * RAW output: {!! ... !!} → <?php echo ... ?>;
         */
        $parsedContent = preg_replace_callback('/{!!\s*(.+?)\s*!!}/', function ($matches) {
            return "<?php echo {$matches[1]}; ?>";
        }, $rawContent);

        /**
         * ESCAPED output: {{ ... }} → <?php echo e(...); ?>
         */
        $parsedContent = preg_replace_callback('/{{\s*(.+?)\s*}}/', function ($matches) {
            $expr = trim($matches[1]);

            // Match expressions que começam com uma variável simples (sem $)
            if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)(.*)$/', $expr, $parts)) {
                if (!str_starts_with($parts[1], '$')) {
                    $expr = '$' . $parts[1] . $parts[2];
                }
            }

            return "<?php echo e($expr); ?>";
        }, $rawContent);



        // Generate temporary cached file path
        $cacheDir = dirname(__DIR__) . '/storage/views/';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $cacheFile = $cacheDir . md5($viewPath) . '.cache.php';

        // file_put_contents($cacheFile, $parsedContent);

        // Only recompile if original view is newer than the cache
        if (!file_exists($cacheFile) || filemtime($viewPath) > filemtime($cacheFile)) {
            file_put_contents($cacheFile, $parsedContent);
        }


        extract($data);

        // Load the parsed view into $content
        ob_start();
        include $cacheFile;
        $content = ob_get_clean();

        // Render layout if needed
        if (self::$layout) {
            $layoutPath = $basePath . "layouts/" . self::$layout . ".pluma.php";

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout '" . self::$layout . "' not found at: {$layoutPath}");
            }

            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        return $content;
    }


    /**
     * Checks if a view file exists.
     *
     * @param string $view Dot-notated view name (e.g. "errors.404")
     * @return bool
     */
    public static function exists(string $view): bool
    {
        $basePath = dirname(__DIR__) . "/public/views/";
        $dotPath = str_replace('.', '/', $view);
        $viewPath = $basePath . $dotPath . '.pluma.php';

        return file_exists($viewPath);
    }
}
