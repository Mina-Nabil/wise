<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Users\ContactInfo;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class ContactInfoIndex extends Component
{
    use WithPagination,AlertFrontEnd,ToggleSectionLivewire;

    public $first_name;
    public $last_name;
    public $job_title;
    public $email;
    public $mob_number1;
    public $mob_number2;
    public $home_number1;
    public $home_number2;
    public $work_number1;
    public $work_number2;
    public $address_line1;
    public $address_line2;
    public $address_district;
    public $address_governate;
    public $address_country;
    public $url;
    public $image;

    public $addContactSec = false;

    public function toggleAddSection()
    {   
        $this->toggle($this->addContactSec);
        // if($this->addContactSec){
        //     $this->reset();
        // }
    }

    public function addContact(){
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mob_number1' => 'nullable|string|max:255',
            'mob_number2' => 'nullable|string|max:255',
            'home_number1' => 'nullable|string|max:255',
            'home_number2' => 'nullable|string|max:255',
            'work_number1' => 'nullable|string|max:255',
            'work_number2' => 'nullable|string|max:255',
            'address_district' => 'nullable|string|max:255',
            'address_governate' => 'nullable|string|max:255',
            'address_country' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        if($this->image){
            $imageurl = $this->image->store(ContactInfo::FILES_DIRECTORY, 's3');
        }else{
            $imageurl = null;
        }

        $res = ContactInfo::createNewContact(
            $this->first_name,
            $this->last_name,
            $this->job_title,
            $this->email,
            $this->mob_number1,
            $this->mob_number2,
            $this->home_number1,
            $this->home_number2,
            $this->work_number1,
            $this->work_number2,
            $this->address_line1,
            $this->address_line2,
            $this->address_district,
            $this->address_governate,
            $this->address_country,
            $this->url,
            $imageurl
        );

        if($res){
            $this->reset();
            $this->alert('success','Contact added!');
        }else{
            $this->alert('failed','server error');
        }
    }

    public function render()
    {
        $contacts = ContactInfo::paginate(20);
        return view('livewire.contact-info-index',[
            'contacts' => $contacts
        ]);
    }
}
