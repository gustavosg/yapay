<?php

namespace Yapay\Utils;

use Exception;

/**
 */
class FloatUtil
{

    /**
     * Verifica Valor float
     *
     * @param [type] $value
     * @param [type] $digits
     * @return void
     */
    public static function checkFloatValue($value, $digits)
    {
        // campo não pode ser nulo
        if (empty($value)) {
            throw new Exception("Valor deve ser informado!");
        }

        if (!is_float($value)) {
            throw new Exception("Valor não é ponto flutuante!");
        }

        $value = floatval($value);

        $splits = explode(".", $value);

        if (!empty($splits[1])) {
            $decimals = $splits[1];

            if (strlen($decimals) > $digits) {
                throw new Exception("Valores decimais não devem passar de %s dígitos!", $digits);
            }
        }
    }
}
