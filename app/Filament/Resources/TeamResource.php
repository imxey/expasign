<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Kalau kamu mau tombol "Create" muncul, hapus atau comment aja fungsi di bawah ini
    public static function canCreate(): bool
    {
        return false;
    }

    // INI YANG KAMU LUPA, SAYANG! Form untuk Create/Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('team_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nominal')
                    ->numeric(),
                Forms\Components\Toggle::make('isExpa')
                    ->required(),
                Forms\Components\Toggle::make('isEdu')
                    ->required(),
                Forms\Components\Toggle::make('isSubmit')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\TextInput::make('code')
                    ->maxLength(255),
            ]);
    }

    // Taruh ini di file TeamResource.php kamu

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('team_name')
                ->searchable()
                ->label('Team Name')
                ->sortable(),
            Tables\Columns\TextColumn::make('category')
                ->searchable()
                ->label('Category')
                ->sortable(),
            Tables\Columns\TextColumn::make('nominal')
                ->searchable()
                ->label('Nominal')
                ->sortable(),
            Tables\Columns\ImageColumn::make('receipt_path')
                ->label('Receipt')
                ->openUrlInNewTab(),
            
            // INI DIA BINTANGNYA! ✨ Langsung ganti di sini
            Tables\Columns\SelectColumn::make('status')
                ->options([
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                ])
                ->searchable()
                ->sortable(),

            Tables\Columns\IconColumn::make('isExpa')
                ->label('Expa')
                ->boolean(),
            Tables\Columns\IconColumn::make('isEdu')
                ->label('Edu')
                ->boolean(),
            Tables\Columns\IconColumn::make('isSubmit')
                ->label('Submit')
                ->boolean(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Created At')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
            // Karena udah bisa edit status langsung, tombol editnya bisa dihapus
            // Tapi kalo mau disimpen juga boleh, buat jaga-jaga
            Tables\Actions\ViewAction::make(),
            // Tables\Actions\EditAction::make(), // ini bisa di-comment atau dihapus
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}