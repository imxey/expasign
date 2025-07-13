<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrantResource\Pages;
use App\Filament\Resources\RegistrantResource\RelationManagers;
use App\Models\Registrant;
use Filament\Forms;
use Closure;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrantResource extends Resource
{
    protected static ?string $model = Registrant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                //
                Forms\Components\TextInput::make('name')

                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()

                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')

                    ->maxLength(20),
                Forms\Components\TextInput::make('school')

                    ->maxLength(255),
                Forms\Components\TextInput::make('nim')
                    ->numeric()
                    ->maxLength(20),
                Forms\Components\Select::make('category')
                    ->options(
                        [
                        'category1' => 'Category 1',
                        'category2' => 'Category 2',
                        'category3' => 'Category 3',
                        ]
                    )
                    ,
                Forms\Components\TextInput::make('nominal')
                    ->numeric()

                    ->maxLength(10),
                Forms\Components\Toggle::make('isEdu')
                    ->label('Is Edutime')
                    ->default(false),
                Forms\Components\Select::make('status')
                    ->options(
                        [
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                        ]
                    )
                    ->default('pending')
                    ,
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                //
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nim')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('school')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('receipt')
                    ->sortable()
                    ->label('Receipt')
                    ->height(250)
                    ->width(250),
                Tables\Columns\BooleanColumn::make('isEdu')
                    ->label('Edutime')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->label('Status')
                    ->searchable()]
            )
            ->filters(
                [
                //
                ]
            )
            ->actions(
                [
                Tables\Actions\EditAction::make(),
                ]
            )
            ->bulkActions(
                [
                Tables\Actions\BulkActionGroup::make(
                    [
                    Tables\Actions\DeleteBulkAction::make(),
                    ]
                ),
                ]
            );
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
            'index' => Pages\ListRegistrants::route('/'),
            'create' => Pages\CreateRegistrant::route('/create'),
            'edit' => Pages\EditRegistrant::route('/{record}/edit'),
        ];
    }
}
