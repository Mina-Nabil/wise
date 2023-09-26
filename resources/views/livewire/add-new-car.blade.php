<div>
    <div class="p-6 space-y-4">

        <!-- car-component.blade.php -->
        <div>
            @if (session()->has('message'))
                <div
                    class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-success-500 bg-opacity-[14%] text-success-500">
                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                        <div class="flex-1">
                            {{ session('message') }}
                        </div>
                    </div>
                </div>
            @endif
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    Faild Adding New Car!
                </div>
            @endif --}}
            <div class="checkbox-area">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="hidden" name="checkbox" checked="checked" wire:model="addNewBrand">
                    <span
                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                        <img src="assets/images/icon/ck-white.svg" alt=""
                            class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Add New Brand</span>
                </label>
            </div>
        </div>
        <div>


            @if (!$addNewBrand)
                <label for="brandSelect" class="form-label">Brand</label>
                <select name="brand_id" wire:model="brandId" id="brandSelect" class="form-control w-full mt-2">
                    <option value="" selected>Select an option</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            @else
                <div class="input-area">
                    <label for="name" class="form-label">Brand</label>
                    <input name="brand_name" id="name" type="text"
                        class="form-control @if ($errors->has('brandName')) !border-danger-500 !pr-9 @endif"
                        placeholder="Enter" wire:model="brandName">
                    @if ($errors->has('brandName'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('brandName') }}</span>
                    @endif
                </div>
                <div>
                    <label for="carModelSelect" class="form-label">Country</label>
                    <select name="country_id" wire:model="country" id="carModelSelect"
                        class="form-control w-full mt-2 @if ($errors->has('country')) !border-danger-500 !pr-9 @endif">
                        <option value="" selected>Select an option</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('country'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('country') }}</span>
                    @endif
                </div>
                <div class="input-area">
                    <label for="name" class="form-label">Model</label>
                    <input name="model_name" id="name" type="text"
                        class="form-control @if ($errors->has('modelName')) !border-danger-500 !pr-9 @endif"
                        placeholder="Enter" wire:model="modelName">
                    @if ($errors->has('modelName'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('modelName') }}</span>
                    @endif
                </div>
                <div class="input-area">
                    <label for="name" class="form-label">Category</label>
                    <input id="category_name" type="text"
                        class="form-control @if ($errors->has('categoryName')) !border-danger-500 !pr-9 @endif"
                        placeholder="Enter" wire:model="categoryName">
                    @if ($errors->has('categoryName'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('categoryName') }}</span>
                    @endif
                </div>
            @endif

        </div>

        @if ($brandId)
            <div class="checkbox-area">
                <label class="inline-flex items-center cursor-pointer ">
                    <input type="checkbox" class="hidden" name="checkbox" checked="checked" wire:model="addNewModel">
                    <span
                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                        <img src="assets/images/icon/ck-white.svg" alt=""
                            class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Add New Model</span>
                </label>

            </div>
            @if ($addNewModel)
                <div class="input-area">
                    <label for="model_name" class="form-label">Model</label>
                    <input name="model_name" id="model_name" type="text"
                        class="form-control @if ($errors->has('modelName')) !border-danger-500 !pr-9 @endif"
                        placeholder="Enter model name" wire:model="modelName">
                    @if ($errors->has('modelName'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('modelName') }}</span>
                    @endif
                </div>
                <div class="input-area">
                    <label for="category" class="form-label">Category</label>
                    <input name="category_name" id="category" type="text"
                        class="form-control @if ($errors->has('categoryName')) !border-danger-500 !pr-9 @endif"
                        placeholder="Enter category" wire:model="categoryName">
                    @if ($errors->has('categoryName'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('categoryName') }}</span>
                    @endif
                </div>
            @else
                <div>
                    <label for="carModelSelect" class="form-label">Model</label>
                    <select name="model_id" wire:model="selectedCarModel" id="carModelSelect"
                        class="form-control w-full mt-2 @if ($errors->has('selectedCarModel')) !border-danger-500 !pr-9 @endif">
                        <option value="" selected>Select an option</option>
                        @foreach ($carModels as $carModel)
                            <option value="{{ $carModel->id }}">{{ $carModel->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('selectedCarModel'))
                        <span
                            class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('selectedCarModel') }}</span>
                    @endif
                </div>
            @endif


        @endif

        @if ($selectedCarModel)
            <div class="input-area">
                <label for="name" class="form-label">Category</label>
                <input name="category_name" id="name" type="text"
                    class="form-control @if ($errors->has('categoryName')) !border-danger-500 !pr-9 @endif"
                    placeholder="Enter" wire:model="categoryName">
                @if ($errors->has('categoryName'))
                    <span
                        class="font-Inter text-sm text-danger-500 pt-2 inline-block">{{ $errors->first('categoryName') }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
        <button class="btn inline-flex justify-center text-white bg-success-500" wire:click="saveCar">Submit</button>
    </div>
</div>
