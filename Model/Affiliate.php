<?php

namespace Yapay\Model;

use DateTime;

/**
 * Affiliate Model
 *
 * Entity for Affiliate
 *
 * @property string $account_email
 * @property integer $percentage
 * @property float $commission_amount
 * @property string $type_affiliate
 *
 * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
 * @since 1.0.0
 *
 * @date 2020-11-16
 */
class Affiliate implements IYapayModel
{
    /**
     * Constructor
     *
     * @param string $account_email
     * @param integer $percentage
     * @param float $commission_amount
     * @param string $type_affiliate
     *
     * @return Affiliate $Affiliate Entity
     */
    public function __construct(
        string $account_email,
        int $percentage,
        float $commission_amount,
        string $type_affiliate
    ) {
        $this->account_email = $account_email;
        $this->percentage = $percentage;
        $this->commission_amount = $commission_amount;
        $this->type_affiliate = $type_affiliate;

        return $this;
    }

    public function validate()
    {
        $errors = [];

        if (filter_var($this->account_email, FILTER_VALIDATE_EMAIL)) {
            $errors["Affiliate.Email"][] = sprintf(
                "E-mail para afiliado inválido. Deve possuir o seguinte formato: %s",
                "user@email.com"
            );
        }

        if (!empty($this->percentage) && ($this->percentage < 1 || $this->percentage > 100)) {
            $errors["Affiliate.Percentage"][] = sprintf(
                "Valor da porcentagem do afiliado deve ser entre %s e %s",
                1,
                100
            );
        }

        if (!empty($this->commission_amount) && !filter_var($this->commission_amount, FILTER_VALIDATE_FLOAT)) {
            $errors["Affiliate.ComissionAmount"][] = sprintf(
                "O valor deste campo deve conter %s casas decimais",
                2
            );
        }

        return count($errors) > 0 ? $errors : null;
    }
}
