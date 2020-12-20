<?php

namespace Yapay\Model;

/**
 * Address Model
 *
 * @property string $type_address Type Address (Charge => B, Deliver => D)
 * @property string $postal_code Address Postal Code
 * @property string $street Address Street
 * @property string $number Address Number
 * @property string $state State
 * @property string $city City
 * @property string $neighborhood Neighborhood
 * @property string $completion Completion Address Buyer
 */
class Address implements IYapayModel
{
    const ADDRESS_TYPE_CHARGE = 'B';
    const ADDRESS_TYPE_DELIVER = 'D';

    /**
     * Constructor
     *
     * @param string $type_address Type Address (Charge => B, Deliver => D)
     * @param string $postal_code Address Postal Code
     * @param string $street Address Street
     * @param string $number Address Number
     * @param string $state State
     * @param string $city City
     * @param string $neighborhood Neighborhood
     * @param string $completion Completion Address Buyer
     */
    public function __construct(
        string $type_address,
        string $postal_code,
        string $street,
        string $number,
        string $state,
        string $city,
        string $neighborhood,
        string $completion = null
    ) {
        $this->type_address = $type_address;
        $this->postal_code = $postal_code;
        $this->street = $street;
        $this->number = $number;
        $this->state = $state;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        $this->completion = $completion;

        return $this;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->type_address)) {
            $errors["Address.TypeAddress"][] = "Tipo de endereço deve ser informado.";
        }

        if (empty($this->postal_code)) {
            $errors["Address.PostalCode"][] = "Código postal deve ser informado.";
        } else {
            if (strlen($this->postal_code) != 8) {
                $errors["Address.PostalCode"][] =
                    sprintf(
                        "Código postal deve ter %s dígitos.",
                        8
                    );
            } elseif (preg_match("/\D/", $this->postal_code)) {
                $errors["Address.PostalCode"][] =
                    sprintf(
                        "Código postal deve ter o seguinte formato: %s.",
                        "99999999"
                    );
            }
        }

        if (!empty($this->street) && preg_match("/\d/", $this->street)) {
            $errors["Address.Street"][]
                = "Campo Endereço deve ser enviado somente a RUA, informe o número no campo de Número";
        }

        if (empty($this->state)) {
            $errors["Address.State"][] = "Estado deve ser informado.";
        } elseif (strlen($this->state) !== 2) {
            $errors["Address.State"][] = sprintf("Estado deve conter %s dígitos.", 2);
        }

        if (empty($this->city)) {
            $errors["Address.City"][] = "Cidade deve ser informada.";
        }

        if (empty($this->neighborhood)) {
            $errors["Address.Neighborhood"][] = "Bairro deve ser informado.";
        } elseif (strlen($this->neighborhood) > 100) {
            $errors["Address.Neighborhood"][] = sprintf("Bairro deve ser no máximo %s dígitos.", 100);
        }

        if (!empty($this->completion) && strlen($this->completion) > 100) {
            $errors["Address.Completion"][] = sprintf("Dados Complementares deve ser no máximo %s dígitos.", 100);
        }

        return count($errors) > 0 ? $errors : null;
    }
}
