<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBarangResource\Pages;
use App\Filament\Resources\JenisBarangResource\RelationManagers;
use App\Models\JenisBarang;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisBarangResource extends Resource
{
    protected static ?string $model = JenisBarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Sarana Prasarana';
    
    protected static ?string $navigationLabel = 'Jenis Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jenis_barang')
                            ->required(),
                // Repeater::make('jenis_barangs')
                //     ->simple(
                //         TextInput::make('jenis_barang')
                //             ->required()
                //     )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_barang')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJenisBarangs::route('/'),
        ];
    }
}
