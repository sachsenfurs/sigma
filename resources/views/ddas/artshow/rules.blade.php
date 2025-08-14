@extends('layouts.app')
@section('title', __('Art Show Rules'))

@section("artshow.rules.default.de")
    <ol>
        <li>
            <strong>Auswahl der ausgestellten Gegenstände</strong>
            <ul>
                <li>Aus Platzgründen können unter Umständen nicht alle eingereichten Gegenstände in der digitalen Artshow live präsentiert werden.</li>
            </ul>
        </li>

        <li>
            <strong>Zulassung zur Auktion</strong>
            <ul>
                <li>Gegenstände mit mehr als fünf Geboten kommen für die Auktion in Frage. Darüber hinaus behalten wir uns das Recht vor, auch solche Werke in die Auktion oder Präsentation aufzunehmen, die unserer Einschätzung nach bisher keine oder nur unzureichende Beachtung erhalten haben.</li>
            </ul>
        </li>

        <li>
            <strong>Verbindlichkeit der Gebote</strong>
            <ul>
                <li>Alle Gebote sind bindend. Bitte stelle sicher, dass dein Gebot korrekt übermittelt und von uns wahrgenommen wurde.</li>
            </ul>
        </li>

        <li>
            <strong>Zahlungsmöglichkeiten</strong>
            <ul>
                <li>Zahlungen sind in bar, mit Karte sowie in Ausnahmefällen per PayPal möglich.</li>
            </ul>
        </li>

        <li>
            <strong>Abholung</strong>
            <ul>
                <li>Informiere dich vor dem Bieten bitte über die Abholzeiten! Die Ausgabe an die Gewinner erfolgt zum Ende der Convention. Die genauen Zeiten findest du im Programmplan unter Artshow Abolung / Pick-up</li>
            </ul>
        </li>
    </ol>
@endsection
@section("artshow.rules.default.en")
    <ol>
        <li>
            <strong>Selection of Exhibited Items</strong>
            <ul>
                <li>Due to space limitations, it may not be possible to present all submitted items live in the digital art show.</li>
            </ul>
        </li>

        <li>
            <strong>Admission to the Auction</strong>
            <ul>
                <li>Items with more than five bids are eligible for the auction. In addition, we reserve the right to include in the auction or presentation works that, in our assessment, have so far received little or no attention.</li>
            </ul>
        </li>

        <li>
            <strong>Binding Nature of Bids</strong>
            <ul>
                <li>All bids are binding. Please make sure that your bid is correctly submitted and acknowledged by us.</li>
            </ul>
        </li>

        <li>
            <strong>Payment Options</strong>
            <ul>
                <li>Payments can be made in cash, by card, and, in exceptional cases, via PayPal.</li>
            </ul>
        </li>

        <li>
            <strong>Pick-up</strong>
            <ul>
                <li>Please check the pick-up times before bidding! Items will be handed over to the winners at the end of the convention. You can find the exact times in the program schedule under Art Show Pick-up.</li>
            </ul>
        </li>
    </ol>
@endsection
@section('content')
    <style>
        ol > li {
            margin-top: 1em;
        }
    </style>
    <div class="container">
        <h2 class="mb-4">{{ __("Rules for Participation in the Digital Art Show & Auction") }}</h2>
        {!! \App\Services\PageHookService::resolve("artshow.rules", \Illuminate\Support\Facades\View::yieldContent("artshow.rules.default.".app()->getLocale())) !!}
    </div>
@endsection
