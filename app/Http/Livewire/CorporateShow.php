<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Corporates\Corporate;
use App\Models\Corporates\Address;
use App\Models\Corporates\BankAccount;
use App\Models\Corporates\Contact;
use App\Models\Corporates\Phone;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;

class CorporateShow extends Component
{
    use ToggleSectionLivewire, AlertFrontEnd;
    public $corporate;

    //corporate
    public $name;
    public $arabicName;
    public $email;
    public $commercialRecord;
    public $commercialRecordDoc;
    public $taxId;
    public $taxIdDoc;
    public $kyc;
    public $kycDoc;
    public $contractDoc;
    public $mainBandEvidence;
    public $editCorporateSection = false;

    //address
    public $type;
    public $line1;
    public $line2;
    public $country;
    public $city;
    public $building;
    public $flat;
    public $editAddressId;
    public $deleteAddressId;
    public $addAddressSection = false;


    //phone
    public $phoneType;
    public $number;
    public $PhoneId;
    public $deletePhoneId;
    public $addPhoneSection = false;

    //contact
    public $contactName;
    public $jobTitle;
    public $contactEmail;
    public $contactPhone;
    public $contactId;
    public $deleteContactId;
    public $addContactSection = false;

    //bank account
    public $accountType;
    public $bankName;
    public $accountNumber;
    public $ownerName;
    public $evidenceDoc;
    public $iban;
    public $bankBranch;
    public $bankAccountId;
    public $deleteBankAccountId;
    public $addBankAccountSection;

    public function toggleEditCorporate()
    {
        $this->toggle($this->editCorporateSection);
    }

    public function toggleAddAddress()
    {
        $this->toggle($this->addAddressSection);
    }

    public function editThisAddress($id)
    {
        $this->editAddressId = $id;
    }

    public function closeEditAddress()
    {
        $this->editAddressId = null;
    }

    public function toggleAddPhone()
    {
        $this->toggle($this->addPhoneSection);
    }

    public function editThisPhone($id)
    {
        $this->PhoneId = $id;
    }

    public function closeEditPhone()
    {
        $this->PhoneId = null;
    }

    public function toggleAddContact()
    {
        $this->toggle($this->addContactSection);
    }

    public function editThisContact($id)
    {
        $this->contactId = $id;
    }

    public function closeEditContact()
    {
        $this->contactId = null;
    }


    public function toggleAddBankAccount()
    {
        $this->toggle($this->addBankAccountSection);
    }

    public function editThisBankAccount($id)
    {
        $this->bankAccountId = $id;
    }

    public function closeEditBankAccount()
    {
        $this->bankAccountId = null;
    }

    public function mount($corporateId)
    {
        $this->corporate = Corporate::findOrFail($corporateId);
    }

    public function editCorporate()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'arabicName' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'commercialRecord' => 'nullable|string|max:255',
            'commercialRecordDoc' => 'nullable|string|max:255',
            'taxId' => 'nullable|string|max:255',
            'taxIdDoc' => 'nullable|string|max:255',
            'kyc' => 'nullable|string|max:255',
            'kycDoc' => 'nullable|string|max:255',
            'contractDoc' => 'nullable|string|max:255',
            'mainBandEvidence' => 'nullable|string|max:255'
        ]);

        $c = Corporate::find($this->corporate->id);
        $res = $c->editInfo(
            $this->name,
            $this->arabicName,
            $this->email,
            $this->commercialRecord,
            $this->commercialRecordDoc,
            $this->taxId,
            $this->taxIdDoc,
            $this->kyc,
            $this->kycDoc,
            $this->contractDoc,
            $this->mainBandEvidence
        );
        if ($res) {
            $this->alert('success', 'Corporate edited successfuly');
            $this->name = null;
            $this->arabicName = null;
            $this->email = null;
            $this->commercialRecord = null;
            $this->commercialRecordDoc = null;
            $this->taxId = null;
            $this->taxIdDoc = null;
            $this->kyc = null;
            $this->kycDoc = null;
            $this->contractDoc = null;
            $this->mainBandEvidence = null;
            $this->editCorporateSection = false;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addAddress()
    {
        $this->validate([
            'type' => 'required|in:' . implode(',', Address::TYPES),
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'flat' => 'nullable|string|max:255'

        ]);
        $c = Corporate::find($this->corporate->id);
        $c->addAddress(
            $this->type,
            $this->line1,
            $this->line2,
            $this->country,
            $this->city,
            $this->building,
            $this->flat,
            false
        );
        if ($c) {
            $this->alert('success', 'Address added successfuly');
            $this->type = null;
            $this->line1 = null;
            $this->line2 = null;
            $this->country = null;
            $this->city = null;
            $this->building = null;
            $this->flat = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editAddress()
    {
        $this->validate([
            'type' => 'required|in:' . implode(',', Address::TYPES),
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'flat' => 'nullable|string|max:255'

        ]);
        $a = Address::find($this->editAddressId);
        $res = $a->editInfo(
            $this->type,
            $this->line1,
            $this->line2,
            $this->country,
            $this->city,
            $this->building,
            $this->flat
        );
        if ($res) {
            $this->alert('success', 'Address edited successfuly');
            $this->type = null;
            $this->line1 = null;
            $this->line2 = null;
            $this->country = null;
            $this->city = null;
            $this->building = null;
            $this->flat = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteAddress()
    {
        $r = Address::find($this->deleteAddressId)->delete();
        if ($r) {
            $this->alert('success', 'Address deleted sucessfuly!');
            $this->deleteAddressId = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addPhone()
    {
        $this->validate([
            'phoneType' =>  'required|in:' . implode(',', Phone::TYPES),
            'number' => 'required|string|max:255',
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addPhone(
            $this->phoneType,
            $this->number,
            null,
        );
        if ($res) {
            $this->alert('success', 'Phone Added successfuly');
            $this->phoneType = null;
            $this->number = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editPhone()
    {
        $this->validate([
            'phoneType' =>  'required|in:' . implode(',', Phone::TYPES),
            'number' => 'required|string|max:255',
        ]);
        $p = Phone::find($this->PhoneId);
        $res = $p->editInfo(
            $this->phoneType,
            $this->number,
            null,
        );
        if ($res) {
            $this->alert('success', 'Phone Edited successfuly');
            $this->phoneType = null;
            $this->number = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deletePhone()
    {
        $r = Phone::find($this->deletePhoneId)->delete();
        if ($r) {
            $this->alert('success', 'Phone deleted sucessfuly!');
            $this->deletePhoneId = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addContact()
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'jobTitle' => 'nullable|string|max:255',
            'contactEmail' => 'nullable|string|max:255',
            'contactPhone' => 'nullable|string|max:255'
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addContact(
            $this->contactName,
            $this->jobTitle,
            $this->contactEmail,
            $this->contactPhone,
            false
        );
        if ($res) {
            $this->alert('success', 'Contact Added successfuly');
            $this->contactName = null;
            $this->jobTitle = null;
            $this->contactEmail = null;
            $this->contactPhone = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editContact()
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'jobTitle' => 'nullable|string|max:255',
            'contactEmail' => 'nullable|string|max:255',
            'contactPhone' => 'nullable|string|max:255'
        ]);
        $c = Contact::find($this->contactId);
        $res = $c->editInfo(
            $this->contactName,
            $this->jobTitle,
            $this->contactEmail,
            $this->contactPhone,
        );
        if ($res) {
            $this->alert('success', 'Contact Edited successfuly');
            $this->contactName = null;
            $this->jobTitle = null;
            $this->contactEmail = null;
            $this->contactPhone = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteContact()
    {
        $r = Contact::find($this->deleteContactId)->delete();
        if ($r) {
            $this->alert('success', 'Contact deleted sucessfuly!');
            $this->deleteContactId = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addBankAccount()
    {
        $this->validate([
            'accountType' =>  'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' =>  'required|string|max:255',
            'ownerName' =>  'required|string|max:255',
            'evidenceDoc' =>  'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addBankAccount(
            $this->accountType,
            $this->bankName,
            $this->accountNumber,
            $this->ownerName,
            $this->evidenceDoc,
            $this->iban,
            $this->bankBranch,
            false
        );
        if ($res) {
            $this->alert('success', 'Account Added successfuly');
            $this->accountType = null;
            $this->bankName = null;
            $this->accountNumber = null;
            $this->ownerName = null;
            $this->evidenceDoc = null;
            $this->iban = null;
            $this->bankBranch = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editBankAccount()
    {
        $this->validate([
            'accountType' =>  'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' =>  'required|string|max:255',
            'ownerName' =>  'required|string|max:255',
            'evidenceDoc' =>  'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $b = BankAccount::find($this->bankAccountId);
        $res = $b->addBankAccount(
            $this->accountType,
            $this->bankName,
            $this->accountNumber,
            $this->ownerName,
            $this->evidenceDoc,
            $this->iban,
            $this->bankBranch,
            false
        );
        if ($res) {
            $this->alert('success', 'Account Added successfuly');
            $this->accountType = null;
            $this->bankName = null;
            $this->accountNumber = null;
            $this->ownerName = null;
            $this->evidenceDoc = null;
            $this->iban = null;
            $this->bankBranch = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function deleteBankAccount()
    {
        $r = BankAccount::find($this->deleteBankAccountId)->delete();
        if ($r) {
            $this->alert('success', 'Account deleted sucessfuly!');
            $this->deleteBankAccountId = null;
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function render()
    {
        return view('livewire.corporate-show');
    }
}
