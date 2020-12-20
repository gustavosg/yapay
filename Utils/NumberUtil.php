<?php

namespace Yapay\Lib\Utils;

/**
 */
class NumberUtil
{

    /**
     * NumberUtil::validarCPF
     *
     * Função que valida o CPF informado
     *
     * @param string $cpf CPF à ser validado
     *
     * @author @rafael-neri
     * @link https://gist.github.com/rafael-neri/ab3e58803a08cb4def059fce4e3c0e40
     *
     * @return bool
     */
    public static function validateCPF(string $cpf)
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return [
                "status" => false,
                "message" => sprintf("Tamanho do %s deve ser de %s dígitos", "CPF", 11)
            ];
        }

        $invalidNumber = false;

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $invalidNumber = true;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{
                    $c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{
                $c} != $d) {
                $invalidNumber = true;
            }
        }

        if ($invalidNumber) {
            return [
                "status" => false,
                "message" => "Número de CPF informado é inválido"
            ];
        }
        return ["status" => true];
    }

    /**
     * NumberUtil::validateCNPJ
     *
     * Função que realiza validação de CNPJ
     *
     * @param string $cnpj CNPJ
     *
     * @author Gerador CNPJ
     * @link https://www.geradorcnpj.com/script-validar-cnpj-php.htm
     * @since 20/11/2018
     */
    public static function validateCNPJ(string $cnpj = null)
    {
        try {
            // Verifica se um número foi informado
            if (empty($cnpj)) {
                return false;
            }

            // Elimina possivel mascara
            $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
            $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
            $cnpj = strval($cnpj);

            // Verifica se o numero de digitos informados é igual a 11
            if (strlen($cnpj) != 14) {
                return false;
            }

            // Verifica se nenhuma das sequências invalidas abaixo
            // foi digitada. Caso afirmativo, retorna falso
            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            elseif (preg_match('/(\d)\1{13}/', $cnpj)) {
                $invalidNumber = true;
                // Calcula os digitos verificadores para verificar se o
                // CNPJ é válido
            } else {
                $j = 5;
                $k = 6;
                $soma1 = 0;
                $soma2 = 0;

                for ($i = 0; $i < 13; $i++) {

                    $j = $j == 1 ? 9 : $j;
                    $k = $k == 1 ? 9 : $k;

                    $soma2 += ($cnpj[$i] * $k);

                    if ($i < 12) {
                        $soma1 += ($cnpj[$i] * $j);
                    }

                    $k--;
                    $j--;
                }

                $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
                $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

                return (($cnpj{
                    12} == $digito1) and ($cnpj{
                    13} == $digito2));
            }
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
