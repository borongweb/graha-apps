<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Consumer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\ConsumerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ConsumerResource\RelationManagers;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ConsumerResource extends Resource
{
    protected static ?string $model = Consumer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Marketing';

    public static function getPluralLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Daftar Konsumen";
        } else
            return "Daftar Konsumen";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Konsumen')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. HP/WA')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_id')
                    ->required()
                    ->image()
                    ->maxSize(500)
                    ->imageEditor()
                    ->directory('ktp')
                    ->label('Unggah KTP/KK/NPWP (maksimal 500kb)'),
                Forms\Components\FileUpload::make('file_payment')
                    ->image()
                    ->maxSize(500)
                    ->imageEditor()
                    ->directory('payment')
                    ->label('Unggah Bukti Pembayaran (maksimal 500kb)'),
                Forms\Components\Textarea::make('information')
                    ->label('Keterangan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultGroup('status')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Konsumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Marketing')
                    ->searchable(),
                Tables\Columns\TextColumn::make('information')
                    ->label('Keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar Pada')
                    ->date()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make('view')
                    ->modalHeading(fn(Consumer $record) => $record->name),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn(Consumer $record) => $record->delete())
                    ->action(function (Consumer $record) {
                        if ($record->file_id != null && Storage::disk('public')->exists($record->file_id)) {
                            Storage::disk('public')->delete($record->file_id);
                        }
                        if ($record->file_payment != null && Storage::disk('public')->exists($record->file_payment)) {
                            Storage::disk('public')->delete($record->file_payment);
                        }
                        $record->delete();
                    })
                    ->requiresConfirmation()
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
            'index' => Pages\ListConsumers::route('/'),
            'create' => Pages\CreateConsumer::route('/create'),
            'edit' => Pages\EditConsumer::route('/{record}/edit'),
        ];
    }
}