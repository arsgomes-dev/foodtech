<?php
namespace Microfw\Src\Main\Common\Helpers\Public\PostRedirect;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

class PostRedirect {
    /**
     * Redireciona para uma página enviando POST automaticamente.
     *
     * @param string $url
     * @param array $data
     */
    public static function to(string $url, array $data = [])
    {
        // Previne warning caso já tenha saída
        if (headers_sent()) {
            // Apenas exibe formulário mesmo assim
        }

        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Redirecionando...</title>
        </head>
        <body onload="document.forms[\'redirectForm\'].submit();">
            <form id="redirectForm" action="' . htmlspecialchars($url) . '" method="POST">';

        foreach ($data as $key => $value) {
            echo '<input type="hidden" name="' . htmlspecialchars($key ?? '', ENT_QUOTES, 'UTF-8'). '" value="' . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'). '">';
        }

        echo '</form>
            <p style="font-family: Arial; text-align:center; margin-top:20px;">Redirecionando...</p>
        </body>
        </html>';

        exit; // garante que o fluxo pare aqui
    }
}
