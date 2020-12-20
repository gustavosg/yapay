<?php

namespace Yapay\Model;

/**
 * Customer Model
 *
 * @property string $type_contact Type Contact(H => Home, M => Mobile, W => Work)
 * @property string $number_contact Telephone
 */
class Contact implements IYapayModel
{
    const CONTACT_TYPE_HOME = "H";
    const CONTACT_TYPE_MOBILE = "M";
    const CONTACT_TYPE_WORK = "W";

    /**
     * @param string $type_contact Type Contact(H => Home, M => Mobile, W => Work
     * @param string $number_contact Telephone
     */
    public function __construct(
        string $type_contact,
        string $number_contact
    ) {
        $this->type_contact = $type_contact;
        $this->number_contact = $number_contact;

        return $this;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->type_contact) || !in_array(
            $this->type_contact,
            [
                self::CONTACT_TYPE_HOME,
                self::CONTACT_TYPE_MOBILE,
                self::CONTACT_TYPE_WORK
            ]
        )) {
            $errors["Contact.TypeContact"][] =
                sprintf("Tipo de Contato deve ser H-Residencial, W-Comercial ou M-Celular!");
        }

        if (empty($this->number_contact) || (strlen($this->number_contact) < 8 || strlen($this->number_contact) > 15)) {
            $errors["Contact.NumberContact"][] =
                sprintf("Número de Contato deve ter %s e %s dígitos.", 8, 15);
        }

        return count($errors) > 0 ? $errors : null;
    }
}
