<?php

namespace Yapay\Model;

interface IYapayModel
{

    /**
     * Validates an Entity
     *
     * @return array $errors Errors List
     */
    public function validate();
}
