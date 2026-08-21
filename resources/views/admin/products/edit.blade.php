<x-layouts.admin>
    <div class="space-y-6 max-w-5xl mx-auto" x-data="{ 
        step: 1,
        productType: '{{ old('type', $product->type) }}',
        techStack: {{ json_encode(old('tech_stack', $product->tech_stack ?? ['Laravel', 'TailwindCSS'])) }},
        newTech: '',
        addTech() {
            if (this.newTech.trim() && !this.techStack.includes(this.newTech.trim())) {
                this.techStack.push(this.newTech.trim());
                this.newTech = '';
            }
        },
        removeTech(index) {
            this.techStack.splice(index, 1);
        },
        screenshots: {{ json_encode(old('screenshots', $product->screenshots ?? [''])) }},
        addScreenshot() {
            this.screenshots.push('');
        },
        removeScreenshot(index) {
            if (this.screenshots.length > 1) {
                this.screenshots.splice(index, 1);
            }
        },
        features: {{ json_encode(old('features', $product->features ?? [])) }},
        newFeatureTitle: '',
        newFeatureDesc: '',
        newFeatureCost: '0',
        addFeature() {
            if (this.newFeatureTitle.trim()) {
                this.features.push({
                    title: this.newFeatureTitle.trim(),
                    desc: this.newFeatureDesc.trim(),
                    cost: this.newFeatureCost || '0'
                });
                this.newFeatureTitle = '';
                this.newFeatureDesc = '';
                this.newFeatureCost = '0';
            }
        },
        removeFeature(index) {
            this.features.splice(index, 1);
        }
    }">
        <!-- Page Header & Back Button -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-theme-card border border-theme text-theme-muted hover:text-theme-main transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-theme-main tracking-tight">Edit Product: {{ $product->title }}</h1>
                    <p class="text-xs text-theme-muted">Update product specifications, demos, and contact channels.</p>
                </div>
            </div>
            <div class="text-xs font-bold text-theme-primary px-3 py-1.5 rounded-xl bg-theme-primary/10 border border-theme-primary/20">
                Step <span x-text="step"></span> of 4
            </div>
        </div>

        <!-- 4-STEP WIZARD PROGRESS BAR -->
        <div class="bg-theme-card p-4 rounded-2xl border border-theme shadow-xs">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <!-- Step 1 Tab -->
                <button type="button" @click="step = 1" 
                        class="p-3 rounded-xl flex items-center gap-3 transition-all text-left"
                        :class="step === 1 ? 'bg-theme-primary text-white shadow-md' : (step > 1 ? 'bg-theme-primary/10 text-theme-primary' : 'bg-theme-main/50 text-theme-muted')">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0" :class="step === 1 ? 'bg-white/20 text-white' : 'bg-theme-card'">
                        1
                    </div>
                    <div class="min-w-0 hidden sm:block">
                        <p class="text-xs font-bold truncate">Basic Info</p>
                        <p class="text-[10px] opacity-80 truncate">Title, Type & Price</p>
                    </div>
                </button>

                <!-- Step 2 Tab -->
                <button type="button" @click="step = 2" 
                        class="p-3 rounded-xl flex items-center gap-3 transition-all text-left"
                        :class="step === 2 ? 'bg-theme-primary text-white shadow-md' : (step > 2 ? 'bg-theme-primary/10 text-theme-primary' : 'bg-theme-main/50 text-theme-muted')">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0" :class="step === 2 ? 'bg-white/20 text-white' : 'bg-theme-card'">
                        2
                    </div>
                    <div class="min-w-0 hidden sm:block">
                        <p class="text-xs font-bold truncate">Demos & Media</p>
                        <p class="text-[10px] opacity-80 truncate">URLs, Videos & Images</p>
                    </div>
                </button>

                <!-- Step 3 Tab -->
                <button type="button" @click="step = 3" 
                        class="p-3 rounded-xl flex items-center gap-3 transition-all text-left"
                        :class="step === 3 ? 'bg-theme-primary text-white shadow-md' : (step > 3 ? 'bg-theme-primary/10 text-theme-primary' : 'bg-theme-main/50 text-theme-muted')">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0" :class="step === 3 ? 'bg-white/20 text-white' : 'bg-theme-card'">
                        3
                    </div>
                    <div class="min-w-0 hidden sm:block">
                        <p class="text-xs font-bold truncate">Tech & Features</p>
                        <p class="text-[10px] opacity-80 truncate">Stack & Add-on Costs</p>
                    </div>
                </button>

                <!-- Step 4 Tab -->
                <button type="button" @click="step = 4" 
                        class="p-3 rounded-xl flex items-center gap-3 transition-all text-left"
                        :class="step === 4 ? 'bg-theme-primary text-white shadow-md' : 'bg-theme-main/50 text-theme-muted'">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0" :class="step === 4 ? 'bg-white/20 text-white' : 'bg-theme-card'">
                        4
                    </div>
                    <div class="min-w-0 hidden sm:block">
                        <p class="text-xs font-bold truncate">Contacts & Leads</p>
                        <p class="text-[10px] opacity-80 truncate">WhatsApp, Fiverr & Quote</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- FORM CONTAINER -->
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="bg-theme-card p-6 md:p-8 rounded-2xl border border-theme shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <!-- Hidden Inputs for Dynamic Arrays -->
            <template x-for="(tech, index) in techStack" :key="index">
                <input type="hidden" name="tech_stack[]" :value="tech">
            </template>

            <template x-for="(feature, index) in features" :key="index">
                <div>
                    <input type="hidden" :name="`features[${index}][title]`" :value="feature.title">
                    <input type="hidden" :name="`features[${index}][desc]`" :value="feature.desc">
                    <input type="hidden" :name="`features[${index}][cost]`" :value="feature.cost">
                </div>
            </template>

            <!-- STEP 1: BASIC INFORMATION -->
            <div x-show="step === 1" x-transition class="space-y-6">
                <div class="border-b border-theme pb-4">
                    <h3 class="text-base font-bold text-theme-main">Step 1: General Product Information</h3>
                    <p class="text-xs text-theme-muted">Set product title, type, category, price, and descriptions.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Product Title *</label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Product Type Selection Cards -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Product Type *</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col items-center justify-center gap-2 text-center"
                                   :class="productType === 'web_app' ? 'border-theme-primary bg-theme-primary/10 text-theme-primary font-bold shadow-xs' : 'border-theme bg-theme-main text-theme-muted'">
                                <input type="radio" name="type" value="web_app" x-model="productType" class="sr-only">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                                <span class="text-xs">Web App</span>
                            </label>

                            <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col items-center justify-center gap-2 text-center"
                                   :class="productType === 'android' ? 'border-sky-500 bg-sky-500/10 text-sky-500 font-bold shadow-xs' : 'border-theme bg-theme-main text-theme-muted'">
                                <input type="radio" name="type" value="android" x-model="productType" class="sr-only">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-xs">Android App</span>
                            </label>

                            <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col items-center justify-center gap-2 text-center"
                                   :class="productType === 'ios' ? 'border-indigo-500 bg-indigo-500/10 text-indigo-500 font-bold shadow-xs' : 'border-theme bg-theme-main text-theme-muted'">
                                <input type="radio" name="type" value="ios" x-model="productType" class="sr-only">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-xs">iOS / Apple App</span>
                            </label>

                            <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col items-center justify-center gap-2 text-center"
                                   :class="productType === 'custom' ? 'border-amber-500 bg-amber-500/10 text-amber-500 font-bold shadow-xs' : 'border-theme bg-theme-main text-theme-muted'">
                                <input type="radio" name="type" value="custom" x-model="productType" class="sr-only">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                <span class="text-xs">Custom Template</span>
                            </label>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Category</label>
                        <input type="text" name="category" value="{{ old('category', $product->category) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Base Price ($ USD) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Short Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Short Summary</label>
                        <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <!-- Full Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Full Description</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Flags -->
                    <div class="md:col-span-2 flex flex-wrap gap-6 pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-theme-primary focus:ring-0">
                            <span class="text-xs font-bold text-theme-main">Active & Published</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-theme-primary focus:ring-0">
                            <span class="text-xs font-bold text-theme-main">Mark as Featured Product</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- STEP 2: DEMOS & MEDIA ASSETS -->
            <div x-show="step === 2" x-transition class="space-y-6">
                <div class="border-b border-theme pb-4">
                    <h3 class="text-base font-bold text-theme-main">Step 2: Live Preview Demos & Media Uploads</h3>
                    <p class="text-xs text-theme-muted">Configure live web demo, video walkthroughs, and upload media via polymorphic media system.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Live Web Demo URL -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Live Web Demo URL</label>
                        <input type="url" name="demo_url" value="{{ old('demo_url', $product->demo_url) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Admin Demo URL -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Admin Demo URL</label>
                        <input type="url" name="admin_demo_url" value="{{ old('admin_demo_url', $product->admin_demo_url) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Admin Demo Username -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Admin Demo Username</label>
                        <input type="text" name="admin_demo_username" value="{{ old('admin_demo_username', $product->admin_demo_username) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Admin Demo Password -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Admin Demo Password</label>
                        <input type="text" name="admin_demo_password" value="{{ old('admin_demo_password', $product->admin_demo_password) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Video Demo URL -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Video Walkthrough (YouTube / Vimeo)</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $product->video_url) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Mobile Builds (APK & TestFlight) -->
                    <div x-show="productType === 'android' || productType === 'web_app'">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Demo APK Download URL (Android)</label>
                        <input type="url" name="apk_url" value="{{ old('apk_url', $product->apk_url) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <div x-show="productType === 'ios' || productType === 'web_app'">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">TestFlight / App Store URL (iOS)</label>
                        <input type="url" name="testflight_url" value="{{ old('testflight_url', $product->testflight_url) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Polymorphic Media File Uploaders -->
                    <div class="md:col-span-2 p-5 rounded-2xl bg-theme-main/50 border border-theme space-y-4">
                        <h4 class="text-xs font-bold text-theme-main uppercase tracking-wider">Polymorphic Media System Uploads</h4>
                        
                        <div>
                            <label class="block text-xs font-bold text-theme-main mb-1.5">Product Thumbnail Image Upload (Polymorphic Media)</label>
                            <x-form.file name="thumbnail_file" accept="image/*" />
                            @if($product->thumbnail_url)
                                <div class="mt-2 flex items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}" class="w-12 h-12 rounded-xl object-cover border border-theme" alt="Current Thumbnail">
                                    <span class="text-xs text-theme-muted">Current product thumbnail</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-theme-main mb-1.5">Product Screenshots Gallery Upload (Multiple Files)</label>
                            <x-form.file name="screenshots_files[]" multiple accept="image/*" />
                            @if(count($product->gallery_urls) > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($product->gallery_urls as $gUrl)
                                        <img src="{{ $gUrl }}" class="w-12 h-12 rounded-xl object-cover border border-theme" alt="Gallery item">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Main Thumbnail -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Or External Product Thumbnail Image URL</label>
                        <input type="url" name="thumbnail" value="{{ old('thumbnail', $product->thumbnail) }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Multi-Screenshot URLs Array -->
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-theme-main uppercase tracking-wider">Product Screenshots Gallery URLs</label>
                            <button type="button" @click="addScreenshot()" class="text-xs font-bold text-theme-primary hover:underline">+ Add Another Screenshot</button>
                        </div>
                        
                        <template x-for="(url, index) in screenshots" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="url" :name="`screenshots[${index}]`" x-model="screenshots[index]" class="flex-1 px-4 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden">
                                <button type="button" @click="removeScreenshot(index)" class="p-2 rounded-xl text-rose-500 hover:bg-rose-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- STEP 3: TECH STACK & FEATURES -->
            <div x-show="step === 3" x-transition class="space-y-6">
                <div class="border-b border-theme pb-4">
                    <h3 class="text-base font-bold text-theme-main">Step 3: Tech Stack & Feature Modules</h3>
                    <p class="text-xs text-theme-muted">List technology tags and core/add-on features for custom cost calculation.</p>
                </div>

                <!-- Tech Stack Tags -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-theme-main uppercase tracking-wider">Tech Stack Tags</label>
                    <div class="flex flex-wrap items-center gap-2 p-3 rounded-xl bg-theme-main border border-theme min-h-[50px]">
                        <template x-for="(tech, index) in techStack" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-theme-primary/10 text-theme-primary text-xs font-bold">
                                <span x-text="tech"></span>
                                <button type="button" @click="removeTech(index)" class="hover:text-rose-500">✕</button>
                            </span>
                        </template>

                        <div class="flex items-center gap-2">
                            <input type="text" x-model="newTech" @keydown.enter.prevent="addTech()" placeholder="Add new tag..." class="px-3 py-1 text-xs bg-transparent border-none text-theme-main focus:outline-hidden">
                            <button type="button" @click="addTech()" class="px-2 py-1 text-xs font-bold rounded-lg bg-theme-primary text-white">+ Add Tag</button>
                        </div>
                    </div>
                </div>

                <!-- Core & Add-on Features for Custom Cost Calculator -->
                <div class="space-y-4 pt-4 border-t border-theme">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-theme-main">Features & Module Cost Estimator</h4>
                            <p class="text-xs text-theme-muted">Add features included in base price or additional modules for custom quotes.</p>
                        </div>
                    </div>

                    <!-- Feature Items List -->
                    <div class="space-y-2">
                        <template x-for="(feat, index) in features" :key="index">
                            <div class="p-3 rounded-xl bg-theme-main border border-theme flex items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-theme-main truncate" x-text="feat.title"></p>
                                    <p class="text-[11px] text-theme-muted truncate" x-text="feat.desc"></p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg" :class="feat.cost > 0 ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500'" x-text="feat.cost > 0 ? `+$${feat.cost} USD` : 'Included Free'"></span>
                                    <button type="button" @click="removeFeature(index)" class="text-rose-500 hover:text-rose-700">✕</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Add New Feature Form Box -->
                    <div class="p-4 rounded-xl bg-theme-main/50 border border-theme space-y-3">
                        <p class="text-xs font-bold text-theme-main uppercase">Add Feature / Module Item</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <input type="text" x-model="newFeatureTitle" placeholder="Feature Name" class="px-3 py-2 text-xs rounded-xl bg-theme-card border border-theme text-theme-main">
                            <input type="text" x-model="newFeatureDesc" placeholder="Description" class="px-3 py-2 text-xs rounded-xl bg-theme-card border border-theme text-theme-main">
                            <div class="flex gap-2">
                                <input type="number" x-model="newFeatureCost" placeholder="Extra Cost" class="w-full px-3 py-2 text-xs rounded-xl bg-theme-card border border-theme text-theme-main">
                                <button type="button" @click="addFeature()" class="px-4 py-2 text-xs font-bold rounded-xl bg-theme-primary text-white hover:bg-theme-primary-hover shrink-0">+ Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: DYNAMIC CONTACT CHANNELS & LEADS -->
            <div x-show="step === 4" x-transition class="space-y-6">
                <div class="border-b border-theme pb-4">
                    <h3 class="text-base font-bold text-theme-main">Step 4: Dynamic Contact Channels & Inquiry Setup</h3>
                    <p class="text-xs text-theme-muted">Configure WhatsApp, Email, Fiverr, Upwork, and Custom Project Inquiry options.</p>
                </div>

                @php $contacts = $product->contact_channels ?? []; @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- WhatsApp Number -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">WhatsApp Contact Number</label>
                        <input type="text" name="contact_channels[whatsapp]" value="{{ old('contact_channels.whatsapp', $contacts['whatsapp'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- WhatsApp Greeting Text -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">WhatsApp Pre-filled Message</label>
                        <input type="text" name="contact_channels[whatsapp_text]" value="{{ old('contact_channels.whatsapp_text', $contacts['whatsapp_text'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Direct Email -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Direct Contact Email</label>
                        <input type="email" name="contact_channels[email]" value="{{ old('contact_channels.email', $contacts['email'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Direct Phone Line</label>
                        <input type="text" name="contact_channels[phone]" value="{{ old('contact_channels.phone', $contacts['phone'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Fiverr Gig Link -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Fiverr Gig / Profile URL</label>
                        <input type="url" name="contact_channels[fiverr]" value="{{ old('contact_channels.fiverr', $contacts['fiverr'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Upwork Link -->
                    <div>
                        <label class="block text-xs font-bold text-theme-main uppercase tracking-wider mb-2">Upwork Profile / Agency URL</label>
                        <input type="url" name="contact_channels[upwork]" value="{{ old('contact_channels.upwork', $contacts['upwork'] ?? '') }}" class="w-full px-4 py-2.5 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:border-theme-primary">
                    </div>

                    <!-- Custom Quotes Toggle -->
                    <div class="md:col-span-2 pt-2">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="allow_custom_quotes" value="1" {{ old('allow_custom_quotes', $product->allow_custom_quotes) ? 'checked' : '' }} class="w-5 h-5 rounded text-theme-primary focus:ring-0">
                            <div>
                                <span class="text-sm font-bold text-theme-main block">Enable "Request Custom Project Quote" Button</span>
                                <span class="text-xs text-theme-muted">Allows visitors to submit custom development requirements directly to your inbox.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- STEPPER NAVIGATION BUTTONS -->
            <div class="pt-6 border-t border-theme flex items-center justify-between">
                <button type="button" 
                        @click="step--" 
                        x-show="step > 1" 
                        class="px-5 py-2.5 rounded-xl border border-theme text-theme-main font-bold text-xs hover:bg-theme-main transition-colors">
                    ← Previous Step
                </button>

                <div x-show="step === 1"></div>

                <div class="flex items-center gap-3">
                    <button type="button" 
                            @click="step++" 
                            x-show="step < 4" 
                            class="px-6 py-2.5 rounded-xl bg-theme-primary text-white font-bold text-xs hover:bg-theme-primary-hover transition-colors shadow-md">
                        Next Step →
                    </button>

                    <button type="submit" 
                            x-show="step === 4" 
                            class="px-6 py-2.5 rounded-xl bg-emerald-500 text-white font-bold text-xs hover:bg-emerald-600 transition-colors shadow-lg">
                        ✓ Update Product Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
