<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveAgendaAction;
use App\Http\Requests\CreateOrUpdateAgendaRequest;
use App\Models\Agenda;
use App\Http\Controllers\Controller;
use App\Transformers\AgendaTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AgendaController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $agendas = Agenda::filter($filters)->latest()->paginate(30);
        $agendas = fractal($agendas, new AgendaTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Agendas/Index',
            [
                'agendas' => $agendas,
                'filters' => $filters,
            ]);
    }
    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Agendas/Create',
            [
                'agenda' => new Agenda()
            ]);
    }

    public function store(CreateOrUpdateAgendaRequest $request, SaveAgendaAction $saveAgendaAction): RedirectResponse
    {
        $agenda = new Agenda();
        $agenda = $saveAgendaAction->execute($agenda, $request->validated());

        return redirect()->route('dashboard.agendas.show', ['agenda' => $agenda])->with("success", ' Agenda  has been create!');
    }

    public function show(Agenda $agenda): Response
    {
        $agenda = fractal($agenda, new AgendaTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Agendas/Show',
            [
                'agenda' => $agenda,
            ]);
    }

    public function edit(Agenda $agenda): Response
    {
        return Inertia::render(
            'Dashboard/Agendas/Edit',
            [
                'agenda' => $agenda
            ]);
    }

    public function update(CreateOrUpdateAgendaRequest $request, Agenda $agenda, SaveAgendaAction $saveAgendaAction): RedirectResponse
    {
        $agenda = $saveAgendaAction->execute($agenda, $request->validated());

        return redirect()->route("dashboard.agendas.show", ['agenda' => $agenda])->with("success", "Agenda has been update!");
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        if ($agenda->delete()) {
            return redirect()->route("dashboard.agendas.index")->with("success", "Agenda has been destroy!");
        } else {
            return redirect()->route("dashboard.agendas.index")->with("error", "Can't delete Agenda");
        }
    }

}
