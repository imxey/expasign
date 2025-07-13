<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
{
    return false;
}
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                Tables\Columns\TextColumn::make('registrant.name')
                    ->searchable()
                    ->label('Registrant Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrant.email')
                    ->searchable()
                    ->label('Registrant Email')
                    ->sortable(),

                Tables\Columns\TextColumn::make('registrant.phone')
                    ->searchable()
                    ->label('Registrant Phone')
                    ->sortable(),

                Tables\Columns\TextColumn::make('registrant.school')
                    ->searchable()
                    ->label('Registrant School')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrant.category')
                    ->searchable()
                    ->label('Registrant Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('file')
                    ->searchable()
                    ->url(fn ($record) => $record->file)
                    ->openUrlInNewTab()
                    ->label('Registrant File')
                    ->sortable(),
                ]
            )
            ->filters(
                [
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
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}
