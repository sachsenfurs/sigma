# SIGMA - Special Interest Group Management Assistant
Conference planning software for furry conventions

> [!WARNING]
> This project is being actively developed and some features are still in progress!
> A full guide for installation and setup will follow as soon as all core features has been implemented!

> [!NOTE]
> If you experience any issues or want to give some feedback feel free to [open a new issue](https://github.com/sachsenfurs/sigma/issues/new)

This project uses Laravel, Livewire, Bootstrap, Vue, Alpine.js and filamentphp

Convention & Event Management System for the [EAST Convention](https://www.sachsenfurs.de/east) featuring:
- Manage SIGs/Panels/Events/Hosts/Locations
- Displaying timetable (Responsive UI, mobile friendly)
- API for digital signage and external services (eg. LASSIE)
- Application for SIGs, Art Show, Dealers Den
- Creating event reminders via Telegram
- Registering for specific events or even timeslots within the event (Photoshooting etc.)

# Installation

## System requirements
- Docker
- Docker Compose

## Local installation
1. Clone git repository
2. Run `make init`
3. (Optional) Test connection with `php artisan db`, when successful, exit with typing `exit`
4. Run `make up`
5. Open http://localhost:8000

## Production
- Deploy the application and install dependencies
- To quickly create the initial data run the ` php artisan db:seed ProductionSeeder`

# FAQ

## Adding a custom repository to composer.json
If you need to extend the features of, lets say, filament-fullcalendar, create a fork and add the following

```
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "saade/filament-fullcalendar",
                "version": "3.1.3",
                "source": {
                    "url": "https://github.com/URL-TO-YOUR-REPO OR local file path (absolute)",
                    "type": "git",
                    "reference": "3.x"
                },
                "autoload": {
                    "psr-4": {
                        "Saade\\FilamentFullCalendar\\": "src"
                    }
                },
                "extra": {
                    "laravel": {
                        "providers": [
                            "Saade\\FilamentFullCalendar\\FilamentFullCalendarServiceProvider"
                        ]
                    }
                }
            }
        }
    ],
```
