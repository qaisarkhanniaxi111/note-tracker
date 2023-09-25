<?php

namespace App\Console\Commands;

use App\Mail\NotFixedClinicianNoteMail;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class NotFixedClinicianNoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clinician:not-fixed-notes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to clinician about not fixed notes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notes = [];
        $passed14Days = now()->subDay(14)->format('Y-m-d');

        $clinicians = User::clinicians()->active()->with(['notes' => function ($query) use ($passed14Days) {
            $query->where('status', Note::NOT_FIXED);
            $query->whereDate('date_of_service', '<=', $passed14Days);
        }])->get();

        if (count($clinicians) > 0) {

            foreach ($clinicians as $clinician) {

                foreach ($clinician->notes as $note) {

                    $notes[] = [
                        'patient' => $note->patient ? $note->patient->name: '',
                        'location' => $note->location ? $note->location->name: '',
                        'date_of_service' => date('m/d/Y', strtotime($note->date_of_service))
                    ];
                }

                Mail::to($clinician->email)->send(new NotFixedClinicianNoteMail($notes));

            }
        }
    }
}
