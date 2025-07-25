<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case TRANSFER = 'transfer';
    case PAYMENT = 'payment';
    case RECEIPT = 'receipt';
    case REFUND = 'refund';
    case CHARGE = 'charge';
    case CREDIT = 'credit';
    case DEBIT = 'debit';
    case OTHER = 'other';

}
