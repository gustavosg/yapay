<?php

namespace Yapay\Model;

use App\Lib\Utils\DateTimeUtil;
use DateTime;
use Yapay\Lib\YapayPaymentMethods;
use Yapay\Settings;

/**
 * Payment Model
 *
 * Entity for Payment
 *
 * @property float $split Split Payments
 * @property int $payment_method_id Payment Method Id
 * @property string $billet_date_expiration Billet Date $billet_date_expiration (DD/MM/YYYY format)
 * @property string $card_token Card Token
 * @property string $card_name Card Name of Customer
 * @property string $card_number Card Number
 * @property string $card_expdate_month Card Month Expiration Date
 * @property string $card_expdate_year Card Year Expiration Date
 * @property string $card_cvv Card Verification Value
 * @property string $environment Environment Mode (PRODUCTION / HOMOLOGATION)
 *
 * @author Gustavo Souza Gonçalves <gustavosouzagoncalves@outlook.com>
 * @since 1.0.0
 *
 * @date 2020-11-16
 */
class Payment implements IYapayModel
{
    /**
     * Constructor
     *
     * @param string $billet_date_expiration Billet Date $billet_date_expiration (DD/MM/YYYY format)
     * @param float $split Split Payments
     * @param string $card_token Card Token
     * @param string $card_name Card Name of Customer
     * @param string $card_number Card Number
     * @param string $card_expdate_month Card Month Expiration Date
     * @param string $card_expdate_year Card Year Expiration Date
     * @param string $card_cvv Card Verification Value
     * @param string $environment Environment Mode (PRODUCTION / HOMOLOGATION)
     * @param int $payment_method_id Payment Method Id (if 15 or 19, only accepts 1x split)
     *
     * @return Payment $payment Entity
     */
    public function __construct(
        string $billet_date_expiration,
        float $split = null,
        string $card_token = null,
        string $card_name = null,
        string $card_number = null,
        string $card_expdate_month = null,
        string $card_expdate_year = null,
        string $card_cvv = null,
        string $environment = "PRODUCTION",
        int $payment_method_id = null
    ) {
        $this->billet_date_expiration = $billet_date_expiration;
        // If not null, should be the value passed in parameter. Otherwise, is calculated
        $this->payment_method_id = $payment_method_id ?? YapayPaymentMethods::getPaymentMethodId($card_number);
        $this->environment = $environment;

        if (empty($payment_method_id)) {
            $this->split = $split;
            $this->card_token = $card_token;
            $this->card_name = $card_name;
            $this->card_number = $card_number;
            $this->card_expdate_month = str_pad($card_expdate_month, 2, "0", STR_PAD_LEFT);
            $this->card_expdate_year = str_pad($card_expdate_year, 4, "20", STR_PAD_LEFT);
            $this->card_cvv = $card_cvv;
        }

        return $this;
    }

    public function validate()
    {
        $errors = [];
        $actualMonth = intval(date("m"));
        $actualYear = intval(date("Y"));

        $paymentValidation = YapayPaymentMethods::validatePaymentMethod($this->payment_method_id);

        if (!empty($paymentValidation)) {
            $errors["Payment.PaymentMethodId"][] = $paymentValidation;
        }

        if ($this->payment_method_id !== YapayPaymentMethods::BILLET_BOLETO_BANCARIO) {
            $splitsValidation = YapayPaymentMethods::validatePaymentSplits($this->payment_method_id, $this->split);

            if (!empty($splitsValidation)) {
                $errors["Payment.Splits"][] = $splitsValidation;
            }
        }

        if (($this->payment_method_id == YapayPaymentMethods::BILLET_BOLETO_BANCARIO)
            && !DateTimeUtil::validateDate($this->billet_date_expiration, "d/m/Y")
        ) {
            $errors["Payment.BilletDateExpiration"][] =
                "Data de Vencimento com formato inválido. Formato deve ser DD/MM/YYYY.";
        }

        if ($this->payment_method_id !== YapayPaymentMethods::BILLET_BOLETO_BANCARIO) {
            // Validação do card_token só deve acontecer em produção
            if ($this->environment === Settings::YAPAY_ENVIRONMENT_PRODUCTION && empty($this->card_token)) {
                $errors["Payment.CardToken"][] = "Token do Cartão não informado.";
            }

            if (empty($this->card_cvv)) {
                $errors["Payment.CardCVV"][] = "Código CVV do Cartão não informado.";
            } elseif (strlen($this->card_cvv) > 4) {
                $errors["Payment.CardCVV"][] = sprintf("Código CVV do Cartão deve conter no máximo %s dígitos.", 4);
            }

            if (empty($this->card_name)) {
                $errors["Payment.CardName"][] = "Favor informar nome conforme escrito no Cartão de Crédito.";
            } elseif (preg_match("/\d/", $this->card_name)) {
                $errors["Payment.CardName"][] = "Informe nome conforme escrito no Cartão de Crédito, sem numerações.";
            }

            if (empty($this->card_number)) {
                $errors["Payment.CardNumber"][] = "Favor informar número do Cartão de Crédito.";
            } elseif (preg_match("/\D/", $this->card_number)) {
                $errors["Payment.CardNumber"][] = "Informe número conforme escrito no Cartão de Crédito, sem letras.";
            }

            if ($actualYear > intval($this->card_expdate_year)) {
                $errors["Payment.CardExpdateYear"][] = "Ano do Cartão de Crédito deve ser igual ou maior que o ano atual.";
            }

            if ($actualYear === intval($this->card_expdate_year) && $actualMonth > intval($this->card_expdate_month)) {
                $errors["Payment.CardExpdateMonth"][] = "Mês do Cartão de Crédito deve ser igual ou maior que o ano atual.";
            }
        }


        return count($errors) > 0 ? $errors : null;
    }
}
