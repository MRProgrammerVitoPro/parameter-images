<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use voku\helper\ASCII;

class ParameterController extends Controller
{
    public function index(Request $request)
    {
        $query = Parameter::where('type', 2);

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $parameters = $query->with('images')->get();

        return view('parameters.index', compact('parameters'));
    }

    public function create()
    {
        return view('parameters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2',
        ]);

        $parameter = new Parameter();
        $parameter->title = $request->input('title');
        $parameter->type = $request->input('type');
        $parameter->save();

        return redirect()->route('parameters.index')->with('success', 'Parameter created successfully.');
    }

    public function upload(Request $request, $id)
    {
        $parameter = Parameter::findOrFail($id);

        if ($parameter->type != 2) {
            return response()->json(['error' => 'Parameter type is not 2'], 400);
        }

        $imageData = [];

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = Str::slug(ASCII::to_ascii(pathinfo($icon->getClientOriginalName(), PATHINFO_FILENAME))) . '.' . $icon->getClientOriginalExtension();
            $iconPath = $icon->storeAs('images', $iconName, 'public');
            $imageData['icon'] = $iconPath;
        }

        if ($request->hasFile('icon_gray')) {
            $iconGray = $request->file('icon_gray');
            $iconGrayName = Str::slug(ASCII::to_ascii(pathinfo($iconGray->getClientOriginalName(), PATHINFO_FILENAME))) . '.' . $iconGray->getClientOriginalExtension();
            $iconGrayPath = $iconGray->storeAs('images', $iconGrayName, 'public');
            $imageData['icon_gray'] = $iconGrayPath;
        }

        $parameterImage = Image::updateOrCreate(
            ['parameter_id' => $parameter->id],
            $imageData
        );

        return redirect()->back()->with('success', 'Images uploaded successfully');
    }

    public function deleteImage($id, $type)
    {
        $parameterImage = Image::where('parameter_id', $id)->firstOrFail();

        if ($type == 'icon' && $parameterImage->icon) {
            Storage::disk('public')->delete($parameterImage->icon);
            $parameterImage->icon = null;
        } elseif ($type == 'icon_gray' && $parameterImage->icon_gray) {
            Storage::disk('public')->delete($parameterImage->icon_gray);
            $parameterImage->icon_gray = null;
        } else {
            return response()->json(['error' => 'Invalid image type'], 400);
        }

        $parameterImage->save();

        return redirect()->back()->with('success', 'Image deleted successfully');
    }
}
