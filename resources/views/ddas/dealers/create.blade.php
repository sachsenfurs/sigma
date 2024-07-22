@extends('layouts.app')
@section('title', __("Dealer's Den Sign Up"))

@section('content')

    <div class="container">
{{--        <h2>{{ __('Dealers Den Sign Up') }}</h2>--}}
{{--        <div class="card">--}}
{{--            <div class="card-body">--}}
{{--                <section>--}}
{{--                    <p>Im Dealers Den haben Künstler und kommerzielle Verkäufer die Möglichkeit, ihre Kunst oder ihre Produkte zu verkaufen, sowie Auftragsarbeiten anzunehmen.</p>--}}
{{--                    <strong>Bitte beachte</strong>--}}
{{--                    <ul>--}}
{{--                        <li>Die Anzahl an Plätzen im Dealers Den ist begrenzt und kann erst nach einer endgültigen Zusage von uns garantiert werden.</li>--}}
{{--                        <li>Für eine Teilnahme ist eine abgeschlossene Registrierung zur EAST Voraussetzung, sowie eine Anmeldung für den Dealers Den / Artshow unter folgendem Link:</li>--}}
{{--                        <li>Wir stellen euch Tische, Stühle, Strom sowie eine begrenzte Anzahl an mobilen Wänden zur Verfügung.</li>--}}
{{--                    </ul>--}}

{{--                    <p style="font-size: 0.8em; text-align: justify">--}}
{{--                        Euer Stand wird außerhalb der Öffnungszeiten in einem abgeschlossenen Raum sein, daher müsst ihr nicht täglich auf- und abbauen. Der Sachsen Furs e.V. stellt im Rahmen seiner Veranstaltung lediglich die Räumlichkeiten zur Verfügung und ist zu keinem Zeitpunkt Partner von Rechtsgeschäften. Allein der Künstler / Verkäufer ist verantwortlich für die Einhaltung der geltenden Gesetze und die Abfuhr der ggf. notwendigen Steuern. Da es sich um eine Veranstaltung nur für Volljährige handelt, ist der Verkauf / die Ausstellung von 18+ / NSFW Material gestattet. Der Sachsen Furs e.V. übernimmt im Rahmen der Einlasskontrolle die Verantwortung für die Einhaltung des Mindestalters. Es wird keine Haftung für entwendete oder beschädigte Gegenstände übernommen. Bei Fragen oder Kritik könnt ihr euch jederzeit an ein Teammitglied des Dealers Den / der Artshow wenden.--}}
{{--                    </p>--}}
{{--                    <p>Die regulären Öffnungszeiten sind:</p>--}}
{{--                    <strong>Set-up (Aufbau für Künstler)</strong>--}}
{{--                    <ul>--}}
{{--                        <li>Mittwoch 	15:30 - 17:00 Uhr</li>--}}
{{--                        <li>Donnerstag 	09:00 - 12:00 Uhr</li>--}}
{{--                    </ul>--}}
{{--                    <strong>Öffnungszeiten</strong>--}}
{{--                    <ul>--}}
{{--                        <li>Donnerstag 	14:00 - 17:00 Uhr</li>--}}
{{--                        <li>Freitag 10:00 - 12:00,  14:00 - 17:00 Uhr</li>--}}
{{--                        <li>Samstag 	10:00 - ca. 14:00 Uhr</li>--}}
{{--                    </ul>--}}
{{--                    <strong>Abbau</strong>--}}
{{--                    <ul>--}}
{{--                        <li>Samstag 	ab 14:00 Uhr</li>--}}
{{--                        <li>Sonntag 	ab 11:00 Uhr</li>--}}
{{--                    </ul>--}}
{{--                </section>--}}

{{--            </div>--}}
{{--        </div>--}}
        <livewire:ddas.dealers-signup />
    </div>
@endsection
