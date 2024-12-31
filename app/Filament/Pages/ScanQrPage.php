<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ScanQrPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.pages.qr-scanner';

    protected static ?int $navigationSort = 4;
}
