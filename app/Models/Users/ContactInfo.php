<?php

namespace App\Models\Users;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use JeroenDesloovere\VCard\VCard;


class ContactInfo extends Model
{
    use HasFactory;
    const FILES_DIRECTORY = 'contacts/';
    protected $table = 'contact_info';
    protected $fillable = [
        'first_name', 'last_name', 'job_title', 'email', 'mob_number1',
        'mob_number2', 'home_number1', 'home_number2', 'work_number1', 'work_number2',
        'address_street', 'address_district', 'address_governate',
        'address_country', 'url', 'image', 'company'
    ];

    ///model functions
    public function generateQRCode()
    {
        return "https://quickchart.io/qr?text=" . urlencode(url("contact/" . $this->id)) . "&choe=UTF-8";
    }

    public function downloadvCard()
    {
        $vcard = new VCard();

        // add personal data
        $vcard->addName($this->last_name, $this->first_name);
        if ($this->job_title)
            $vcard->addJobtitle($this->job_title);

        if ($this->company)
            $vcard->addCompany($this->company);

        if ($this->email)
            $vcard->addEmail($this->email);

        if ($this->mob_number1)
            $vcard->addPhoneNumber($this->mob_number1);

        if ($this->mob_number1)
            $vcard->addPhoneNumber($this->mob_number2);

        if ($this->home_number1)
            $vcard->addPhoneNumber($this->home_number1, 'HOME');

        if ($this->home_number2)
            $vcard->addNote("Hotline:  $this->home_number2");

        if ($this->work_number1)
            $vcard->addPhoneNumber($this->work_number1, 'WORK');

        if ($this->work_number2)
            $vcard->addPhoneNumber($this->work_number2, 'WORK');

        if ($this->address_street)
            $vcard->addAddress(null, null, $this->address_street, $this->address_district, $this->address_governate, null, $this->address_country);

        if ($this->url)
            $vcard->addURL($this->url);

        if ($this->image)
            $vcard->addPhoto(Storage::disk('s3')->url(str_replace('//', '/', $this->image)));

        // return vcard as a string
        //return $vcard->getOutput();

        // return vcard as a download
        return $vcard->download();

        // save vcard on disk
        //$vcard->setSavePath('/path/to/directory');
        //$vcard->save();
    }

    public function editInfo(
        $first_name,
        $last_name,
        $job_title,
        $company,
        $email,
        $mob_number1,
        $mob_number2,
        $home_number1,
        $home_number2,
        $work_number1,
        $work_number2,
        $address_street,
        $address_district,
        $address_governate,
        $address_country,
        $url,
        $image,
    ) {
        try {
            return $this->update([
                "first_name"    =>  $first_name,
                "last_name" =>  $last_name,
                "job_title" =>  $job_title,
                "company" =>  $company,
                "email" =>  $email,
                "mob_number1"   =>  $mob_number1,
                "mob_number2"   =>  $mob_number2,
                "home_number1"  =>  $home_number1,
                "home_number2"  =>  $home_number2,
                "work_number1"  =>  $work_number1,
                "work_number2"  =>  $work_number2,
                "address_street" =>  $address_street,
                "address_district"  =>  $address_district,
                "address_governate" =>  $address_governate,
                "address_country"   =>  $address_country,
                "url"   =>  $url,
                "image" =>  $image,
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///attributes
    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    ///static functions
    public static function createNewContact(
        $first_name,
        $last_name,
        $job_title,
        $email,
        $mob_number1,
        $mob_number2,
        $home_number1,
        $home_number2,
        $work_number1,
        $work_number2,
        $address_street,
        $address_district,
        $address_governate,
        $address_country,
        $url,
        $image,
        $company,
    ) {
        try {
            $tmpContact = new self([
                "first_name"    =>  $first_name,
                "last_name" =>  $last_name,
                "job_title" =>  $job_title,
                "company" =>  $company,
                "email" =>  $email,
                "mob_number1"   =>  $mob_number1,
                "mob_number2"   =>  $mob_number2,
                "home_number1"  =>  $home_number1,
                "home_number2"  =>  $home_number2,
                "work_number1"  =>  $work_number1,
                "work_number2"  =>  $work_number2,
                "address_street" =>  $address_street,
                "address_district"  =>  $address_district,
                "address_governate" =>  $address_governate,
                "address_country"   =>  $address_country,
                "url"   =>  $url,
                "image" =>  $image,
            ]);
            $tmpContact->save();
            return $tmpContact;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ////scope
    public function scopeSearch($query, $text)
    {
        $words = explode(" ", $text);
        foreach ($words as $w) {
            $query->where(function ($q) use ($w) {
                $q->orWhere("first_name", "LIKE", "%$w%")
                    ->orWhere("last_name", "LIKE", "%$w%")
                    ->orWhere("job_title", "LIKE", "%$w%")
                    ->orWhere("email", "LIKE", "%$w%")
                    ->orWhere("mob_number1", "LIKE", "%$w%")
                    ->orWhere("home_number1", "LIKE", "%$w%")
                    ->orWhere("mob_number2", "LIKE", "%$w%")
                    ->orWhere("home_number2", "LIKE", "%$w%");
            });
        }
    }
}
