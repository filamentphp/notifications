---
title: Sending notifications
---
import AutoScreenshot from "@components/AutoScreenshot.astro"

## Overview

> To start, make sure the package is [installed](installation) - `@livewire('notifications')` should be in your Blade layout somewhere.

Notifications are sent using a `Notification` object that's constructed through a fluent API. Calling the `send()` method on the `Notification` object will dispatch the notification and display it in your application. As the session is used to flash notifications, they can be sent from anywhere in your code, including JavaScript, not just Livewire components.

```php
<?php

namespace App\Http\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class EditPost extends Component
{
    public function save(): void
    {
        // ...

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
```

<AutoScreenshot name="notifications/success" alt="Success notification" version="3.x" />

## Setting a title

The main message of the notification is shown in the title. You can set the title as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .send()
```

Markdown text will automatically be rendered if passed to the title.

## Setting an icon

Optionally, a notification can have an [icon](https://blade-ui-kit.com/blade-icons?set=1#search) that's displayed in front of its content. You may also set a color for the icon, which is gray by default:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->icon('heroicon-o-document-text')
    ->iconColor('success')
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .icon('heroicon-o-document-text')
    .iconColor('success')
    .send()
```

<AutoScreenshot name="notifications/icon" alt="Notification with icon" version="3.x" />

Notifications often have a status like `success`, `warning`, `danger` or `info`. Instead of manually setting the corresponding icons and colors, there's a `status()` method which you can pass the status. You may also use the dedicated `success()`, `warning()`, `danger()` and `info()` methods instead. So, cleaning up the above example would look like this:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .send()
```

<AutoScreenshot name="notifications/statuses" alt="Notifications with various statuses" version="3.x" />

## Setting a background color

Notifications have no background color by default. You may want to provide additional context to your notification by setting a color as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->color('success') // [tl! focus]
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .color('success') // [tl! focus]
    .send()
```

<AutoScreenshot name="notifications/color" alt="Notification with background color" version="3.x" />

## Setting a duration

By default, notifications are shown for 6 seconds before they're automatically closed. You may specify a custom duration value in milliseconds as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->duration(5000)
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .duration(5000)
    .send()
```

If you prefer setting a duration in seconds instead of milliseconds, you can do so:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->seconds(5)
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .seconds(5)
    .send()
```

You might want some notifications to not automatically close and require the user to close them manually. This can be achieved by making the notification persistent:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->persistent()
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .persistent()
    .send()
```

## Setting body text

Additional notification text can be shown in the body. Similar to the title, it supports Markdown:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the **post** have been saved.')
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .body('Changes to the **post** have been saved.')
    .send()
```

<AutoScreenshot name="notifications/body" alt="Notification with body text" version="3.x" />

## Adding actions to notifications

Notifications support [actions](../actions/trigger-button), which are buttons that render below the content of the notification. They can open a URL or emit a Livewire event. Actions can be defined as follows:

```php
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the **post** have been saved.')
    ->actions([
        Action::make('view')
            ->button(),
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .body('Changes to the **post** have been saved.')
    .actions([
        new NotificationAction('view')
            .button(),
        new NotificationAction('undo')
            .color('gray'),
    ])
    .send()
```

<AutoScreenshot name="notifications/actions" alt="Notification with actions" version="3.x" />

You can learn more about how to style action buttons [here](../actions/trigger-button).

### Opening URLs from notification actions

You can open a URL, optionally in a new tab, when clicking on an action:

```php
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the **post** have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->url(route('posts.show', $post), shouldOpenInNewTab: true)
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .body('Changes to the **post** have been saved.')
    .actions([
        new NotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new NotificationAction('undo')
            .color('gray'),
    ])
    .send()
```

### Emitting Livewire events from notification actions

Sometimes you want to execute additional code when a notification action is clicked. This can be achieved by setting a Livewire event which should be emitted on clicking the action. You may optionally pass an array of data, which will be available as parameters in the event listener on your Livewire component:

```php
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the **post** have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray')
            ->emit('undoEditingPost', [$post->id]),
    ])
    ->send();
```

You can also `emitSelf`, `emitUp` and `emitTo`:

```php
Action::make('undo')
    ->color('gray')
    ->emitSelf('undoEditingPost', [$post->id])

Action::make('undo')
    ->color('gray')
    ->emitUp('undoEditingPost', [$post->id])

Action::make('undo')
    ->color('gray')
    ->emitTo('another_component', 'undoEditingPost', [$post->id])
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .body('Changes to the **post** have been saved.')
    .actions([
        new NotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new NotificationAction('undo')
            .color('gray')
            .emit('undoEditingPost'),
    ])
    .send()
```

Similarly, `emitSelf`, `emitUp` and `emitTo` are also available:

```js
new NotificationAction('undo')
    .color('gray')
    .emitSelf('undoEditingPost')

new NotificationAction('undo')
    .color('gray')
    .emitUp('undoEditingPost')

new NotificationAction('undo')
    .color('gray')
    .emitTo('another_component', 'undoEditingPost')
```

### Closing notifications from actions

After opening a URL or emitting an event from your action, you may want to close the notification right away:

```php
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the **post** have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray')
            ->emit('undoEditingPost', [$post->id])
            ->close(),
    ])
    ->send();
```

Or with JavaScript:

```js
new Notification()
    .title('Saved successfully')
    .success()
    .body('Changes to the **post** have been saved.')
    .actions([
        new NotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new NotificationAction('undo')
            .color('gray')
            .emit('undoEditingPost')
            .close(),
    ])
    .send()
```

## Using the JavaScript objects

The JavaScript objects (`Notification` and `NotificationAction`) are assigned to `window.Notification` and `window.NotificationAction`, so they are available in on-page scripts.

You may also import them in a bundled JavaScript file:

```js
import { Notification, NotificationAction } from '../../vendor/filament/notifications/dist/index.js'

// ...
```
