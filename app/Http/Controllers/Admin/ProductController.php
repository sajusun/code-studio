<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(10);

        if ($request->wantsJson()) {
            return self::success('Products list fetched successfully', compact('products'));
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'type' => 'required|in:web_app,android,ios,custom',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            
            // Demos & Media
            'demo_url' => 'nullable|string|max:500',
            'admin_demo_url' => 'nullable|string|max:500',
            'admin_demo_username' => 'nullable|string|max:100',
            'admin_demo_password' => 'nullable|string|max:100',
            'video_url' => 'nullable|string|max:500',
            'apk_url' => 'nullable|string|max:500',
            'testflight_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:1000',
            'screenshots' => 'nullable|array',
            'screenshots.*' => 'nullable|string',

            // Arrays
            'tech_stack' => 'nullable|array',
            'tech_stack.*' => 'nullable|string',
            'features' => 'nullable|array',
            'contact_channels' => 'nullable|array',
            
            'allow_custom_quotes' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['allow_custom_quotes'] = $request->has('allow_custom_quotes');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Filter nulls in array fields
        if (isset($validated['screenshots'])) {
            $validated['screenshots'] = array_values(array_filter($validated['screenshots']));
        }
        if (isset($validated['tech_stack'])) {
            $validated['tech_stack'] = array_values(array_filter($validated['tech_stack']));
        }

        $product = Product::create($validated);

        // Process Polymorphic Media File Uploads
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $path = $file->store('products/thumbnails', 'public');

            \App\Modules\Media\Models\Media::create([
                'mediable_id' => $product->id,
                'mediable_type' => get_class($product),
                'disk' => 'public',
                'collection_name' => 'thumbnail',
                'original_name' => $file->getClientOriginalName(),
                'file_name' => basename($path),
                'mime_type' => $file->getClientMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'path' => $path,
                'is_primary' => true,
            ]);

            $product->update(['thumbnail' => $path]);
        }

        if ($request->hasFile('screenshots_files')) {
            $uploadedPaths = [];
            foreach ($request->file('screenshots_files') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('products/screenshots', 'public');
                    $uploadedPaths[] = $path;

                    \App\Modules\Media\Models\Media::create([
                        'mediable_id' => $product->id,
                        'mediable_type' => get_class($product),
                        'disk' => 'public',
                        'collection_name' => 'gallery',
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => basename($path),
                        'mime_type' => $file->getClientMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
            $product->update(['screenshots' => $uploadedPaths]);
        }

        if ($request->wantsJson()) {
            return self::success('Product created successfully', compact('product'), 201);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'type' => 'required|in:web_app,android,ios,custom',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            
            // Demos & Media
            'demo_url' => 'nullable|string|max:500',
            'admin_demo_url' => 'nullable|string|max:500',
            'admin_demo_username' => 'nullable|string|max:100',
            'admin_demo_password' => 'nullable|string|max:100',
            'video_url' => 'nullable|string|max:500',
            'apk_url' => 'nullable|string|max:500',
            'testflight_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:1000',
            'thumbnail_file' => 'nullable|image|max:5120',
            'screenshots' => 'nullable|array',
            'screenshots_files.*' => 'nullable|image|max:5120',

            // Arrays
            'tech_stack' => 'nullable|array',
            'features' => 'nullable|array',
            'contact_channels' => 'nullable|array',
            
            'allow_custom_quotes' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : $product->slug;
        $validated['allow_custom_quotes'] = $request->has('allow_custom_quotes');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if (isset($validated['screenshots'])) {
            $validated['screenshots'] = array_values(array_filter($validated['screenshots']));
        }
        if (isset($validated['tech_stack'])) {
            $validated['tech_stack'] = array_values(array_filter($validated['tech_stack']));
        }

        $product->update($validated);

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $path = $file->store('products/thumbnails', 'public');

            \App\Modules\Media\Models\Media::create([
                'mediable_id' => $product->id,
                'mediable_type' => get_class($product),
                'disk' => 'public',
                'collection_name' => 'thumbnail',
                'original_name' => $file->getClientOriginalName(),
                'file_name' => basename($path),
                'mime_type' => $file->getClientMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'path' => $path,
                'is_primary' => true,
            ]);

            $product->update(['thumbnail' => $path]);
        }

        if ($request->hasFile('screenshots_files')) {
            $uploadedPaths = is_array($product->screenshots) ? $product->screenshots : [];
            foreach ($request->file('screenshots_files') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('products/screenshots', 'public');
                    $uploadedPaths[] = $path;

                    \App\Modules\Media\Models\Media::create([
                        'mediable_id' => $product->id,
                        'mediable_type' => get_class($product),
                        'disk' => 'public',
                        'collection_name' => 'gallery',
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => basename($path),
                        'mime_type' => $file->getClientMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
            $product->update(['screenshots' => array_values(array_unique($uploadedPaths))]);
        }

        if ($request->wantsJson()) {
            return self::success('Product updated successfully', compact('product'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
