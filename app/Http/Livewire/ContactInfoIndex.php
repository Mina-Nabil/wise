<?php

namespace App\Http\Livewire;

use App\Models\Corporates\Contact;
use Livewire\Component;
use App\Models\Users\ContactInfo;
use Livewire\WithPagination;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Livewire\WithFileUploads;

class ContactInfoIndex extends Component
{
    use WithPagination, AlertFrontEnd, ToggleSectionLivewire, WithFileUploads;

    public $search;
    
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
    public $address_street;
    public $address_district;
    public $address_governate;
    public $address_country;
    public $url;
    public $image;

    public $contact;
    public $contactId;

    public $addContactSec = false;

    public function toggleAddSection()
    {
        $this->toggle($this->addContactSec);
    }

    public function closeEditSec()
    {
        $this->reset();
    }

    

    public function editThisContact($id)
    {
        $this->contactId = $id;
        $c = ContactInfo::find($id);
        $this->first_name = $c->first_name;
        $this->last_name = $c->last_name;
        $this->job_title = $c->job_title;
        $this->email = $c->email;
        $this->mob_number1 = $c->mob_number1;
        $this->mob_number2 = $c->mob_number2;
        $this->home_number1 = $c->home_number1;
        $this->home_number2 = $c->home_number2;
        $this->work_number1 = $c->work_number1;
        $this->work_number2 = $c->work_number2;
        $this->address_street = $c->address_street;
        $this->address_district = $c->address_district;
        $this->address_governate = $c->address_governate;
        $this->address_country = $c->address_country;
        $this->url = $c->url;
        $this->image = $c->image;
    }

    public function clearImage()
    {
        $this->image = null;
    }

    public function generateQR($id)
    {
        $res = ContactInfo::find($id)->generateQRCode();
        $this->dispatchBrowserEvent('openNewTab', ['url' => $res]);
    }

    public function downloadVcard($id)
    {
        $res = ContactInfo::find($id)->downloadVcard();
        $this->dispatchBrowserEvent('openNewTab', ['url' => $res]);
    }

    public function editInfo()
    {
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
            'address_street' => 'nullable|string|max:255',
            'address_district' => 'nullable|string|max:255',
            'address_governate' => 'nullable|string|max:255',
            'address_country' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            // 'image' => 'nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        
        $contact = ContactInfo::find($this->contactId);

        if (is_null($contact->image) && is_null($this->image)) {
            $imageurl = null;
        } elseif (!is_null($contact->image) && is_null($this->image)) {
            $imageurl = null;
        } elseif (!is_null($contact->image) && !is_null($this->image) && (is_string($this->image)) ) {
                $this->image = null;
                $imageurl = $contact->image;
        } else{

            $this->validate([
                'image' => ['file', 'nullable' , 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp' , 'max:5120'],
            ]);

            $imageurl =  $this->image->store(ContactInfo::FILES_DIRECTORY, 's3');
        }

        $res = ContactInfo::find($this->contactId)->editInfo($this->first_name, $this->last_name, $this->job_title, $this->email, $this->mob_number1, $this->mob_number2, $this->home_number1, $this->home_number2, $this->work_number1, $this->work_number2, $this->address_street, $this->address_district, $this->address_governate, $this->address_country, $this->url, $imageurl);

        if ($res) {
            $this->reset();
            $this->alert('success', 'Contact updated!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addContact()
    {
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
            'address_street' => 'nullable|string|max:255',
            'address_district' => 'nullable|string|max:255',
            'address_governate' => 'nullable|string|max:255',
            'address_country' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:5120',
        ]);

        if ($this->image) {
            $imageurl = $this->image->store(ContactInfo::FILES_DIRECTORY, 's3');
        } else {
            $imageurl = null;
        }

        $res = ContactInfo::createNewContact($this->first_name, $this->last_name, $this->job_title, $this->email, $this->mob_number1, $this->mob_number2, $this->home_number1, $this->home_number2, $this->work_number1, $this->work_number2, $this->address_street, $this->address_district, $this->address_governate, $this->address_country, $this->url, $imageurl);

        if ($res) {
            $this->reset();
            $this->alert('success', 'Contact added!');
        } else {
            $this->alert('failed', 'server error');
        }
    }

    function generateUrl($fieldName, $property)
    {
        $url = null;

        if (is_null($this->contact->$fieldName) && is_null($this->$property)) {
            $url = null;
        } elseif (!is_null($this->contact->$fieldName) && is_null($this->$property)) {
            $url = null;
        } elseif (!is_null($this->contact->$fieldName) && !is_null($this->$property) && (is_string($this->$property)) ) {
                $this->$property = null;
                $url = $this->contact->$fieldName;
        } else{

            $this->validate([
                $property => ['file', 'nullable' , 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp' , 'max:5120'],
            ]);

            $url =  $this->$property->store(ContactInfo::FILES_DIRECTORY, 's3');
        }

        return $url;
    }

    public function render()
    {
        $contacts = ContactInfo::search($this->search)->paginate(20);
        return view('livewire.contact-info-index', [
            'contacts' => $contacts,
        ]);
    }
}
