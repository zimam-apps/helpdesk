<?php

namespace Database\Seeders;

use App\Models\Languages;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class LanguageTableSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br" => "Portuguese(Brazil)",
        ];

        foreach ($languages as $key => $lang) {
            $checkLang = Languages::where('code', $key)->first();
            if (empty($checkLang)) {
                $language = new Languages();
                $language->code = $key;
                $language->fullName = $lang;
                $language->save();
            }
        }

        // For Old Customer If there are already ticket exists then set this column (is_ticket_assign_to_agent) Assign or UnAssignes based on assign agent.	
        $tickets = Ticket::all();
        if (count($tickets) > 0) {
            foreach ($tickets as $ticket) {
                $ticket->is_ticket_assign_to_agent = $ticket->is_assign === null ? 'Unassigned' : 'Assigned';
                $ticket->save();
            }
        }

        // Assign Those Ticket as a Frontend Ticket Whoes Type is UnAssigned or Assigned.
        $getAssignAndUnassignTickets = Ticket::where('type', 'Assigned')->orWhere('type', 'Unassigned')->get();
        if (count($getAssignAndUnassignTickets) > 0) {
            foreach ($getAssignAndUnassignTickets as $ticket) {
                $ticket->type = "TicketForm";
                $ticket->save();
            }
        }
        Artisan::call('optimize:clear');
    }
}
