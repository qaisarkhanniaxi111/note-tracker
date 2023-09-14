<?php

namespace App\Console\Commands;

use App\Mail\NotFixedLocationNoteMail;
use App\Models\Location;
use App\Models\Note;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotFixedLocationNoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:not-fixed-notes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will send email to locations who have assigned notes but not fixed yet.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notes = [];
        $passed14Days = now()->subDay(14)->format('Y-m-d');

        $locations = Location::with(['notes' => function ($query) use ($passed14Days) {
            $query->where('status', Note::NOT_FIXED);
            $query->whereDate('date_of_service', '<=', $passed14Days);
        }])->active()->get();

        if (count($locations) > 0) {

            foreach ($locations as $location) {

                foreach ($location->notes as $note) {

                    $notes[] = [
                        'clinician' => $note->clinician ? $note->clinician->name: '',
                        'date_of_service' => date('m/d/Y', strtotime($note->date_of_service))
                    ];
                }

                Mail::to($location->email)->send(new NotFixedLocationNoteMail($notes));

            }
        }
    }
}
