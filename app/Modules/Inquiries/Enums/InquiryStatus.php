<?php

namespace App\Modules\Inquiries\Enums;

enum InquiryStatus: string
{
    case New = 'new';
    case ToHandle = 'to_handle';
    case WaitingCustomer = 'waiting_customer';
    case Handled = 'handled';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nouveau',
            self::ToHandle => 'À traiter',
            self::WaitingCustomer => 'En attente client',
            self::Handled => 'Traité',
            self::Archived => 'Archivé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'danger',
            self::ToHandle => 'warning',
            self::WaitingCustomer => 'info',
            self::Handled => 'success',
            self::Archived => 'gray',
        };
    }
}
