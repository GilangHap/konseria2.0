<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transaction Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigatio = '';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->numeric(),
                Select::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->options(function () {
                        if (Auth::user()->role === 'admin') {
                            return Event::pluck('title', 'id');
                        }
                        return Event::where('author_id', Auth::id())->pluck('title', 'id');
                    })
                    ->required(),
                TextInput::make('ticket_code')
                    ->label('Ticket Code')
                    ->readOnly()
                    ->default('TICKET-' . Transaction::latest()->first()->event_id . '-' . strtoupper(uniqid())),
                Checkbox::make('is_redeemed')
                    ->label('Is Redeemed')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('transaction_id')->label('Transaction ID')->sortable()->searchable(),
                TextColumn::make('event.title')->label('Event')->sortable()->searchable(),
                TextColumn::make('ticket_code')->label('Ticket Code')->sortable()->searchable(),
                TextColumn::make('is_redeemed')
                ->label('Is Redeemed')
                ->formatStateUsing(fn ($state) => $state ? 'Redeemed' : 'Not Redeemed')
                ->badge()
                ->colors([
                        'success' => 1,
                        'gray' =>  0,
                ])
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
