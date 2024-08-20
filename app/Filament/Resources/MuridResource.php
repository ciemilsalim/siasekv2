<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Murid;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MuridResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MuridResource\RelationManagers;
use App\Models\Periode;

class MuridResource extends Resource
{
    protected static ?string $model = Murid::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Data Pokok';
    
    protected static ?string $navigationLabel = 'Data Murid';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nis')
                    ->required(),
                TextInput::make('nama_murid')
                    ->required(),
                Select::make('kelas_id')
                    ->relationship(name:'kelas', titleAttribute:'kelas')
                        ->createOptionForm([
                            TextInput::make('nama_kelas')
                            ->required(),
                            Radio::make('tingkat')
                                ->options([
                                    '7' => '7',
                                    '8' => '8',
                                    '9' => '9'
                                ])
                                ->required(),
                            Select::make('periode_id')
                                ->relationship(name:'periode', titleAttribute:'periode')
                                ->createOptionForm([
                                    TextInput::make('periode')
                                    ->required()
                                ])
                                ->label('Periode')
                                ->options(Periode::all()->pluck('periode', 'id'))
                                ->required()
                    ])
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('nama_kelas', 'id'))
                    ->required(),
                TextInput::make('no_hp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis'),
                TextColumn::make('nama_murid'),
                TextColumn::make('kelas.nama_kelas'),
                TextColumn::make('no_hp'),
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
            'index' => Pages\ManageMurids::route('/'),
        ];
    }
}
