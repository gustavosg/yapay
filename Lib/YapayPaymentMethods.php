<?php

namespace Yapay\Lib;

use ReflectionClass;

abstract class YapayPaymentMethods
{
    // Transferências Online
    const ONLINE_TRANSFER_ITAU_SHOPLINE = 7;
    const ONLINE_TRANSFER_BRADESCO = 22;
    const ONLINE_TRANSFER_BANCO_DO_BRASIL = 23;

    // Boleto
    const BILLET_BOLETO_BANCARIO = 6;

    // Saldo
    const BALANCE_PAGAMENTO_COM_SALDO = 8;

    // Cartões de Crédito
    const CREDIT_CARD_VISA = 3;
    const CREDIT_CARD_MASTERCARD = 4;
    const CREDIT_CARD_AMERICAN_EXPRESS = 5;
    const CREDIT_CARD_DISCOVER = 15;
    const CREDIT_CARD_ELO = 16;
    const CREDIT_CARD_AURA = 18;
    const CREDIT_CARD_JCB = 19;
    const CREDIT_CARD_HIPERCARD = 20;
    const CREDIT_CARD_HIPER_ITAU = 25;

    private static function getClassConstant(string $constantName)
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstant($constantName);
    }

    /**
     * Validação da forma de pagamento
     *
     * @param integer $paymentMethodId id da Forma de pagamento aceita pela Yapay
     *
     * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
     * @since 1.0.0
     *
     * @date 2020-11-20
     *
     * @return string $message
     */
    public static function validatePaymentMethod(int $paymentMethodId)
    {
        $paymentAllowedList = [
            self::ONLINE_TRANSFER_ITAU_SHOPLINE,
            self::ONLINE_TRANSFER_BRADESCO,
            self::ONLINE_TRANSFER_BANCO_DO_BRASIL,
            self::BILLET_BOLETO_BANCARIO,
            self::BALANCE_PAGAMENTO_COM_SALDO,
            self::CREDIT_CARD_VISA,
            self::CREDIT_CARD_MASTERCARD,
            self::CREDIT_CARD_AMERICAN_EXPRESS,
            self::CREDIT_CARD_DISCOVER,
            self::CREDIT_CARD_ELO,
            self::CREDIT_CARD_AURA,
            self::CREDIT_CARD_JCB,
            self::CREDIT_CARD_HIPERCARD,
            self::CREDIT_CARD_HIPER_ITAU
        ];

        if (!in_array($paymentMethodId, $paymentAllowedList)) {
            return sprintf("Forma de pagamento não aceita pela plataforma %s!", "Yapay");
        }

        return null;
    }

    /**
     * Validação da forma de pagamento
     *
     * @param integer $paymentMethodId id da Forma de pagamento da Yapay
     * @param integer $splits Quantidade de parcelamentos
     *
     * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
     * @since 1.0.0
     *
     * @date 2020-11-20
     *
     * @return string $message
     */
    public static function validatePaymentSplits(int $paymentMethodId, int $splits)
    {
        $creditCardAllowed = [
            self::CREDIT_CARD_VISA,
            self::CREDIT_CARD_MASTERCARD,
            self::CREDIT_CARD_AMERICAN_EXPRESS,
            // self::CREDIT_CARD_DISCOVER,
            self::CREDIT_CARD_ELO,
            self::CREDIT_CARD_AURA,
            // self::CREDIT_CARD_JCB,
            self::CREDIT_CARD_HIPERCARD,
            self::CREDIT_CARD_HIPER_ITAU
        ];

        if (!in_array($paymentMethodId, $creditCardAllowed) && $splits > 1) {
            return sprintf("Esta forma de pagamento só suporta pagamento %s!", "À Vista");
        } elseif ($splits > 12) {
            return sprintf("Máximo de parcelas permitida é %s vezes.", 12);
        }

        return null;
    }

    /**
     * Retorna id de método de pagamento pelo cartão
     *
     * @param string $creditCardNumber Cartão de Crédito
     *
     * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
     * @since 1.0.0
     * @date 2020-11-23
     *
     * @return int $paymentMethodId
     */
    public static function getPaymentMethodId(string $creditCardNumber)
    {
        // https://intermediador.dev.yapay.com.br/#/tabelas?id=tabela-3-formas-de-pagamento

        // Brands regex
        $brands = [
            'CREDIT_CARD_VISA'       => '/^4\d{12}(\d{3})?$/',
            'CREDIT_CARD_MASTERCARD' => '/^(5[1-5]\d{4}|677189)\d{10}$/',
            //'diners'     => '/^3(0[0-5]|[68]\d)\d{11}$/',
            'CREDIT_CARD_DISCOVER'   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'CREDIT_CARD_ELO'        => '/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
            // 'amex'       => '/^3[47]\d{13}$/',
            'CREDIT_CARD_JCB'        => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'CREDIT_CARD_AURA'       => '/^(5078\d{2})(\d{2})(\d{11})$/',
            'CREDIT_CARD_HIPERCARD'  => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
            // 'maestro'    => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
        ];

        // Run test
        $brand = '';

        foreach ($brands as $_brand => $regex) {
            if (preg_match($regex, $creditCardNumber)) {
                $brand = $_brand;
                break;
            }
        }

        return self::getClassConstant($brand);
    }
}
