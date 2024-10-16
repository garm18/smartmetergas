<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Regency;
use App\Models\Village;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\User;
use App\Models\District;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection as SupportCollection;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $modelLabel = 'Users';
    protected static ?string $slug = 'customers';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\section::make('Login')
                ->description('Fill Username and Password')
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required()
                        ->maxLength(255),
            ])->columns(2),

            Forms\Components\section::make('Profile')
            ->description('Fill the document')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('noTelp')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('province_id')
                    ->relationship(name:'province',titleAttribute:'name')
                    ->label('Province id')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('regency_id',null);
                        $set('district_id',null);
                        $set('village_id',null);
                    })
                    ->required(),

                Forms\Components\Select::make('regency_id')
                    ->options(function (Get $get): Collection {
                        return Regency::query()
                            ->where('province_id', $get('province_id'))
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('district_id',null);
                        $set('village_id',null);
                    })
                    ->required(),
                
                Forms\Components\Select::make('district_id')
                    ->options(function (Get $get): Collection {
                        return District::query()
                            ->where('regency_id', $get('regency_id'))
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('village_id',null);
                    })
                    ->required(),
                
                Forms\Components\Select::make('village_id')
                    ->options(function (Get $get): Collection {
                        return Village::query()
                            ->where('district_id', $get('district_id'))
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('noTelp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('regency.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    SelectFilter::make('province')
                        ->relationship('province', 'name')
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('regency')
                        ->relationship('regency', 'name')
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('district')
                        ->relationship('district', 'name')
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('village')
                        ->relationship('village', 'name')
                        ->searchable()
                        ->preload(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
