<!-- Modal -->
<div class="modal fade" id="creditsModal" tabindex="-1" aria-labelledby="creditsModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5" id="creditsModalLabel">Credits</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <a class="text-decoration-none" href="https://github.com/sachsenfurs/sigma" target="_blank"><i class="bi bi-github icon-link"></i> SIGMA on GitHub</a>
                    <ul>
                        <li>
                            <a href="https://fullcalendar.io/" class="text-decoration-none" target="_blank">FullCalendar</a> -
                            <a href="https://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank" class="text-decoration-none">Non-Commercial License</a>
                        </li>
                        <li>
                            <a href="https://www.cheetagonzita.com/" class="text-decoration-none" target="_blank">Artworks by Zita</a>
                        </li>
                    </ul>
                </div>

                <h5>Contributors</h5>
                <div class="d-flex flex-wrap pb-3 gap-2">
                    @foreach([
                        'Kidran' => [
                            'img' => "https://avatars.githubusercontent.com/u/63103731?s=24",
                            'href' => "https://github.com/Kidran",
                        ],
                        'Lytrox' => [
                            'img' => "https://avatars.githubusercontent.com/u/9468383?s=24",
                            'href' => "https://github.com/Lytrox",
                        ],
                        'CyberSpaceDragon' => [
                            'img' => "https://avatars.githubusercontent.com/u/58507164?s=24",
                            'href' => "https://github.com/CyberSpaceDragon",
                        ],
                        'Kacec' => [
                            'img' => "https://avatars.githubusercontent.com/u/30048300?s=24",
                            'href' => "https://github.com/kacecfox",
                        ],
                        'Dexter' => [
                            'img' => "https://avatars.githubusercontent.com/u/62628584?s=24",
                            'href' => "https://github.com/d3xter-dev",
                        ],
                        'Kenthanar' => [
                            'img' => "https://avatars.githubusercontent.com/u/100375107?s=24",
                            'href' => "https://github.com/Kenthanar",
                        ],
                    ] AS $contributor => $data)
                        <a href="{{ $data['href'] }}" target="_blank" class="text-decoration-none">
                            <span class="badge d-flex align-items-center p-1 pe-2 text-dark-emphasis bg-light-subtle border border-dark-subtle rounded-pill">
                                <img class="rounded-circle me-1" width="24" height="24" src="{{ $data['img'] }}" loading="lazy" alt="">
                                {{ $contributor }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
