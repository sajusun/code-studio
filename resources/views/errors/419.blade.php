<x-error-layout title="419 Page Expired">
    <h1 class="text-8xl md:text-9xl font-extrabold text-slate-900">419</h1>

    <h2 class="mt-6 text-3xl md:text-4xl font-bold">
        Page Expired
    </h2>

    <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
        Your session has expired. Please refresh the page and try again.
    </p>

    <div class="mt-10">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center rounded-xl bg-slate-900 px-6 py-3 font-medium text-white transition hover:bg-slate-800">
            Go Back
        </a>
    </div>
</x-error-layout>