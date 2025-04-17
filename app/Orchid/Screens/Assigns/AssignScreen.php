<?php

namespace App\Orchid\Screens\Assigns;

use App\Models\Item;
use App\Models\User;
use App\Orchid\Layouts\Assigns\AssignTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AssignScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
            'items' => $items = Item::with([
                'users' => function ($query) {
                    $query->wherePivotNull('deleted_at'); // Excluir soft deleted en la tabla pivot
                },
                'users.campaign',
                'location'
            ])->get()

        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Assign Computers to Users';
    }



    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Assign Computer')
                ->icon('bs.collection')
            ->modal('assignModal')
            ->method('AssignComputerToUser')

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
            AssignTable::class,

            Layout::modal('assignModal', Layout::rows([
                Group::make([
                    Select::make('user_id')
                        ->title('Select Users')
                        ->popover('All the PCs have to be assigned to 2 users max')
                        ->options(
                            User::where('id', '!=', 1) // Exclude admin/system user
                            ->whereNotIn('id', function ($subquery) {
                                $subquery->select('user_id')
                                    ->from('item_user')
                                    ->groupBy('user_id')
                                    ->havingRaw('COUNT(item_id) >= 2'); // Exclude users assigned to 2+ items
                            })
                                ->pluck('name', 'id') // Format: [id => name]
                                ->toArray()
                        )
                        ->multiple() // Allow selecting multiple users
                            ->required()
                        ->placeholder('Select User')
                        ->help('Choose the users available with less than 2 items assigned')
        ]),
                Group::make([
                    //CPUS
                    Select::make('cpu_id')
                        ->title('Select CPU')
                        ->required()
                        ->fromModel(
                            Item::whereHas('category', function ($query) {
                                $query->where('name', '=', 'CPU'); // Filtrar por categoría
                            })
                                ->whereDoesntHave('users', function ($query) {
                                    $query->select('item_user.item_id')
                                        ->groupBy('item_user.item_id')
                                        ->havingRaw('COUNT(item_user.user_id) > 1');
                                }),
                            'item_code', // Mostrar el código del ítem
                            'id' // Usar el ID como valor
                        )
                        ->placeholder('Select CPU')
                        ->help('Elija un CPU disponible'),

        // Monitor
                      Select::make('monitor_id')
                          ->title('Select Monitor')
                          ->required()
                          ->placeholder('Select Monitor')
                          ->fromModel(
                              Item::whereHas('category', function ($query) {
                                  $query->where('name', '=', 'Monitor');
                              })
                          ->whereDoesntHave('users', function ($query) {
                              $query->select('item_user.item_id')
                                  ->groupBy('item_user.item_id')
                                  ->havingRaw('COUNT(item_user.user_id) > 1');
                          }),
                              'item_code', 'id'
                          )
                          ->multiple()
                          ->maximumSelectionLength(2)
                        ,

                ]),

                Group::make([
                    //Headset
                    Select::make('headset_id')
                        ->title('Select Headset')
                        ->required()
                        ->placeholder('Select Headset')
                        ->fromModel(
                            Item::whereHas('category', function ($query) {
                                $query->where('name', '=', 'Headsets');
                            })->whereDoesntHave('users', function ($query) {
                                $query->select('item_user.item_id')
                                    ->groupBy('item_user.item_id')
                                    ->havingRaw('COUNT(item_user.user_id) > 1');
                            }),
                            'item_code', 'id'
                        ),

                    // Keyboard
                      Select::make('keyboard_id')
                          ->title('Select Keyboard')
                          ->required()
                          ->placeholder('Select Keyboard')
                          ->fromModel(
                              Item::whereHas('category', function ($query) {
                                  $query->where('name', '=', 'Keyboards');
                              })->whereDoesntHave('users', function ($query) {
                                  $query->select('item_user.item_id')
                                      ->groupBy('item_user.item_id')
                                      ->havingRaw('COUNT(item_user.user_id) > 1');
                              }),
                              'item_code', 'id'
                          ),


                        // Mouse
                      Select::make('mouse_id')
                          ->title('Select Mouse')
                          ->required()
                          ->placeholder('Select Mouse')
                          ->fromModel(
                              Item::whereHas('category', function ($query) {
                                  $query->where('name', '=', 'Mouses');
                              })->whereDoesntHave('users', function ($query) {
                                  $query->select('item_user.item_id')
                                      ->groupBy('item_user.item_id')
                                      ->havingRaw('COUNT(item_user.user_id) > 1');
                              }),
                              'item_code', 'id'
                          )

                    ])
            ]))->title('Assign Computer'),
        ];
    }

    public function AssignComputerToUser(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'required|array|max:2', // Limit assignment to 2 users
            'user_id.*' => 'integer|exists:users,id', // Validate each user ID
            'cpu_id' => 'required|integer|exists:items,id',
            'monitor_id' => 'nullable|array|max:2',
            'monitor_id.*' => 'integer|exists:items,id',
            'headset_id' => 'nullable|integer|exists:items,id',
            'keyboard_id' => 'nullable|integer|exists:items,id',
            'mouse_id' => 'nullable|integer|exists:items,id',
        ]);

        $this->storeAssignments($validatedData); // Call store logic

    }

    protected function storeAssignments(array $data)
    {
        DB::transaction(function () use ($data) {
            // Helper to insert assignments
            $insertAssignments = function ($itemIds, $userIds) {
                $assignments = [];
                foreach ($itemIds as $itemId) {
                    foreach ($userIds as $userId) {
                        $assignments[] = [
                            'item_id' => $itemId,
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                DB::table('item_user')->insert($assignments);
            };

            // Assign CPU to users
            $insertAssignments([$data['cpu_id']], $data['user_id']);

            // Assign Monitors to users if selected
            if (!empty($data['monitor_id'])) {
                $insertAssignments($data['monitor_id'], $data['user_id']);
            }

            // Assign other items (headset, keyboard, mouse) to users
            foreach (['headset_id', 'keyboard_id', 'mouse_id'] as $itemField) {
                if (!empty($data[$itemField])) {
                    $insertAssignments([$data[$itemField]], $data['user_id']);
                }
            }
        });

        // Send notification
        TOAST::INFO('Assigned properly completed');
    }
}
