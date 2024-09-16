<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use App\Livewire\CustomProfileComponent;
use Filament\Http\Middleware\Authenticate;
use Filament\Support\Facades\FilamentView;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel{
        return $panel
        ->spa()      
            ->default()
            ->id('admin')
            ->path('/')
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::FitContent)
            ->font('Josefin Sans')
            ->brandLogo(fn () => view('filament.component.brand'))
            ->topNavigation()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Yellow,
                'violet' => Color::Violet,            
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                
                   ->slug('my-profile')
                   ->setTitle('Profile')
                   ->setNavigationLabel('Profile')
                   ->setNavigationGroup('Edit Profile')
                   ->setIcon('heroicon-o-user')
                   ->setSort(10)
                  
                   ->shouldRegisterNavigation(true)
                   ->shouldShowDeleteAccountForm(false)
                   
                   ->shouldShowBrowserSessionsForm()
                   ->shouldShowAvatarForm()
                   
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}
