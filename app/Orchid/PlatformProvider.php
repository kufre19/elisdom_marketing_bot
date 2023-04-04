<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;
use Chatify\Facades\ChatifyMessenger as Chatify;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
             Menu::make('Campaign')
                ->icon('feed')
                ->route('platform.main'),

            Menu::make('Chat App')
                ->icon('bubble')
                ->url('chatify')
                ->permission('platform.systems.users'),
                
                Menu::make('Chat Request')
                ->icon('earphones-alt')
                ->route('platform.chat-request')
                ->badge(function () {
                    $count = session()->get("pending_request_count");
                    return $count;
                }),
            
            // Menu::make('Basic Elements')
            //     ->title('Form controls')
            //     ->icon('note')
            //     ->route('platform.example.fields'),

            // Menu::make('Advanced Elements')
            //     ->icon('briefcase')
            //     ->route('platform.example.advanced'),

            // Menu::make('Text Editors')
            //     ->icon('list')
            //     ->route('platform.exacommentmple.editors'),

           
            // Menu::make('Chart tools')
            //     ->icon('bar-chart')
            //     ->route('platform.example.charts'),

            Menu::make('Add Contacts')
                ->icon('user')
                ->url('wa-user')
                ->divider(),




            Menu::make(__('Employees'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

           
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
