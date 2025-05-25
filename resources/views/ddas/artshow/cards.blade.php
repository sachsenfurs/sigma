@section("title", "Artshow Cards")
@include('layouts/head')
    <body>
        <style>
            a[href]:after {
                content: none !important;
            }
            @page {
                size: A6 landscape;  /* auto is the initial value */
                margin: 0;  /* this affects the margin in the printer settings */
                max-height: 100vh;
            }

            html, body {
                width: 100%;
                margin: 0;
                padding: 0;
                font-family: "Open Sans", sans-serif;
                height: auto;
            }

            @media only screen {
                article {
                    /*margin: 25px;*/
                }
            }

            article {
                break-after: page; /* for printing */
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                font-size: 5.3vh;
                padding: 0.6em;
            }
            header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
            }

            header img {
                max-height: 5em;
                object-fit: cover;
                width: 100%;
            }
            header h1 {
                font-size: 1.5rem;
                margin: 0;
            }

            header span {
                flex-grow: 1;
                text-align: end;
                color: #6e6e6e;
            }

            h1, h2, h3, h4 {
                padding: 0.4em 0 0.1em 0;
                margin: 0;
                overflow: hidden;
            }
            p {
                word-break: break-word;
                flex-basis: 0;
                margin: 0.2em 0 0.2em 0;
                font-size: 0.8em;
            }

            .row {
                display: flex;
            }
            .col {
                flex: 1;
                padding: 0.2rem;
                align-content: center;
                /*border: 1px solid #ccc;*/
            }
        </style>

        @foreach($items AS $item)
            <article>
                <header>
                    <div style="">
                        <img src="{{ $item->image_url }}">
                    </div>
                    <div>
                        <h2>{{ $item->name }}</h2>
                    </div>
                    <span>[#{{ $item->id }}]</span>
                </header>
                <div class="row">
                    <div class="col">
                        <strong>{{ __("Artist") }}</strong>
                    </div>
                    <div class="col">
                        {{ $item->artist->name }}
                    </div>
                    <div class="col">
                        <strong>{{ __("Starting Bid") }}</strong>
                    </div>
                    <div class="col">
                        {{ \Illuminate\Support\Number::currency($item->starting_bid) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <strong>{{ __("Current Bid") }} ({{ $item->artshow_bids_count }})</strong>
                    </div>
                    <div class="col">
                        {{ \Illuminate\Support\Number::currency($item->minBidValue()) }}
                    </div>
                    <div class="col">
                        <strong>{{ __("Charity %") }}</strong>
                    </div>
                    <div class="col">
                        {{ $item->charity_percentage }}
                    </div>
                </div>

                <h4>{{ __("Description") }}</h4>
                <p>
                    {{ $item->description_localized }}
                </p>
                <h4>{{ __("Additional Information") }}</h4>
                <p>
                    {{ $item->additional_info }}
                </p>
            </article>
        @endforeach
    </body>
</html>
