<?php

namespace Yapay\Model;

use App\Lib\Utils\DateTimeUtil;
use App\Lib\Utils\NumberUtil;

/**
 * Customer Model
 *
 * @property string $name Name
 * @property string $email EMAIL
 * @property string $cpf CPF
 * @property string $cnpj CNPJ
 * @property string $municipal_inscrition Municipal Inscrition
 * @property string $trade_name Trade Name
 * @property string $company_name Company Name
 * @property string $birth_date Birth Date (DD/MM/YYYY Format)
 * @property Contact $contacts[] Contacts
 * @property Address $addresses[] Addresses
 */
class Customer
{
    const CUSTOMER_ACCOUNT_TYPE_PERSONAL = "1";
    const CUSTOMER_ACCOUNT_TYPE_BUSINESS = "2";

    /**
     * Customer Model
     *
     * @param string $name Name
     * @param string $email EMAIL
     * @param string $cpf CPF
     * @param string $cnpj CNPJ
     * @param string $municipal_inscrition Municipal Inscrition
     * @param string $trade_name Trade Name
     * @param string $customer_name Customer Name
     * @param string $birth_date Birth Date (DD/MM/YYYY Format)
     * @param Contact $contacts Contacts
     * @param Address $addresses Addresses
     */
    public function __construct(
        string $name,
        string $email,
        string $cpf,
        string $cnpj = null,
        string $municipal_inscrition = null,
        string $trade_name = null,
        string $company_name = null,
        string $birth_date = null,
        array $contacts = [],
        array $addresses = []
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->cpf = preg_replace("/\D/", "", $cpf);
        $this->cnpj = preg_replace("/\D/", "", $cnpj);
        $this->municipal_inscrition = preg_replace("/\D/", "", $municipal_inscrition);
        $this->trade_name = trim($trade_name);
        $this->company_name = trim($company_name);
        $this->birth_date = $birth_date;
        $this->contacts = $contacts;
        $this->addresses = $addresses;

        return $this;
    }

    public function validate()
    {
        $errors = [];

        $cpfValidation = NumberUtil::validateCPF($this->cpf);
        $cnpjValidation = NumberUtil::validateCNPJ($this->cnpj);

        if (empty($this->name)) {
            $errors["Customer.Name"][] = "Nome Completo deve ser informado";
        }

        if (!$cpfValidation["status"]) {
            $errors["Customer.CPF"][] = $cpfValidation["message"];
        }

        if (!empty($this->cnpj) && !$cnpjValidation["status"]) {
            $errors["Customer.CNPJ"][] = $cnpjValidation["message"];
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors["Customer.Email"][] = sprintf(
                "E-mail inválido. Deve possuir o seguinte formato: %s",
                "user@email.com"
            );
        }

        if (!empty($this->birth_date) && !DateTimeUtil::validateDate($this->birth_date, "d/m/Y")) {
            $errors["Customer.BirthDate"][] =
                "Data de Nascimento com formato inválido. Formato deve ser DD/MM/YYYY.";
        }

        return count($errors) > 0 ? $errors : null;
    }
}
