# Flyingfoxx / CommandCenter

This package provides a framework agnostic architecture for utilizing commands and domain events in your applications. Any of the components can be easily extended for your specific use.

**Laravel implementation is included.**

*inspired and expanded on [Jeffrey Way](https://github.com/JeffreyWay) at [Laracasts](https://github.com/Laracasts)*

## Installation

Install CommandCenter through Composer.

```js
"require": {
    "flyingfoxx/commandcenter": "~1.0"
}
```

### Laravel

If using Laravel, update `app/config/app.php` to include the package's service provider.

```php
'Flyingfoxx\CommandCenter\Laravel\CommandCenterServiceProvider'
```

**Check out the Laravel section at the end of this document for additional features!**

## Usage

Before getting started, this approach is not recommended for smaller projects where architecture isn't as important. This package helps to structure your business logic, stick to the single responsibility principle, and keep your controllers skinny.

### CommandApplication

In order to get started, you must register your specific application with CommandCenter by implementing the package's `CommandApplication` interface and registering any bindings. Please use the included Laravel implementation and service provider for reference in other frameworks. The reference classes are below:

- Flyingfoxx\CommandCenter\Laravel\Application
- Flyingfoxx\CommandCenter\Laravel\CommandCenterServiceProvider

### Command
It all starts with a command. A command is an "instruction" you give to your domain to carry out specific actions. It is represented as a simple DTO (data transfer object) that carries the data needed to perform that specific command.

For example, say you need to register a new user. You would then create a `RegisterUserCommand` that would look like this:

```php
<?php namespace Foxx\Users;

class RegisterUserCommand
{
    public $username;

    public $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
```

So instead of putting all the logic in the controller, you can now create a command passing data to a handler where the logic will reside. But now you need a transportation method to pass the command to its respective handler. How about a bus?

### Command Bus

First, you need to inject the package's `CommandBus` into your controller. This will be how you transport commands to their respective handlers.

```php
<?php

use Flyingfoxx\CommandCenter\CommandBus;

class RegistrationController
{
    protected $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}
```

Next, you create and pass the command to the command bus.

```php
<?php

use Flyingfoxx\CommandCenter\CommandBus;
use Foxx\Users\RegisterUserCommand;

class RegistrationController
{
    protected $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function store()
    {
        // Grab the input (using Laravel in this example)
        $input = Input::only('username', 'password');

        // Create command
        $command = new RegisterUserCommand($input['username'], $input['password']);

        // Pass command to command bus
        $this->commandBus->execute($command);
    }
}
```

By doing this, the command bus will pass the command to its respective handler, where the logic for the command will be carried out.

It does this by mapping a command class to its respective handler class as follows:

- RegisterUserCommand => RegisterUserCommandHandler
- PostBlogEntryCommand => PostBlogEntryCommandHandler

> Keep in mind you can easily change this by implementing the package's `CommandTranslator` class. Don't forget to update any application bindings.

### Command Handler

Now you need a handler class that will handle the command. This will be where the command bus delivers the command. If the command class was `RegisterUserCommand`, then the handler class must be `RegisterUserCommandHandler`.

The handler class must implement the package's `CommandHandler` interface, requiring the `handle()` method.

```php
<?php namespace Foxx\Users;

use Flyingfoxx\CommandCenter\CommandHandler;

class RegisterUserCommandHandler implements CommandHandler
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle($command)
    {
        $user = $this->user->register($command->username, $command->password);

        return $user;
    }
}
```

And it should work. You can now leverage commands within your applications. But now you need a way to hook into those commands to perform other tasks. You can use domain events and listeners that will only perform those tasks when an event has occurred.


## Events

A domain event is when something significant has occurred in your domain. Continuing from the previous example, once the `RegisterUserCommand` has been executed, an event has occurred, a user was registered.

So you can call the event, `UserWasRegistered` and it will be represented as a simple DTO (data transfer object) that carry data needed by the event listeners.

```php
<?php namespace Foxx\Events;

use Foxx\Users\User;

class UserWasRegistered
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
```

Now that you have an event, you must raise the event (creating an instance of the event within the application) in your model class. To do this, you can use the package's `EventGenerator` trait as follows:

```php
<?php namespace Foxx\Users\User;

use Flyingfoxx\CommandCenter\Eventing\EventGenerator;
use Foxx\Events\UserWasRegistered;

class User
{
    use EventGenerator;

    protected $username;
    protected $password;

    public function register($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->raise(new UserWasRegistered($this));

        return $this;
    }
}
```

Okay, the `UserWasRegistered` event has now been raised and is now ready to be dispatched (making your application aware of its occurrence). You can do this in your command handler class by injecting the package's `EventDispatcher` class and calling the `dispatch($events)` method.

```php
<?php namespace Foxx\Users;

use Flyingfoxx\CommandCenter\CommandHandler;
use Flyingfoxx\CommandCenter\Eventing\EventDispatcher;

class RegisterUserCommandHandler implements CommandHandler
{
    protected $user;

    protected $dispatcher;

    public function __construct(User $user, EventDispatcher $dispatcher)
    {
        $this->user = $user;
        $this->dispatcher = $dispatcher;
    }

    public function handle($command)
    {
        $user = $this->user->register($command->username, $command->password);

        $this->dispatcher->dispatch($user->releaseEvents());

        return $user;
    }
}
```

> Don't forget to call `releaseEvents()` on the entity object (since it uses the `EventGenerator` trait). That way all dispatched events are deleted from raised events.

### Event Listeners

Now that the event is raised and dispatched, you need to register listeners for that event.

Following a convention, if we raise the event `Foxx\Events\UserWasRegistered`, then the event name to listen for will be `Foxx.Events.UserWasRegistered`.

The next step is to register an event listener class within your application. You might need to send an email to the user after they are registered.
For example, in Laravel you might do this:

```php
Event::listen('Foxx.Events.UserWasRegistered', 'Foxx\Listeners\EmailNotifier');
```

Or to register this listener with any application event, you might try this:

```php
Event::listen('Foxx.Events.*', 'Foxx\Listeners\EmailNotifier');
```

So, now, when any event is dispatched under this namespace, this listener class will fire its `handle()` method. Of course, you may only want to respond to certain events using this listener class. You can do this using the package's `EventListener` class.

By simply extending this `EventListener` class, you can create methods that follow a convention to handle each specific event. The convention is if the event dispatched is `UserWasRegistered`, then the method fired in the listener class will be `whenUserWasRegistered`. If it does not find this method, it will simply continue on.

With that, your `EmailNotifier` class might look like this:

```php
<?php namespace Foxx\Listeners;

use Flyingfoxx\CommandCenter\Eventing\EventListener;
use Flyingfoxx\Events\UserWasRegistered;

class EmailNotifier extends EventListener
{
    public function whenUserWasRegistered(UserWasRegistered $event)
    {
        // send an email to the user
    }
}
```

## Decorating the Command Bus

There may be times when you want to decorate the command bus to perform additional actions before handling the command. This package already includes a validation decorator.

### Validation

The included validation command bus can be used with the main command bus as a decorator. This must be implemented within your specific application. Once a command has been passed to the command bus, it will check for an associated validator class calling its `validate($command)` method. Otherwise, it will continue on. This way, you can perform any validation before executing the command and firing any domain events.

The convention for creating a validator class is as follows:

- RegisterUserCommand => RegisterUserValidator

Simply include a `validate($command)` method and perform your validation as you would.

```php
<?php namespace Foxx\Users;

use Flyingfoxx\CommandCenter\CommandValidator;

class RegisterUserValidator implements CommandValidator
{
    public function validate($command)
    {
        var_dump('validating register user command');
    }
}
```

> You can always create your own decorators by simply creating a class that implements the package's `CommandBus` interface and following the Decorator design pattern. Your best bet would be to copy the included `ValidationCommandBus` class and modify it according to your needs.

> Don't forget to load your new decorator properly within your specific application. And don't forget to include a new translator method for your new decorator by either extending the `MainCommandTranslator` class or creating a new class that implements the package's `CommandTranslator` class.

## Laravel

If you are a Laravel user, then provided for you in this package is a ready-made solution. It includes the Laravel implementation of the package's `CommandApplication` interface and a service provider ready to be loaded in the config. The default command bus uses the validation decorator, already set right out of the box for you. Again, if you need to provide additional decorators, follow the pattern of the validation command bus and it should work.

There are a couple of traits included in this package built for Laravel use. These traits help to clean up your classes and help the readability.

### Commander

This trait essentially wraps the command bus up and can be used in any controller. Instead of injecting the command bus, you can inject the package's `Commander` trait as follows:

```php
<?php

use Flyingfoxx\CommandCenter\Laravel\Commander;
use Foxx\Users\RegisterUserCommand;

class RegistrationController
{
    use Commander;

    public function store()
    {
        // Grab the input (using Laravel in this example)
        $input = Input::only('username', 'password');

        // Create command
        $command = new RegisterUserCommand($input['username'], $input['password']);

        // Pass command to command bus
        $this->execute($command);
    }
}
```

Now you can simply call execute on the controller itself. Another option would be put this in a base controller and write it once.

### Dispatcher

This trait essentially wraps the event dispatcher up and can be used in your handler classes. Instead of injecting the event dispatcher, you can inject the package's `Dispatcher` trait as follows:

```php
<?php namespace Foxx\Users;

use Flyingfoxx\CommandCenter\CommandHandler;
use Flyingfoxx\CommandCenter\Laravel\Dispatcher;

class RegisterUserCommandHandler implements CommandHandler
{
    use Dispatcher;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->dispatcher = $dispatcher;
    }

    public function handle($command)
    {
        $user = $this->user->register($command->username, $command->password);

        $this->dispatchEventsFor($user);

        return $user;
    }
}
```

So, instead of calling `dispatch($events)`, you would call `dispatchEventsFor($entity)` on the handler class, passing in the entity. The trait will also automatically release the events on the passed in entity.

## Conclusion

That is all. Feel free to extend as you wish, ask questions, or make comments. Also, be sure to check out Jeffrey Way's [Commands and Domain Events](https://laracasts.com/series/commands-and-domain-events) series on [Laracasts](https://laracasts.com) to learn more about this stuff.