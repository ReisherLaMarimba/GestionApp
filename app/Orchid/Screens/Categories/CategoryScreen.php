<?php

namespace App\Orchid\Screens\Categories;

use App\Http\Requests\CategoriesRequest;
use App\Models\Category;
use App\Orchid\Layouts\Categories\CategoryTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        return [
        'categories' => Category::all()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categorias';
    }

    public function description(): ?string{
        return 'Aqui podras ver y editar todas las categorias';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear Categoria')
            ->modal('categoriesModal')
            ->method('create')
            ->icon('plus'),
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

            Layout::modal('categoriesModal', Layout::rows([
                Input::make('name')->label('Nombre')
                ->required()
                ->title('Nombre de la categoria')
                ->placeholder('Nombre de la categoria'),

                Input::make('description')->label('Descripcion')
                    ->required()
                    ->title('Descripcion de la categoria')
                    ->placeholder('descripcion de la categoria'),

                Select::make('risk')->label('Riesgo')
                    ->options([
                        'Alta' => 'Alta',
                        'Medio' => 'Medio',
                        'Baja' => 'Baja',
                    ])
                    ->required()
                    ->title('Riesgo de la categoria')
                    ->placeholder('Riesgo de la categoria'),
            ]))->title('Crear Categoria'),


            CategoryTable::class,

        ];
    }




    public function create( CategoriesRequest $request)
    {
            $request->validate($request->rules());

            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'risk' => $request->risk,
            ]);
            Toast::success('Categoria creada exitosamente');
    }

    public function delete(Category $category)
    {
        $category->delete();
        Toast::success('Categoria eliminada exitosamente');
    }

    public function redirectToEdit(Category $category)
    {
        $Category = Category::findOrFail($category->id);


        return redirect()->route('platform.categories.edit', [$Category->id]);

    }


}
