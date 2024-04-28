<div>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between">
            <div>
                {{ __("Art Show Item") }} ID {{ $item->pseudo_id }}
            </div>
            <div>
                <button wire:click="removeItem({{ $loop->index }})" class="btn btn-danger btn-sm">{{ __("Remove") }}</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-start">
                <div class="col-md-7 col-12 col-lg-8">
                    <div class="row g-3 d-flex">
                        <div class="col-12">
                            <x-form.input-floating wire:model.live="items[{{ $loop->index }}].name" :placeholder="__('Item Name')" name="name" />
                        </div>
                        <fieldset>
                            <legend class="col-12 fs-5">
                                <input type="checkbox" class="form-check-input" id="auction">
                                <label for="auction">{{ __("Up for auction") }}</label>
                            </legend>
                            <div class="row row-cols-2 mt-0 d-flex align-items-baseline">
                                <div class="col">
                                    <label for="starting_bid">{{ __("Starting Bid") }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="starting_bid">
                                        <span class="input-group-text">EUR</span>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="charity_percentage">{{ __("Charity Percentage") }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" max="100" class="form-control" id="charity_percentage">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-12" style="height: 100%">
                            <label for="info">{{ __("Description") }}</label>
                            <textarea id="info" style="width: 100%" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="col-12" style="height: 100%">
                            <label for="info">{{ __("Additional Info") }}</label>
                            <textarea id="info" style="width: 100%" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-7 col-lg-4">
                    <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                        Bild (optional)
                        <div class="text-center" style="">
                            <img id="preview" src="#" alt="" class="rounded" style="display: none; width: 100%; height: 100%;  object-fit: cover"/>
                        </div>
                    </label>
                    <x-form.input-image name="image" id="selectImage" />
                </div>
            </div>
        </div>
    </div>
    <div class="container text-center p-4 vstack gap-3">
        <button type="submit" class="btn btn-secondary btn-lg px-5" wire:click="addItem">{{ __("Add Another") }}</button>
        <button type="submit" class="btn btn-primary btn-lg px-5">{{ __("Submit") }}</button>
    </div>
</div>
