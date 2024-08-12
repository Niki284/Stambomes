<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    //

    public function index()
    {
        return view('gallery.index');
    }

    public function create($personId)
    {
        $person = People::findOrFail($personId);
        return view('gallery.create', compact('person'));
    }

    public function store(Request $request, $personId)
    {
        $person = People::findOrFail($personId);

    // Проверка прав доступа
    if (Auth::id() !== (int)$person->beheerder_id) {
        return redirect()->route('peoples.show', $personId)
            ->with('error', 'You do not have permission to add images for this person.');
    }

    // Валидация изображения
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        try {
            // Сохранение изображения
            $imageFile = $request->file('image');
            $filename = time() . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('gallery', $filename, 'public');

            // Создание записи в базе данных
            $person->galleries()->create([
                'image' => $path,
            ]);

            return redirect()->route('peoples.show', $personId)
                ->with('success', 'Image uploaded successfully.');

        } catch (\Exception $e) {
            // Обработка ошибок
            return redirect()->route('peoples.show', $personId)
                ->with('error', 'Failed to upload image. Please try again.');
        }
    }

    return redirect()->route('peoples.show', $personId)
        ->with('error', 'No image file was uploaded.');
    }


    public function destroy($personId, $galleryId)
    {
        $person = People::findOrFail($personId);
        $gallery = $person->galleries()->findOrFail($galleryId);
    
        if (Auth::id() !== (int)$person->beheerder_id) {
            return redirect()->route('peoples.show', $personId)->with('error', 'You do not have permission to delete this image.');
        }
    
        // Удаление файла изображения из хранилища
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
    
        $gallery->delete();
    
        return redirect()->route('peoples.show', $personId)
            ->with('success', 'Image deleted successfully.');
    }
}
