<?php
namespace App\Enums;
enum OrderPaymentTypeEnum: string
{
    case COD = "cod";
    case VISA = "visa";
    case WALLET = "wallet";
}
