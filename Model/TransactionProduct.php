<?php

namespace Yapay\Model;

use Yapay\Utils\FloatUtil;

/**
 * TransactionProduct Model
 *
 * Entity for TransactionProduct
 *
 * @property string $description Product Description
 * @property int $quantity Product Quantity
 * @property float $priceUnit Price Unit
 * @property string $code Product Code
 * @property string $skuCode SKU Code
 * @property string $extra Product Free Field
 *
 * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
 * @since 1.0.0
 *
 * @return TransactionProduct $order Entity
 *
 * @date 2020-11-18
 */
class TransactionProduct implements IYapayModel
{
    /**
     * Constructor
     *
     * @param string $description Product Description
     * @param int $quantity Product Quantity
     * @param float $priceUnit Price Unit
     * @param string $code Product Code
     * @param string $skuCode SKU Code
     * @param string $extra Product Free Field
     *
     * @return ProductOrder $productOrder Entity
     */
    public function __construct(
        string $description,
        int $quantity,
        float $price_unit,
        string $code = null,
        string $sku_code = null,
        string $extra = null
    ) {
        $this->code = $code;
        $this->sku_code = $sku_code;
        $this->price_unit = $price_unit;
        $this->quantity  = $quantity;
        $this->description = trim($description);
        $this->extra = trim($extra);

        return $this;
    }

    public function validate()
    {
        $errors = [];

        if (strlen($this->sku_code) > 50) {
            $errors["TransactionProduct.SKUCode"][] = sprintf("Máximo %s caracteres.", 50);
        }

        try {
            FloatUtil::checkFloatValue($this->price_unit, 2);
        } catch (\Throwable $th) {
            $errors["TransactionProduct.PriceUnit"][] = sprintf("Máximo %s caracteres.", 50);
        }

        if (empty($this->quantity)) {
            $errors["TransactionProduct.Quantity"][] = "Quantidade do Produto deve ser informada.";
        }

        if (empty($this->description)) {
            $errors["TransactionProduct.Description"][] = "Descrição do Produto deve ser informada.";
        }

        return count($errors) > 0 ? $errors : null;
    }
}
