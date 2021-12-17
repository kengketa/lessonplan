<?php

namespace App\Actions;

use App\Models\Agenda;
use Illuminate\Support\Str;

class SaveAgendaAction
{
    protected Agenda $agenda;

    public function execute(Agenda $agenda, array $data): Agenda
    {
        $this->agenda = $agenda;

        if (! empty($this->agenda->id)) {
            $this->agenda->update($data);
            return $this->agenda;
        }

        //create case
        $this->agenda = $this->agenda->create($data);
        return $this->agenda;
    }

}
