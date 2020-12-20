<?php

namespace Yapay\Model;

use Exception;
use Throwable;
use Yapay\Utils\FloatUtil;

/**
 * Transaction Model
 *
 * Entity for Transaction
 *
 * @property int $order_number Máx 20 caracters. Should not repeat
 * @property float $price_discount Price Discount (X.99 format)
 * @property float $shipping_price Shipping Price (X.99 format)
 * @property string $shipping_type Shipping Type
 * @property float $price_additional Additional Price. (X.99 format)
 * @property string $url_notification URL Notification
 * @property string $available_payment_methods Available Payment Methods
 * @property string $free Free
 * @property string $sub_store Sub Store
 * @property int $max_split_transaction Max Split Transaction
 *
 * @property string $customer_ip [READ ONLY] Customer IP Auto-filled by server
 *
 * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
 * @since 1.0.0
 *
 * @return Transaction $order Entity
 *
 * @date 2020-11-13
 */
class Transaction implements IYapayModel
{
    protected $customer_ip;

    /**
     * Constructor
     *
     * @param int $order_number Número do Pedido
     * @param float $price_discount Desconto
     * @param float $shipping_price Valor de envio
     * @param string $shipping_type Shipping Type
     * @param float $price_additional Valores adicionais
     * @param string $url_notification URL Notification
     * @param string $available_payment_methods Available Payment Methods
     * @param string $free Free
     * @param string $sub_store Sub Store
     * @param int $max_split_transaction Max Split Transaction
     *
     * @return Transaction $order Entity
     */
    public function __construct(
        int $order_number,
        float $price_discount,
        float $shipping_price,
        string $shipping_type,
        float $price_additional,
        string $url_notification = "",
        string $available_payment_methods = "",
        string $free = "",
        string $sub_store = "",
        int $maxSplitTransaction = null
    ) {
        $this->order_number = $order_number;
        $this->price_discount = $price_discount;
        $this->shipping_price = $shipping_price;
        $this->shipping_type = trim($shipping_type);
        $this->price_additional = $price_additional;

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $this->customer_ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $this->customer_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $this->customer_ip = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $this->customer_ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $this->customer_ip = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $this->customer_ip = $_SERVER['REMOTE_ADDR'];
        else
            $this->customer_ip = gethostbyname(gethostname());

        $this->url_notification = trim($url_notification);
        $this->available_payment_methods = trim($available_payment_methods);
        $this->free = trim($free);
        $this->sub_store = trim($sub_store);
        $this->max_split_transaction = $maxSplitTransaction;

        return $this;
    }

    public function validate()
    {
        $errors = [];

        if (strlen($this->order_number) > 20) {
            $errors["Transaction.OrderNumber"][] = sprintf(
                "Tamanho do Número da Transação não deve ser superior à %s caracteres.",
                20
            );
        }

        if (!empty($this->shipping_price)) {
            try {
                FloatUtil::checkFloatValue($this->shipping_price, 2);
            } catch (Throwable $th) {
                $errors["Transaction.ShippingPrice"][] = $th->getMessage();
            }
        }

        if (empty($this->shipping_type)) {
            $errors["Transaction.ShippingType"][] = "Tipo de Envio deve ser informado.";
        }

        if (!empty($this->price_additional)) {
            try {
                FloatUtil::checkFloatValue($this->price_additional, 2);
            } catch (Throwable $th) {
                $errors["Transaction.PriceAdditional"][] = $th->getMessage();
            }
        }

        if (empty($this->customer_ip)) {
            $errors["Transaction.CustomerIP"][] = "Servidor não conseguiu preencher IP de usuário na requisição. Informe suporte!";
        }

        if (!empty($this->price_discount)) {
            try {
                FloatUtil::checkFloatValue($this->price_discount, 2);
            } catch (Throwable $th) {
                $errors["Transaction.PriceDiscount"][] = $th->getMessage();
            }
        }

        return count($errors) > 0 ? $errors : null;
    }
}
