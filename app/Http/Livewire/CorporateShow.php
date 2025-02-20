<?php

namespace App\Http\Livewire;

use App\Models\Base\Area;
use App\Models\Base\City;
use App\Models\Base\Country;
use Livewire\Component;
use App\Models\Corporates\Corporate;
use App\Models\Customers\Customer;
use App\Models\Corporates\Address;
use App\Models\Corporates\BankAccount;
use App\Models\Corporates\Contact;
use App\Models\Corporates\Interest;
use App\Models\Corporates\Phone;
use App\Models\Customers\Followup;
use App\Models\Insurance\Policy;
use App\Traits\AlertFrontEnd;
use App\Traits\ToggleSectionLivewire;
use Exception;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class CorporateShow extends Component
{
    use ToggleSectionLivewire, AlertFrontEnd, WithFileUploads;
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
    public $area;
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

    //followups
    public $addFollowupSection = false;
    public $followupTitle;
    public $followupCallDate;
    public $followupCallTime;
    public $followupDesc;
    public $followupId;
    public $deleteFollowupId;
    public $isMeeting;
    public $FollowupLineOfBussiness = Policy::BUSINESS_CORPORATE_MOTOR;
    public $is_meeting = false;

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

    //interests
    public $interestSection = false;
    public $editedLob;
    public $interested;
    public $interestNote;
    public $editInteresetSec = false;
    public $isCreateFollowup;

    public $callerNoteSec = false;
    public $callerNotetype;
    public $callerNoteId;
    public $note;

    public $deleteCorporateId;

    public $section = 'profile';

    protected $queryString = ['section'];

    public function clearCommercialRecordDoc()
    {
        $this->commercialRecordDoc = null;
    }

    public function clearTaxIdDoc()
    {
        $this->taxIdDoc = null;
    }

    public function clearKycDoc()
    {
        $this->kycDoc = null;
    }

    public function clearContractDoc()
    {
        $this->contractDoc = null;
    }

    public function clearMainBandEvidence()
    {
        $this->mainBandEvidence = null;
    }

    public function changeSection($section)
    {
        $this->section = $section;
        $this->mount($this->corporate->id);
    }

    public function toggleCallerNote($type = null, $id = null)
    {
        $this->callerNotetype = $type;
        $this->callerNoteId = $id;
        $this->toggle($this->callerNoteSec);
    }

    public function submitCallerNote()
    {
        if ($this->callerNotetype === 'called') {
            $this->setFollowupAsCalled($this->callerNoteId, $this->note);
        } elseif ($this->callerNotetype == 'cancelled') {
            $this->setFollowupAsCancelled($this->callerNoteId, $this->note);
        }
    }

    public function toggleEditCorporate()
    {
        $this->name = $this->corporate->name;
        $this->arabicName = $this->corporate->arabic_name;
        $this->email = $this->corporate->email;
        $this->commercialRecord = $this->corporate->commercial_record;
        $this->commercialRecordDoc = $this->corporate->commercial_record_doc;
        $this->taxId = $this->corporate->tax_id;
        $this->taxIdDoc = $this->corporate->tax_id_doc;
        $this->kyc = $this->corporate->kyc;
        $this->kycDoc = $this->corporate->kyc_doc;
        $this->contractDoc = $this->corporate->contract_doc;
        $this->mainBandEvidence = $this->corporate->main_bank_evidence;
        $this->toggle($this->editCorporateSection);
    }

    public function toggleAddAddress()
    {
        $this->toggle($this->addAddressSection);
    }

    public function editThisAddress($id)
    {
        $this->editAddressId = $id;
        $a = Address::find($id);
        $this->type = $a->type;
        $this->line1 = $a->line_1;
        $this->line2 = $a->line_2;
        $this->country = $a->country;
        $this->city = $a->city;
        $this->area = $a->area;
        $this->building = $a->building;
        $this->flat = $a->flat;
    }

    public function closeEditAddress()
    {
        $this->editAddressId = null;
        $this->type = null;
        $this->line1 = null;
        $this->line2 = null;
        $this->country = null;
        $this->city = null;
        $this->area = null;
        $this->building = null;
        $this->flat = null;
    }

    public function deleteThisAddress($id)
    {
        $this->deleteAddressId = $id;
    }

    public function closeDeleteAddress()
    {
        $this->deleteAddressId = null;
    }

    public function toggleAddPhone()
    {
        $this->toggle($this->addPhoneSection);
    }

    public function editThisPhone($id)
    {
        $this->PhoneId = $id;
        $p = Phone::find($id);
        $this->phoneType = $p->type;
        $this->number = $p->number;
    }

    public function closeEditPhone()
    {
        $this->PhoneId = null;
    }

    public function deleteThisPhone($id)
    {
        $this->deletePhoneId = $id;
    }

    public function closeDeletePhone()
    {
        $this->deletePhoneId = null;
    }

    public function toggleAddContact()
    {
        $this->toggle($this->addContactSection);
    }

    public function editThisContact($id)
    {
        $this->contactId = $id;
        $c = Contact::find($id);
        $this->contactName = $c->name;
        $this->jobTitle = $c->job_title;
        $this->contactEmail = $c->email;
        $this->contactPhone = $c->phone;
    }

    public function closeEditContact()
    {
        $this->contactId = null;
        $this->contactName = null;
        $this->jobTitle = null;
        $this->contactEmail = null;
        $this->contactPhone = null;
    }

    public function deleteThisContact($id)
    {
        $this->deleteContactId = $id;
    }

    public function closeDeleteContact()
    {
        $this->deleteContactId = null;
    }

    public function toggleAddBankAccount()
    {
        $this->toggle($this->addBankAccountSection);
    }

    public function editThisBankAccount($id)
    {
        $this->bankAccountId = $id;
        $b = BankAccount::find($id);
        $this->accountType = $b->type;
        $this->bankName = $b->bank_name;
        $this->accountNumber = $b->account_number;
        $this->ownerName = $b->owner_name;
        $this->evidenceDoc = $b->evidence_doc;
        $this->iban = $b->iban;
        $this->bankBranch = $b->bank_branch;
    }

    public function closeEditBankAccount()
    {
        $this->bankAccountId = null;
        $this->accountType = null;
        $this->bankName = null;
        $this->accountNumber = null;
        $this->ownerName = null;
        $this->evidenceDoc = null;
        $this->iban = null;
        $this->bankBranch = null;
    }

    public function deleteThisBankAccount($id)
    {
        $this->deleteBankAccountId = $id;
    }

    public function closeDeleteBankAccount()
    {
        $this->deleteBankAccountId = null;
    }

    public function closeEditFollowup()
    {
        $this->followupId = null;
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
    }

    public function closeFollowupSection()
    {
        $this->followupTitle = null;
        $this->followupCallDate = null;
        $this->followupCallTime = null;
        $this->followupDesc = null;
        $this->addFollowupSection = false;
        $this->is_meeting = false;
        $this->FollowupLineOfBussiness = Policy::BUSINESS_CORPORATE_MOTOR;
    }

    public function OpenAddFollowupSection()
    {
        $this->addFollowupSection = true;
    }

    public function editThisFollowup($id)
    {
        $this->followupId = $id;
        $f = Followup::find($id);
        $this->followupTitle = $f->title;
        $combinedDateTime = new \DateTime($f->call_time);
        $this->followupCallDate = $combinedDateTime->format('Y-m-d');
        $this->followupCallTime = $combinedDateTime->format('H:i:s');
        $this->followupDesc = $f->desc;
        $this->is_meeting = $f->is_meeting;
        $this->FollowupLineOfBussiness = $f->line_of_business;
    }

    public function deleteThisFollowup($id)
    {
        $this->deleteFollowupId = $id;
    }

    public function dismissDeleteFollowup()
    {
        $this->deleteFollowupId = null;
    }

    public function toggleAddInterest()
    {
        $this->toggle($this->interestSection);
    }

    public function removeInterest($id)
    {
        $res = Interest::find($id)->delete();
        if ($res) {
            $this->mount($this->corporate->id);
            $this->alert('success', 'Interest removed!');
        } else {
            $this->alert('failed', 'Server error');
        }
    }

    public function editThisInterest($status, $lob)
    {
        $this->editInteresetSec = true;
        $this->editedLob  = $lob;
        $this->interested = $status;
    }

    public function closeEditInterest()
    {
        $this->editInteresetSec = false;
    }

    public function editInterest()
    {
        if ($this->interested === 'YES') {
            $this->interested = true;
        } else {
            $this->interested = false;
        }

        $this->validate([
            'editedLob' => 'required|in:' . implode(',', Policy::LINES_OF_BUSINESS),
            'interested' => 'required|boolean',
            'interestNote' => 'nullable|string|max:255'
        ]);

        $res = $this->corporate->addInterest($this->editedLob, $this->interested, $this->interestNote);

        if ($res) {
            $this->editInteresetSec = false;
            $this->editedLob = null;
            $this->interested = null;
            $this->interestNote = null;
            $this->mount($this->corporate->id);
            $this->alert('success', 'Interest edited!');
            if ($this->isCreateFollowup) {
                $this->FollowupLineOfBussiness = $res->business;
                $this->is_meeting = true;
                $this->section = 'followups';
                $this->OpenAddFollowupSection();
            }
        } else {
            $this->alert('failed', 'Server error');
        }
    }


    public function deleteFollowup()
    {
        $res = Followup::find($this->deleteFollowupId)->delete();
        if ($res) {
            $this->alert('success', 'Followup Deleted successfuly');
            $this->dismissDeleteFollowup();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function addFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255',
            'FollowupLineOfBussiness' => 'nullable|in:' . implode(',', policy::LINES_OF_BUSINESS),
            'is_meeting' => 'nullable|boolean',
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $corporate = Corporate::find($this->corporate->id);

        $res = $corporate->addFollowup(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc,
            $this->is_meeting,
            $this->FollowupLineOfBussiness
        );

        if ($res) {
            $this->alert('success', 'Followup added successfuly');
            $this->closeFollowupSection();
            $this->mount($this->corporate->id);
            return redirect()->route('corporates.show', $this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editFollowup()
    {
        $this->validate([
            'followupTitle' => 'required|string|max:255',
            'followupCallDate' => 'nullable|date',
            'followupCallTime' => 'nullable',
            'followupDesc' => 'nullable|string|max:255',
            'FollowupLineOfBussiness' => 'nullable|in:' . implode(',', policy::LINES_OF_BUSINESS),
            'is_meeting' => 'nullable|boolean',
        ]);

        $combinedDateTimeString = $this->followupCallDate . ' ' . $this->followupCallTime;
        $combinedDateTime = new \DateTime($combinedDateTimeString);

        $followup = Followup::find($this->followupId);

        $res = $followup->editInfo(
            $this->followupTitle,
            $combinedDateTime,
            $this->followupDesc,
            $this->is_meeting,
            $this->FollowupLineOfBussiness
        );

        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->closeEditFollowup();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function ConfirmDeleteCorporate($id)
    {
        $this->deleteCorporateId = $id;
    }

    public function DismissDeleteCorporate()
    {
        $this->deleteCorporateId = null;
    }

    public function deleteCorporate()
    {
        try {
            $corporate = Corporate::findOrFail($this->deleteCorporateId);
            $corporate->delete();
            $this->alert('success', 'Corporate deleted successfully.');
            return redirect(Route('corporates.index'));
        } catch (Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }


    public function redirectToOffer($id)
    {
        return redirect(Route('offers.show', $id));
    }


    public function setFollowupAsCalled($id)
    {
        $res = Followup::find($id)->setAsCalled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function setFollowupAsCancelled($id)
    {
        $res = Followup::find($id)->setAsCancelled($this->note);
        if ($res) {
            $this->alert('success', 'Followup updated successfuly');
            $this->toggleCallerNote();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function mount($corporateId)
    {
        $this->corporate = Corporate::findOrFail($corporateId);
        $this->name = $this->corporate->name;
        $this->arabicName = $this->corporate->arabic_name;
        $this->email = $this->corporate->email;
        $this->commercialRecord = $this->corporate->commercial_record;
        $this->commercialRecordDoc = $this->corporate->commercial_record_doc;
        $this->taxId = $this->corporate->tax_id;
        $this->taxIdDoc = $this->corporate->tax_id_doc;
        $this->kyc = $this->corporate->kyc;
        $this->kycDoc = $this->corporate->kyc_doc;
        $this->contractDoc = $this->corporate->contract_doc;
        $this->mainBandEvidence = $this->corporate->main_bank_evidence;
    }

    public function downloadDoc($url, $fileType)
    {
        $filename = $this->corporate->name . '_' . $fileType . '.' . pathinfo($url, PATHINFO_EXTENSION);
        $fileContents = Storage::disk('s3')->get($url);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(
            function () use ($fileContents) {
                echo $fileContents;
            },
            200,
            $headers,
        );
    }

    function generateUrl($fieldName, $property)
    {
        $url = null;

        if (is_null($this->corporate->$fieldName) && is_null($this->$property)) {
            $url = null;
        } elseif (!is_null($this->corporate->$fieldName) && is_null($this->$property)) {
            $url = null;
        } elseif (!is_null($this->corporate->$fieldName) && !is_null($this->$property) && (is_string($this->$property))) {
            $this->$property = null;
            $url = $this->corporate->$fieldName;
        } else {

            $this->validate([
                $property => ['file', 'nullable', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,gif,svg,webp', 'max:33000'],
            ]);

            $url =  $this->$property->store(Customer::FILES_DIRECTORY, 's3');
        }

        return $url;
    }

    public function editCorporate()
    {


        $this->validate([
            'name' => 'required|string|max:255',
            'arabicName' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'commercialRecord' => 'nullable|string|max:255',
            'taxId' => 'nullable|string|max:255',
            'kyc' => 'nullable|string|max:255',
        ]);

        $commercialRecordDoc = $this->generateUrl('commercial_record_doc', 'commercialRecordDoc');
        $taxIdDoc = $this->generateUrl('tax_id_doc', 'taxIdDoc');
        $kycDoc = $this->generateUrl('kyc_doc', 'kycDoc');
        $contractDoc = $this->generateUrl('contract_doc', 'contractDoc');
        $mainBandEvidence = $this->generateUrl('main_bank_evidence', 'mainBandEvidence');

        $c = Corporate::find($this->corporate->id);
        $res = $c->editInfo($this->name, $this->arabicName, $this->email, $this->commercialRecord, $commercialRecordDoc, $this->taxId, $taxIdDoc, $this->kyc, $kycDoc, $contractDoc, $mainBandEvidence);
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
            'area' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'flat' => 'nullable|string|max:255',
        ]);
        /** @var Corporate */
        $c = Corporate::find($this->corporate->id);
        $c->addAddress($this->type, $this->line1, $this->line2, $this->country, $this->city, $this->area, $this->building, $this->flat, false);
        if ($c) {
            $this->alert('success', 'Address added successfuly');
            $this->type = null;
            $this->line1 = null;
            $this->line2 = null;
            $this->country = null;
            $this->city = null;
            $this->area = null;
            $this->building = null;
            $this->flat = null;
            $this->toggleAddAddress();
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
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'flat' => 'nullable|string|max:255',
        ]);
        /** @var Address */
        $a = Address::find($this->editAddressId);
        $res = $a->editInfo($this->type, $this->line1, $this->line2, $this->country, $this->city, $this->building, $this->flat, $this->area);
        if ($res) {
            $this->alert('success', 'Address edited successfuly');
            $this->closeEditAddress();
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
            'phoneType' => 'required|in:' . implode(',', Phone::TYPES),
            'number' => 'required|string|max:255',
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addPhone($this->phoneType, $this->number);
        if ($res) {
            $this->alert('success', 'Phone Added successfuly');
            $this->phoneType = null;
            $this->number = null;
            $this->toggleAddPhone();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editPhone()
    {
        $this->validate([
            'phoneType' => 'required|in:' . implode(',', Phone::TYPES),
            'number' => 'required|string|max:255',
        ]);
        $p = Phone::find($this->PhoneId);
        $res = $p->editInfo($this->phoneType, $this->number, false);
        if ($res) {
            $this->alert('success', 'Phone Edited successfuly');
            $this->phoneType = null;
            $this->number = null;
            $this->closeEditPhone();
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

    public function setPhoneAsDefault($id)
    {
        $r = Phone::find($id)->setAsDefault();
        if ($r) {
            $this->alert('success', 'Phone updated!');
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
            'contactEmail' => 'nullable|email',
            'contactPhone' => 'nullable|string|max:255',
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addContact($this->contactName, $this->jobTitle, $this->contactEmail, $this->contactPhone, false);
        if ($res) {
            $this->alert('success', 'Contact Added successfuly');
            $this->contactName = null;
            $this->jobTitle = null;
            $this->contactEmail = null;
            $this->contactPhone = null;
            $this->toggleAddContact();
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
            'contactEmail' => 'nullable|email',
            'contactPhone' => 'nullable|string|max:255',
        ]);
        $c = Contact::find($this->contactId);
        $res = $c->editInfo($this->contactName, $this->jobTitle, $this->contactEmail, $this->contactPhone);
        if ($res) {
            $this->alert('success', 'Contact Edited successfuly');
            $this->contactName = null;
            $this->jobTitle = null;
            $this->contactEmail = null;
            $this->contactPhone = null;
            $this->closeEditContact();
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
            'accountType' => 'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' => 'required|string|max:255',
            'ownerName' => 'required|string|max:255',
            'evidenceDoc' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $c = Corporate::find($this->corporate->id);
        $res = $c->addBankAccount($this->accountType, $this->bankName, $this->accountNumber, $this->ownerName, $this->evidenceDoc, $this->iban, $this->bankBranch, false);
        if ($res) {
            $this->alert('success', 'Account Added successfuly');
            $this->accountType = null;
            $this->bankName = null;
            $this->accountNumber = null;
            $this->ownerName = null;
            $this->evidenceDoc = null;
            $this->iban = null;
            $this->bankBranch = null;
            $this->toggleAddBankAccount();
            $this->mount($this->corporate->id);
        } else {
            $this->alert('failed', 'server error');
        }
    }

    public function editBankAccount()
    {
        $this->validate([
            'accountType' => 'required|in:' . implode(',', BankAccount::TYPES),
            'bankName' => 'required|string|max:255',
            'accountNumber' => 'required|string|max:255',
            'ownerName' => 'required|string|max:255',
            'evidenceDoc' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bankBranch' => 'nullable|string|max:255',
        ]);
        $b = BankAccount::find($this->bankAccountId);
        $res = $b->editInfo($this->accountType, $this->bankName, $this->accountNumber, $this->ownerName, $this->evidenceDoc, $this->iban, $this->bankBranch, false);
        if ($res) {
            $this->alert('success', 'Account edited successfuly');
            $this->closeEditBankAccount();
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
        $addressTypes = Address::TYPES;
        $bankAccTypes = BankAccount::TYPES;
        $phoneTypes = Phone::TYPES;
        $areas = Area::all();
        $cities = City::all();
        $countries = Country::all();
        $tasks = $this->corporate->tasks;
        $offers = $this->corporate->offers;
        $LINES_OF_BUSINESS = Policy::CORPORATE_TYPES;


        return view('livewire.corporate-show', [
            'addressTypes' => $addressTypes,
            'bankAccTypes' => $bankAccTypes,
            'phoneTypes' => $phoneTypes,
            'tasks' => $tasks,
            'areas' => $areas,
            'cities' => $cities,
            'countries' => $countries,
            'offers' => $offers,
            'LINES_OF_BUSINESS' => $LINES_OF_BUSINESS
        ]);
    }
}
