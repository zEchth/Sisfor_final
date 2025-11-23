<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ->default()
            ->id('dashboard')
            ->path('dashboard')

            // ->brandLogo('favicon.png')
            ->brandName('Kelola Uang')
            // ->brandLogoHeight('2.5rem')
            ->globalSearch(false)

            ->favicon('favicon.png')

            ->authGuard('web')

            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')

            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\Laporan::class,
            ])

            ->userMenuItems([
                'logout' => fn (Action $action) => $action->label('Keluar'),
            ])
            ->sidebarWidth('16rem') // default 20rem

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\LaporanFilterWidget::class,
                \App\Filament\Widgets\LaporanStatsWidget::class,
                \App\Filament\Widgets\LaporanTableWidget::class,
                // \App\Filament\Widgets\PeriodeFilterWidget::class,
                // \App\Filament\Widgets\KategoriFilterWidget::class,
                // \App\Filament\Widgets\TipeFilterWidget::class,
                // \App\Filament\Widgets\DateRangeFilterWidget::class,
            ])
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa();
    }
}
