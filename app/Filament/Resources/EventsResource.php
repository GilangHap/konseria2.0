<?php
namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EventsResource\Pages;

class EventsResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Events Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->helperText('Landscape ratio 644 x 259')
                    ->image()
                    ->required(),
                DatePicker::make('date')
                    ->label('Date')
                    ->minDate(now())
                    ->required(),
                TimePicker::make('time')
                    ->label('Time')
                    ->required(),
                TextInput::make('location')
                    ->label('Location')
                    ->required(),
                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required(),
                TextInput::make('ticket_quota')
                    ->label('Capacity')
                    ->numeric()
                    ->required(),
                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->required()
                    ->options(function () {
                        return Auth::user()->role === 'admin'
                            ? \App\Models\User::pluck('name', 'id')
                            : [Auth::id() => Auth::user()->name];
                    })
                    ->hidden(fn () => Auth::user()->role !== 'admin')
                    ->default(Auth::id()),
                Hidden::make('author_id')
                    ->default(Auth::id())
                    ->visible(fn () => Auth::user()->role !== 'admin'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('title')->label('Title')->sortable()->searchable(),
                TextColumn::make('description')->label('Description')->limit(50)->sortable(),
                TextColumn::make('date')->label('Date')->date()->sortable(),
                TextColumn::make('time')->label('Time')->time()->sortable(),
                TextColumn::make('location')->label('Location')->sortable(),
                TextColumn::make('price')->label('Price')->prefix('Rp')->sortable(),
                TextColumn::make('ticket_quota')->label('Capacity')->sortable(),
                TextColumn::make('author.name')->label('Author')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                ->colors([
                        'success' => 'active', 'danger' =>  'completed',])->sortable(),
            ])
            ->filters([
                Filter::make('active')
                    ->label('Active Events')
                    ->query(fn (Builder $query) => $query->where('status', 'active')),
                Filter::make('completed')
                    ->label('Completed Events')
                    ->query(fn (Builder $query) => $query->where('status', 'completed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Auth::user()->role === 'admin'
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->where('author_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvents::route('/create'),
            'edit' => Pages\EditEvents::route('/{record}/edit'),
        ];
    }
}
