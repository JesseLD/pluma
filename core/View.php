<?php

namespace Core;

class View
{
    protected static ?string $layout = null;
    protected static array $sections = [];
    protected static array $sectionStack = [];
    protected static array $shared = [];

    /**
     * Define uma variável compartilhada acessível nas views/layouts
     */
    public static function set(string $key, $value): void
    {
        self::$shared[$key] = $value;
    }

    /**
     * Recupera uma variável compartilhada
     */
    public static function get(string $key, $default = null)
    {
        return self::$shared[$key] ?? $default;
    }

    /**
     * Define qual layout será usado
     */
    public static function extend(string $layout): void
    {
        self::$layout = $layout;
    }

    /**
     * Inicia uma seção de conteúdo
     */
    public static function startSection(string $name): void
    {
        self::$sectionStack[] = $name;
        ob_start();
    }

    /**
     * Finaliza a seção atual e armazena seu conteúdo
     */
    public static function endSection(): void
    {
        $content = ob_get_clean();
        $name = array_pop(self::$sectionStack);
        self::$sections[$name] = $content;
    }

    /**
     * Exibe o conteúdo de uma seção no layout
     */
    public static function section(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    /**
     * Renderiza a view e o layout (se definido)
     */
    public static function render(string $view, array $data = []): string
    {
        $basePath = dirname(__DIR__) . "/public/views/";
        $viewPath = $basePath . str_replace('.', '/', $view) . ".php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View '$view' não encontrada.");
        }

        extract($data);

        // Executa a view para preencher as seções
        include $viewPath;

        // Se tiver layout, renderiza o layout com as seções
        if (self::$layout) {
            $layoutPath = $basePath . "layouts/" . self::$layout . ".php";

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout '" . self::$layout . "' não encontrado.");
            }

            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        // Se não houver layout, retorna a seção "content", se existir
        return self::$sections['content'] ?? '';
    }
}
