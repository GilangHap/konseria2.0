<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transaction Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
            ->label('Event')
            ->options(function () {
                if (Auth::user()->role === 'admin') {
                    return Event::pluck('title', 'id');
                }

                return Event::where('author_id', Auth::id())->pluck('title', 'id');
                })
                ->required(),

                TextInput::make('name')->label('Name')->required(),
                TextInput::make('email')->label('Email')->required()->email(),
                TextInput::make('nik')->label('NIK')->numeric()->required(),
                TextInput::make('quantity')->label('Quantity')->numeric()->minValue(1)->required(),
                TextInput::make('total_price')->label('Total Price')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('event.title')->label('Event')->sortable()->searchable(),
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
                TextColumn::make('nik')->label('NIK')->sortable()->searchable(),
                TextColumn::make('quantity')->label('Quantity')->sortable()->searchable(),
                TextColumn::make('total_price')->label('Total Price')->prefix('Rp ')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Created At')->sortable()->dateTime('F j, Y, g:i a')->searchable(),
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
{
    if (Auth::user()->role === 'admin') {
        return parent::getEloquentQuery();
    }

    return parent::getEloquentQuery()->whereHas('event', function ($query) {
        $query->where('author_id', Auth::id());
    });
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
