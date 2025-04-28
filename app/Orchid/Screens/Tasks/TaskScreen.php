<?php

namespace App\Orchid\Screens\Tasks;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Orchid\Layouts\Tasks\Charts\UserPerTaskChart;
use App\Orchid\Layouts\Tasks\TaskTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TaskScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'Tasks' => Task::with('users')->get(),

            'user_per_task_chart' => Task::with('users')->get()->map(function ($task) {
                return [
                    'name' => $task->name,
                    'value' => $task->users->count(),
                    'label' => $task->users->count(),

                ];
            }),

        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Task List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create Task')
                ->modal('taskmodal')
                ->method('create')
                ->icon('plus')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            TaskTable::class,

            Layout::modal('edittaskmodal', Layout::rows([])),

            Layout::modal('taskmodal', Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->placeholder('Inserte el nombre del artículo'),

                Input::make('description')
                    ->title('Description')
                    ->placeholder('Inserte la descripción del artículo'),


            ]))->title('Create Task')
            ->applyButton('Create Task'),

            UserPerTaskChart::make('user_per_task_chart','Users per task')
            ->description('This bars represent the number of users assigned to each task')
        ];
    }

    Public function create(TaskRequest $taskRequest){

        $task = Task::create([
            'name' => $taskRequest->name,
            'description' => $taskRequest->description,
        ]);

        TOAST::success('Task created successfully');
    }

    public function delete(Request $request)
    {
            $task = Task::findOrFail($request->get('id'));
            $task->delete();

        Toast::info(__('Task was deleted'));
    }
}
