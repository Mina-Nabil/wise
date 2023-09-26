@extends('layouts.app')

@section('title')
    Wise · Policies
@endsection

@section('content')
    <div class="grid md:grid-cols-5 lg:grid-cols-2 gap-5">
        <div>
            <div class="card mb-5">
                <div class="card-body flex flex-col p-6">
                    <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                        <div class="flex-1">
                            <div class="card-title text-slate-900 dark:text-white">Policy Info</div>
                        </div>
                    </header>
                    <div class="card-text h-full ">
                        <form class="space-y-4">
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">L.O.B</label>
                                <select name="line_" id="basicSelect" class="form-control w-full mt-2">
                                    <option selected="Selected" disabled="disabled" value="none"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Select an
                                        option
                                    </option>
                                    <option value="option1" selected
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Motor
                                    </option>
                                    <option value="option2"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Life
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Health
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Cargo
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Properties
                                    </option>
                                </select>
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Name</label>
                                <input type="email" class="form-control" placeholder="Enter Policy Name"
                                    name="policy_name">
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Company</label>
                                <select name="company" id="select2basic" class="select2 form-control w-full mt-2 py-2">
                                    <option value="option1"
                                        class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                        Option 1</option>
                                    <option value="option2"
                                        class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                        Option 2</option>
                                    <option value="option3"
                                        class=" inline-block font-Inter font-normal text-sm text-slate-600">
                                        Option 3</option>
                                </select>
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Note</label>
                                <textarea class="form-control" placeholder="Leave a note" name="note"></textarea>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body flex flex-col p-6">
                    <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                        <div class="flex-1">
                            <div class="card-title text-slate-900 dark:text-white">Policy Rule</div>
                        </div>
                    </header>
                    <div class="card-text h-full ">
                        <form class="space-y-4">
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Option</label>
                                <select name="option" id="basicSelect" class="form-control w-full mt-2">
                                    <option selected="Selected" disabled="disabled" value="none"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Select an
                                        option
                                    </option>
                                    <option value="option1" selected
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Motor
                                    </option>
                                    <option value="option2"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Life
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Health
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Cargo
                                    </option>
                                    <option value="option3"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">Properties
                                    </option>
                                </select>
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Operation</label>
                                <select name="operation" id="basicSelect" class="form-control w-full mt-2">
                                    <option selected="Selected" disabled="disabled" value="none"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Select an option
                                    </option>
                                    <option value="option1" selected class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Less Than (<) 
                                    </option>
                                    <option value="option2" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Less Than Or Equal (<=) </option>
                                    <option value="option3" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Greater Than (>)
                                    </option>
                                    <option value="option3" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                        Greater Than Or Equal (>=)
                                    </option>
                                </select>
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Value</label>
                                <input type="number" class="form-control" name="value">
                            </div>
                            <div class="input-area relative pl-28">
                                <label for="largeInput" class="inline-inputLabel">Rate</label>
                                <input type="number" class="form-control" name="rate">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div>
                <button class="btn inline-flex justify-center btn-success light float-right">Save</button>
            </div>
        </div>
    </div>
@endsection
