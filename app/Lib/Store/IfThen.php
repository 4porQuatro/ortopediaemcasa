<?php

namespace App\Lib\Store;

class IfThen
{
    private $entity_id;
    private $sub_entity_id;

    /**
     * IfThen constructor.
     *
     * @param $entity_id
     * @param $sub_entity_id
     */
    public function __construct($entity_id, $sub_entity_id)
    {
        $this->entity_id = $entity_id;
        $this->sub_entity_id = $sub_entity_id;
    }

    private function format_number($number)
    {
        $verifySepDecimal = number_format(99,2);
        $valorTmp = $number;
        $sepDecimal = substr($verifySepDecimal, 2, 1);
        $hasSepDecimal = True;

        $i=(strlen($valorTmp) - 1);

        for($i; $i != 0; $i -= 1)
        {
            if(substr($valorTmp, $i, 1) == "." || substr($valorTmp, $i, 1) == ",")
            {
                $hasSepDecimal = True;
                $valorTmp = trim(substr($valorTmp, 0, $i)) . "@" . trim(substr($valorTmp, 1+ $i));
                break;
            }
        }

        if($hasSepDecimal!=True){
            $valorTmp = number_format($valorTmp, 2);

            $i = (strlen($valorTmp) - 1);

            for($i; $i != 1; $i--)
            {
                if(substr($valorTmp, $i, 1) == "." || substr($valorTmp, $i, 1) == ",")
                {
                    $hasSepDecimal = True;
                    $valorTmp = trim(substr($valorTmp, 0, $i)) . "@" . trim(substr($valorTmp, 1 + $i));
                    break;
                }
            }
        }

        for($i = 1; $i != (strlen($valorTmp) - 1); $i++)
        {
            if(substr($valorTmp, $i, 1) == "." || substr($valorTmp, $i, 1) == "," || substr($valorTmp, $i, 1) == " "){
                $valorTmp = trim(substr($valorTmp, 0, $i)).trim(substr($valorTmp, 1 + $i));
                break;
            }
        }

        if (strlen(strstr($valorTmp, '@')) > 0)
        {
            $valorTmp = trim(substr($valorTmp, 0, strpos($valorTmp, '@'))) . trim($sepDecimal) . trim(substr($valorTmp, strpos($valorTmp, '@') + 1));
        }

        return $valorTmp;
    }

    /**
     * @param $order_id
     * @param $order_value
     * @return $entity
     */
    public function GenerateMbRef($order_id, $order_value)
    {
        $chk_val = 0;
        $order_id = "0000" . $order_id;
        $order_value =  $this->format_number($order_value);

        //Apenas sao considerados os 4 caracteres mais a direita do order_id
        $order_id = substr($order_id, (strlen($order_id) - 4), strlen($order_id));


        if ($order_value < 1)
        {
            die("Lamentamos mas é impossível gerar uma referência MB para valores inferiores a 1 Euro");
            return;
        }
        else if ($order_value >= 1000000)
        {
            die("<b>AVISO:</b> Pagamento fraccionado por exceder o valor limite para pagamentos no sistema Multibanco<br>");
            return;
        }


        //cálculo dos check digits
        $chk_str = sprintf('%05u%03u%04u%08u', $this->entity_id, $this->sub_entity_id, $order_id, round($order_value * 100));
        $chk_array = array(3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53, 45, 62, 38, 89, 17, 73, 51);

        for ($i = 0; $i < 20; $i++)
        {
            $chk_int = substr($chk_str, 19 - $i, 1);
            $chk_val += ($chk_int % 10) * $chk_array[$i];
        }

        $chk_val %= 97;
        $chk_digits = sprintf('%02u', 98 - $chk_val);

        $entity = $this->sub_entity_id . " " . substr($chk_str, 8, 3) . " " . substr($chk_str, 11, 1) . $chk_digits;

        return $entity;
    }
}
