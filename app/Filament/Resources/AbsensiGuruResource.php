<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiGuruResource\Pages;
use App\Filament\Resources\AbsensiGuruResource\RelationManagers;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

use function Laravel\Prompts\select;

class AbsensiGuruResource extends Resource
{
    protected static ?string $model = AbsensiGuru::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationGroup = 'Absensi';
    
    protected static ?string $navigationLabel = 'Absen Guru';
    // protected static ?int $navigationsort = 'Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('guru_id')
                    ->label('Nama Guru')
                    ->options(Guru::all()->pluck('nama_guru', 'id'))
                    ->searchable(),
                DatePicker::make('tanggal_absen_guru'),
                TimePicker::make('jam_absen_datang_guru'),
                TimePicker::make('jam_absen_pulang_guru'),
                Select::make('status_kehadiran_guru')
                    ->options([
                        'terlambat' => 'terlambat',
                        'ontime' => 'ontime'
                    ]),
                Select::make('keterangan_kehadiran_guru')
                    ->options([
                        'hadir' => 'hadir',
                        'sakit' => 'sakit',
                        'izin' => 'izin',
                        'dinasluar' => 'dinas luar',
                        'alpa' => 'alpa'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('guru.nama_guru')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('tanggal_absen_guru')
                    ->label('Tanggal Absen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jam_absen_datang_guru')
                    ->label('Jam Datang')
                    ->sortable(),
                TextColumn::make('jam_absen_pulang_guru')
                    ->label('Jam Pulang'),
                TextColumn::make('status_kehadiran_guru')
                    ->label('Status'),
                TextColumn::make('keterangan_kehadiran_guru')
                    ->label('Keterangan'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensiGurus::route('/'),
            'create' => Pages\CreateAbsensiGuru::route('/create'),
            'edit' => Pages\EditAbsensiGuru::route('/{record}/edit'),
        ];
    }
}
