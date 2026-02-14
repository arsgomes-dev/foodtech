<?php
namespace Microfw\Src\Main\Common\Settings;

/**
 * Description of RequestSanitizer
 *
 * @author Ricardo Gomes
 */
class RequestSanitizer
{
    private $errors = [];

    /**
     * Retorna TRUE se todas validações passaram
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Retorna erros armazenados
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /* ============================================================
     *  MÉTODOS DE SANITIZAÇÃO
     * ============================================================ */

    public function text(string $key): ?string
    {
        // Captura conteúdo cru
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);

        if ($value === null || $value === '') {
            return null;
        }

        // Remove tags perigosas e normaliza espaços
        $value = strip_tags($value);

        return trim($value);
    }

    public function textarea(string $key): ?string
    {
        // Aceita conteúdo mais livre, mas evita tags perigosas
        $value = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);

        if ($value === null) {
            return null;
        }

        return trim($value);
    }

    public function int(string $key): ?int
    {
        $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT);

        return ($value === '' || $value === null)
            ? null
            : (int) $value;
    }

    public function float(string $key): ?float
    {
        $value = filter_input(
            INPUT_POST,
            $key,
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        return ($value === '' || $value === null)
            ? null
            : (float) $value;
    }

    public function bool(string $key): bool
    {
        return (bool) filter_input(INPUT_POST, $key, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Transforma CSV em array seguro
     * Ex: "a, b, c" -> ["a", "b", "c"]
     */
    public function csvArray(string $key): array
    {
        $raw = $this->textarea($key);
        if (!$raw) return [];

        $items = explode(',', $raw);

        return array_filter(array_map(function ($item) {
            return trim(strip_tags($item));
        }, $items));
    }


    /* ============================================================
     *  VALIDAÇÕES
     * ============================================================ */

    public function required($value, string $label): void
    {
        if ($value === null || $value === '') {
            $this->errors[] = "$label é obrigatório.";
        }
    }

    public function minLength($value, string $label, int $min): void
    {
        if ($value !== null && mb_strlen($value) < $min) {
            $this->errors[] = "$label deve ter pelo menos $min caracteres.";
        }
    }

    public function maxLength($value, string $label, int $max): void
    {
        if ($value !== null && mb_strlen($value) > $max) {
            $this->errors[] = "$label deve ter no máximo $max caracteres.";
        }
    }

    public function minNumber($value, string $label, float $min): void
    {
        if ($value !== null && $value < $min) {
            $this->errors[] = "$label deve ser maior ou igual a $min.";
        }
    }

    public function maxNumber($value, string $label, float $max): void
    {
        if ($value !== null && $value > $max) {
            $this->errors[] = "$label deve ser menor ou igual a $max.";
        }
    }

    public function inArray($value, string $label, array $list): void
    {
        if ($value !== null && !in_array($value, $list)) {
            $this->errors[] = "$label possui um valor inválido.";
        }
    }
}
