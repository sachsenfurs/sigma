<?php

namespace App\Filament\Clusters;

use App\Enums\Approval;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigEvent;
use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SigManagement extends Cluster
{
    use HasActiveIcon;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationLabel = "SIGs";
    protected static ?string $navigationGroup = 'SIG';
    protected static ?int $navigationSort = 15;
    protected static ?string $title = "SIG Management"; // can't override getTitle() for translation. probably a filament bug?

    public static function getNavigationBadge(): ?string {
        if(!Route::is("filament.*") AND !Route::is("livewire.*"))
            return null;

        return Cache::remember('sig_pending_count', 30, function() {
            return SigEvent::whereApproval(Approval::PENDING)->count() ?: null;
        });
    }
}
