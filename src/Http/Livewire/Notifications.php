<?php

namespace Filament\Notifications\Http\Livewire;

use Filament\Notifications\Collection;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Notifications extends Component
{
    // Used to check if Livewire messages should trigger notification animations.
    public bool $isFilamentNotificationsComponent = true;

    public Collection $notifications;

    /**
     * @var array<string, string>
     */
    protected $listeners = [
        'notificationSent' => 'pushNotificationFromEvent',
        'notificationsSent' => 'pullNotificationsFromSession',
        'notificationClosed' => 'removeNotification',
    ];

    public static string $horizontalAlignment = 'right';

    public static string $verticalAlignment = 'top';

    public function mount(): void
    {
        $this->notifications = new Collection();
        $this->pullNotificationsFromSession();
    }

    public function pullNotificationsFromSession(): void
    {
        foreach (session()->pull('filament.notifications') ?? [] as $notification) {
            $notification = Notification::fromArray($notification);

            $this->pushNotification($notification);
        }
    }

    /**
     * @param  array<string, mixed>  $notification
     */
    public function pushNotificationFromEvent(array $notification): void
    {
        $notification = Notification::fromArray($notification);

        $this->pushNotification($notification);
    }

    public function removeNotification(string $id): void
    {
        if (! $this->notifications->has($id)) {
            return;
        }

        $this->notifications->forget($id);
    }

    /**
     * @param  array<string, mixed>  $notification
     */
    public function handleBroadcastNotification(array $notification): void
    {
        if (($notification['format'] ?? null) !== 'filament') {
            return;
        }

        $this->pushNotification(Notification::fromArray($notification));
    }

    protected function pushNotification(Notification $notification): void
    {
        $this->notifications->put(
            $notification->getId(),
            $notification,
        );
    }

    public function getUser(): Model | Authenticatable | null
    {
        return auth()->user();
    }

    public function getBroadcastChannel(): ?string
    {
        $user = $this->getUser();

        if (! $user) {
            return null;
        }

        if (method_exists($user, 'receivesBroadcastNotificationsOn')) {
            return $user->receivesBroadcastNotificationsOn();
        }

        $userClass = str_replace('\\', '.', $user::class);

        return "{$userClass}.{$user->getKey()}";
    }

    public static function horizontalAlignment(string $alignment): void
    {
        static::$horizontalAlignment = $alignment;
    }

    public static function verticalAlignment(string $alignment): void
    {
        static::$verticalAlignment = $alignment;
    }

    public function render(): View
    {
        return view('filament-notifications::notifications');
    }
}
