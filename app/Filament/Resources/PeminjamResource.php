<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use App\Models\Peminjam;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PeminjamResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PeminjamResource\RelationManagers;
use App\Models\Guru;

class PeminjamResource extends Resource
{
    protected static ?string $model = Peminjam::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    
    protected static ?string $navigationGroup = 'Sarana Prasarana';
    
    protected static ?string $navigationLabel = 'Peminjam';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('nama_peminjam'),
                Select::make('guru_id')
                    ->label('Nama Peminjam')
                    ->options(Guru::all()->pluck('nama_guru', 'id'))
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Guru::where('nik', 'like', "%{$search}%")->limit(50)->pluck('nama_guru', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Guru::find($value)?->id),
                Select::make('barang_id')
                    ->relationship(name:'barang', titleAttribute:'nama_barang')
                    ->createOptionForm([
                        TextInput::make('kode_barang')
                            ->default(Barang::getNextKodeBarang())
                            ->readOnly(),
                        TextInput::make('nama_barang'),
                        Select::make('jenis_barang')
                            ->options([
                                'Komputer' => 'Komputer',
                                'Laptop' => 'Laptop',
                                'Chromebook' => 'Chromebook',
                                'Konektor' => 'Konektor',
                                'Router' => 'Router',
                                'Wireless Router' => 'Wireless Router',
                                'Switch Hub' => 'Switch Hub',
                                'Printer' => 'Printer',
                                'Scanner' => 'Scanner',
                                'LCD Projektor' => 'LCD Projektor'
                            ])
                            ->searchable(),
                        TextInput::make('merk_barang'),
                        Select::make('kondisi_barang')
                                ->options([
                                    'baik' => 'Baik',
                                    'rusak ringan' => 'Rusak Ringan',
                                    'rusak berat' => 'Rusak Berat'
                                ]),
                        TextInput::make('tahun_pembelian'),
                        Select::make('lokasi_penyimpanan')
                                ->options([
                                    'IT Center' => 'ICT Center',
                                    'Laboratorium Komputer' => 'Laboratorium Komputer',
                                    'Laboratorium IPA' => 'Laboratorium IPA',
                                    'Ruang Guru' => 'Ruang Guru',
                                    'Ruang Kantor' => 'Ruang kantor',
                                    'Ruang Perpustakaan' => 'Ruang Perpustakaan',
                                    'Ruang Seni' => 'Ruang Seni',
                                    'Ruang UKS' => 'Ruang UKS',
                                    'Ruang Kelas' => 'Ruang Kelas',
                                    'Gudang' => 'Gudang'
                                ])
                                ->searchable(),
                        Radio::make('status_barang')
                                ->options([
                                    'aktif' => 'aktif',
                                    'tidak aktif' => 'tidak aktif',
                                ]),
                        Radio::make('status_kepemilikan')
                                ->options([
                                    'milik' => 'Milik',
                                    'bukan milik' => 'Bukan Milik',
                                ]),
                        Textarea::make('keterangan')
                    ])
                    ->label('Nama barang')
                    ->options(Barang::all()->pluck('nama_barang', 'id'))
                    ->searchable(),
                DatePicker::make('tanggal_peminjaman')
                    ->default(now()),
                DatePicker::make('tanggal_pengembalian')
                    ->nullable(),
                Select::make('kondisi_barang_kembali')
                    ->options([
                        'baik' => 'baik',
                        'rusak ringan' => 'rusak ringan',
                        'rusak berat' => 'rusak berat'
                    ])
                    ->nullable(),
                Textarea::make('keterangan_peminjaman')
                    ->required()                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang'),
                TextColumn::make('guru.nama_guru')
                    ->label('Nama Peminjam'),
                TextColumn::make('tanggal_peminjaman')
                    ->label('Tanggal Peminjaman'),
                TextColumn::make('tanggal_pengembalian')
                    ->label('Tanggal Pengembalian')
                    ->getStateUsing(fn ($record) => $record->tanggal_pengembalian ? $record->tanggal_pengembalian : 'Belum Kembali')
                    // ->extraAttributes([
                    //     'class' => fn ($record) => now()->diffInDays($record->tanggal_pengembalian) > 7 ? 'text-gray-500' : '', // Mengembalikan string
                    // ]),
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
            'index' => Pages\ListPeminjams::route('/'),
            'create' => Pages\CreatePeminjam::route('/create'),
            'edit' => Pages\EditPeminjam::route('/{record}/edit'),
        ];
    }
}
